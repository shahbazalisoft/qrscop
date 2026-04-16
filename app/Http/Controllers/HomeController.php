<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Mail\ContactUs;
use App\Models\AdminFeature;
use App\Models\AdminPromotionalBanner;
use Illuminate\Support\Facades\Mail;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\AdminSpecialCriteria;
use App\Models\AdminTestimonial;
use App\Models\BusinessSetting;
use App\Models\Contact;
use App\Models\DataSetting;
use App\Models\Item;
use App\Models\MenuTemplate;
use App\Models\Store;
use App\Models\CareerJob;
use App\Models\JobApplication;
use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $menuTemplates = MenuTemplate::where('status',1)->get();
        $datas =  DataSetting::with('translations', 'storage')->where('type', 'admin_landing_page')->get();
        $data = [];
        foreach ($datas as $key => $value) {
            if (count($value->translations) > 0) {
                $cred = [
                    $value->key => $value->translations[0]['value'],
                ];
                array_push($data, $cred);
            } else {
                $cred = [
                    $value->key => $value->value,
                ];
                array_push($data, $cred);
            }
            if (count($value->storage) > 0) {
                $cred = [
                    $value->key . '_storage' => $value->storage[0]['value'],
                ];
                array_push($data, $cred);
            } else {
                $cred = [
                    $value->key . '_storage' => 'public',
                ];
                array_push($data, $cred);
            }
        }
        $settings = [];
        foreach ($data as $single_data) {
            foreach ($single_data as $key => $single_value) {
                $settings[$key] = $single_value;
            }
        }

        // $settings =  DataSetting::with('translations')->where('type','admin_landing_page')->pluck('value','key')->toArray();
        $opening_time = BusinessSetting::where('key', 'opening_time')->first();
        $closing_time = BusinessSetting::where('key', 'closing_time')->first();
        $opening_day = BusinessSetting::where('key', 'opening_day')->first();
        $closing_day = BusinessSetting::where('key', 'closing_day')->first();
        $promotional_banners = AdminPromotionalBanner::where('status', 1)->get()->toArray();
        $features = AdminFeature::where('status', 1)->get()->toArray();
        $criterias = AdminSpecialCriteria::where('status', 1)->get();
        $testimonials = AdminTestimonial::where('status', 1)->get();

        $landing_data = [
            'fixed_header_title' => (isset($settings['fixed_header_title']))  ? $settings['fixed_header_title'] : null,
            'fixed_header_sub_title' => (isset($settings['fixed_header_sub_title']))  ? $settings['fixed_header_sub_title'] : null,
            'fixed_module_title' => (isset($settings['fixed_module_title']))  ? $settings['fixed_module_title'] : null,
            'fixed_module_sub_title' => (isset($settings['fixed_module_sub_title']))  ? $settings['fixed_module_sub_title'] : null,
            'fixed_referal_title' => (isset($settings['fixed_referal_title']))  ? $settings['fixed_referal_title'] : null,
            'fixed_referal_sub_title' => (isset($settings['fixed_referal_sub_title']))  ? $settings['fixed_referal_sub_title'] : null,
            'fixed_newsletter_title' => (isset($settings['fixed_newsletter_title']))  ? $settings['fixed_newsletter_title'] : null,
            'fixed_newsletter_sub_title' => (isset($settings['fixed_newsletter_sub_title']))  ? $settings['fixed_newsletter_sub_title'] : null,
            'fixed_footer_article_title' => (isset($settings['fixed_footer_article_title']))  ? $settings['fixed_footer_article_title'] : null,
            'feature_title' => (isset($settings['feature_title']))  ? $settings['feature_title'] : null,
            'feature_short_description' => (isset($settings['feature_short_description']))  ? $settings['feature_short_description'] : null,
            'earning_title' => (isset($settings['earning_title']))  ? $settings['earning_title'] : null,
            'earning_sub_title' => (isset($settings['earning_sub_title']))  ? $settings['earning_sub_title'] : null,
            'earning_seller_image' => (isset($settings['earning_seller_image']))  ? $settings['earning_seller_image'] : null,
            'earning_seller_image_storage' => (isset($settings['earning_seller_image_storage']))  ? $settings['earning_seller_image_storage'] : 'public',
            'earning_delivery_image' => (isset($settings['earning_delivery_image']))  ? $settings['earning_delivery_image'] : null,
            'earning_delivery_image_storage' => (isset($settings['earning_delivery_image_storage']))  ? $settings['earning_delivery_image_storage'] : 'public',
            'why_choose_title' => (isset($settings['why_choose_title']))  ? $settings['why_choose_title'] : null,
            'download_user_app_title' => (isset($settings['download_user_app_title']))  ? $settings['download_user_app_title'] : null,
            'download_user_app_sub_title' => (isset($settings['download_user_app_sub_title']))  ? $settings['download_user_app_sub_title'] : null,
            'download_user_app_image' => (isset($settings['download_user_app_image']))  ? $settings['download_user_app_image'] : null,
            'download_user_app_image_storage' => (isset($settings['download_user_app_image_storage']))  ? $settings['download_user_app_image_storage'] : 'public',
            'testimonial_title' => (isset($settings['testimonial_title']))  ? $settings['testimonial_title'] : null,
            'contact_us_title' => (isset($settings['contact_us_title']))  ? $settings['contact_us_title'] : null,
            'contact_us_sub_title' => (isset($settings['contact_us_sub_title']))  ? $settings['contact_us_sub_title'] : null,
            'contact_us_image' => (isset($settings['contact_us_image']))  ? $settings['contact_us_image'] : null,
            'contact_us_image_storage' => (isset($settings['contact_us_image_storage']))  ? $settings['contact_us_image_storage'] : 'public',
            'opening_time' => $opening_time ? $opening_time->value : null,
            'closing_time' => $closing_time ? $closing_time->value : null,
            'opening_day' => $opening_day ? $opening_day->value : null,
            'closing_day' => $closing_day ? $closing_day->value : null,
            'promotional_banners' => (isset($promotional_banners))  ? $promotional_banners : null,
            'features' => (isset($features))  ? $features : [],
            'criterias' => (isset($criterias))  ? $criterias : null,
            'testimonials' => (isset($testimonials))  ? $testimonials : null,

            'counter_section' => (isset($settings['counter_section']))  ? json_decode($settings['counter_section'], true) : null,
            'seller_app_earning_links' => (isset($settings['seller_app_earning_links']))  ? json_decode($settings['seller_app_earning_links'], true) : null,
            'dm_app_earning_links' => (isset($settings['dm_app_earning_links']))  ? json_decode($settings['dm_app_earning_links'], true) : null,
            'download_user_app_links' => (isset($settings['download_user_app_links']))  ? json_decode($settings['download_user_app_links'], true) : null,
            'fixed_link' => (isset($settings['fixed_link']))  ? json_decode($settings['fixed_link'], true) : null,

            'available_zone_status' => (int)((isset($settings['available_zone_status'])) ? $settings['available_zone_status'] : 0),
            'available_zone_title' => (isset($settings['available_zone_title'])) ? $settings['available_zone_title'] : null,
            'available_zone_short_description' => (isset($settings['available_zone_short_description'])) ? $settings['available_zone_short_description'] : null,
            'available_zone_image' => (isset($settings['available_zone_image'])) ? $settings['available_zone_image'] : null,
            
        ];


        $config = Helpers::get_business_settings('landing_page');
        $landing_integration_type = Helpers::get_business_data('landing_integration_type');
        $redirect_url = Helpers::get_business_data('landing_page_custom_url');

        $new_user = request()?->new_user ?? null;
        
        return view('web/home', compact('menuTemplates', 'new_user'));
    }

    public function terms_and_conditions(Request $request)
    {
        $data = self::get_settings('terms_and_conditions');
        $config = Helpers::get_business_settings('landing_page');
        $landing_integration_type = Helpers::get_business_data('landing_integration_type');
        $redirect_url = Helpers::get_business_data('landing_page_custom_url');

        return view('web/terms-and-conditions', compact('data'));
    }

    public function about_us(Request $request)
    {
        $data = self::get_settings('about_us');
        $data_title = self::get_settings('about_title');
        $menuTemplates = MenuTemplate::where('status', 1)->get();

        $config = Helpers::get_business_settings('landing_page');
        $landing_integration_type = Helpers::get_business_data('landing_integration_type');
        $redirect_url = Helpers::get_business_data('landing_page_custom_url');

        return view('web/about-us', compact('data', 'data_title', 'menuTemplates'));
    }

    public function restaurants(Request $request)
    {
        $search = $request->search;
        $stores = Store::where('status', 1)
            ->whereNotNull('slug')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12);

        return view('web.restaurants', compact('stores', 'search'));
    }

    public function contact_us()
    {
        $recaptcha = Helpers::get_business_settings('recaptcha');
        $custome_recaptcha = new CaptchaBuilder;
        $custome_recaptcha->build();
        Session::put('six_captcha', $custome_recaptcha->getPhrase());
        return view('web/contact-us', compact('recaptcha', 'custome_recaptcha'));
    }

    public function store_contactus(Request $request)
    {
        // Server-side validation with custom messages
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:20',
            // 'restaurant' => 'nullable|string|max:100',
            // 'subject' => 'required|string|max:50',
            'message' => 'required|string|min:10|max:1000',
            'terms' => 'required|accepted',
        ], [
            'first_name.required' => 'Please enter your first name.',
            'first_name.max' => 'First name cannot exceed 50 characters.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            // 'subject.required' => 'Please select a subject.',
            'message.required' => 'Please enter your message.',
            'message.min' => 'Message must be at least 10 characters.',
            'message.max' => 'Message cannot exceed 1000 characters.',
            'terms.required' => 'You must agree to the terms and conditions.',
            'terms.accepted' => 'You must agree to the terms and conditions.',
        ]);

        // Captcha validation
        $recaptcha = Helpers::get_business_settings('recaptcha');
        if (isset($recaptcha) && $recaptcha['status'] == 1) {
            // Google reCAPTCHA v3 validation
            $token = $request->input('g-recaptcha-response');
            if ($token) {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $recaptcha['secret_key'],
                    'response' => $token,
                ]);
                $result = $response->json();
                if (!($result['success'] ?? false) || ($result['score'] ?? 0) < 0.3) {
                    $error = 'ReCAPTCHA verification failed. Please try again.';
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['errors' => ['captcha' => [$error]]], 422);
                    }
                    Toastr::error($error);
                    return back();
                }
            } else {
                // Fallback custom captcha
                if (strtolower(session('six_captcha')) != strtolower($request->custome_recaptcha)) {
                    $error = translate('messages.ReCAPTCHA Failed');
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['errors' => ['captcha' => [$error]]], 422);
                    }
                    Toastr::error($error);
                    return back();
                }
            }
        } else {
            // Default custom captcha
            if (strtolower(session('six_captcha')) != strtolower($request->custome_recaptcha)) {
                $error = translate('messages.ReCAPTCHA Failed');
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['errors' => ['captcha' => [$error]]], 422);
                }
                Toastr::error($error);
                return back();
            }
        }

        // Combine first and last name
        $fullName = trim($request->first_name . ' ' . $request->last_name);

        $contact = new Contact;
        $contact->name = $fullName;
        $contact->email = $request->email;
        $contact->subject = 'General Inquiry';
        $contact->message = $request->message;
        $contact->save();

        \App\Models\AdminNotification::send(
            'New contact message from ' . $fullName,
            'contact_message',
            'Subject: General Inquiry',
            route('admin.users.contact.contact-view', $contact->id)
        );
        if(config('mail.status') && Helpers::get_mail_status('contact_us_mail_status_store') == '1'){
            Mail::to($contact->email)->send(new ContactUs($contact->name));
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Thank you! Your message has been sent successfully.']);
        }

        Toastr::success('Thank you! Your message has been sent successfully.');
        return back();
    }

    public function quick_connect(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:100',
        ]);

        $contact = new Contact;
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->subject = 'Quick Connect';
        $contact->message = 'Phone: ' . $request->phone;
        $contact->save();

        \App\Models\AdminNotification::send(
            'Quick connect from ' . $request->name,
            'contact_message',
            'Phone: ' . $request->phone . ' | Email: ' . $request->email,
            route('admin.users.contact.contact-view', $contact->id)
        );

        if(config('mail.status') && Helpers::get_mail_status('contact_us_mail_status_store') == '1'){
            Mail::to($contact->email)->send(new ContactUs($contact->name));
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Thank you! We will get back to you shortly.']);
        }

        Toastr::success('Thank you! We will get back to you shortly.');
        return back();
    }

    public function privacy_policy(Request $request)
    {
        $data = self::get_settings('privacy_policy');

        $config = Helpers::get_business_settings('landing_page');
        $landing_integration_type = Helpers::get_business_data('landing_integration_type');
        $redirect_url = Helpers::get_business_data('landing_page_custom_url');

        return view('web/privacy-policy', compact('data'));
    }

    public function pricing()
    {
        $packages = SubscriptionPackage::where('status', 1)->get();
        return view('web/pricing', compact('packages'));
    }

    public function careers()
    {
        $jobs = CareerJob::active()->latest()->get();
        $business_name = BusinessSetting::where('key', 'business_name')->first()?->value;
        return view('web/careers', compact('jobs', 'business_name'));
    }

    public function careerDetail($id)
    {
        $job = CareerJob::active()->findOrFail($id);
        $business_name = BusinessSetting::where('key', 'business_name')->first()?->value;
        $recaptcha = Helpers::get_business_settings('recaptcha');
        $custome_recaptcha = new CaptchaBuilder;
        $custome_recaptcha->build();
        Session::put('six_captcha', $custome_recaptcha->getPhrase());
        return view('web/career-detail', compact('job', 'business_name', 'recaptcha', 'custome_recaptcha'));
    }

    public function careerApply(Request $request, $id)
    {
        $job = CareerJob::active()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:20',
            'cover_letter' => 'nullable|string|max:2000',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // Captcha validation
        $recaptcha = Helpers::get_business_settings('recaptcha');
        if (isset($recaptcha) && $recaptcha['status'] == 1) {
            $token = $request->input('g-recaptcha-response');
            if ($token) {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $recaptcha['secret_key'],
                    'response' => $token,
                ]);
                $result = $response->json();
                if (!($result['success'] ?? false) || ($result['score'] ?? 0) < 0.3) {
                    Toastr::error('ReCAPTCHA verification failed. Please try again.');
                    return back()->withInput();
                }
            } else {
                if (strtolower(session('six_captcha')) != strtolower($request->custome_recaptcha)) {
                    Toastr::error(translate('messages.ReCAPTCHA Failed'));
                    return back()->withInput();
                }
            }
        } else {
            if (strtolower(session('six_captcha')) != strtolower($request->custome_recaptcha)) {
                Toastr::error(translate('messages.ReCAPTCHA Failed'));
                return back()->withInput();
            }
        }

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        }

        JobApplication::create([
            'career_job_id' => $job->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'cover_letter' => $request->cover_letter,
            'resume' => $resumePath,
        ]);

        Toastr::success('Your application has been submitted successfully!');
        return back();
    }

    public function blogs()
    {
        return view('web/blogs');
    }

    public static function get_settings($name)
    {
        $config = null;
        $data = DataSetting::where(['key' => $name])->first();
        return $data ? $data->value : '';
    }
    
}
