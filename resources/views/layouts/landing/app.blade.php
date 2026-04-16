
<!DOCTYPE html>
<?php
    $landing_site_direction = session()->get('landing_site_direction');
    $country= \App\CentralLogics\Helpers::get_business_settings('country')  ;
    $countryCode= strtolower($country??'auto');
   $metaData=  \App\Models\DataSetting::where('type','admin_landing_page')->whereIn('key',['meta_title','meta_description','meta_image'])->get()->keyBy('key')??[];
?>
<html dir="{{ $landing_site_direction }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>
    @include('layouts.landing._seo')
    <title>Qrscop - Digital QR Menu Solutions for Restaurants</title>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('public/assets/web/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('public/assets/web/css/bootstrap-icons.css') }}">
    <!-- Local Fonts (Inter & Playfair Display) -->
    <link rel="stylesheet" href="{{ asset('public/assets/web/css/local-fonts.css') }}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('public/assets/web/css/styles.css') }}">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ asset('public/assets/admin/css/toastr.css') }}">

    <link rel="icon" type="image/x-icon" href="{{\App\CentralLogics\Helpers::iconFullUrl()}}">
    @stack('css_or_js')
     @php($backgroundChange = \App\CentralLogics\Helpers::get_business_settings('backgroundChange')??[])
    @if (isset($backgroundChange['primary_1_hex']) && isset($backgroundChange['primary_2_hex']))
        <style>
            :root {
                --base-1: <?php echo $backgroundChange['primary_1_hex']; ?>;
                --base-rgb: <?php echo $backgroundChange['primary_1_rgb']; ?>;
                --base-2: <?php echo $backgroundChange['primary_2_hex']; ?>;
                --base-rgb-2:<?php echo $backgroundChange['primary_2_rgb']; ?>;
            }
        </style>
    @endif
    @php($websiteColors = \App\CentralLogics\Helpers::get_business_settings('website_colors'))
    @php($websiteColors = is_string($websiteColors) ? json_decode($websiteColors, true) : $websiteColors)
    @if($websiteColors)
        <style>
            :root {
                --primary-color: {{ $websiteColors['primary_color'] ?? '#10847E' }};
                --secondary-color: {{ $websiteColors['secondary_color'] ?? '#1a1a2e' }};
                --dark-bg: {{ $websiteColors['dark_bg'] ?? '#0d0d0d' }};
                --light-bg: {{ $websiteColors['light_bg'] ?? '#f8f9fa' }};
                --text-light: {{ $websiteColors['text_light'] ?? '#ffffff' }};
                --text-dark: {{ $websiteColors['text_dark'] ?? '#333333' }};
                --text-muted: {{ $websiteColors['text_muted'] ?? '#888888' }};
                --border-color: {{ $websiteColors['border_color'] ?? '#2a2a2a' }};
                --gradient-primary: linear-gradient(135deg, {{ $websiteColors['primary_color'] ?? '#10847E' }} 0%, {{ $websiteColors['btn_hover_color'] ?? '#0c6b66' }} 100%);
                --bs-warning: {{ $websiteColors['primary_color'] ?? '#10847E' }};
            }
            .text-warning { color: {{ $websiteColors['primary_color'] ?? '#10847E' }} !important; }
            .btn-warning {
                --bs-btn-bg: {{ $websiteColors['primary_color'] ?? '#10847E' }};
                --bs-btn-border-color: {{ $websiteColors['primary_color'] ?? '#10847E' }};
                --bs-btn-hover-bg: {{ $websiteColors['btn_hover_color'] ?? '#0c6b66' }};
                --bs-btn-hover-border-color: {{ $websiteColors['btn_hover_color'] ?? '#0c6b66' }};
                --bs-btn-active-bg: {{ $websiteColors['btn_hover_color'] ?? '#0c6b66' }};
                --bs-btn-active-border-color: {{ $websiteColors['btn_hover_color'] ?? '#0c6b66' }};
                background-color: {{ $websiteColors['primary_color'] ?? '#10847E' }};
                border-color: {{ $websiteColors['primary_color'] ?? '#10847E' }};
            }
        </style>
    @endif
    @php($config=\App\CentralLogics\Helpers::get_business_settings('analytics'))
    @if($config['status'])
    <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $config['measurement_key'] }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}

            gtag('js', new Date()); // IMPORTANT 🔥
            gtag('config', '{{ $config['measurement_key'] }}');
        </script>
    @endif
