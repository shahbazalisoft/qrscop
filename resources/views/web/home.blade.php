@extends('layouts.landing.app')
@php($business_name =    \App\CentralLogics\Helpers::get_business_settings('business_name'))
@section('title', translate('messages.landing_page') . ' | ' . $business_name != 'null' ? $business_name : 'Sixam Mart')
@section('content')

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="hero-bg-elements">
            <div class="floating-bean bean-1">
                <i class="bi bi-qr-code" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
            <div class="floating-bean bean-2">
                <i class="bi bi-phone" style="font-size: 35px; opacity: 0.3;"></i>
            </div>
            <div class="floating-bean bean-3">
                <i class="bi bi-qr-code-scan" style="font-size: 45px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <span class="hero-badge">
                            <i class="bi bi-qr-code me-2"></i>Digital Menu Solutions
                        </span>
                        <h1 class="hero-title">Transform Your Restaurant with QR Menus</h1>
                        <p class="hero-text">Provide contactless digital menus to your customers. Simply scan the QR code and explore the complete menu on any smartphone - no app required.</p>
                        <div class="hero-buttons">
                            <a href="{{route('restaurant.create')}}" class="btn btn-warning btn-lg me-3">Get Started Free</a>
                            <a href="#how-it-works" class="btn btn-outline-light btn-lg">See How It Works</a>
                        </div>
                        
                        <div class="hero-stats mt-4">
                            <div class="stat-item">
                                <h3>500+</h3>
                                <p>Restaurants</p>
                            </div>
                            <div class="stat-item">
                                <h3>1M+</h3>
                                <p>Scans Monthly</p>
                            </div>
                            <div class="stat-item">
                                <h3>99%</h3>
                                <p>Satisfaction</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image">
                        <div class="qr-demo-wrapper">
                            <img src="{{ asset('/public/assets/web/image/banner.jpg') }}" alt="Restaurant QR Menu" class="img-fluid hero-main-img">
                            <div class="qr-floating-card">
                                <div class="qr-code-box">
                                    <img src="{{ asset('/public/assets/web/image/demo-qr.svg') }}" alt="QR Code">
                                </div>
                                <p>Scan to see demo menu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section py-5">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="about-images">
                        <div class="row g-3">
                            <div class="col-6">
                                <img src="{{ asset('/public/assets/web/image/about-one.jpg') }}" alt="Restaurant Interior" class="img-fluid rounded about-img-1">
                            </div>
                            <div class="col-6 mt-5">
                                <img src="{{ asset('/public/assets/web/image/about-two.jpg') }}" alt="Restaurant Food" class="img-fluid rounded about-img-2">
                            </div>
                        </div>
                        <div class="about-pattern">
                            <span></span><span></span><span></span><span></span><span></span>
                            <span></span><span></span><span></span><span></span><span></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content">
                        <span class="section-subtitle">About Us</span>
                        <h2 class="section-title">We Help Restaurants Go <span class="text-warning">Digital</span></h2>
                        <p class="section-text">{{ \App\CentralLogics\Helpers::get_settings('business_name') }} provides innovative QR code solutions that help restaurant owners create beautiful, interactive digital menus. When customers scan your unique QR code, they instantly access your complete menu on their smartphones.</p>
                        <ul class="about-features">
                            <li><i class="bi bi-check-square-fill text-warning"></i> Contactless & Hygienic Solution</li>
                            <li><i class="bi bi-check-square-fill text-warning"></i> Real-time Menu Updates</li>
                            <li><i class="bi bi-check-square-fill text-warning"></i> No App Download Required</li>
                            <li><i class="bi bi-check-square-fill text-warning"></i> Works on All Smartphones</li>
                        </ul>
                        <a href="{{route('contact-us')}}" class="btn btn-warning">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="category-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-subtitle">Features</span>
                <h2 class="section-title">Why Choose Our Qrscop</h2>
                <p class="section-text mx-auto" style="max-width: 600px;">Powerful features designed to enhance your restaurant's digital presence and improve customer experience</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="bi bi-qr-code-scan"></i>
                        </div>
                        <h4>Instant QR Codes</h4>
                        <p>Generate unique QR codes for each table or location. Customers simply scan and view your menu instantly.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <h4>Easy Menu Editor</h4>
                        <p>Update your menu items, prices, and images anytime. Changes reflect instantly for all customers.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="category-card featured">
                        <div class="category-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                        <h4>Mobile Optimized</h4>
                        <p>Beautiful, responsive design that looks perfect on any smartphone or tablet device.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="bi bi-translate"></i>
                        </div>
                        <h4>Multi-Language</h4>
                        <p>Support multiple languages to serve international customers. Auto-translate feature available.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h4>Analytics Dashboard</h4>
                        <p>Track menu views, popular items, and customer engagement with detailed analytics.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="bi bi-palette"></i>
                        </div>
                        <h4>Custom Branding</h4>
                        <p>Match your restaurant's brand with custom colors, logos, and themes for your digital menu.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="why-choose-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-subtitle">Process</span>
                <h2 class="section-title">How It Works</h2>
                <p class="section-text mx-auto" style="max-width: 600px;">Get your digital menu up and running in just 3 simple steps</p>
            </div>
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="why-images">
                        <img src="{{ asset('/public/assets/web/image/how-work-one.jpg') }}" alt="Restaurant Owner" class="img-fluid rounded why-img-main">
                        <img src="{{ asset('/public/assets/web/image/how-work-two.jpg') }}" alt="QR Scan" class="why-img-overlay" style="border-radius: 15px;">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="why-content">
                        <div class="why-features">
                            <div class="why-feature-item">
                                <div class="feature-icon">
                                    <span>1</span>
                                </div>
                                <div class="feature-content">
                                    <h5>Sign Up & Create Menu</h5>
                                    <p>Register your restaurant and add your menu items with descriptions, prices, and photos using our easy editor.</p>
                                </div>
                            </div>
                            <div class="why-feature-item">
                                <div class="feature-icon">
                                    <span>2</span>
                                </div>
                                <div class="feature-content">
                                    <h5>Get Your QR Codes</h5>
                                    <p>We generate unique QR codes for your restaurant. Download and print them for your tables, windows, or marketing materials.</p>
                                </div>
                            </div>
                            <div class="why-feature-item">
                                <div class="feature-icon">
                                    <span>3</span>
                                </div>
                                <div class="feature-content">
                                    <h5>Customers Scan & Order</h5>
                                    <p>Customers scan the QR code with their phone camera, instantly view your menu, and can place orders directly.</p>
                                </div>
                            </div>
                        </div>
                        <a href="#pricing" class="btn btn-warning mt-4">Start Free Trial</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Templates Section -->
    <section id="templates" class="templates-section py-5">
        <div class="container">
            <div class="row align-items-center g-5">
                <!-- Left Side Content -->
                <div class="col-lg-6">
                    <div class="template-showcase-content">
                        <span class="hero-badge mb-3">
                            <i class="bi bi-palette me-2"></i>Our Collection
                        </span>
                        <h2 class="section-title">Premium Menu <span class="text-highlight">Templates</span></h2>
                        <p class="section-text">Choose from our stunning collection of professionally designed menu templates. Each template is crafted to match your restaurant's unique style and brand identity.</p>
                        <ul class="template-feature-list">
                            <li><i class="bi bi-check-circle-fill text-warning"></i> {{ $menuTemplates->count() }}+ beautifully designed templates</li>
                            <li><i class="bi bi-check-circle-fill text-warning"></i> Fully customizable colors & fonts</li>
                            <li><i class="bi bi-check-circle-fill text-warning"></i> Mobile responsive designs</li>
                            <li><i class="bi bi-check-circle-fill text-warning"></i> New templates added regularly</li>
                        </ul>
                        <a href="{{ route('about-us') }}" class="btn btn-warning btn-lg">
                            <i class="bi bi-grid me-2"></i>View All Templates
                        </a>
                    </div>
                </div>
                <!-- Right Side Template Preview -->
                <div class="col-lg-6">
                    <div class="template-showcase-preview">
                        @if($menuTemplates->count() > 0)
                        <div class="phone-mockup phone-mockup-large">
                            <div class="phone-notch"></div>
                            <div class="phone-screen">
                                <img src="{{asset('storage/app/public/menu-template')}}/{{ $menuTemplates->first()->template }}" alt="{{ $menuTemplates->first()->title }}">
                            </div>
                        </div>
                        <!-- Small floating previews -->
                        @if($menuTemplates->count() > 1)
                        <div class="floating-template floating-template-1">
                            <img src="{{asset('storage/app/public/menu-template')}}/{{ $menuTemplates->skip(1)->first()->template }}" alt="">
                        </div>
                        @endif
                        @if($menuTemplates->count() > 2)
                        <div class="floating-template floating-template-2">
                            <img src="{{asset('storage/app/public/menu-template')}}/{{ $menuTemplates->skip(2)->first()->template }}" alt="">
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="chef-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-subtitle">Benefits</span>
                <h2 class="section-title">Why Restaurants Love Us</h2>
                <p class="section-text mx-auto" style="max-width: 600px;">Join hundreds of restaurants already using {{ \App\CentralLogics\Helpers::get_settings('business_name') }} to enhance their customer experience</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-3 col-md-6">
                    <div class="benefit-card text-center">
                        <div class="benefit-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5>Contactless & Safe</h5>
                        <p>Reduce physical contact with shared menus. Keep your customers and staff safe.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="benefit-card text-center">
                        <div class="benefit-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <h5>Save Money</h5>
                        <p>No more printing costs. Update your menu digitally without reprinting.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="benefit-card text-center">
                        <div class="benefit-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h5>Save Time</h5>
                        <p>Update prices and items instantly. Changes go live immediately.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="benefit-card text-center">
                        <div class="benefit-icon">
                            <i class="bi bi-tree"></i>
                        </div>
                        <h5>Eco-Friendly</h5>
                        <p>Go paperless and reduce your environmental footprint.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="testimonial-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="testimonial-card text-center">
                        <span class="section-subtitle">Testimonials</span>
                        <h2 class="section-title mb-4">What Restaurant Owners Say</h2>
                        <div class="quote-icon">
                            <i class="bi bi-quote"></i>
                        </div>
                        <p class="testimonial-text" id="testimonialText">{{ \App\CentralLogics\Helpers::get_settings('business_name') }} transformed how we serve our customers. The QR menu is so easy to use, and we save hundreds on printing costs every month. Our customers love the convenience!</p>
                        <div class="testimonial-author">
                            <img src="{{ asset('public/assets/web/img/client/1.svg') }}" alt="Rajesh Kumar" id="testimonialAvatar">
                            <h5 id="testimonialName">Rajesh Kumar</h5>
                            <p id="testimonialRole">Owner, Spice Garden Restaurant</p>
                        </div>
                        <div class="testimonial-dots">
                            <span class="dot active" data-index="0"></span>
                            <span class="dot" data-index="1"></span>
                            <span class="dot" data-index="2"></span>
                            <span class="dot" data-index="3"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Teaser Section -->
    <section class="pricing-teaser-section">
        <div class="container">
            <div class="pricing-teaser-box">
                <div class="pricing-teaser-decoration">
                    <div class="deco-circle deco-1"></div>
                    <div class="deco-circle deco-2"></div>
                    <div class="deco-dots"></div>
                </div>
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-12 text-center mb-4 mb-lg-0">
                        <div class="pricing-teaser-visual">
                            <div class="pricing-icon-stack">
                                <div class="pricing-icon-circle main-circle">
                                    <i class="bi bi-rocket-takeoff-fill"></i>
                                </div>
                                <div class="pricing-icon-circle sub-circle sc-1"><i class="bi bi-qr-code"></i></div>
                                <div class="pricing-icon-circle sub-circle sc-2"><i class="bi bi-palette"></i></div>
                                <div class="pricing-icon-circle sub-circle sc-3"><i class="bi bi-graph-up"></i></div>
                            </div>
                            <div class="pricing-free-tag">FREE</div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-7">
                        <div class="pricing-teaser-content">
                            <span class="pricing-teaser-badge"><i class="bi bi-stars me-1"></i> Limited Time Offer</span>
                            <h3>Launch Your Digital Menu in Minutes</h3>
                            <div class="pricing-highlights">
                                <div class="pricing-highlight-item">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Free plan forever</span>
                                </div>
                                <div class="pricing-highlight-item">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>No credit card needed</span>
                                </div>
                                <div class="pricing-highlight-item">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Upgrade anytime</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5 text-center text-md-end">
                        <div class="pricing-teaser-cta">
                            <div class="pricing-from">Plans from</div>
                            <div class="pricing-amount"><span>$</span>0<small>/mo</small></div>
                            <a href="{{ route('pricing') }}" class="pricing-teaser-btn">
                                <span>See All Plans</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Badges Section -->
    <section class="trust-badges-section">
        <div class="container">
            <div class="trust-badges-box">
                <div class="text-center mb-4">
                    <span class="section-subtitle">Why Choose Us</span>
                    <h2 class="section-title">Trusted by Thousands of Restaurants</h2>
                </div>
                <div class="row g-3 g-md-4">
                    <div class="col-6 col-md-4 col-lg">
                        <div class="trust-badge">
                            <div class="trust-badge-icon">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <h5>SSL Secured</h5>
                            <p>Enterprise-grade security for your data</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg">
                        <div class="trust-badge">
                            <div class="trust-badge-icon">
                                <i class="bi bi-patch-check-fill"></i>
                            </div>
                            <h5>99.9% Uptime</h5>
                            <p>Reliable service you can count on</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg">
                        <div class="trust-badge">
                            <div class="trust-badge-icon">
                                <i class="bi bi-headset"></i>
                            </div>
                            <h5>24/7 Support</h5>
                            <p>Always here when you need us</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-6 col-lg">
                        <div class="trust-badge">
                            <div class="trust-badge-icon">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>
                            <h5>Free Updates</h5>
                            <p>Latest features at no extra cost</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg">
                        <div class="trust-badge">
                            <div class="trust-badge-icon">
                                <i class="bi bi-credit-card"></i>
                            </div>
                            <h5>Secure Payment</h5>
                            <p>Safe and protected transactions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section py-5">
        <div class="container">
            <div class="row align-items-center g-5">
                <!-- Left Side - Image & Content -->
                <div class="col-lg-5">
                    <div class="faq-left-content">
                        <span class="section-subtitle">FAQ</span>
                        <h2 class="section-title">Frequently Asked <span class="text-warning">Questions</span></h2>
                        <p class="section-text">Find answers to common questions about our QR menu service. Can't find what you're looking for? Feel free to contact us.</p>
                        <div class="faq-image-wrapper">
                            <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600&q=80" alt="Restaurant QR Menu" class="img-fluid faq-main-img">
                            <div class="faq-stats-badge">
                                <div class="faq-stat-number">500+</div>
                                <div class="faq-stat-text">Happy Restaurants</div>
                            </div>
                        </div>
                        <a href="{{ route('contact-us') }}" class="faq-contact-btn mt-4">
                            <span class="faq-btn-content">
                                <i class="bi bi-chat-dots"></i>
                                <span>Contact Us</span>
                            </span>
                            <i class="bi bi-arrow-right faq-btn-arrow"></i>
                        </a>
                    </div>
                </div>
                <!-- Right Side - FAQ Accordion -->
                <div class="col-lg-7">
                    <div class="faq-accordion">
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        Do customers need to download an app to view the menu?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        No! Customers simply scan the QR code with their smartphone camera, and the menu opens directly in their web browser. No app download required.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        Can I update my menu after creating it?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Absolutely! You can update your menu items, prices, descriptions, and images anytime through our easy-to-use dashboard. Changes reflect instantly.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        What if a customer's phone can't scan QR codes?
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Most modern smartphones (iPhone and Android) can scan QR codes natively. For older phones, we also provide a short URL that customers can type manually.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                        Can I try the service before paying?
                                    </button>
                                </h2>
                                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Yes! Our Starter plan is completely free and lets you create a basic menu with up to 20 items. You can upgrade anytime as your needs grow.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                        Do you provide the printed QR codes?
                                    </button>
                                </h2>
                                <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        We provide downloadable high-resolution QR code images that you can print yourself. We also offer premium table stands and printed materials for an additional fee.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trusted Restaurants Section -->
    <section class="trusted-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-subtitle">Trusted By</span>
                <h2 class="section-title">Restaurants Using <span class="text-warning">{{ \App\CentralLogics\Helpers::get_settings('business_name') }}</span></h2>
                <p class="section-text mx-auto" style="max-width: 600px;">Join 500+ restaurants that trust us for their digital menu solutions</p>
            </div>
        </div>
        <!-- Scrolling Marquee -->
        <div class="marquee-wrapper">
            <div class="marquee-track">
                <!-- First set of cards -->
                <div class="trust-card">
                    <div class="trust-icon">
                        <img src="{{ asset('/public/assets/web/image/our-client.jpg') }}" alt="Fine Dine">
                    </div>
                    <div class="trust-content">
                        <h5>Fine Dine</h5>
                        <p>Mumbai, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <i class="bi bi-cup-hot"></i>
                    </div>
                    <div class="trust-content">
                        <h5>Cafe Corner</h5>
                        <p>Delhi, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <img src="{{ asset('/public/assets/web/image/our-client.jpg') }}" alt="Breakfast Hub">
                    </div>
                    <div class="trust-content">
                        <h5>Breakfast Hub</h5>
                        <p>Bangalore, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <i class="bi bi-cake"></i>
                    </div>
                    <div class="trust-content">
                        <h5>Sweet Treats</h5>
                        <p>Chennai, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <img src="{{ asset('/public/assets/web/image/our-client.jpg') }}" alt="Spice Kitchen">
                    </div>
                    <div class="trust-content">
                        <h5>Spice Kitchen</h5>
                        <p>Hyderabad, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <i class="bi bi-tropical-storm"></i>
                    </div>
                    <div class="trust-content">
                        <h5>Asian Fusion</h5>
                        <p>Pune, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <img src="{{ asset('/public/assets/web/image/our-client.jpg') }}" alt="Royal Tandoor">
                    </div>
                    <div class="trust-content">
                        <h5>Royal Tandoor</h5>
                        <p>Jaipur, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <i class="bi bi-cup-straw"></i>
                    </div>
                    <div class="trust-content">
                        <h5>Juice Junction</h5>
                        <p>Kolkata, India</p>
                    </div>
                </div>
                <!-- Duplicate set for seamless loop -->
                <div class="trust-card">
                    <div class="trust-icon">
                        <img src="{{ asset('/public/assets/web/image/our-client.jpg') }}" alt="Fine Dine">
                    </div>
                    <div class="trust-content">
                        <h5>Fine Dine</h5>
                        <p>Mumbai, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <i class="bi bi-cup-hot"></i>
                    </div>
                    <div class="trust-content">
                        <h5>Cafe Corner</h5>
                        <p>Delhi, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <img src="{{ asset('/public/assets/web/image/our-client.jpg') }}" alt="Breakfast Hub">
                    </div>
                    <div class="trust-content">
                        <h5>Breakfast Hub</h5>
                        <p>Bangalore, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <i class="bi bi-cake"></i>
                    </div>
                    <div class="trust-content">
                        <h5>Sweet Treats</h5>
                        <p>Chennai, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <img src="{{ asset('/public/assets/web/image/our-client.jpg') }}" alt="Spice Kitchen">
                    </div>
                    <div class="trust-content">
                        <h5>Spice Kitchen</h5>
                        <p>Hyderabad, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <i class="bi bi-tropical-storm"></i>
                    </div>
                    <div class="trust-content">
                        <h5>Asian Fusion</h5>
                        <p>Pune, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <img src="{{ asset('/public/assets/web/image/our-client.jpg') }}" alt="Royal Tandoor">
                    </div>
                    <div class="trust-content">
                        <h5>Royal Tandoor</h5>
                        <p>Jaipur, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <i class="bi bi-cup-straw"></i>
                    </div>
                    <div class="trust-content">
                        <h5>Juice Junction</h5>
                        <p>Kolkata, India</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="marquee-wrapper">
            <div class="marquee-track">
                <!-- First set of cards -->
                <div class="trust-card">
                    <div class="trust-icon">
                        <img src="{{ asset('/public/assets/web/image/our-client.jpg') }}" alt="Fine Dine">
                    </div>
                    <div class="trust-content">
                        <h5>Fine Dine</h5>
                        <p>Mumbai, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <i class="bi bi-cup-hot"></i>
                    </div>
                    <div class="trust-content">
                        <h5>Cafe Corner</h5>
                        <p>Delhi, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <img src="{{ asset('/public/assets/web/image/our-client.jpg') }}" alt="Breakfast Hub">
                    </div>
                    <div class="trust-content">
                        <h5>Breakfast Hub</h5>
                        <p>Bangalore, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <i class="bi bi-cake"></i>
                    </div>
                    <div class="trust-content">
                        <h5>Sweet Treats</h5>
                        <p>Chennai, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <img src="{{ asset('/public/assets/web/image/our-client.jpg') }}" alt="Spice Kitchen">
                    </div>
                    <div class="trust-content">
                        <h5>Spice Kitchen</h5>
                        <p>Hyderabad, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <i class="bi bi-tropical-storm"></i>
                    </div>
                    <div class="trust-content">
                        <h5>Asian Fusion</h5>
                        <p>Pune, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <img src="{{ asset('/public/assets/web/image/our-client.jpg') }}" alt="Royal Tandoor">
                    </div>
                    <div class="trust-content">
                        <h5>Royal Tandoor</h5>
                        <p>Jaipur, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <i class="bi bi-cup-straw"></i>
                    </div>
                    <div class="trust-content">
                        <h5>Juice Junction</h5>
                        <p>Kolkata, India</p>
                    </div>
                </div>
                <!-- Duplicate set for seamless loop -->
                <div class="trust-card">
                    <div class="trust-icon">
                        <img src="{{ asset('/public/assets/web/image/our-client.jpg') }}" alt="Fine Dine">
                    </div>
                    <div class="trust-content">
                        <h5>Fine Dine</h5>
                        <p>Mumbai, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <i class="bi bi-cup-hot"></i>
                    </div>
                    <div class="trust-content">
                        <h5>Cafe Corner</h5>
                        <p>Delhi, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <img src="{{ asset('/public/assets/web/image/our-client.jpg') }}" alt="Breakfast Hub">
                    </div>
                    <div class="trust-content">
                        <h5>Breakfast Hub</h5>
                        <p>Bangalore, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <i class="bi bi-cake"></i>
                    </div>
                    <div class="trust-content">
                        <h5>Sweet Treats</h5>
                        <p>Chennai, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <img src="{{ asset('/public/assets/web/image/our-client.jpg') }}" alt="Spice Kitchen">
                    </div>
                    <div class="trust-content">
                        <h5>Spice Kitchen</h5>
                        <p>Hyderabad, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <i class="bi bi-tropical-storm"></i>
                    </div>
                    <div class="trust-content">
                        <h5>Asian Fusion</h5>
                        <p>Pune, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <img src="{{ asset('/public/assets/web/image/our-client.jpg') }}" alt="Royal Tandoor">
                    </div>
                    <div class="trust-content">
                        <h5>Royal Tandoor</h5>
                        <p>Jaipur, India</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">
                        <i class="bi bi-cup-straw"></i>
                    </div>
                    <div class="trust-content">
                        <h5>Juice Junction</h5>
                        <p>Kolkata, India</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Connect Section -->
    <section class="newsletter-section py-5" id="quick-connect">
        <div class="container">
            <div class="newsletter-box">
                <div class="row align-items-center">
                    <div class="col-lg-5 mb-4 mb-lg-0">
                        <h3><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Quick Connect</h3>
                        <p class="text-muted mb-0">Get in touch with us and we'll help you get started right away.</p>
                    </div>
                    <div class="col-lg-7">
                        <form class="quick-connect-form" action="{{ route('quick_connect') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="qc-input-group">
                                        <i class="bi bi-person"></i>
                                        <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="qc-input-group">
                                        <i class="bi bi-telephone"></i>
                                        <input type="tel" name="phone" class="form-control" placeholder="Phone Number" required>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="qc-input-group">
                                        <i class="bi bi-envelope"></i>
                                        <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-warning w-100 qc-submit-btn" type="submit">
                                        <i class="bi bi-send me-1"></i> Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="social-icons mt-3 text-lg-end">
                            <a href="#"><i class="bi bi-facebook"></i></a>
                            <a href="#"><i class="bi bi-twitter"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                            <a href="#"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('script_2')
