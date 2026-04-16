<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\BusinessSetting;
use App\CentralLogics\Helpers;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;


class EmailSettingsController extends Controller
{
    public function index()
    {
        return view('admin-views.settings.email.index');
    }

    public function mail_config(Request $request)
    {
        Helpers::businessUpdateOrInsert(
            ['key' => 'mail_config'],
            [
                'value' => json_encode([
                    'status' => $request['status'] ?? 0,
                    'name' => $request['name'],
                    'host' => $request['host'],
                    'driver' => $request['driver'],
                    'port' => $request['port'],
                    'username' => $request['username'],
                    'email_id' => $request['email'],
                    'encryption' => $request['encryption'],
                    'password' => $request['password'],
                ]),
                'updated_at' => now(),
            ]
        );
        Toastr::success(translate('messages.configuration_updated_successfully'));

        return back();
    }

    public function config_status(Request $request)
    {
        $config = BusinessSetting::where(['key' => 'mail_config'])->first();

        $data = $config ? json_decode($config['value'], true) : null;

        Helpers::businessUpdateOrInsert(
            ['key' => 'mail_config'],
            [
                'value' => json_encode([
                    'status' => $request['status'] ?? 0,
                    'name' => $data['name'] ?? '',
                    'host' => $data['host'] ?? '',
                    'driver' => $data['driver'] ?? '',
                    'port' => $data['port'] ?? '',
                    'username' => $data['username'] ?? '',
                    'email_id' => $data['email_id'] ?? '',
                    'encryption' => $data['encryption'] ?? '',
                    'password' => $data['password'] ?? '',
                ]),
                'updated_at' => now(),
            ]
        );
        Toastr::success(translate('messages.configuration_updated_successfully'));

        return back();
    }

    public function test_mail()
    {
        return view('admin-views.business-settings.send-mail-index');
    }

    // Send Mail
    public function send_mail(Request $request)
    {
        $response_flag = 0;
        try {
            Mail::to($request->email)->send(new \App\Mail\TestEmailSender);
            $response_flag = 1;
        } catch (\Exception $exception) {
            info($exception->getMessage());
            $response_flag = 2;
        }

        return response()->json(['success' => $response_flag]);
    }

