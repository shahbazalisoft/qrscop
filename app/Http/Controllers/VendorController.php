<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Admin;
use App\Models\Store;
use App\Models\Module;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Mail\StoreRegistration;
use App\Models\BusinessSetting;
use App\CentralLogics\StoreLogic;
use Illuminate\Http\JsonResponse;
use App\Models\StoreSubscription;
use App\Models\SubscriptionPackage;
use App\Models\SubscriptionTransaction;
use Gregwar\Captcha\CaptchaBuilder;
use App\Mail\VendorSelfRegistration;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Modules\Rental\Emails\ProviderRegistration;
use Modules\Rental\Emails\ProviderSelfRegistration;

class VendorController extends Controller
{
    /**
     * Step 1: Show registration form
     */
    public function create()
    {
        $status = Helpers::get_business_settings('toggle_store_registration');
        if (!isset($status) || $status == '0') {
            Toastr::error(translate('messages.not_found'));
            return back();
        }

        return view('vendor-views.auth.general-info');
    }

    /**
     * Step 1: Store vendor and redirect to step 2
     */
    public function store(Request $request)
    {
        $status = Helpers::get_business_settings('toggle_store_registration');
        if (!isset($status) || $status == '0') {
            Toastr::error(translate('messages.registration_not_available'));
            return back();
        }

        // Validation for account info
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'restaurant_name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:5|unique:vendors,phone',
            'password' => ['required', 'min:8', 'confirmed'],
            'address' => 'required|string',
            'agree_terms' => 'required',
        ], [
            'name.required' => translate('Full name is required'),
            'restaurant_name.required' => translate('Restaurant name is required'),
            'email.required' => translate('Email is required'),
            'email.unique' => translate('Email already exists'),
            'phone.required' => translate('Phone is required'),
            'phone.unique' => translate('Phone already exists'),
            'password.required' => translate('Password is required'),
            'password.min' => translate('Password must be at least 8 characters'),
            'password.confirmed' => translate('Password confirmation does not match'),
            'address.required' => translate('Address is required'),
            'agree_terms.required' => translate('You must agree to the terms and conditions'),
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $referralCode = null;
        if (!empty($request->apply_referral_code)) {
            $code = trim($request->apply_referral_code);
            $exists = Store::where('referral_code', $code)->exists();
            if (!$exists) {
                Toastr::error(translate('referral_code_invalid!'));
                return back()->withInput();
            }
            $referralCode = $code;
        }

        // Create vendor
        $vendor = new Vendor();
        $vendor->f_name = $request->name;
        $vendor->l_name = '';
        $vendor->email = $request->email;
        $vendor->phone = $request->phone;
        $vendor->password = bcrypt($request->password);
        $vendor->status = 1;
        $vendor->save();

        // Create store with defaults
        $store = new Store;
        $store->name = $request->restaurant_name;
        $store->phone = $request->phone;
        $store->email = $request->email;
        $store->logo = 'default.png';
        $store->cover_photo = null;
        $store->address = $request->address;
        $store->vendor_id = $vendor->id;
        $store->status = 1;
        $store->tracking_order_mobile_no = $request->phone;
        $store->store_business_model = 'none';
        $store->apply_referral_code = $referralCode;
        $store->save();
        // For Notification to admin
        \App\Models\AdminNotification::send(
            'New Registration Form ' . $request->name,
            'registration_message',
            'Phone: ' . $request->phone . ' | Email: ' . $request->email,
            route('admin.store.view', $store->id)
        );

        Helpers::subscription_plan_chosen(
                store_id: $store->id,
                package_id: 1,
                payment_method: 'free_trial',
                discount: 0,
                reference: 'free_trial',
                type: 'new_join'
            );

        try{
            $admin= Admin::where('role_id', 1)->first();
            if(config('mail.status') && Helpers::get_mail_status('registration_mail_status_store') == '1' &&  Helpers::getNotificationStatusData('store','store_registration','mail_status') ){
                Mail::to($request['email'])->send(new VendorSelfRegistration('pending', $vendor->f_name.' '.$vendor->l_name));
            }

            if(config('mail.status') && Helpers::get_mail_status('store_registration_mail_status_admin') == '1' &&  Helpers::getNotificationStatusData('admin','store_self_registration','mail_status') ){
                Mail::to($admin['email'])->send(new StoreRegistration('pending', $vendor->f_name.' '.$vendor->l_name));
            }

        }catch(\Exception $ex){
            info($ex->getMessage());
        }

        // Store the store_id in session for subsequent steps
        session(['vendor_store_id' => $store->id]);

        // Check if subscription is enabled
        if (Helpers::subscription_check()) {
            // Redirect to step 2 (business plan selection)
            return redirect()->route('restaurant.secondStep');
        } else {
            // No subscription, set commission and go to complete
            $store->store_business_model = 'commission';
            $store->save();
            Toastr::success(translate('Registration successful!'));
            return redirect()->route('restaurant.final_step', ['type' => 'commission']);
        }
    }

