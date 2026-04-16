<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use App\Models\BusinessSetting;
use App\CentralLogics\Helpers;
use Illuminate\Http\Request;
use App\Traits\Processor;
use App\Models\Setting;

class ThirdPartySettingsController extends Controller
{
    use Processor;

    // recaptcha
    public function recaptcha_index(Request $request)
    {
        return view('admin-views.business-settings.recaptcha-index');
    }

    public function recaptcha_update(Request $request)
    {
        Helpers::businessUpdateOrInsert(['key' => 'recaptcha'], [
            'key' => 'recaptcha',
            'value' => json_encode([
                'status' => $request['status'],
                'site_key' => $request['site_key'],
                'secret_key' => $request['secret_key'],
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Toastr::success(translate('messages.updated_successfully'));

        return back();
    }

    public function analytics_index(Request $request)
    {
        return view('admin-views.business-settings.analytics-index');
    }

    public function analytics_update(Request $request)
    {
        Helpers::businessUpdateOrInsert(['key' => 'analytics'], [
            'key' => 'analytics',
            'value' => json_encode([
                'status' => $request['status'],
                'measurement_key' => $request['measurement_key'],
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Toastr::success(translate('messages.updated_successfully'));

        return back();
    }

    public function smsModule(Request $request)
    {
        $published_status = addon_published_status('Gateways');

        $routes = config('addon_admin_routes');
        $desiredName = 'sms_setup';
        $payment_url = '';
        // foreach ($routes as $routeArray) {
        //     foreach ($routeArray as $route) {
        //         if ($route['name'] === $desiredName) {
        //             $payment_url = $route['url'];
        //             break 2;
        //         }
        //     }
        // }
        $data_values=  Setting::where('settings_type','sms_config')->whereIn('key_name', ['twilio','nexmo','2factor','msg91','twilio_whatsapp'])->get() ?? [];
        return view('admin-views.business-settings.sms-index',compact('data_values','published_status','payment_url'));
    }

    public function sms_update(Request $request, $module)
    {

        if ($module == 'twilio') {
                $additional_data = [
                    'status' => $request['status'],
                    'sid' => $request['sid'],
                    'messaging_service_sid' => $request['messaging_service_sid'],
                    'token' => $request['token'],
                    'from' => $request['from'],
                    'otp_template' => $request['otp_template'],
                ];

        } elseif ($module == 'nexmo') {
            $additional_data = [
                'status' =>$request['status'],
                'api_key' => $request['api_key'],
                'api_secret' => $request['api_secret'],
                'token' =>$request['token'] ?? null,
                'from' => $request['from'],
                'otp_template' => $request['otp_template'],
            ];

        } elseif ($module == '2factor') {
            $additional_data = [
                'status' => $request['status'],
                'api_key' => $request['api_key'],
            ];
        } elseif ($module == 'msg91') {
            $additional_data = [
                'status' => $request['status'],
                'template_id' => $request['template_id'],
                'auth_key' => $request['auth_key'],
            ];
        } elseif ($module == 'twilio_whatsapp') {
            $additional_data = [
                'status' => $request['status'],
                'sid' => $request['sid'],
                'token' => $request['token'],
                'from' => $request['from'],
                'order_message_template' => $request['order_message_template'],
            ];
        }

        $data= ['gateway' => $module ,
        'mode' =>  isset($request['status']) == 1  ?  'live': 'test'
        ];

    $credentials= json_encode(array_merge($data, $additional_data));
    DB::table('addon_settings')->updateOrInsert(['key_name' => $module, 'settings_type' => 'sms_config'], [
        'key_name' => $module,
        'live_values' => $credentials,
        'test_values' => $credentials,
        'settings_type' => 'sms_config',
        'mode' => isset($request['status']) == 1  ?  'live': 'test',
        'is_active' => isset($request['status']) == 1  ?  1: 0 ,
    ]);

    if ($request['status'] == 1 && $module != 'twilio_whatsapp') {
        foreach (['twilio','nexmo','2factor','msg91'] as $gateway) {
            if ($module != $gateway) {
                $keep = Setting::where(['key_name' => $gateway, 'settings_type' => 'sms_config'])->first();
                if (isset($keep)) {
                    $hold = $keep->live_values;
                    $hold['status'] = 0;
                    Setting::where(['key_name' => $gateway, 'settings_type' => 'sms_config'])->update([
                        'live_values' => $hold,
                        'test_values' => $hold,
                        'is_active' => 0,
                    ]);
                }
            }
        }
    }
        return back();
    }

    public function viewSocialLogin()
    {
        $data = BusinessSetting::where('key', 'social_login')->first();
        if (!$data) {
            Helpers::insert_business_settings_key('social_login', '[{"login_medium":"google","client_id":"","client_secret":"","status":"0"},{"login_medium":"facebook","client_id":"","client_secret":"","status":""}]');
            $data = BusinessSetting::where('key', 'social_login')->first();
        }
        $apple = BusinessSetting::where('key', 'apple_login')->first();
        if (!$apple) {
            Helpers::insert_business_settings_key('apple_login', '[{"login_medium":"apple","client_id":"","client_secret":"","team_id":"","key_id":"","service_file":"","redirect_url":"","status":""}]');
            $apple = BusinessSetting::where('key', 'apple_login')->first();
        }
        $appleLoginServices = json_decode($apple->value, true);
        $socialLoginServices = json_decode($data->value, true);

        return view('admin-views.business-settings.social-login.view', compact('socialLoginServices', 'appleLoginServices'));
    }

    public function updateSocialLogin($service, Request $request)
    {
        $login_setup_status = Helpers::get_business_settings($service . '_login_status') ?? 0;
        if ($login_setup_status && ($request['status'] == 0)) {
            Toastr::warning(translate($service . '_login_status_is_enabled_in_login_setup._First_disable_from_login_setup.'));

            return redirect()->back();
        }
        $socialLogin = BusinessSetting::where('key', 'social_login')->first();
        $credential_array = [];
        foreach (json_decode($socialLogin['value'], true) as $key => $data) {
            if ($data['login_medium'] == $service) {
                $cred = [
                    'login_medium' => $service,
                    'client_id' => $request['client_id'],
                    'client_secret' => $request['client_secret'],
                    'status' => $request['status'],
                ];
                array_push($credential_array, $cred);
            } else {
                array_push($credential_array, $data);
            }
        }

        Helpers::businessUpdateOrInsert(['key' => 'social_login'], [
            'value' => $credential_array,
        ]);

        Toastr::success(translate('messages.credential_updated', ['service' => $service]));

        return redirect()->back();
    }

    public function updateAppleLogin($service, Request $request)
    {
        $appleLogin = BusinessSetting::where('key', 'apple_login')->firstOrNew(['key' => 'apple_login']);
        $credential_array = [];
        if ($request->hasfile('service_file')) {
            $fileName = Helpers::upload('apple-login/', 'p8', $request->file('service_file'));
        }
        foreach (json_decode($appleLogin['value'], true) as $key => $data) {
            if ($data['login_medium'] == $service) {
                $cred = [
                    'login_medium' => $service,
                    'client_id' => $request['client_id'],
                    'client_id_app' => $request['client_id_app'],
                    'client_secret' => $request['client_secret'],
                    'status' => $request['status'],
                    'team_id' => $request['team_id'],
                    'key_id' => $request['key_id'],
                    'service_file' => isset($fileName) ? $fileName : $data['service_file'],
                    'redirect_url_flutter' => $request['redirect_url_flutter'],
                    'redirect_url_react' => $request['redirect_url_react'],
                ];
                array_push($credential_array, $cred);
            } else {
                array_push($credential_array, $data);
            }
        }
        $appleLogin->value = $credential_array;

        $appleLogin->save();

        Toastr::success(translate('messages.credential_updated', ['service' => $service]));

        return redirect()->back();
    }

    public function payment_index()
    {
        $published_status = 0; // Set a default value
        $payment_published_status = config('get_payment_publish_status');
        if (isset($payment_published_status[0]['is_published'])) {
            $published_status = $payment_published_status[0]['is_published'];
        }

        $routes = config('addon_admin_routes');
        $desiredName = 'payment_setup';
        $payment_url = '';
        
        // foreach ($routes as $routeArray) {
        //     foreach ($routeArray as $route) {
        //         if ($route['name'] === $desiredName) {
        //             $payment_url = $route['url'];
        //             break 2;
        //         }
        //     }
        // }
        $data_values = Setting::whereIn('settings_type', ['payment_config'])->whereIn('key_name', ['ssl_commerz', 'paypal', 'stripe', 'razor_pay', 'senang_pay', 'paytabs', 'paystack', 'paymob_accept', 'paytm', 'flutterwave', 'liqpay', 'bkash', 'mercadopago', 'phonepe'])->get();

        return view('admin-views.business-settings.payment-index', compact('published_status', 'payment_url', 'data_values'));
    }

    public function payment_update(Request $request, $name)
    {
        // dd($name);
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));

            return back();
        }
        if ($name == 'cash_on_delivery') {
            $payment = BusinessSetting::where('key', 'cash_on_delivery')->first();
            if (isset($payment) == false) {
                Helpers::businessInsert([
                    'key' => 'cash_on_delivery',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'cash_on_delivery'])->update([
                    'key' => 'cash_on_delivery',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'digital_payment') {
            $payment = BusinessSetting::where('key', 'digital_payment')->first();
            if (isset($payment) == false) {
                Helpers::businessInsert([
                    'key' => 'digital_payment',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'digital_payment'])->update([
                    'key' => 'digital_payment',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'ssl_commerz_payment') {
            $payment = BusinessSetting::where('key', 'ssl_commerz_payment')->first();
            if (isset($payment) == false) {
                Helpers::businessInsert([
                    'key' => 'ssl_commerz_payment',
                    'value' => json_encode([
                        'status' => 1,
                        'store_id' => '',
                        'store_password' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'ssl_commerz_payment'])->update([
                    'key' => 'ssl_commerz_payment',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'store_id' => $request['store_id'],
                        'store_password' => $request['store_password'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'razor_pay') {
            $payment = BusinessSetting::where('key', 'razor_pay')->first();
            if (isset($payment) == false) {
                Helpers::businessInsert([
                    'key' => 'razor_pay',
                    'value' => json_encode([
                        'status' => 1,
                        'razor_key' => '',
                        'razor_secret' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'razor_pay'])->update([
                    'key' => 'razor_pay',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'razor_key' => $request['razor_key'],
                        'razor_secret' => $request['razor_secret'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'paypal') {
            $payment = BusinessSetting::where('key', 'paypal')->first();
            if (isset($payment) == false) {
                Helpers::businessInsert([
                    'key' => 'paypal',
                    'value' => json_encode([
                        'status' => 1,
                        'mode' => '',
                        'paypal_client_id' => '',
                        'paypal_secret' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'paypal'])->update([
                    'key' => 'paypal',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'mode' => $request['mode'],
                        'paypal_client_id' => $request['paypal_client_id'],
                        'paypal_secret' => $request['paypal_secret'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'stripe') {
            $payment = BusinessSetting::where('key', 'stripe')->first();
            if (isset($payment) == false) {
                Helpers::businessInsert([
                    'key' => 'stripe',
                    'value' => json_encode([
                        'status' => 1,
                        'api_key' => '',
                        'published_key' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'stripe'])->update([
                    'key' => 'stripe',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'api_key' => $request['api_key'],
                        'published_key' => $request['published_key'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'senang_pay') {
            $payment = BusinessSetting::where('key', 'senang_pay')->first();
            if (isset($payment) == false) {
                Helpers::businessInsert([

                    'key' => 'senang_pay',
                    'value' => json_encode([
                        'status' => 1,
                        'secret_key' => '',
                        'published_key' => '',
                        'merchant_id' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'senang_pay'])->update([
                    'key' => 'senang_pay',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'secret_key' => $request['secret_key'],
                        'published_key' => $request['publish_key'],
                        'merchant_id' => $request['merchant_id'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'paystack') {
            $payment = BusinessSetting::where('key', 'paystack')->first();
            if (isset($payment) == false) {
                Helpers::businessInsert([
                    'key' => 'paystack',
                    'value' => json_encode([
                        'status' => 1,
                        'publicKey' => '',
                        'secretKey' => '',
                        'paymentUrl' => '',
                        'merchantEmail' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'paystack'])->update([
                    'key' => 'paystack',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'publicKey' => $request['publicKey'],
                        'secretKey' => $request['secretKey'],
                        'paymentUrl' => $request['paymentUrl'],
                        'merchantEmail' => $request['merchantEmail'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'flutterwave') {
            $payment = BusinessSetting::where('key', 'flutterwave')->first();
            if (isset($payment) == false) {
                Helpers::businessInsert([
                    'key' => 'flutterwave',
                    'value' => json_encode([
                        'status' => 1,
                        'public_key' => '',
                        'secret_key' => '',
                        'hash' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'flutterwave'])->update([
                    'key' => 'flutterwave',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'public_key' => $request['public_key'],
                        'secret_key' => $request['secret_key'],
                        'hash' => $request['hash'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'mercadopago') {
            $payment = Helpers::businessUpdateOrInsert(
                ['key' => 'mercadopago'],
                [
                    'value' => json_encode([
                        'status' => $request['status'],
                        'public_key' => $request['public_key'],
                        'access_token' => $request['access_token'],
                    ]),
                    'updated_at' => now(),
                ]
            );
        } elseif ($name == 'paymob_accept') {
            Helpers::businessUpdateOrInsert(['key' => 'paymob_accept'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'api_key' => $request['api_key'],
                    'iframe_id' => $request['iframe_id'],
                    'integration_id' => $request['integration_id'],
                    'hmac' => $request['hmac'],
                ]),
                'updated_at' => now(),
            ]);
        } elseif ($name == 'liqpay') {
            Helpers::businessUpdateOrInsert(['key' => 'liqpay'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'public_key' => $request['public_key'],
                    'private_key' => $request['private_key'],
                ]),
                'updated_at' => now(),
            ]);
        } elseif ($name == 'paytm') {
            Helpers::businessUpdateOrInsert(['key' => 'paytm'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'paytm_merchant_key' => $request['paytm_merchant_key'],
                    'paytm_merchant_mid' => $request['paytm_merchant_mid'],
                    'paytm_merchant_website' => $request['paytm_merchant_website'],
                    'paytm_refund_url' => $request['paytm_refund_url'],
                ]),
                'updated_at' => now(),
            ]);
        } elseif ($name == 'bkash') {
            Helpers::businessUpdateOrInsert(['key' => 'bkash'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'api_key' => $request['api_key'],
                    'api_secret' => $request['api_secret'],
                    'username' => $request['username'],
                    'password' => $request['password'],
                ]),
                'updated_at' => now(),
            ]);
        } elseif ($name == 'paytabs') {
            Helpers::businessUpdateOrInsert(['key' => 'paytabs'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'profile_id' => $request['profile_id'],
                    'server_key' => $request['server_key'],
                    'base_url' => $request['base_url'],
                ]),
                'updated_at' => now(),
            ]);
        }

        Toastr::success(translate('messages.payment_settings_updated'));

        return back();
    }

    public function canTogglePaymentMethod($method, $newStatus)
    {
        if ($newStatus == 1) {
            return true;
        }

        $allMethods = BusinessSetting::whereIn('key', [
            'offline_payment_status',
            'cash_on_delivery',
            'digital_payment',
        ])->get();

        $activeCount = 0;

        foreach ($allMethods as $setting) {
            if ($setting->key === $method) {
                continue;
            }

            $value = $setting->value;
            if ($setting->key === 'offline_payment_status') {
                $status = (int)$value;
            } else {
                $decoded = json_decode($value, true);
                $status = $decoded['status'] ?? 0;
            }

            if (is_array($status) && in_array(1, $status)) {
                $activeCount++;
            } elseif ($status == 1) {
                $activeCount++;
            }
        }

        return $activeCount > 0;
    }

    public function payment_config_update(Request $request)
    {
        if ($request->toggle_type) {
            if (!$this->canTogglePaymentMethod($request->toggle_type, $request->status)) {
                Toastr::error(translate('messages.atleast_one_method_must_be_active'));
                return back();
            }
            Helpers::businessUpdateOrInsert(['key' => $request->toggle_type], [
                'value' => $request->toggle_type == 'offline_payment_status' ? $request?->status : json_encode(['status' => $request?->status]),
                'updated_at' => now(),
            ]);
            Toastr::success(translate('messages.payment_settings_updated'));

            return back();
        }

        $request['status'] = $request->status ?? 0;

        $validation = [
            'gateway' => 'required|in:ssl_commerz,paypal,stripe,razor_pay,senang_pay,paytabs,paystack,paymob_accept,paytm,flutterwave,liqpay,bkash,mercadopago,phonepe',
            'mode' => 'required|in:live,test',
        ];

        $additional_data = [];

        if ($request['gateway'] == 'ssl_commerz') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'store_id' => 'required_if:status,1',
                'store_password' => 'required_if:status,1',
            ];
        } elseif ($request['gateway'] == 'paypal') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'client_id' => 'required_if:status,1',
                'client_secret' => 'required_if:status,1',
            ];
        } elseif ($request['gateway'] == 'stripe') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required_if:status,1',
                'published_key' => 'required_if:status,1',
            ];
        } elseif ($request['gateway'] == 'razor_pay') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required_if:status,1',
                'api_secret' => 'required_if:status,1',
            ];
        } elseif ($request['gateway'] == 'senang_pay') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'callback_url' => 'required_if:status,1',
                'secret_key' => 'required_if:status,1',
                'merchant_id' => 'required_if:status,1',
            ];
        } elseif ($request['gateway'] == 'paytabs') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'profile_id' => 'required_if:status,1',
                'server_key' => 'required_if:status,1',
                'base_url' => 'required_if:status,1',
            ];
        } elseif ($request['gateway'] == 'paystack') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'public_key' => 'required_if:status,1',
                'secret_key' => 'required_if:status,1',
                'merchant_email' => 'required_if:status,1',
            ];
        } elseif ($request['gateway'] == 'paymob_accept') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'callback_url' => 'required_if:status,1',
                'api_key' => 'required_if:status,1',
                'iframe_id' => 'required_if:status,1',
                'integration_id' => 'required_if:status,1',
                'hmac' => 'required_if:status,1',
            ];
        } elseif ($request['gateway'] == 'mercadopago') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'access_token' => 'required_if:status,1',
                'public_key' => 'required_if:status,1',
            ];
        } elseif ($request['gateway'] == 'liqpay') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'private_key' => 'required_if:status,1',
                'public_key' => 'required_if:status,1',
            ];
        } elseif ($request['gateway'] == 'flutterwave') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'secret_key' => 'required_if:status,1',
                'public_key' => 'required_if:status,1',
                'hash' => 'required_if:status,1',
            ];
        } elseif ($request['gateway'] == 'paytm') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'merchant_key' => 'required_if:status,1',
                'merchant_id' => 'required_if:status,1',
                'merchant_website_link' => 'required_if:status,1',
            ];
        } elseif ($request['gateway'] == 'bkash') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'app_key' => 'required_if:status,1',
                'app_secret' => 'required_if:status,1',
                'username' => 'required_if:status,1',
                'password' => 'required_if:status,1',
            ];
        } elseif ($request['gateway'] == 'phonepe') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'merchant_id' => 'required_if:status,1',
                'salt_key' => 'required_if:status,1',
                'salt_index' => 'required_if:status,1',
            ];
        }

        $request->validate(array_merge($validation, $additional_data));

        $settings = Setting::where('key_name', $request['gateway'])->where('settings_type', 'payment_config')->first();

        $additional_data_image = $settings['additional_data'] != null ? json_decode($settings['additional_data']) : null;

        if ($request->has('gateway_image')) {
            $gateway_image = $this->file_uploader('payment_modules/gateway_image/', 'png', $request['gateway_image'], $additional_data_image != null ? $additional_data_image->gateway_image : '');
        } else {
            $gateway_image = $additional_data_image != null ? $additional_data_image->gateway_image : '';
        }

        $payment_additional_data = [
            'gateway_title' => $request['gateway_title'],
            'gateway_image' => $gateway_image,
            'storage' => self::getDisk(),
        ];

        $validator = Validator::make($request->all(), array_merge($validation, $additional_data));

        $settings = Setting::firstOrNew(['key_name' => $request['gateway'], 'settings_type' => 'payment_config']);
        $settings->live_values = $validator->validate();
        $settings->test_values = $validator->validate();
        $settings->mode = $request['mode'];
        $settings->is_active = $request['status'];
        $settings->additional_data = json_encode($payment_additional_data);
        $settings->save();

        Toastr::success(GATEWAYS_DEFAULT_UPDATE_200['message']);

        return back();
    }
}
