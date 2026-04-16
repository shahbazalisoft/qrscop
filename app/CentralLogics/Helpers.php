<?php

namespace App\CentralLogics;

use App\Library\Payer;
use App\Library\Payment as PaymentInfo;
use App\Library\Receiver;
use App\Models\BusinessSetting;
use App\Models\Currency;
use App\Models\DataSetting;
use App\Models\Item;
use App\Models\NotificationSetting;
use App\Models\ReferralTransaction;
use App\Models\Store;
use App\Models\StoreNotificationSetting;
use App\Models\StoreSubscription;
use App\Models\StoreWallet;
use App\Models\SubscriptionBillingAndRefundHistory;
use App\Models\SubscriptionPackage;
use App\Models\SubscriptionTransaction;
use App\Traits\NotificationDataSetUpTrait;
use App\Traits\PaymentGatewayTrait;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\Payment;

class Helpers
{
    use PaymentGatewayTrait, NotificationDataSetUpTrait;
    // , NotificationDataSetUpTrait;

    public static function get_business_settings($key, $json_decode = true, $relations = [])
    {
        try {
            static $allSettings = null;

            $configKey = $key . '_conf';
            if (Config::has($configKey)) {
                $data = Config::get($configKey);
            } else {
                if (is_null($allSettings)) {
                    $allSettings = Cache::rememberForever('business_settings_all_data', function () {
                        return BusinessSetting::select('key', 'value')->get();
                    });
                }

                $data = $allSettings->firstWhere('key', $key);
                if ($data && !empty($relations)) {
                    $data->loadMissing($relations);
                }
                Config::set($configKey, $data);
            }

            if (!isset($data['value'])) {
                return null;
            }

            $value = $data['value'];
            if ($json_decode && is_string($value)) {
                $decoded = json_decode($value, true);
                return is_null($decoded) ? $value : $decoded;
            }

            return $value;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public static function getDisk()
    {
        $config=self::get_business_settings('local_storage');

        return isset($config) ? ($config == 0 ? 's3' : 'public') : 'public';
    }
    public static function get_business_data($name)
    {
        return self::get_business_settings($name);
    }

    public static function getActivePaymentGateways()
    {

        if (!Schema::hasTable('addon_settings')) {
            return [];
        }

        $digital_payment = \App\CentralLogics\Helpers::get_business_settings('digital_payment');
        if ($digital_payment && $digital_payment['status'] == 0) {
            return [];
        }

        $published_status = 0;
        $payment_published_status = config('get_payment_publish_status');
        if (isset($payment_published_status[0]['is_published'])) {
            $published_status = $payment_published_status[0]['is_published'];
        }


        if ($published_status == 1) {
            $methods = DB::table('addon_settings')->where('is_active', 1)->where('settings_type', 'payment_config')->get();
            $env = env('APP_ENV') == 'live' ? 'live' : 'test';
            $credentials = $env . '_values';

        } else {
            $methods = DB::table('addon_settings')->where('is_active', 1)->whereIn('settings_type', ['payment_config'])->whereIn('key_name', ['ssl_commerz', 'paypal', 'stripe', 'razor_pay', 'senang_pay', 'paytabs', 'paystack', 'paymob_accept', 'paytm', 'flutterwave', 'liqpay', 'bkash', 'mercadopago', 'phonepe'])->get();
            $env = env('APP_ENV') == 'live' ? 'live' : 'test';
            $credentials = $env . '_values';

        }

        $data = [];
        foreach ($methods as $method) {
            $credentialsData = json_decode($method->$credentials);
            $additional_data = json_decode($method->additional_data);
            if ($credentialsData?->status == 1) {
                $data[] = [
                    'gateway' => $method->key_name,
                    'gateway_title' => $additional_data?->gateway_title,
                    'gateway_image' => $additional_data?->gateway_image,
                    'gateway_image_full_url' => Helpers::get_full_url('payment_modules/gateway_image', $additional_data?->gateway_image, $additional_data?->storage ?? 'public')
                ];
            }
        }
        return $data;

    }

    public static function apply_referral_reward($payment)
    {
        try {
            // ✅ Find store
            $store = Store::where('id', $payment['payer_id'])->first();
            
            // ❌ No referral code
            if (empty($store->apply_referral_code)) {
                return false;
            }

            if (!$store) {
                return false;
            }

            $ref_store = Store::where('referral_code', $store->apply_referral_code)->first()->id;
            if($ref_store){
                $subscription = StoreSubscription::where('store_id', $ref_store)->first();
                if ($subscription) {
                    $subscription->expiry_date = Carbon::parse($subscription->expiry_date)->addDays(30);
                    $subscription->save();
                    // Save history
                    ReferralTransaction::create([
                        'store_id' => $ref_store,
                        'apply_store' => $store->id,
                        'apply_referral_code' => $store->apply_referral_code,
                        'days' => 30,
                        'note' => "Reward: 30 days",
                    ]);
                }
            }
            $store->update([
                'apply_referral_code' => null
            ]);

            return true;

        } catch (\Exception $e) {
            \Log::error('Referral Error', [
                'message' => $e->getMessage(),
                'payment_id' => $payment->id ?? null
            ]);
            return false;
        }
    }

    public static function Export_generator($datas)
    {
        foreach ($datas as $data) {
            yield $data;
        }
        return true;
    }

    public static function updateStorageTable($dataType, $dataId, $image)
    {
        $value = Helpers::getDisk();
        DB::table('storages')->updateOrInsert([
            'data_type' => $dataType,
            'data_id' => $dataId,
            'key' => 'image',
        ], [
            'value' => $value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public static function checkCurrency($data, $type = null)
    {

        $digital_payment = self::get_business_settings('digital_payment');

        if ($digital_payment && $digital_payment['status'] == 1) {
            if ($type === null) {
                if (is_array(self::getActivePaymentGateways())) {
                    foreach (self::getActivePaymentGateways() as $payment_gateway) {

                        if (!empty(self::getPaymentGatewaySupportedCurrencies($payment_gateway['gateway'])) && !array_key_exists($data, self::getPaymentGatewaySupportedCurrencies($payment_gateway['gateway']))) {
                            return $payment_gateway['gateway'];
                        }
                    }
                }
            }
            elseif($type == 'payment_gateway'){
                $currency=   self::get_business_settings('currency');
                    if(!empty(self::getPaymentGatewaySupportedCurrencies($data)) && !array_key_exists($currency,self::getPaymentGatewaySupportedCurrencies($data))    ){
                        return  $data;
                    }
            }
        }

        return true;
    }

    public static function generate_reset_password_code()
    {
        $code = strtoupper(Str::random(15));

        if (self::reset_password_code_exists($code)) {
            return self::generate_reset_password_code();
        }

        return $code;
    }

    public static function reset_password_code_exists($code)
    {
        return DB::table('password_resets')->where('token', '=', $code)->exists();
    }

    public static function text_variable_data_format($value, $name = null,$user_name = null, $store_name = null, $delivery_man_name = null, $transaction_id = null, $order_id = null, $add_id = null)
    {
        $data = $value;
        if ($value) {
            if ($name) {
                $data = str_replace("{name}", $name, $data);
            }

            if ($user_name) {
                $data = str_replace("{userName}", $user_name, $data);
            }

            if ($store_name) {
                $data = str_replace("{storeName}", $store_name, $data);
                $data = str_replace("{providerName}", $store_name, $data);
            }

            if ($delivery_man_name) {
                $data = str_replace("{deliveryManName}", $delivery_man_name, $data);
            }

            if ($transaction_id) {
                $data = str_replace("{transactionId}", $transaction_id, $data);
            }

            if ($order_id) {
                $data = str_replace("{orderId}", $order_id, $data);
                $data = str_replace("{tripId}", $order_id, $data);
            }
            if ($add_id) {
                $data = str_replace("{advertisementId}", $add_id, $data);
            }
        }

        return $data;
    }

    public static function system_default_direction()
    {
        $languages = self::get_business_settings('system_language');
        $lang = 'en';

        foreach ($languages as $key => $language) {
            if ($language['default']) {
                $lang = $language['direction'];
            }
        }
        return $lang;
    }

    public static function get_full_url($path, $data, $type, $placeholder = null)
    {
        $place_holders = [
            'default' => asset('public/assets/admin/img/100x100/2.jpg'),
            'business' => asset('public/assets/admin/img/160x160/img2.jpg'),
            'contact_us_image' => asset('public/assets/admin/img/160x160/img2.jpg'),
            'profile' => asset('public/assets/admin/img/160x160/img2.jpg'),
            'product' => asset('public/assets/admin/img/160x160/img2.jpg'),
            'order' => asset('public/assets/admin/img/160x160/img2.jpg'),
            'refund' => asset('public/assets/admin/img/160x160/img2.jpg'),
            'delivery-man' => asset('public/assets/admin/img/160x160/img2.jpg'),
            'admin' => asset('public/assets/admin/img/160x160/img1.jpg'),
            'conversation' => asset('public/assets/admin/img/160x160/img1.jpg'),
            'banner' => asset('public/assets/admin/img/900x400/img1.jpg'),
            'campaign' => asset('public/assets/admin/img/900x400/img1.jpg'),
            'notification' => asset('public/assets/admin/img/900x400/img1.jpg'),
            'category' => asset('public/assets/admin/img/100x100/2.jpg'),
            'store' => asset('public/assets/admin/img/160x160/img1.jpg'),
            'vendor' => asset('public/assets/admin/img/160x160/img1.jpg'),
            'brand' => asset('public/assets/admin/img/100x100/2.jpg'),
            'upload_image' => asset('public/assets/admin/img/upload-img.png'),
            'store/cover' => asset('public/assets/admin/img/100x100/2.jpg'),
            'upload_image_4' => asset('/public/assets/admin/img/upload-4.png'),
            'promotional_banner' => asset('public/assets/admin/img/100x100/2.jpg'),
            'admin_feature' => asset('public/assets/admin/img/100x100/2.jpg'),
            'aspect_1' => asset('/public/assets/admin/img/aspect-1.png'),
            'special_criteria' => asset('public/assets/admin/img/100x100/2.jpg'),
            'download_user_app_image' => asset('public/assets/admin/img/100x100/2.jpg'),
            'reviewer_image' => asset('public/assets/admin/img/100x100/2.jpg'),
            'fixed_header_image' => asset('/public/assets/admin/img/aspect-1.png'),
            'header_icon' => asset('/public/assets/admin/img/aspect-1.png'),
            'available_zone_image' => asset('public/assets/admin/img/100x100/2.jpg'),
            'why_choose' => asset('/public/assets/admin/img/aspect-1.png'),
            'header_banner' => asset('/public/assets/admin/img/aspect-1.png'),
            'reviewer_company_image' => asset('public/assets/admin/img/100x100/2.jpg'),
            'module' => asset('public/assets/admin/img/100x100/2.jpg'),
            'parcel_category' => asset('/public/assets/admin/img/400x400/img2.jpg'),
            'favicon' => asset('/public/assets/admin/img/favicon.png'),
            'seller' => asset('public/assets/back-end/img/160x160/img1.jpg'),
            'upload_placeholder' => asset('/public/assets/admin/img/upload-placeholder.png'),
            'payment_modules/gateway_image' => asset('/public/assets/admin/img/payment/placeholder.png'),
            'email_template' => asset('/public/assets/admin/img/blank1.png'),
        ];
        try {
            if ($data && $type == 's3' && Storage::disk('s3')->exists($path . '/' . $data)) {
                return Storage::disk('s3')->url($path . '/' . $data);
//                $awsUrl = config('filesystems.disks.s3.url');
//                $awsBucket = config('filesystems.disks.s3.bucket');
//                return rtrim($awsUrl, '/') . '/' . ltrim($awsBucket . '/' . $path . '/' . $data, '/');
            }
        } catch (\Exception $e) {
        }
        // Check in public (live server)
        // if ($data && file_exists(public_path('storage/' . $path . '/' . $data))) {
        //     return asset('storage/' . $path . '/' . $data);
        // }

        // Check in storage (local with symlink)
        if ($data && Storage::disk('public')->exists($path . '/' . $data)) {
            return Storage::url($path . '/' . $data);
        }

        if (request()->is('api/*')) {
            return null;
        }

        if (isset($placeholder) && array_key_exists($placeholder, $place_holders)) {
            return $place_holders[$placeholder];
        } elseif (array_key_exists($path, $place_holders)) {
            return $place_holders[$path];
        } else {
            return $place_holders['default'];
        }

        return 'def.png';
    }

    public static function getSettingsDataFromConfig($settings, $relations = [])
    {
        try {
            if (!config($settings . '_conf')) {
                $data = BusinessSetting::where('key', $settings)->with($relations)->first();
                Config::set($settings . '_conf', $data);
            } else {
                $data = config($settings . '_conf');
            }
            return $data;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public static function logoFullUrl(){
        $logo = self::getSettingsDataFromConfig('logo',['storage']);
        return self::get_full_url('business', $logo?->value ?? '', $logo?->storage[0]?->value ?? 'public', 'favicon');
    }

    public static function iconFullUrl(){
        $icon = self::getSettingsDataFromConfig('icon',['storage']);
        return self::get_full_url('business', $icon?->value ?? '', $icon?->storage[0]?->value ?? 'public', 'favicon');
    }

    public static function get_settings($name)
    {
        return self::get_business_settings($name);
    }

    public static function module_permission_check($mod_name)
    {
        if (!auth('admin')->user()->role) {
            return false;
        }

        if ($mod_name == 'zone' && auth('admin')->user()->zone_id) {
            return false;
        }

        $permission = auth('admin')->user()->role->modules;
        if (isset($permission) && in_array($mod_name, (array)json_decode($permission)) == true) {
            return true;
        }

        if (auth('admin')->user()->role_id == 1) {
            return true;
        }
        return false;
    }

    public static function employee_module_permission_check($mod_name)
    {
        if (auth('vendor')->check()) {
            if ($mod_name == 'reviews') {
                return auth('vendor')->user()->stores[0]->reviews_section;
            } else if ($mod_name == 'deliveryman' || $mod_name == 'deliveryman_list') {
                return auth('vendor')->user()->stores[0]->self_delivery_system;
            } else if ($mod_name == 'pos') {
                return auth('vendor')->user()->stores[0]->pos_system;
            } else if ($mod_name == 'addon') {
                return config('module.' . auth('vendor')->user()->stores[0]->module->module_type)['add_on'];
            }
            return true;
        } else if (auth('vendor_employee')->check()) {
            $permission = auth('vendor_employee')->user()->role->modules;
            if (isset($permission) && in_array($mod_name, (array)json_decode($permission)) == true) {
                if ($mod_name == 'reviews') {
                    return auth('vendor_employee')->user()->store->reviews_section;
                } else if ($mod_name == 'deliveryman' || $mod_name == 'deliveryman_list') {
                    return auth('vendor_employee')->user()->store->self_delivery_system;
                } else if ($mod_name == 'pos') {
                    return auth('vendor_employee')->user()->store->pos_system;
                } else if ($mod_name == 'addon') {
                    return config('module.' . auth('vendor_employee')->user()->store->module->module_type)['add_on'];
                }
                return true;
            }
        }

        return false;
    }

    //Mail Config Check
    public static function remove_invalid_charcaters($str)
    {
        return str_ireplace(['\'', '"', ';', '<', '>'], ' ', $str);
    }

    public static function get_login_url($type)
    {
        $data = DataSetting::whereIn('key', ['store_employee_login_url', 'store_login_url', 'admin_employee_login_url', 'admin_login_url'
        ])->pluck('key', 'value')->toArray();

        return array_search($type, $data);
    }

    public static function update(string $dir, $old_image, string $format, $image = null)
    {
        if ($image == null) {
            return $old_image;
        }
        try {
            if (Storage::disk(self::getDisk())->exists($dir . $old_image)) {
                Storage::disk(self::getDisk())->delete($dir . $old_image);
            }
        } catch (\Exception $e) {
        }
        $imageName = Helpers::upload($dir, $format, $image);
        return $imageName;
    }

    // public static function upload(string $dir, string $format, $image = null)
    // {
    //     try {
    //         if ($image != null) {
    //             $format = $image->getClientOriginalExtension();
    //             $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
    //             if (!Storage::disk(self::getDisk())->exists($dir)) {
    //                 Storage::disk(self::getDisk())->makeDirectory($dir);
    //             }
    //             Storage::disk(self::getDisk())->putFileAs($dir, $image, $imageName, ['visibility' => 'public']);
    //         } else {
    //             $imageName = 'def.png';
    //         }
    //     } catch (\Exception $e) {
    //     }
    //     return $imageName;
    // }

    public static function upload(string $dir, string $format, $image = null)
    {
        try {
            if ($image != null) {
                $format = $image->getClientOriginalExtension();
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
                $path = public_path('storage/' . $dir);

                // create folder if not exist
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                // move file directly to public
                $image->move($path, $imageName);

            } else {
                $imageName = 'def.png';
            }
        } catch (\Exception $e) {
        }

        return $imageName;
    }

    public static function deleteCacheData($prefix)
    {
        $cacheKeys = DB::table('cache')
            ->where('key', 'like', "%" . $prefix . "%")
            ->pluck('key');
        $appName = env('APP_NAME') . '_cache';
        $remove_prefix = strtolower(str_replace('=', '', $appName));
        $sanitizedKeys = $cacheKeys->map(function ($key) use ($remove_prefix) {
            $key = str_replace($remove_prefix, '', $key);
            return $key;
        });
        foreach ($sanitizedKeys as $key) {
            Cache::forget($key);
        }
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
    }

    public static function get_store_data()
    {
        if (auth('vendor_employee')->check()) {
            return auth('vendor_employee')->user()->store;
        }
        return auth('vendor')->user()->stores[0];
    }

    public static function currency_code()
    {
        if (!config('currency') ){
            $currency = self::get_business_settings('currency');
            Config::set('currency', $currency );
        }
        else{
            $currency = config('currency');
        }

        return $currency;
    }

    public static function currency_symbol()
    {
        if (!config('currency_symbol')) {
            $currency_symbol = Currency::where(['currency_code' => Helpers::currency_code()])->first()?->currency_symbol;
            Config::set('currency_symbol', $currency_symbol);
        } else {
            $currency_symbol = config('currency_symbol');
        }
        return $currency_symbol;
    }

    public static function subscriptionPayment($store_id, $package_id, $payment_gateway, $url, $pending_bill = 0, $type = 'payment', $payment_platform = 'web')
    {
        $store = Store::where('id', $store_id)->first();
        $package = SubscriptionPackage::where('id', $package_id)->first();
        $type == null ? 'payment' : $type;

        $payer = new Payer(
            $store->name,
            $store->email,
            $store->phone,
            ''
        );
        $store_logo = BusinessSetting::where(['key' => 'logo'])->first();
        $additional_data = [
            'business_name' => self::get_business_settings('business_name'),
            'business_logo' => self::get_full_url('business',$store_logo?->value,$store_logo?->storage[0]?->value ?? 'public')
        ];
        $payment_info = new PaymentInfo(
            success_hook: 'sub_success',
            failure_hook: 'sub_fail',
            currency_code: Helpers::currency_code(),
            payment_method: $payment_gateway,
            payment_platform: $payment_platform,
            payer_id: $store->id,
            receiver_id: $package->id,
            additional_data: $additional_data,
            payment_amount: $package->price + $pending_bill,
            external_redirect_link: $url,
            attribute: 'store_subscription_' . $type,
            attribute_id: $package->id,
        );
        $receiver_info = new Receiver('Admin', 'example.png');
        $redirect_link = Payment::generate_link($payer, $payment_info, $receiver_info);

        return $redirect_link;
    }

    public static function random_icon($key = null)
    {
        $icons = [
            'bi-star-fill',
            'bi-shop',
            'bi-shop-window',
            'bi-egg-fried',
            'bi-cup-hot-fill',
            'bi-emoji-smile-fill',

            // 🍕 Fast food / meals
            'bi-pizza-slice',
            'bi-burger',
            'bi-basket-fill',
            'bi-bag-fill',
            'bi-box-seam',
            // 🍚 Indian / main course vibes
            'bi-bowl-hot',
            'bi-fire',
            'bi-award-fill',
            'bi-stars',
            // 🥗 Veg / healthy
            'bi-flower1',
            'bi-leaf-fill',
            'bi-droplet-fill',
            // 🍰 Desserts
            'bi-cake2-fill',
            'bi-balloon-fill',
            'bi-heart-fill',
            // 🥤 Drinks
            'bi-cup-straw',
            'bi-glass-water',
            'bi-moon-stars-fill',
            // 🛒 Ordering / delivery
            'bi-cart-fill',
            'bi-truck',
            'bi-clock-fill',
            'bi-lightning-fill',
        ];

        // 🔁 Fixed icon if key provided (category id, slug, index)
        if ($key !== null) {
            return $icons[$key % count($icons)];
        }

        // 🎲 Random icon
        return $icons[array_rand($icons)];
    }

    public static function subscription_plan_chosen($store_id, $package_id, $payment_method, $discount = 0, $pending_bill = 0, $reference = null, $type = null)
    {
        $store = Store::find($store_id);
        $package = SubscriptionPackage::withoutGlobalScope('translate')->find($package_id);
        $add_days = 0;
        $add_orders = 0;

        try {
            $store_subscription = $store->store_sub;
            $store_old_subscription = $store->store_sub_update_application;
            if (isset($store_subscription) && $type == 'renew') {
                $store_subscription->total_package_renewed = $store_subscription->total_package_renewed + 1;

                $day_left = $store_subscription->expiry_date_parsed->format('Y-m-d');
                if (Carbon::now()->diffInDays($day_left, false) > 0 && $store_subscription->is_canceled != 1) {
                    $add_days = Carbon::now()->subDays(1)->diffInDays($day_left, false);
                }
                if ($store_subscription->max_order != 'unlimited' && $store_subscription->max_order > 0) {
                    $add_orders = $store_subscription->max_order;
                }

            } elseif ($store_old_subscription && $store_old_subscription->package_id == $package->id && $type == 'renew') {
                $store_subscription = $store_old_subscription;
                $store_subscription->total_package_renewed = $store_subscription->total_package_renewed + 1;
            } else {
                self::calculateSubscriptionRefundAmount($store);
                StoreSubscription::where('store_id', $store->id)->update([
                    'status' => 0,
                ]);
                $store_subscription = new StoreSubscription();
                $store_subscription->total_package_renewed = 0;

                }

            $store_subscription->is_trial= 0;
            $store_subscription->renewed_at=now();
            $store_subscription->package_id=$package->id;
            $store_subscription->store_id=$store->id;
            if ($payment_method  == 'free_trial' ) {

                $free_trial_period= (int) self::get_business_settings('subscription_free_trial_days') ?? 1;

                $store_subscription->expiry_date= Carbon::now()->addDays($free_trial_period)->format('Y-m-d');
                $store_subscription->validity= $free_trial_period;
            }

            $store_subscription->is_trial = 0;
            $store_subscription->renewed_at = now();
            $store_subscription->package_id = $package->id;
            $store_subscription->store_id = $store->id;
            if ($payment_method == 'free_trial') {

                $free_trial_period = (int) self::get_business_settings('subscription_free_trial_days') ?? 1;

                $store_subscription->expiry_date = Carbon::now()->addDays($free_trial_period)->format('Y-m-d');
                $store_subscription->validity = $free_trial_period;
            } else {
                $store_subscription->expiry_date = Carbon::now()->addDays((int) ($package->validity + $add_days))->format('Y-m-d');
                $store_subscription->validity = $package->validity + $add_days;
            }
            if ($package->max_order != 'unlimited') {
                $store_subscription->max_order = $package->max_order + $add_orders;
            } else {
                $store_subscription->max_order = $package->max_order;
            }


            $store_subscription->max_product = $package->max_product;
            $store_subscription->pos = $package->pos;
            $store_subscription->mobile_app = $package->mobile_app;
            $store_subscription->chat = $package->chat;
            $store_subscription->review = $package->review;
            $store_subscription->self_delivery = $package->self_delivery;
            $store_subscription->is_canceled = 0;
            $store_subscription->canceled_by = 'none';

            $store->item_section = 1;
            $store->pos_system = 1;
            if ($type == 'new_join' && $store->vendor?->status == 0) {
                $store->status = 0;
                $store_subscription->status = 0;

            } else {
                $store->status = 1;
                $store_subscription->status = 1;

            }

            // For Store Free Delivery
            if ($store->free_delivery == 1 && $package->self_delivery == 1) {
                $store->free_delivery = 1;
            } else {
                $store->free_delivery = 0;
                $store->coupon()->where('created_by', 'vendor')->where('coupon_type', 'free_delivery')->delete();
            }


            $store->package_id = $package->id;
            $store->reviews_section = 1;
            $store->self_delivery_system = 1;
            $store->store_business_model = 'subscription';

            $subscription_transaction = new SubscriptionTransaction();

            $subscription_transaction->package_id = $package->id;
            $subscription_transaction->store_id = $store->id;
            $subscription_transaction->price = $package->price;

            $subscription_transaction->validity = $package->validity;
            $subscription_transaction->paid_amount = $package->price - (($package->price * $discount) / 100) + $pending_bill;

            $subscription_transaction->payment_status = 'success';
            $subscription_transaction->created_by = in_array($payment_method, ['wallet_payment_by_admin', 'manual_payment_by_admin', 'plan_shift_by_admin']) ? 'Admin' : 'Store';

            if ($payment_method == 'free_trial') {
                $subscription_transaction->validity = $free_trial_period;
                $subscription_transaction->paid_amount = 0;
                $subscription_transaction->is_trial = 1;
                $store_subscription->is_trial = 1;
            } elseif ($payment_method == 'pay_now') {
                $subscription_transaction->payment_status = 'on_hold';
                $subscription_transaction->transaction_status = 0;
                $store_subscription->status = 0;
            }


            $subscription_transaction->payment_method = $payment_method;
            $subscription_transaction->reference = $reference ?? null;
            $subscription_transaction->discount = $discount ?? 0;
            if (in_array($type, ['renew', 'free_trial'])) {
                $subscription_transaction->plan_type = $type;
            } elseif (StoreSubscription::where('store_id', $store->id)->where('is_trial', 0)->count() > 0 || $reference == 'plan_shift_by_admin') {
                $subscription_transaction->plan_type = 'new_plan';
            }


            $subscription_transaction->package_details = [
                'pos' => $package->pos,
                'review' => $package->review,
                'self_delivery' => $package->self_delivery,
                'chat' => $package->chat,
                'mobile_app' => $package->mobile_app,
                'max_order' => $package->max_order,
                'max_product' => $package->max_product,
            ];
            DB::beginTransaction();
            $store->save();
            $subscription_transaction->save();
            $store_subscription->save();
            DB::commit();
            $subscription_transaction->store_subscription_id = $store_subscription->id;
            $subscription_transaction->save();

            SubscriptionBillingAndRefundHistory::where(['store_id' => $store->id,
                'transaction_type' => 'pending_bill', 'is_success' => 0])->update([
                'is_success' => 1,
                'reference' => 'payment_via_' . $payment_method . ' _transaction_id_' . $subscription_transaction->id
            ]);

            if ($reference == 'plan_shift_by_admin') {
                $billing = new SubscriptionBillingAndRefundHistory();
                $billing->store_id = $store->id;
                $billing->subscription_id = $store_subscription->id;
                $billing->package_id = $store_subscription->package_id;
                $billing->transaction_type = 'pending_bill';
                $billing->is_success = 0;
                $billing->amount = $package->price;
                $billing->save();
            }


        } catch (\Exception $e) {
            DB::rollBack();
            info(["line___{$e->getLine()}", $e->getMessage()]);
            return false;
        }


        if (data_get(self::subscriptionConditionsCheck(store_id: $store->id, package_id: $package->id), 'disable_item_count') > 0) {
            $disable_item_count = data_get(Helpers::subscriptionConditionsCheck(store_id: $store->id, package_id: $package->id), 'disable_item_count');
            $store->item_section = 0;
            $store->save();
            Item::where('store_id', $store->id)->oldest()->take($disable_item_count)->update([
                    'status' => 0
                ]);
            // if ($store->module_type == 'rental') {
            //     Vehicle::where('provider_id', $store->id)->oldest()->take($disable_item_count)->update([
            //         'status' => 0
            //     ]);
            // } else {
            //     Item::where('store_id', $store->id)->oldest()->take($disable_item_count)->update([
            //         'status' => 0
            //     ]);
            // }
        }

        if (!(in_array($payment_method, ['manual_payment_by_admin', 'plan_shift_by_admin']) && $store_old_subscription == null)) {
            self::subscriptionNotifications($store, $type, $subscription_transaction);
        }

        return $subscription_transaction->id;
    }

    public static function calculateSubscriptionRefundAmount($store, $return_data = null)
    {

        $store_subscription = $store->store_sub;
        if ($store_subscription && $store_subscription?->is_canceled === 0 && $store_subscription?->is_trial === 0) {
            $day_left = $store_subscription->expiry_date_parsed->format('Y-m-d');
            if (Carbon::now()->diffInDays($day_left, false) > 0) {
                $add_days= Carbon::now()->diffInDays($day_left, false);
                $validity=$store_subscription?->validity;
                $subscription_usage_max_time= self::get_business_settings('subscription_usage_max_time')  ?? 50 ;
                $subscription_usage_max_time=  ($validity * $subscription_usage_max_time) /100 ;

                if (($validity - $add_days) < $subscription_usage_max_time) {
                    $per_day = $store->store_sub_trans->price / $store->store_sub_trans->validity;
                    $back_amount = $per_day * $add_days;

                    if ($return_data == true) {
                        return ['back_amount' => $back_amount, 'days' => $add_days];
                    }

                    $vendorWallet = StoreWallet::firstOrNew(
                        ['vendor_id' => $store->vendor_id]
                    );
                    $vendorWallet->total_earning = $vendorWallet->total_earning + $back_amount;
                    $vendorWallet->save();

                    $refund = new SubscriptionBillingAndRefundHistory();
                    $refund->store_id = $store->id;
                    $refund->subscription_id = $store_subscription->id;
                    $refund->package_id = $store_subscription->package_id;
                    $refund->transaction_type = 'refund';
                    $refund->is_success = 1;
                    $refund->amount = $back_amount;
                    $refund->reference = 'validity_left_' . $add_days;
                    $refund->save();

                }
            }

        }

        return true;
    }

    public static function subscriptionConditionsCheck($store_id, $package_id)
    {
        $store = Store::findOrFail($store_id);
        $package = SubscriptionPackage::withoutGlobalScope('translate')->find($package_id);
        if ($store->module_type == 'rental') {
            $total_food = $store->vehicles()->count();
        } else {
            $total_food = $store->items()->withoutGlobalScope(\App\Scopes\StoreScope::class)->count();
        }
        if ($package->max_product != 'unlimited' && $total_food >= $package->max_product) {
            return ['disable_item_count' => $total_food - $package->max_product];
        }
        return null;
    }

    public static function subscriptionNotifications($store, $type, $subscription_transaction)
    {
        try {
            if ($type == 'renew') {
                $push_notification_status = $store->module->module_type !== 'rental' ? self::getNotificationStatusData('store', 'store_subscription_renew', 'push_notification_status', $store->id) : self::getRentalNotificationStatusData('provider', 'provider_subscription_renew', 'push_notification_status', $store->id);
                $title = translate('subscription_renewed');
                $des = translate('Your_subscription_successfully_renewed');
            } elseif ($type != 'renew') {
                $des = translate('Your_subscription_successfully_shifted');
                $title = translate('subscription_shifted');
                $push_notification_status = $store->module->module_type !== 'rental' ? self::getNotificationStatusData('store', 'store_subscription_shift', 'push_notification_status', $store->id) : self::getRentalNotificationStatusData('provider', 'provider_subscription_shift', 'push_notification_status', $store->id);
            }

            // if ($push_notification_status && $store?->vendor?->firebase_token) {
            //     $data = [
            //         'title' => $title ?? '',
            //         'description' => $des ?? '',
            //         'order_id' => '',
            //         'image' => '',
            //         'type' => 'subscription',
            //         'order_status' => '',
            //     ];
            //     self::send_push_notif_to_device($store?->vendor?->firebase_token, $data);
            //     DB::table('user_notifications')->insert([
            //         'data' => json_encode($data),
            //         'vendor_id' => $store?->vendor_id,
            //         'created_at' => now(),
            //         'updated_at' => now()
            //     ]);
            // }


            if ($store->module->module_type !== 'rental' && config('mail.status')) {

                // if (self::get_mail_status('subscription_renew_mail_status_store') == '1' && $type == 'renew' && self::getNotificationStatusData('store', 'store_subscription_renew', 'mail_status', $store->id)) {
                //     Mail::to($store->email)->send(new SubscriptionRenewOrShift($type, $store->name));
                // }
                // if (self::get_mail_status('subscription_shift_mail_status_store') == '1' && $type != 'renew' && self::getNotificationStatusData('store', 'store_subscription_shift', 'mail_status', $store->id)) {
                //     Mail::to($store->email)->send(new SubscriptionRenewOrShift($type, $store->name));
                // }
                // if (self::get_mail_status('subscription_successful_mail_status_store') == '1' && self::getNotificationStatusData('store', 'store_subscription_success', 'mail_status', $store->id)) {
                //     $url = route('subscription_invoice', ['id' => base64_encode($subscription_transaction->id)]);
                //     Mail::to($store->email)->send(new SubscriptionSuccessful($store->name, $url));
                // }


            }

        } catch (\Exception $ex) {
            info($ex->getMessage());
        }
        return true;
    }

    public static function get_mail_status($name)
    {
        return  self::get_business_settings($name);
    }

    public static function get_store_id()
    {
        if (auth('vendor_employee')->check()) {
            return auth('vendor_employee')->user()->store->id;
        }
        return auth('vendor')->user()->stores[0]->id;
    }

    public static function get_loggedin_user()
    {
        if (auth('vendor')->check()) {
            return auth('vendor')->user();
        } else if (auth('vendor_employee')->check()) {
            return auth('vendor_employee')->user();
        }
        return 0;
    }

    public static function get_vendor_data()
    {
        if (auth('vendor')->check()) {
            return auth('vendor')->user();
        } else if (auth('vendor_employee')->check()) {
            return auth('vendor_employee')->user()->vendor;
        }
        return 0;
    }

    public static function businessUpdateOrInsert($key, $value)
    {
        $businessSetting = BusinessSetting::firstOrNew(['key' => $key['key']]);
        $businessSetting->value = $value['value'];
        $businessSetting->save();
    }

    public static function commission_check()
    {
        $commission_business_model=  self::get_business_settings('commission_business_model');
        if($commission_business_model == null ){
            Helpers::insert_business_settings_key('commission_business_model', '1');
            $commission_business_model=  self::get_business_settings('commission_business_model');
        }
        return $commission_business_model ?? 1;
    }

    public static function insert_business_settings_key($key, $value = null)
    {
        $data = BusinessSetting::where('key', $key)->first();
        if (!$data) {
            Helpers::businessUpdateOrInsert(['key' => $key], [
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return true;
    }

    public static function subscription_check()
    {
        $subscription_business_model= self::get_business_settings('subscription_business_model');
        if($subscription_business_model == null ){
            Helpers::insert_business_settings_key('subscription_business_model', '1');
            $subscription_business_model= self::get_business_settings('subscription_business_model');
        }
        return $subscription_business_model ?? 1;

    }

    public static function gen_mpdf($view, $file_prefix, $file_postfix)
    {
        $mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../storage/tmp', 'default_font' => 'Inter', 'mode' => 'utf-8', 'format' => [190, 250]]);
        /* $mpdf->AddPage('XL', '', '', '', '', 10, 10, 10, '10', '270', '');*/
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;

        $mpdf_view = $view;
        $mpdf_view = $mpdf_view->render();
        $mpdf->WriteHTML($mpdf_view);
        $mpdf->Output($file_prefix . $file_postfix . '.pdf', 'D');
    }

    public static function time_date_format($data)
    {
        $time = config('timeformat') ?? 'H:i';
        return Carbon::parse($data)->locale(app()->getLocale())->translatedFormat('d M Y ' . $time);
    }

    public static function date_format($data)
    {
        return Carbon::parse($data)->locale(app()->getLocale())->translatedFormat('d M Y');
    }

    public static function time_format($data)
    {
        $time = config('timeformat') ?? 'H:i';
        return Carbon::parse($data)->locale(app()->getLocale())->translatedFormat($time);
    }

    public static function get_language_name($key)
    {
        $languages = array(
            "af" => "Afrikaans",
            "sq" => "Albanian - shqip",
            "am" => "Amharic - አማርኛ",
            "ar" => "Arabic - العربية",
            "an" => "Aragonese - aragonés",
            "hy" => "Armenian - հայերեն",
            "ast" => "Asturian - asturianu",
            "az" => "Azerbaijani - azərbaycan dili",
            "eu" => "Basque - euskara",
            "be" => "Belarusian - беларуская",
            "bn" => "Bengali - বাংলা",
            "bs" => "Bosnian - bosanski",
            "br" => "Breton - brezhoneg",
            "bg" => "Bulgarian - български",
            "ca" => "Catalan - català",
            "ckb" => "Central Kurdish - کوردی (دەستنوسی عەرەبی)",
            "zh" => "Chinese - 中文",
            "zh-HK" => "Chinese (Hong Kong) - 中文（香港）",
            "zh-CN" => "Chinese (Simplified) - 中文（简体）",
            "zh-TW" => "Chinese (Traditional) - 中文（繁體）",
            "co" => "Corsican",
            "hr" => "Croatian - hrvatski",
            "cs" => "Czech - čeština",
            "da" => "Danish - dansk",
            "nl" => "Dutch - Nederlands",
            "en" => "English",
            "en-AU" => "English (Australia)",
            "en-CA" => "English (Canada)",
            "en-IN" => "English (India)",
            "en-NZ" => "English (New Zealand)",
            "en-ZA" => "English (South Africa)",
            "en-GB" => "English (United Kingdom)",
            "en-US" => "English (United States)",
            "eo" => "Esperanto - esperanto",
            "et" => "Estonian - eesti",
            "fo" => "Faroese - føroyskt",
            "fil" => "Filipino",
            "fi" => "Finnish - suomi",
            "fr" => "French - français",
            "fr-CA" => "French (Canada) - français (Canada)",
            "fr-FR" => "French (France) - français (France)",
            "fr-CH" => "French (Switzerland) - français (Suisse)",
            "gl" => "Galician - galego",
            "ka" => "Georgian - ქართული",
            "de" => "German - Deutsch",
            "de-AT" => "German (Austria) - Deutsch (Österreich)",
            "de-DE" => "German (Germany) - Deutsch (Deutschland)",
            "de-LI" => "German (Liechtenstein) - Deutsch (Liechtenstein)",
            "de-CH" => "German (Switzerland) - Deutsch (Schweiz)",
            "el" => "Greek - Ελληνικά",
            "gn" => "Guarani",
            "gu" => "Gujarati - ગુજરાતી",
            "ha" => "Hausa",
            "haw" => "Hawaiian - ʻŌlelo Hawaiʻi",
            "he" => "Hebrew - עברית",
            "hi" => "Hindi - हिन्दी",
            "hu" => "Hungarian - magyar",
            "is" => "Icelandic - íslenska",
            "id" => "Indonesian - Indonesia",
            "ia" => "Interlingua",
            "ga" => "Irish - Gaeilge",
            "it" => "Italian - italiano",
            "it-IT" => "Italian (Italy) - italiano (Italia)",
            "it-CH" => "Italian (Switzerland) - italiano (Svizzera)",
            "ja" => "Japanese - 日本語",
            "kn" => "Kannada - ಕನ್ನಡ",
            "kk" => "Kazakh - қазақ тілі",
            "km" => "Khmer - ខ្មែរ",
            "ko" => "Korean - 한국어",
            "ku" => "Kurdish - Kurdî",
            "ky" => "Kyrgyz - кыргызча",
            "lo" => "Lao - ລາວ",
            "la" => "Latin",
            "lv" => "Latvian - latviešu",
            "ln" => "Lingala - lingála",
            "lt" => "Lithuanian - lietuvių",
            "mk" => "Macedonian - македонски",
            "ms" => "Malay - Bahasa Melayu",
            "ml" => "Malayalam - മലയാളം",
            "mt" => "Maltese - Malti",
            "mr" => "Marathi - मराठी",
            "mn" => "Mongolian - монгол",
            "ne" => "Nepali - नेपाली",
            "no" => "Norwegian - norsk",
            "nb" => "Norwegian Bokmål - norsk bokmål",
            "nn" => "Norwegian Nynorsk - nynorsk",
            "oc" => "Occitan",
            "or" => "Oriya - ଓଡ଼ିଆ",
            "om" => "Oromo - Oromoo",
            "ps" => "Pashto - پښتو",
            "fa" => "Persian - فارسی",
            "pl" => "Polish - polski",
            "pt" => "Portuguese - português",
            "pt-BR" => "Portuguese (Brazil) - português (Brasil)",
            "pt-PT" => "Portuguese (Portugal) - português (Portugal)",
            "pa" => "Punjabi - ਪੰਜਾਬੀ",
            "qu" => "Quechua",
            "ro" => "Romanian - română",
            "mo" => "Romanian (Moldova) - română (Moldova)",
            "rm" => "Romansh - rumantsch",
            "ru" => "Russian - русский",
            "gd" => "Scottish Gaelic",
            "sr" => "Serbian - српски",
            "sh" => "Serbo-Croatian - Srpskohrvatski",
            "sn" => "Shona - chiShona",
            "sd" => "Sindhi",
            "si" => "Sinhala - සිංහල",
            "sk" => "Slovak - slovenčina",
            "sl" => "Slovenian - slovenščina",
            "so" => "Somali - Soomaali",
            "st" => "Southern Sotho",
            "es" => "Spanish - español",
            "es-AR" => "Spanish (Argentina) - español (Argentina)",
            "es-419" => "Spanish (Latin America) - español (Latinoamérica)",
            "es-MX" => "Spanish (Mexico) - español (México)",
            "es-ES" => "Spanish (Spain) - español (España)",
            "es-US" => "Spanish (United States) - español (Estados Unidos)",
            "su" => "Sundanese",
            "sw" => "Swahili - Kiswahili",
            "sv" => "Swedish - svenska",
            "tg" => "Tajik - тоҷикӣ",
            "ta" => "Tamil - தமிழ்",
            "tt" => "Tatar",
            "te" => "Telugu - తెలుగు",
            "th" => "Thai - ไทย",
            "ti" => "Tigrinya - ትግርኛ",
            "to" => "Tongan - lea fakatonga",
            "tr" => "Turkish - Türkçe",
            "tk" => "Turkmen",
            "tw" => "Twi",
            "uk" => "Ukrainian - українська",
            "ur" => "Urdu - اردو",
            "ug" => "Uyghur",
            "uz" => "Uzbek - o‘zbek",
            "vi" => "Vietnamese - Tiếng Việt",
            "wa" => "Walloon - wa",
            "cy" => "Welsh - Cymraeg",
            "fy" => "Western Frisian",
            "xh" => "Xhosa",
            "yi" => "Yiddish",
            "yo" => "Yoruba - Èdè Yorùbá",
            "zu" => "Zulu - isiZulu",
        );
        return array_key_exists($key, $languages) ? $languages[$key] : $key;
    }

    public static function getLanguageCode(string $country_code): string
    {
        $locales = array(
            'en-English(default)',
            'af-Afrikaans',
            'sq-Albanian - shqip',
            'am-Amharic - አማርኛ',
            'ar-Arabic - العربية',
            'an-Aragonese - aragonés',
            'hy-Armenian - հայերեն',
            'ast-Asturian - asturianu',
            'az-Azerbaijani - azərbaycan dili',
            'eu-Basque - euskara',
            'be-Belarusian - беларуская',
            'bn-Bengali - বাংলা',
            'bs-Bosnian - bosanski',
            'br-Breton - brezhoneg',
            'bg-Bulgarian - български',
            'ca-Catalan - català',
            'ckb-Central Kurdish - کوردی (دەستنوسی عەرەبی)',
            'zh-Chinese - 中文',
            'zh-HK-Chinese (Hong Kong) - 中文（香港）',
            'zh-CN-Chinese (Simplified) - 中文（简体）',
            'zh-TW-Chinese (Traditional) - 中文（繁體）',
            'co-Corsican',
            'hr-Croatian - hrvatski',
            'cs-Czech - čeština',
            'da-Danish - dansk',
            'nl-Dutch - Nederlands',
            'en-AU-English (Australia)',
            'en-CA-English (Canada)',
            'en-IN-English (India)',
            'en-NZ-English (New Zealand)',
            'en-ZA-English (South Africa)',
            'en-GB-English (United Kingdom)',
            'en-US-English (United States)',
            'eo-Esperanto - esperanto',
            'et-Estonian - eesti',
            'fo-Faroese - føroyskt',
            'fil-Filipino',
            'fi-Finnish - suomi',
            'fr-French - français',
            'fr-CA-French (Canada) - français (Canada)',
            'fr-FR-French (France) - français (France)',
            'fr-CH-French (Switzerland) - français (Suisse)',
            'gl-Galician - galego',
            'ka-Georgian - ქართული',
            'de-German - Deutsch',
            'de-AT-German (Austria) - Deutsch (Österreich)',
            'de-DE-German (Germany) - Deutsch (Deutschland)',
            'de-LI-German (Liechtenstein) - Deutsch (Liechtenstein)
            ',
            'de-CH-German (Switzerland) - Deutsch (Schweiz)',
            'el-Greek - Ελληνικά',
            'gn-Guarani',
            'gu-Gujarati - ગુજરાતી',
            'ha-Hausa',
            'haw-Hawaiian - ʻŌlelo Hawaiʻi',
            'he-Hebrew - עברית',
            'hi-Hindi - हिन्दी',
            'hu-Hungarian - magyar',
            'is-Icelandic - íslenska',
            'id-Indonesian - Indonesia',
            'ia-Interlingua',
            'ga-Irish - Gaeilge',
            'it-Italian - italiano',
            'it-IT-Italian (Italy) - italiano (Italia)',
            'it-CH-Italian (Switzerland) - italiano (Svizzera)',
            'ja-Japanese - 日本語',
            'kn-Kannada - ಕನ್ನಡ',
            'kk-Kazakh - қазақ тілі',
            'km-Khmer - ខ្មែរ',
            'ko-Korean - 한국어',
            'ku-Kurdish - Kurdî',
            'ky-Kyrgyz - кыргызча',
            'lo-Lao - ລາວ',
            'la-Latin',
            'lv-Latvian - latviešu',
            'ln-Lingala - lingála',
            'lt-Lithuanian - lietuvių',
            'mk-Macedonian - македонски',
            'ms-Malay - Bahasa Melayu',
            'ml-Malayalam - മലയാളം',
            'mt-Maltese - Malti',
            'mr-Marathi - मराठी',
            'mn-Mongolian - монгол',
            'ne-Nepali - नेपाली',
            'no-Norwegian - norsk',
            'nb-Norwegian Bokmål - norsk bokmål',
            'nn-Norwegian Nynorsk - nynorsk',
            'oc-Occitan',
            'or-Oriya - ଓଡ଼ିଆ',
            'om-Oromo - Oromoo',
            'ps-Pashto - پښتو',
            'fa-Persian - فارسی',
            'pl-Polish - polski',
            'pt-Portuguese - português',
            'pt-BR-Portuguese (Brazil) - português (Brasil)',
            'pt-PT-Portuguese (Portugal) - português (Portugal)',
            'pa-Punjabi - ਪੰਜਾਬੀ',
            'qu-Quechua',
            'ro-Romanian - română',
            'mo-Romanian (Moldova) - română (Moldova)',
            'rm-Romansh - rumantsch',
            'ru-Russian - русский',
            'gd-Scottish Gaelic',
            'sr-Serbian - српски',
            'sh-Serbo-Croatian - Srpskohrvatski',
            'sn-Shona - chiShona',
            'sd-Sindhi',
            'si-Sinhala - සිංහල',
            'sk-Slovak - slovenčina',
            'sl-Slovenian - slovenščina',
            'so-Somali - Soomaali',
            'st-Southern Sotho',
            'es-Spanish - español',
            'es-AR-Spanish (Argentina) - español (Argentina)',
            'es-419-Spanish (Latin America) - español (Latinoamérica)
            ',
            'es-MX-Spanish (Mexico) - español (México)',
            'es-ES-Spanish (Spain) - español (España)',
            'es-US-Spanish (United States) - español (Estados Unidos)
            ',
            'su-Sundanese',
            'sw-Swahili - Kiswahili',
            'sv-Swedish - svenska',
            'tg-Tajik - тоҷикӣ',
            'ta-Tamil - தமிழ்',
            'tt-Tatar',
            'te-Telugu - తెలుగు',
            'th-Thai - ไทย',
            'ti-Tigrinya - ትግርኛ',
            'to-Tongan - lea fakatonga',
            'tr-Turkish - Türkçe',
            'tk-Turkmen',
            'tw-Twi',
            'uk-Ukrainian - українська',
            'ur-Urdu - اردو',
            'ug-Uyghur',
            'uz-Uzbek - o‘zbek',
            'vi-Vietnamese - Tiếng Việt',
            'wa-Walloon - wa',
            'cy-Welsh - Cymraeg',
            'fy-Western Frisian',
            'xh-Xhosa',
            'yi-Yiddish',
            'yo-Yoruba - Èdè Yorùbá',
            'zu-Zulu - isiZulu',
        );

        foreach ($locales as $locale) {
            $locale_region = explode('-', $locale);
            if ($country_code == $locale_region[0]) {
                return $locale_region[0];
            }
        }

        return "en";
    }

    public static function auto_translator($q, $sl, $tl)
    {
        $res = file_get_contents("https://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=" . $sl . "&tl=" . $tl . "&hl=hl&q=" . urlencode($q), $_SERVER['DOCUMENT_ROOT'] . "/transes.html");
        $res = json_decode($res);
        return str_replace('_', ' ', $res[0][0][0]);
    }

    public static function language_load()
    {
        if (\session()->has('language_settings')) {
            $language = \session('language_settings');
        } else {
            $language = BusinessSetting::where('key', 'system_language')->first();
            \session()->put('language_settings', $language);
        }
        return $language;
    }

    public static function vendor_language_load()
    {
        if (\session()->has('vendor_language_settings')) {
            $language = \session('vendor_language_settings');
        } else {
            $language = BusinessSetting::where('key', 'system_language')->first();
            \session()->put('vendor_language_settings', $language);
        }
        return $language;
    }

    public static function landing_language_load()
    {
        if (\session()->has('landing_language_settings')) {
            $language = \session('landing_language_settings');
        } else {
            $language = BusinessSetting::where('key', 'system_language')->first();
            \session()->put('landing_language_settings', $language);
        }
        return $language;
    }

    public static function format_currency($value)
    {
        if (!config('currency_symbol_position') ){
            $currency_symbol_position = self::get_business_settings('currency_symbol_position');
            Config::set('currency_symbol_position', $currency_symbol_position );
        }
        else{
            $currency_symbol_position =config('currency_symbol_position');
        }

        return $currency_symbol_position == 'right' ? number_format($value, config('round_up_to_digit')) . ' ' . self::currency_symbol() : self::currency_symbol() . ' ' . number_format($value, config('round_up_to_digit'));
    }

    public static function getNotificationStatusData($user_type, $key, $notification_type, $store_id = null)
    {
        $data = NotificationSetting::where('type', $user_type)->where('key', $key)->select($notification_type)->first();
        $data = $data?->{$notification_type} === 'active' ? 1 : 0;

        if ($store_id && $user_type == 'store' && $data === 1) {
            $data = self::getStoreNotificationStatusData(store_id: $store_id, key: $key, notification_type: $notification_type);
            $data = $data?->{$notification_type} === 'active' ? 1 : 0;
        }

        return $data;
    }

    public static function system_default_language()
    {
        $languages = self::get_business_settings('system_language');
        $lang = 'en';

        foreach ($languages as $key => $language) {
            if ($language['default']) {
                $lang = $language['code'];
            }
        }
        return $lang;
    }

    public static function getRentalNotificationStatusData($user_type, $key, $notification_type, $store_id = null)
    {
        $data = NotificationSetting::where(['type' => $user_type, 'module_type' => 'rental', 'key' => $key])->select($notification_type)->first();
        $data = $data?->{$notification_type} === 'active' ? 1 : 0;

        if ($store_id && $user_type == 'provider' && $data === 1) {
            $data = self::getRentalStoreNotificationStatusData(store_id: $store_id, key: $key, notification_type: $notification_type);
            $data = $data?->{$notification_type} === 'active' ? 1 : 0;
        }

        return $data;
    }

    public static function getNotificationStatusDataAdmin($user_type, $key)
    {
        $data = NotificationSetting::where(['type' => $user_type, 'key' => $key])->select(['mail_status', 'push_notification_status', 'sms_status'])->first();
        return $data ?? null;
    }


    public static function notificationDataSetup()
    {

        $data = self::getAdminNotificationSetupData();
        $data = NotificationSetting::upsert($data, ['key', 'type'], ['title', 'mail_status', 'sms_status', 'push_notification_status', 'sub_title']);
        return true;
    }

    public static function storeNotificationDataSetup($id)
    {
        $data = self::getStoreNotificationSetupData($id);
        $data = StoreNotificationSetting::upsert($data, ['key', 'store_id'], ['title', 'mail_status', 'sms_status', 'push_notification_status', 'sub_title']);
        return true;
    }

    public static function storeRentalNotificationDataSetup($id)
    {
        $data = self::getRentalStoreNotificationSetupData($id);
        $data = StoreNotificationSetting::upsert($data, ['key', 'store_id', 'module_type'], ['title', 'mail_status', 'sms_status', 'push_notification_status', 'sub_title']);
        return true;
    }

    public static function updateAdminNotificationSetupDataSetup()
    {
        self::updateAdminNotificationSetupData();
        return true;
    }

    public static function addNewAdminNotificationSetupDataSetup()
    {
        self::addNewAdminNotificationSetupData();
        return true;
    }

    public static function getRentalAdminNotificationSetupDatasetup()
    {
        self::getRentalAdminNotificationSetupData();
        return true;
    }

    public static function getStoreNotificationStatusData($store_id, $key, $notification_type)
    {
        $data = StoreNotificationSetting::where('store_id', $store_id)->where('key', $key)->select($notification_type)->first();
        if (!$data) {
            self::storeNotificationDataSetup($store_id);
            $data = StoreNotificationSetting::where('store_id', $store_id)->where('key', $key)->select($notification_type)->first();
        }
        return $data ?? null;
    }

    public static function getRentalStoreNotificationStatusData($store_id, $key, $notification_type)
    {
        $data = StoreNotificationSetting::where('store_id', $store_id)->where('key', $key)->select($notification_type)->first();
        if (!$data) {
            self::storeRentalNotificationDataSetup($store_id);
            $data = StoreNotificationSetting::where('store_id', $store_id)->where('key', $key)->select($notification_type)->first();
        }
        return $data ?? null;
    }


    public static function combinations($arrays)
    {
        $result = [[]];
        foreach ($arrays as $property => $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, [$property => $property_value]);
                }
            }
            $result = $tmp;
        }
        return $result;
    }
    public static function check_and_delete(string $dir, $old_image)
    {

        try {
            if (Storage::disk('public')->exists($dir . $old_image)) {
                Storage::disk('public')->delete($dir . $old_image);
            }
            // if (Storage::disk('s3')->exists($dir . $old_image)) {
            //     Storage::disk('s3')->delete($dir . $old_image);
            // }
        } catch (\Exception $e) {
        }

        return true;
    }

    public static function get_stores_name($stores)
    {
        if (is_array($stores)) {
            $data = Store::whereIn('id', $stores)->pluck('name')->toArray();
        } else {
            $data = Store::where('id', $stores)->pluck('name')->toArray();
        }
        $data = implode(', ', $data);
        return $data;
    }
    
    public static function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => translate($error[0])]);
        }
        return $err_keeper;
    }

}