    /**
     * Step 2: Show business plan selection
     */
    public function secondStep(Request $request)
    {
        $store = Store::find(session('vendor_store_id'));

        if (!$store) {
            Toastr::error(translate('Store not found'));
            return redirect()->route('restaurant.create');
        }

        $admin_commission = BusinessSetting::where('key', 'admin_commission')->first();
        $business_name = BusinessSetting::where('key', 'business_name')->first();
        $packages = SubscriptionPackage::where('status', 1)->get();

        return view('vendor-views.auth.register-step-2', [
            'admin_commission' => $admin_commission?->value,
            'business_name' => $business_name?->value,
            'packages' => $packages,
        ]);
    }

    /**
     * Step 2: Process business plan selection
     */
    public function business_plan(Request $request)
    {
        $store = Store::find(session('vendor_store_id'));

        if (!$store) {
            Toastr::error(translate('Store not found'));
            return redirect()->route('restaurant.create');
        }

        if ($request->business_plan == 'subscription-base') {
            // Validate package selection
            if (!$request->package_id) {
                Toastr::error(translate('Please select a package'));
                return back();
            }

            // Save package to store
            $store->package_id = $request->package_id;
            $store->save();

            // Redirect to payment step
            $package = SubscriptionPackage::withoutGlobalScope('translate')->find($request->package_id);
            $payment_methods = Helpers::getActivePaymentGateways();
            $free_trial_settings = [
                'subscription_free_trial_status' => Helpers::get_business_settings('subscription_free_trial_status'),
                'subscription_free_trial_days' => Helpers::get_business_settings('subscription_free_trial_days'),
                'subscription_free_trial_type' => Helpers::get_business_settings('subscription_free_trial_type'),
            ];

            return view('vendor-views.auth.register-subscription-payment', [
                'package_id' => $package->id,
                'package' => $package,
                'payment_methods' => $payment_methods,
                'free_trial_settings' => $free_trial_settings,
            ]);

        } elseif ($request->business_plan == 'commission-base') {
            // Set commission model and complete
            $store->store_business_model = 'commission';
            $store->save();

            Toastr::success(translate('Registration successful!'));
            return redirect()->route('restaurant.final_step', ['type' => 'commission']);
        } else {
            Toastr::error(translate('Please select a business plan'));
            return back();
        }
    }

    /**
     * Back button: Return to step 2
     */
    public function back(Request $request)
    {
        $store = Store::where('id', session('vendor_store_id'))->first();

        if (!$store) {
            Toastr::error(translate('Store not found'));
            return redirect()->route('restaurant.create');
        }

        $admin_commission = BusinessSetting::where('key', 'admin_commission')->first();
        $business_name = BusinessSetting::where('key', 'business_name')->first();
        $packages = SubscriptionPackage::where('status', 1)->where('module_type', 'all')->get();

        return view('vendor-views.auth.register-step-2', [
            'admin_commission' => $admin_commission?->value,
            'business_name' => $business_name?->value,
            'packages' => $packages,
        ]);
    }