<script>
    // Testimonial Slider
    (function() {
        const testimonials = [
            {
                text: "Qrscop transformed how we serve our customers. The QR menu is so easy to use, and we save hundreds on printing costs every month. Our customers love the convenience!",
                name: "Rajesh Kumar",
                image: "{{ asset('public/assets/web/img/client/1.svg') }}",
                role: "Owner, Spice Garden Restaurant"
            },
            {
                text: "Since implementing Qrscop, our table turnover has improved significantly. Customers can browse the menu instantly and our staff can focus on providing better service.",
                name: "Priya Sharma",
                image: "{{ asset('public/assets/web/img/client/2.svg') }}",
                role: "Manager, The Urban Cafe"
            },
            {
                text: "The analytics dashboard is a game-changer. We now know exactly which dishes are most viewed and can optimize our menu accordingly. Highly recommended!",
                name: "Amit Patel",
                image: "{{ asset('public/assets/web/img/client/3.svg') }}",
                role: "Owner, Flavors of India"
            },
            {
                text: "We run multiple restaurant locations and Qrscop makes it easy to manage all menus from one dashboard. The multi-language support helps us serve tourists too!",
                name: "Sarah Chen",
                image: "{{ asset('public/assets/web/img/client/4.svg') }}",
                role: "Director, Dragon Palace Chain"
            }
        ];

        const textEl = document.getElementById('testimonialText');
        const avatarEl = document.getElementById('testimonialAvatar');
        const nameEl = document.getElementById('testimonialName');
        const roleEl = document.getElementById('testimonialRole');
        const dots = document.querySelectorAll('.testimonial-dots .dot');

        if (!textEl || !avatarEl || !nameEl || !roleEl) return;

        let currentIndex = 0;

        function updateTestimonial(index) {
            const testimonial = testimonials[index];

            // Fade out
            textEl.style.opacity = '0';
            avatarEl.style.opacity = '0';
            nameEl.style.opacity = '0';
            roleEl.style.opacity = '0';

            setTimeout(() => {
                textEl.textContent = testimonial.text;
                avatarEl.src = testimonial.image;
                nameEl.textContent = testimonial.name;
                roleEl.textContent = testimonial.role;

                // Fade in
                textEl.style.opacity = '1';
                avatarEl.style.opacity = '1';
                nameEl.style.opacity = '1';
                roleEl.style.opacity = '1';
            }, 300);

            // Update dots
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
        }

        // Add transition styles
        [textEl, avatarEl, nameEl, roleEl].forEach(el => {
            el.style.transition = 'opacity 0.3s ease';
        });

        // Click handlers for dots
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentIndex = index;
                updateTestimonial(index);
            });
        });

        // Auto slide every 5 seconds
        setInterval(() => {
            currentIndex = (currentIndex + 1) % testimonials.length;
            updateTestimonial(currentIndex);
        }, 5000);
    })();

</script>
@endpush