    public function admin_email_index(Request $request, $tab)
    {
        $template = $request->query('template',null);
        if ($tab == 'new-order') {
            return view('admin-views.business-settings.email-format-setting.admin-email-formats.place-order-format',compact('template'));
        } else if ($tab == 'forgot-password') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.forgot-pass-format',compact('template'));
        } else if ($tab == 'notification') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.notification',compact('template'));
        }else if ($tab == 'store-registration') {
            return view('admin-views.business-settings.email-format-setting.admin-email-formats.store-registration-format',compact('template'));
        } else if ($tab == 'contact-us') {
            return view('admin-views.business-settings.email-format-setting.admin-email-formats.contact-us-format',compact('template'));
        } else if ($tab == 'retailer-registration') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.user-registration-format',compact('template'));
        } else if ($tab == 'dm-registration') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.dm-registration-format',compact('template'));
        } else if ($tab == 'registration') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.registration-format',compact('template'));
        } else if ($tab == 'approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.approve-format',compact('template'));
        } else if ($tab == 'deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.deny-format',compact('template'));
        } else if ($tab == 'withdraw-request') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.withdraw-request-format',compact('template'));
        } else if ($tab == 'withdraw-approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.withdraw-approve-format',compact('template'));
        } else if ($tab == 'withdraw-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.withdraw-deny-format',compact('template'));
        } else if ($tab == 'campaign-request') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.campaign-request-format',compact('template'));
        } else if ($tab == 'campaign-approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.campaign-approve-format',compact('template'));
        } else if ($tab == 'campaign-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.campaign-deny-format',compact('template'));
        } else if ($tab == 'refund-request') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.refund-request-format',compact('template'));
        } else if ($tab == 'login') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.login-format',compact('template'));
        } else if ($tab == 'suspend') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.suspend-format',compact('template'));
        } else if ($tab == 'cash-collect') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.cash-collect-format',compact('template'));
        } else if ($tab == 'registration-otp') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.registration-otp-format',compact('template'));
        } else if ($tab == 'login-otp') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.login-otp-format',compact('template'));
        } else if ($tab == 'order-verification') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.order-verification-format',compact('template'));
        } else if ($tab == 'refund-request-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.refund-request-deny-format',compact('template'));
        } else if ($tab == 'add-fund') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.add-fund-format',compact('template'));
        } else if ($tab == 'refund-order') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.refund-order-format',compact('template'));
        } else if ($tab == 'product-approved') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.product-approved-format',compact('template'));
        } else if ($tab == 'product-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.product-deny-format',compact('template'));
        } else if ($tab == 'offline-payment-approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.offline-approved-format',compact('template'));
        } else if ($tab == 'offline-payment-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.offline-deny-format',compact('template'));
        }  else if ($tab == 'approval') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.retailer-approve-format',compact('template'));
        } else if ($tab == 'category-approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.category-approve-format',compact('template'));
        } else if ($tab == 'category-rejection') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.category-rejection-format',compact('template'));
        }

    }

    public function admin_email_update(Request $request,$tab)
    {
        if ($tab == 'new-order') {
            $email_type = 'new_order';
            $template = EmailTemplate::where('type','admin')->where('email_type', 'new_order')->first();
        }elseif($tab == 'notification'){
            $email_type = 'notification';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'notification')->first();
        }elseif($tab == 'forget-password'){
            $email_type = 'forget_password';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'forget_password')->first();
        }elseif($tab == 'store-registration'){
            $email_type = 'store_registration';
            $template = EmailTemplate::where('type','admin')->where('email_type', 'store_registration')->first();
        }elseif($tab == 'contact-us'){
            $email_type = 'contact_us';
            $template = EmailTemplate::where('type','admin')->where('email_type', 'contact_us')->first();
        }elseif($tab == 'retailer-registration'){
            $email_type = 'retailer_registration';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'retailer_registration')->first();
        }elseif($tab == 'dm-registration'){
            $email_type = 'dm_registration';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'dm_registration')->first();
        }elseif($tab == 'registration'){
            $email_type = 'registration';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'registration')->first();
        }elseif($tab == 'approve'){
            $email_type = 'approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'approve')->first();
        }elseif($tab == 'deny'){
            $email_type = 'deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'deny')->first();
        }elseif($tab == 'withdraw-request'){
            $email_type = 'withdraw_request';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'withdraw_request')->first();
        }elseif($tab == 'withdraw-approve'){
            $email_type = 'withdraw_approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'withdraw_approve')->first();
        }elseif($tab == 'withdraw-deny'){
            $email_type = 'withdraw_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'withdraw_deny')->first();
        }elseif($tab == 'campaign-request'){
            $email_type = 'campaign_request';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'campaign_request')->first();
        }elseif($tab == 'campaign-approve'){
            $email_type = 'campaign_approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'campaign_approve')->first();
        }elseif($tab == 'campaign-deny'){
            $email_type = 'campaign_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'campaign_deny')->first();
        }elseif($tab == 'refund-request'){
            $email_type = 'refund_request';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'refund_request')->first();
        }elseif($tab == 'login'){
            $email_type = 'login';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'login')->first();
        }elseif($tab == 'suspend'){
            $email_type = 'suspend';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'suspend')->first();
        }elseif($tab == 'cash-collect'){
            $email_type = 'cash_collect';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'cash_collect')->first();
        }elseif($tab == 'registration-otp'){
            $email_type = 'registration_otp';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'registration_otp')->first();
        }elseif($tab == 'login-otp'){
            $email_type = 'login_otp';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'login_otp')->first();
        }elseif($tab == 'order-verification'){
            $email_type = 'order_verification';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'order_verification')->first();
        }elseif($tab == 'refund-request-deny'){
            $email_type = 'refund_request_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'refund_request_deny')->first();
        }elseif($tab == 'add-fund'){
            $email_type = 'add_fund';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'add_fund')->first();
        }elseif($tab == 'refund-order'){
            $email_type = 'refund_order';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'refund_order')->first();
        }elseif($tab == 'product-deny'){
            $email_type = 'product_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'product_deny')->first();
        }elseif($tab == 'product-approved'){
            $email_type = 'product_approved';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'product_approved')->first();
        }elseif($tab == 'offline-payment-deny'){
            $email_type = 'offline_payment_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'offline_payment_deny')->first();
        }elseif($tab == 'offline-payment-approve'){
            $email_type = 'offline_payment_approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'offline_payment_approve')->first();
        }elseif($tab == 'approval'){
            $email_type = 'approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'approve')->first();
        }elseif($tab == 'category-approve'){
            $email_type = 'category-approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'category-approve')->first();
        }elseif($tab == 'category-rejection'){
            $email_type = 'category-rejection';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'category-rejection')->first();
        }
        
        if ($template == null) {
            $template = new EmailTemplate();
        }
        if($request->title[array_search('default', $request->lang)] == ''){
            Toastr::error(translate('default_data_is_required'));
            return back();
        }
        $template->title = $request->title[array_search('default', $request->lang)];
        $template->body = $request->body[array_search('default', $request->lang)];
        $template->button_name = $request->button_name?$request->button_name[array_search('default', $request->lang)]:'';
        $template->footer_text = $request->footer_text[array_search('default', $request->lang)];
        $template->copyright_text = $request->copyright_text[array_search('default', $request->lang)];
        $template->background_image = $request->has('background_image') ? Helpers::update('email_template/', $template->background_image, 'png', $request->file('background_image')) : $template->background_image;
        $template->image = $request->has('image') ? Helpers::update('email_template/', $template->image, 'png', $request->file('image')) : $template->image;
        $template->logo = $request->has('logo') ? Helpers::update('email_template/', $template->logo, 'png', $request->file('logo')) : $template->logo;
        $template->icon = $request->has('icon') ? Helpers::update('email_template/', $template->icon, 'png', $request->file('icon')) : $template->icon;
        $template->email_type = $email_type;
        $template->type = 'admin';
        $template->button_url = $request->button_url??'';
        $template->email_template = $request->email_template;
        $template->privacy = $request->privacy?'1':0;
        $template->refund = $request->refund?'1':0;
        $template->cancelation = $request->cancelation?'1':0;
        $template->contact = $request->contact?'1':0;
        $template->facebook = $request->facebook?'1':0;
        $template->instagram = $request->instagram?'1':0;
        $template->twitter = $request->twitter?'1':0;
        $template->linkedin = $request->linkedin?'1':0;
        $template->pinterest = $request->pinterest?'1':0;
        $template->save();

        Toastr::success(translate('messages.template_added_successfully'));
        return back();
    }

    public function update_email_status(Request $request,$type,$tab,$status)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }

        if ($tab == 'place-order') {
            DB::table('business_settings')->updateOrInsert(['key' => 'place_order_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'forgot-password') {
            DB::table('business_settings')->updateOrInsert(['key' => 'forget_password_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'notification') {
            DB::table('business_settings')->updateOrInsert(['key' => 'notification_mail_status_'.$type], [
                'value' => $status
            ]);
        }else if ($tab == 'store-registration') {
            DB::table('business_settings')->updateOrInsert(['key' => 'store_registration_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'contact-us') {
            DB::table('business_settings')->updateOrInsert(['key' => 'contact_us_mail_status_'.$type], [
                'value' => $status
            ]);
        }else if ($tab == 'subscription-successful') {
            DB::table('business_settings')->updateOrInsert(['key' => 'subscription_successful_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'subscription-renew') {
            DB::table('business_settings')->updateOrInsert(['key' => 'subscription_renew_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'subscription-shift') {
            DB::table('business_settings')->updateOrInsert(['key' => 'subscription_shift_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'subscription-cancel') {
            DB::table('business_settings')->updateOrInsert(['key' => 'subscription_cancel_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'subscription-deadline') {
            DB::table('business_settings')->updateOrInsert(['key' => 'subscription_deadline_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'subscription-plan_upadte') {
            DB::table('business_settings')->updateOrInsert(['key' => 'subscription_plan_upadte_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'retailer-registration') {
            DB::table('business_settings')->updateOrInsert(['key' => 'retailer_registration_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'dm-registration') {
            DB::table('business_settings')->updateOrInsert(['key' => 'dm_registration_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'registration') {
            DB::table('business_settings')->updateOrInsert(['key' => 'registration_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'approve') {
            DB::table('business_settings')->updateOrInsert(['key' => 'approve_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'deny') {
            DB::table('business_settings')->updateOrInsert(['key' => 'deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'withdraw-request') {
            DB::table('business_settings')->updateOrInsert(['key' => 'withdraw_request_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'withdraw-approve') {
            DB::table('business_settings')->updateOrInsert(['key' => 'withdraw_approve_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'withdraw-deny') {
            DB::table('business_settings')->updateOrInsert(['key' => 'withdraw_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'campaign-request') {
            DB::table('business_settings')->updateOrInsert(['key' => 'campaign_request_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'campaign-approve') {
            DB::table('business_settings')->updateOrInsert(['key' => 'campaign_approve_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'campaign-deny') {
            DB::table('business_settings')->updateOrInsert(['key' => 'campaign_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'refund-request') {
            DB::table('business_settings')->updateOrInsert(['key' => 'refund_request_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'login') {
            DB::table('business_settings')->updateOrInsert(['key' => 'login_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'suspend') {
            DB::table('business_settings')->updateOrInsert(['key' => 'suspend_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'cash-collect') {
            DB::table('business_settings')->updateOrInsert(['key' => 'cash_collect_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'registration-otp') {
            DB::table('business_settings')->updateOrInsert(['key' => 'registration_otp_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'login-otp') {
            DB::table('business_settings')->updateOrInsert(['key' => 'login_otp_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'order-verification') {
            DB::table('business_settings')->updateOrInsert(['key' => 'order_verification_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'refund-request-deny') {
            DB::table('business_settings')->updateOrInsert(['key' => 'refund_request_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'add-fund') {
            DB::table('business_settings')->updateOrInsert(['key' => 'add_fund_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'refund-order') {
            DB::table('business_settings')->updateOrInsert(['key' => 'refund_order_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'product-deny') {
            DB::table('business_settings')->updateOrInsert(['key' => 'product_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'product-approved') {
            DB::table('business_settings')->updateOrInsert(['key' => 'product_approve_mail_status_'.$type], [
                'value' => $status
            ]);

        } else if ($tab == 'offline-payment-deny') {
            DB::table('business_settings')->updateOrInsert(['key' => 'offline_payment_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'offline-payment-approve') {
            DB::table('business_settings')->updateOrInsert(['key' => 'offline_payment_approve_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'category-approve') {
            DB::table('business_settings')->updateOrInsert(['key' => 'category_approve_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'category-rejection') {
            DB::table('business_settings')->updateOrInsert(['key' => 'category_rejection_mail_status_'.$type], [
                'value' => $status
            ]);
        }
        
        Toastr::success(translate('messages.email_status_updated'));
        return back();

    }

    public function vendor_email_index(Request $request, $tab)
    {
        $template = $request->query('template',null);
        
        if ($tab == 'new-order') {
            return view('admin-views.business-settings.email-format-setting.admin-email-formats.place-order-format',compact('template'));
        } else if ($tab == 'forgot-password') {
            return view('admin-views.business-settings.email-format-setting.store-email-formats.forgot-pass-format',compact('template'));
        } else if ($tab == 'notification') {
            return view('admin-views.business-settings.email-format-setting.store-email-formats.notification',compact('template'));
        } else if ($tab == 'subscription-successful') {
            return view('admin-views.business-settings.email-format-setting.store-email-formats.subscription-successful-format',compact('template'));
        } else if ($tab == 'subscription-renew') {
            return view('admin-views.business-settings.email-format-setting.store-email-formats.subscription-renew-format',compact('template'));
        } else if ($tab == 'subscription-shift') {
            return view('admin-views.business-settings.email-format-setting.store-email-formats.subscription-shift-format',compact('template'));
        } else if ($tab == 'subscription-cancel') {
            return view('admin-views.business-settings.email-format-setting.store-email-formats.subscription-cancel-format',compact('template'));
        } else if ($tab == 'subscription-deadline') {
            return view('admin-views.business-settings.email-format-setting.store-email-formats.subscription-deadline-format',compact('template'));
        } else if ($tab == 'subscription-plan_upadte') {
            return view('admin-views.business-settings.email-format-setting.store-email-formats.subscription-plan_upadte-format',compact('template'));
        }else if ($tab == 'store-registration') {
            return view('admin-views.business-settings.email-format-setting.store-email-formats.store-registration-format',compact('template'));
        } else if ($tab == 'contact-us') {
            return view('admin-views.business-settings.email-format-setting.store-email-formats.contact-us-format',compact('template'));
        } else if ($tab == 'retailer-registration') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.user-registration-format',compact('template'));
        } else if ($tab == 'dm-registration') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.dm-registration-format',compact('template'));
        } else if ($tab == 'registration') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.registration-format',compact('template'));
        } else if ($tab == 'approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.approve-format',compact('template'));
        } else if ($tab == 'deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.deny-format',compact('template'));
        } else if ($tab == 'withdraw-request') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.withdraw-request-format',compact('template'));
        } else if ($tab == 'withdraw-approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.withdraw-approve-format',compact('template'));
        } else if ($tab == 'withdraw-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.withdraw-deny-format',compact('template'));
        } else if ($tab == 'campaign-request') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.campaign-request-format',compact('template'));
        } else if ($tab == 'campaign-approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.campaign-approve-format',compact('template'));
        } else if ($tab == 'campaign-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.campaign-deny-format',compact('template'));
        } else if ($tab == 'refund-request') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.refund-request-format',compact('template'));
        } else if ($tab == 'login') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.login-format',compact('template'));
        } else if ($tab == 'suspend') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.suspend-format',compact('template'));
        } else if ($tab == 'cash-collect') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.cash-collect-format',compact('template'));
        } else if ($tab == 'registration-otp') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.registration-otp-format',compact('template'));
        } else if ($tab == 'login-otp') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.login-otp-format',compact('template'));
        } else if ($tab == 'order-verification') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.order-verification-format',compact('template'));
        } else if ($tab == 'refund-request-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.refund-request-deny-format',compact('template'));
        } else if ($tab == 'add-fund') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.add-fund-format',compact('template'));
        } else if ($tab == 'refund-order') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.refund-order-format',compact('template'));
        } else if ($tab == 'product-approved') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.product-approved-format',compact('template'));
        } else if ($tab == 'product-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.product-deny-format',compact('template'));
        } else if ($tab == 'offline-payment-approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.offline-approved-format',compact('template'));
        } else if ($tab == 'offline-payment-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.offline-deny-format',compact('template'));
        }  else if ($tab == 'approval') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.retailer-approve-format',compact('template'));
        } else if ($tab == 'category-approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.category-approve-format',compact('template'));
        } else if ($tab == 'category-rejection') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.category-rejection-format',compact('template'));
        }

    }

    public function vendor_email_update(Request $request,$tab)
    {
        if ($tab == 'new-order') {
            $email_type = 'new_order';
            $template = EmailTemplate::where('type','admin')->where('email_type', 'new_order')->first();
        }elseif($tab == 'notification'){
            $email_type = 'notification';
            $template = EmailTemplate::where('type','store')->where('email_type', 'notification')->first();
        }elseif($tab == 'forget-password'){
            $email_type = 'forget_password';
            $template = EmailTemplate::where('type','store')->where('email_type', 'forget_password')->first();
        }elseif($tab == 'store-registration'){
            $email_type = 'store_registration';
            $template = EmailTemplate::where('type','store')->where('email_type', 'store_registration')->first();
        }elseif($tab == 'subscription-successful'){
            $email_type = 'subscription-successful';
            $template = EmailTemplate::where('type','store')->where('email_type', 'subscription-successful')->first();
        }elseif($tab == 'subscription-renew'){
            $email_type = 'subscription-renew';
            $template = EmailTemplate::where('type','store')->where('email_type', 'subscription-renew')->first();
        }elseif($tab == 'subscription-shift'){
            $email_type = 'subscription-shift';
            $template = EmailTemplate::where('type','store')->where('email_type', 'subscription-shift')->first();
        }elseif($tab == 'subscription-cancel'){
            $email_type = 'subscription-cancel';
            $template = EmailTemplate::where('type','store')->where('email_type', 'subscription-cancel')->first();
        }elseif($tab == 'subscription-deadline'){
            $email_type = 'subscription-deadline';
            $template = EmailTemplate::where('type','store')->where('email_type', 'subscription-deadline')->first();
        }elseif($tab == 'subscription-plan_upadte'){
            $email_type = 'subscription-plan_upadte';
            $template = EmailTemplate::where('type','store')->where('email_type', 'subscription-plan_upadte')->first();
        }elseif($tab == 'contact-us'){
            $email_type = 'contact-us';
            $template = EmailTemplate::where('type','store')->where('email_type', 'contact-us')->first();
        }elseif($tab == 'retailer-registration'){
            $email_type = 'retailer_registration';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'retailer_registration')->first();
        }elseif($tab == 'dm-registration'){
            $email_type = 'dm_registration';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'dm_registration')->first();
        }elseif($tab == 'registration'){
            $email_type = 'registration';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'registration')->first();
        }elseif($tab == 'approve'){
            $email_type = 'approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'approve')->first();
        }elseif($tab == 'deny'){
            $email_type = 'deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'deny')->first();
        }elseif($tab == 'withdraw-request'){
            $email_type = 'withdraw_request';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'withdraw_request')->first();
        }elseif($tab == 'withdraw-approve'){
            $email_type = 'withdraw_approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'withdraw_approve')->first();
        }elseif($tab == 'withdraw-deny'){
            $email_type = 'withdraw_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'withdraw_deny')->first();
        }elseif($tab == 'campaign-request'){
            $email_type = 'campaign_request';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'campaign_request')->first();
        }elseif($tab == 'campaign-approve'){
            $email_type = 'campaign_approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'campaign_approve')->first();
        }elseif($tab == 'campaign-deny'){
            $email_type = 'campaign_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'campaign_deny')->first();
        }elseif($tab == 'refund-request'){
            $email_type = 'refund_request';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'refund_request')->first();
        }elseif($tab == 'login'){
            $email_type = 'login';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'login')->first();
        }elseif($tab == 'suspend'){
            $email_type = 'suspend';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'suspend')->first();
        }elseif($tab == 'cash-collect'){
            $email_type = 'cash_collect';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'cash_collect')->first();
        }elseif($tab == 'registration-otp'){
            $email_type = 'registration_otp';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'registration_otp')->first();
        }elseif($tab == 'login-otp'){
            $email_type = 'login_otp';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'login_otp')->first();
        }elseif($tab == 'order-verification'){
            $email_type = 'order_verification';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'order_verification')->first();
        }elseif($tab == 'refund-request-deny'){
            $email_type = 'refund_request_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'refund_request_deny')->first();
        }elseif($tab == 'add-fund'){
            $email_type = 'add_fund';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'add_fund')->first();
        }elseif($tab == 'refund-order'){
            $email_type = 'refund_order';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'refund_order')->first();
        }elseif($tab == 'product-deny'){
            $email_type = 'product_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'product_deny')->first();
        }elseif($tab == 'product-approved'){
            $email_type = 'product_approved';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'product_approved')->first();
        }elseif($tab == 'offline-payment-deny'){
            $email_type = 'offline_payment_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'offline_payment_deny')->first();
        }elseif($tab == 'offline-payment-approve'){
            $email_type = 'offline_payment_approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'offline_payment_approve')->first();
        }elseif($tab == 'approval'){
            $email_type = 'approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'approve')->first();
        }elseif($tab == 'category-approve'){
            $email_type = 'category-approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'category-approve')->first();
        }elseif($tab == 'category-rejection'){
            $email_type = 'category-rejection';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'category-rejection')->first();
        }
        
        if ($template == null) {
            $template = new EmailTemplate();
        }
        if($request->title[array_search('default', $request->lang)] == ''){
            Toastr::error(translate('default_data_is_required'));
            return back();
        }
        $template->title = $request->title[array_search('default', $request->lang)];
        $template->body = $request->body[array_search('default', $request->lang)];
        $template->button_name = $request->button_name?$request->button_name[array_search('default', $request->lang)]:'';
        $template->footer_text = $request->footer_text[array_search('default', $request->lang)];
        $template->copyright_text = $request->copyright_text[array_search('default', $request->lang)];
        $template->background_image = $request->has('background_image') ? Helpers::update('email_template/', $template->background_image, 'png', $request->file('background_image')) : $template->background_image;
        $template->image = $request->has('image') ? Helpers::update('email_template/', $template->image, 'png', $request->file('image')) : $template->image;
        $template->logo = $request->has('logo') ? Helpers::update('email_template/', $template->logo, 'png', $request->file('logo')) : $template->logo;
        $template->icon = $request->has('icon') ? Helpers::update('email_template/', $template->icon, 'png', $request->file('icon')) : $template->icon;
        $template->email_type = $email_type;
        $template->type = 'store';
        $template->button_url = $request->button_url??'';
        $template->email_template = $request->email_template;
        $template->privacy = $request->privacy?'1':0;
        $template->refund = $request->refund?'1':0;
        $template->cancelation = $request->cancelation?'1':0;
        $template->contact = $request->contact?'1':0;
        $template->facebook = $request->facebook?'1':0;
        $template->instagram = $request->instagram?'1':0;
        $template->twitter = $request->twitter?'1':0;
        $template->linkedin = $request->linkedin?'1':0;
        $template->pinterest = $request->pinterest?'1':0;
        $template->save();

        Toastr::success(translate('messages.template_added_successfully'));
        return back();
    }
}