</head>

<body>

    @php($fixed_link = \App\Models\DataSetting::where(['key'=>'fixed_link','type'=>'admin_landing_page'])->first())
    @php($fixed_link = isset($fixed_link->value)?json_decode($fixed_link->value, true):null)
    <!-- ==== Preloader ==== -->
    <div id="landing-loader"></div>
    <!-- ==== Preloader ==== -->
    <!-- ==== Top Header Bar ==== -->
    <div class="top-header-bar" id="topHeaderBar">
        <div class="container">
            <div class="top-header-inner">
                <div class="top-header-left">
                    <a href="tel:{{ \App\CentralLogics\Helpers::get_settings('phone') }}" class="top-header-link">
                        <i class="bi bi-telephone-fill"></i>
                        <span>{{ \App\CentralLogics\Helpers::get_settings('phone') }}</span>
                    </a>
                    <a href="mailto:{{ \App\CentralLogics\Helpers::get_settings('email_address') }}" class="top-header-link">
                        <i class="bi bi-envelope-fill"></i>
                        <span>{{ \App\CentralLogics\Helpers::get_settings('email_address') }}</span>
                    </a>
                    @if(Route::currentRouteName() == 'home')
                    <a href="#quick-connect" class="top-header-link">
                        <i class="bi bi-person-lines-fill"></i>
                        <span>Quick Connect</span>
                    </a>
                    @endif
                </div>
                <div class="top-header-right">
                    <div class="top-header-social">
                        <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    </div>
                    <a href="{{route('login', 'vendor')}}" class="top-header-login">
                        <i class="bi bi-person-circle"></i>
                        <span>Login</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- ==== Top Header Bar Ends ==== -->

    <!-- ==== Header Section Starts Here ==== -->
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{route('home')}}">
                <!-- Logo: Recommended size 180x45px (SVG or PNG with transparent background) -->
                <img src="{{ asset('public/assets/landing/img/logo.svg') }}" alt="{{ \App\CentralLogics\Helpers::get_settings('business_name') }}" class="navbar-logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{route('home')}}">Home</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link {{ Request::is('about-us') ? 'active' : '' }}" href="{{route('about-us')}}">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('privacy-policy') ? 'active' : '' }}" href="{{route('privacy-policy')}}">Privacy Policy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('terms-and-conditions') ? 'active' : '' }}" href="{{route('terms-and-conditions')}}">TermAndCondition</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('restaurants') ? 'active' : '' }}" href="{{route('restaurants')}}">Restaurants</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('contact-us') ? 'active' : '' }}" href="{{route('contact-us')}}">Contact</a>
                    </li>
                </ul>
                <div class="nav-icons d-flex align-items-center gap-2">
                    {{-- <a href="{{route('restaurant.create')}}" class="nav-link ">Join</a> 
                    <a href="{{route('restaurant.create')}}" class="nav-link">Login</a>  --}}

                    <a href="{{route('restaurant.create')}}" class="btn btn-warning btn-sm">Get Started</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- ==== Header Section Ends Here ==== -->
    @yield('content')
    <!-- ======= Footer Section ======= -->

    <!-- Footer -->
    <footer id="contact" class="footer-section">
        <div class="container">
            <div class="row g-4">
                <!-- Logo & About - Full width on mobile -->
                <div class="col-12 col-md-6 col-lg-4 order-1">
                    <div class="footer-widget footer-about">
                        <a href="{{route('home')}}" class="footer-logo-link">
                            <!-- Logo: Recommended size 200x50px (SVG or PNG with transparent background) -->
                            <img src="{{ asset('public/assets/landing/img/logo.svg') }}" alt="qrscop" class="footer-logo-img">
                        </a>
                        <p>Empowering restaurants with digital QR menu solutions. Make your menu accessible to every customer with a simple scan.</p>
                        <div class="footer-social">
                            <a href="#"><i class="bi bi-facebook"></i></a>
                            <a href="#"><i class="bi bi-twitter"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                            <a href="#"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <!-- Product Links - Half width on mobile -->
                <div class="col-6 col-md-6 col-lg-2 order-2">
                    <div class="footer-widget">
                        <h5>Product</h5>
                        <ul class="footer-links">
                            <li><a href="#features">Features</a></li>
                            <li><a href="{{ route('pricing') }}">Pricing</a></li>
                            <li><a href="#">Demo</a></li>
                            <li><a href="{{ route('careers') }}">Careers</a></li>
                        </ul>
                    </div>
                </div>
                <!-- Company Links - Half width on mobile -->
                <div class="col-6 col-md-6 col-lg-2 order-3">
                    <div class="footer-widget">
                        <h5>Company</h5>
                        <ul class="footer-links">
                            <li><a href="{{route('about-us')}}">About Us</a></li>
                            <li><a href="{{route('careers')}}">Careers</a></li>
                            <li><a href="{{route('blogs')}}">Blog</a></li>
                            <li><a href="#">Press</a></li>
                        </ul>
                    </div>
                </div>
                <!-- Contact Info - Full width on mobile -->
                <div class="col-12 col-md-6 col-lg-4 order-4">
                    <div class="footer-widget">
                        <h5>Contact Us</h5>
                        <ul class="footer-contact">
                            <li><i class="bi bi-geo-alt-fill text-warning"></i> {{ \App\CentralLogics\Helpers::get_settings('address') }}</li>
                            <li><i class="bi bi-envelope-fill text-warning"></i> {{ \App\CentralLogics\Helpers::get_settings('email_address') }}</li>
                            <li><i class="bi bi-telephone-fill text-warning"></i> {{ \App\CentralLogics\Helpers::get_settings('phone') }}</li>
                            <li><i class="bi bi-clock-fill text-warning"></i> 24/7 Support</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">
                             &copy; {{ \App\CentralLogics\Helpers::get_settings('footer_text') }}
                            {{-- by {{ \App\CentralLogics\Helpers::get_settings('business_name') }} --}}
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{route('privacy-policy')}}" class="text-muted me-3 ">Privacy Policy</a>
                        <a href="{{route('terms-and-conditions')}}" class="text-muted">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top" id="backToTop">
        <i class="bi bi-arrow-up"></i>
    </a>

    <!-- Template Preview Modal -->
    <div class="template-modal" id="templateModal">
        <div class="template-modal-overlay"></div>
        <div class="template-modal-content">
            <button class="template-modal-close" id="modalClose">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="template-modal-header">
                <h3 id="modalTemplateName">Template Name</h3>
                <span class="template-modal-badge">Premium Template</span>
            </div>
            <div class="template-modal-body">
                <img src="" alt="Template Preview" id="modalTemplateImage">
            </div>
            <div class="template-modal-footer">
                <button class="btn btn-outline-light" id="modalPrev">
                    <i class="bi bi-chevron-left me-2"></i>Previous
                </button>
                <button class="btn btn-warning" id="modalUseTemplate">
                    <i class="bi bi-check-lg me-2"></i>Use This Template
                </button>
                <button class="btn btn-outline-light" id="modalNext">
                    Next<i class="bi bi-chevron-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- ======= Footer Section ======= -->

    <!-- Welcome Popup (First Visit Only) -->
    @if(\App\CentralLogics\Helpers::get_business_settings('home_banner_popup') == 1)
    <div class="welcome-popup" id="welcomePopup">
        <div class="welcome-popup-overlay"></div>
        <div class="welcome-popup-content">
            <button class="welcome-popup-close" id="welcomePopupClose">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="welcome-popup-banner">
                <div class="welcome-popup-banner-content">
                    <i class="bi bi-qr-code-scan"></i>
                    <span>Digital Menu Revolution</span>
                </div>
            </div>
            <div class="welcome-popup-body">
                <!-- Logo: Recommended size 180x50px (SVG or PNG with transparent background) -->
                <img src="{{ asset('public/assets/landing/img/logo.svg') }}" alt="qrscop" class="welcome-popup-logo">
                <h2>Welcome!</h2>
                <p class="welcome-tagline">Transform Your Restaurant with QR Digital Menus</p>
                <ul class="welcome-features">
                    <li><i class="bi bi-check-circle-fill text-warning"></i> Create beautiful digital menus in minutes</li>
                    <li><i class="bi bi-check-circle-fill text-warning"></i> Generate QR codes for contactless ordering</li>
                    <li><i class="bi bi-check-circle-fill text-warning"></i> Update menu items instantly - no reprinting</li>
                    <li><i class="bi bi-check-circle-fill text-warning"></i> Track menu views and popular items</li>
                </ul>
                <div class="welcome-offer">
                    <i class="bi bi-gift-fill"></i>
                    <span>Start FREE - No credit card required!</span>
                </div>
                <a href="{{route('restaurant.create')}}" class="btn btn-warning btn-lg w-100" id="welcomeGetStarted">
                    <i class="bi bi-rocket-takeoff me-2"></i>Get Started Now
                </a>
                <p class="welcome-skip" id="welcomeSkip">Maybe later, let me explore first</p>
            </div>
        </div>
    </div>
    @endif

    @if(\App\CentralLogics\Helpers::get_business_settings('home_banner_popup') == 2)
    @php($home_banner_popup_image = \App\CentralLogics\Helpers::get_business_settings('home_banner_popup_image'))
    @if($home_banner_popup_image)
    <div class="welcome-popup" id="welcomePopup">
        <div class="welcome-popup-overlay"></div>
        <div class="welcome-popup-content" style="background: transparent; box-shadow: none; max-width: 500px;">
            <button class="welcome-popup-close" id="welcomePopupClose">
                <i class="bi bi-x-lg"></i>
            </button>
            <a href="{{route('restaurant.create')}}">
                <img src="{{ asset('public/assets/landing/image/' . $home_banner_popup_image) }}" alt="Banner" style="width: 100%; border-radius: 12px; display: block;">
            </a>
        </div>
    </div>
    @endif
    @endif

    <style>
        /* Welcome Popup Styles */
        .welcome-popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }
        .welcome-popup.active {
            display: flex;
            animation: fadeIn 0.3s ease;
        }
        .welcome-popup-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(5px);
        }
        .welcome-popup-content {
            position: relative;
            background: #1a1a1a;
            border-radius: 16px;
            max-width: 380px;
            width: 90%;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.4s ease;
            margin: auto;
        }
        .welcome-popup-close {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.3);
            border: none;
            color: #fff;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .welcome-popup-close:hover {
            background: rgba(0, 0, 0, 0.5);
            transform: rotate(90deg);
        }
        .welcome-popup-banner {
            background: linear-gradient(135deg, #10847E 0%, #0c6b66 100%);
            padding: 25px 20px;
            text-align: center;
        }
        .welcome-popup-banner-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .welcome-popup-banner i {
            font-size: 45px;
            color: #0d0d0d;
        }
        .welcome-popup-banner span {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 600;
            color: #0d0d0d;
        }
        .welcome-popup-body {
            padding: 20px;
            text-align: center;
        }
        .welcome-popup-logo {
            height: 40px;
            width: auto;
            max-width: 150px;
            object-fit: contain;
            margin-bottom: 10px;
        }
        .welcome-popup-body h2 {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            margin-bottom: 6px;
            color: #fff;
        }
        .welcome-tagline {
            color: #aaa;
            margin-bottom: 15px;
            font-size: 13px;
        }
        .welcome-features {
            list-style: none;
            padding: 0;
            margin: 0 0 15px 0;
            text-align: left;
        }
        .welcome-features li {
            padding: 5px 0;
            color: #ddd;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
        }
        .welcome-features li i {
            font-size: 14px;
            flex-shrink: 0;
        }
        .welcome-offer {
            background: rgba(16, 132, 126, 0.1);
            border: 1px dashed #10847E;
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #10847E;
            font-weight: 500;
            font-size: 13px;
        }
        .welcome-offer i {
            font-size: 16px;
        }
        .welcome-popup-body .btn {
            padding: 12px 20px;
            font-size: 14px;
            border-radius: 8px;
        }
        .welcome-skip {
            text-align: center;
            color: #888;
            font-size: 12px;
            margin-top: 12px;
            margin-bottom: 0;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .welcome-skip:hover {
            color: #10847E;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Responsive - Tablet */
        @media (max-width: 576px) {
            .welcome-popup {
                padding: 10px;
            }
            .welcome-popup-content {
                max-width: 320px;
                width: 95%;
            }
            .welcome-popup-banner {
                padding: 20px 15px;
            }
            .welcome-popup-banner i {
                font-size: 38px;
            }
            .welcome-popup-banner span {
                font-size: 16px;
            }
            .welcome-popup-body {
                padding: 15px;
            }
            .welcome-popup-logo {
                height: 35px;
            }
            .welcome-popup-body h2 {
                font-size: 18px;
            }
            .welcome-tagline {
                font-size: 12px;
                margin-bottom: 12px;
            }
            .welcome-features li {
                font-size: 11px;
                padding: 4px 0;
            }
            .welcome-features li i {
                font-size: 12px;
            }
            .welcome-offer {
                padding: 8px 10px;
                font-size: 11px;
                margin-bottom: 12px;
            }
            .welcome-popup-body .btn {
                padding: 10px 16px;
                font-size: 13px;
            }
            .welcome-skip {
                font-size: 11px;
                margin-top: 10px;
            }
        }
    </style>

    <script>
        // Welcome Popup - Show only on first visit
        (function() {
            const POPUP_KEY = 'menuscan_welcome_shown';
            const popup = document.getElementById('welcomePopup');
            if (!popup) return;
            const closeBtn = document.getElementById('welcomePopupClose');
            const overlay = document.querySelector('.welcome-popup-overlay');
            const skipBtn = document.getElementById('welcomeSkip');
            const getStartedBtn = document.getElementById('welcomeGetStarted');

            function closePopup() {
                popup.classList.remove('active');
                sessionStorage.setItem(POPUP_KEY, 'true');
            }

            function showPopup() {
                setTimeout(() => {
                    popup.classList.add('active');
                }, 1500);
            }

            if (!sessionStorage.getItem(POPUP_KEY)) {
                showPopup();
            }

            if (closeBtn) closeBtn.addEventListener('click', closePopup);
            if (overlay) overlay.addEventListener('click', closePopup);
            if (skipBtn) skipBtn.addEventListener('click', closePopup);
            if (getStartedBtn) getStartedBtn.addEventListener('click', function() {
                sessionStorage.setItem(POPUP_KEY, 'true');
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && popup.classList.contains('active')) {
                    closePopup();
                }
            });
        })();
    </script>

    <!-- jQuery -->
    <script src="{{ asset('public/assets/admin/js/vendor.min.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('public/assets/web/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Custom JS -->
    <script src="{{ asset('public/assets/web/js/script.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/toastr.js') }}"></script>
    {!! Toastr::message() !!}
    @if ($errors->any())
        <script>
            @foreach($errors->all() as $error)
            toastr.error('{{$error}}', 'Error', {
                CloseButton: true,
                ProgressBar: true
            });
            @endforeach
        </script>
    @endif


    @stack('script_2')

    @include('layouts.landing.partials.cookie-consent')

</body>

</html>