    /**
     * Step 3: Process payment
     */
    public function payment(Request $request)
    {
        $request->validate([
            'package_id' => 'required',
            'payment' => 'required'
        ]);

        $store = Store::where('id', session('vendor_store_id'))->first(['id', 'vendor_id']);

        if (!$store) {
            Toastr::error(translate('Store not found'));
            return redirect()->route('restaurant.create');
        }

        $package = SubscriptionPackage::withoutGlobalScope('translate')->find($request->package_id);

        if (!in_array($request->payment, ['free_trial'])) {
            $url = route('restaurant.final_step');
            return redirect()->away(Helpers::subscriptionPayment(
                store_id: $store->id,
                package_id: $package->id,
                payment_gateway: $request->payment,
                payment_platform: 'web',
                url: $url,
                type: 'new_join'
            ));
        }

        // if ($request->payment == 'free_trial') {
        //     $plan_data = Helpers::subscription_plan_chosen(
        //         store_id: $store->id,
        //         package_id: $package->id,
        //         payment_method: 'free_trial',
        //         discount: 0,
        //         reference: 'free_trial',
        //         type: 'new_join'
        //     );
        // }
        Toastr::success(translate('Successfully_Subscribed.'));
        return to_route('restaurant.final_step');
        
        // $plan_data != false
        //     ? Toastr::success(translate('Successfully_Subscribed.'))
        //     : Toastr::error(translate('Something_went_wrong!.'));

        // return to_route('restaurant.final_step');
    }

    /**
     * Final step: Show completion page
     */
    public function final_step(Request $request)
    {
        $store_id = session('vendor_store_id');
        $type = $request->type ?? null;

        // Payment gateway appends ?flag=success or ?flag=fail as proper query param
        $payment_status = null;
        if ($request->has('flag')) {
            $payment_status = $request->flag === 'success' ? 'success' : 'fail';
        }

        // Fetch transaction & subscription details for the store
        $transaction = null;
        $subscription = null;
        $package = null;

        if ($store_id) {
            $transaction = SubscriptionTransaction::where('store_id', $store_id)
                ->latest()
                ->first();

            $subscription = StoreSubscription::where('store_id', $store_id)
                ->latest()
                ->first();

            if ($transaction) {
                $package = SubscriptionPackage::withoutGlobalScope('translate')
                    ->find($transaction->package_id);
            }
        }

        // Clear session on successful completion (keep for retry on failure)
        if ($payment_status !== 'fail') {
            session()->forget('vendor_store_id');
        }

        return view('vendor-views.auth.register-complete', [
            'store_id' => $store_id,
            'payment_status' => $payment_status,
            'type' => $type,
            'transaction' => $transaction,
            'subscription' => $subscription,
            'package' => $package,
        ]);
    }

    public function get_all_modules(Request $request)
    {
        $module_data = Module::Active()->whereHas('zones', function ($query) use ($request) {
            $query->where('zone_id', $request->zone_id);
        })->notParcel()
            ->where('modules.module_name', 'like', '%' . $request->q . '%')
            ->limit(8)->get()->map(function ($module) {
                return [
                    'id' => $module->id,
                    'text' => $module->module_name
                ];
            });
        return response()->json($module_data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_modules_type(Request $request): JsonResponse
    {
        $module = Module::find($request->id);
        $packages = null;

        if ($module) {
            $packages = SubscriptionPackage::where('status', 1)
                ->where('module_type', $module?->module_type == 'rental' && addon_published_status('Rental') ? 'rental' : 'all')
                ->latest()
                ->get();

            $module = $module->module_type;
            return response()->json([
                'module_type' => $module,
                'view' => view('vendor-views.auth._package_data', compact('packages', 'module'))->render(),
            ]);
        }

        return response()->json(['module_type' => '']);
    }
    public function checkEmailUnique(Request $request)
    {
        $email = $request->input('email');

        // Perform the uniqueness check in the database
        $isUnique = !Vendor::where('email', $email)->exists();
        
        return response()->json($isUnique);
    }

    public function checkPhoneUnique(Request $request)
    {
        $phone = $request->input('phone');

        // Perform the uniqueness check in the database
        $isUnique = !Vendor::where('phone', $phone)->exists();
        
        return response()->json($isUnique);
    }
}
