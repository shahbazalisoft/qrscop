@extends('layouts.landing.app')

@section('title', translate('messages.about_us'))

@section('content')
    <!-- ==== About Section ==== -->
    @php($landing_page_text = \App\Models\BusinessSetting::where(['key' => 'landing_page_text'])->first())
    @php($landing_page_text = isset($landing_page_text->value) ? json_decode($landing_page_text->value, true) : null)
    
    <!-- About Hero Section -->
    <section class="contact-hero-section">
        <div class="hero-bg-elements">
            <div class="floating-bean bean-1">
                <i class="bi bi-people" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
            <div class="floating-bean bean-2">
                <i class="bi bi-building" style="font-size: 35px; opacity: 0.3;"></i>
            </div>
            <div class="floating-bean bean-3">
                <i class="bi bi-heart" style="font-size: 45px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <span class="hero-badge">
                        <i class="bi bi-info-circle me-2"></i>About Us
                    </span>
                    <h1 class="hero-title">Transforming Restaurant Experiences</h1>
                    <p class="hero-text">We're on a mission to help restaurants embrace digital innovation. Learn about our
                        journey, our team, and the passion that drives us to create the best QR menu solutions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="about-section py-5">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="about-images">
                        <div class="row g-3">
                            <div class="col-6">
                                <img src="{{ asset('/public/assets/web/image/about-page-one.jpg') }}"
                                    alt="Team Working" class="img-fluid rounded about-img-1">
                            </div>
                            <div class="col-6 mt-5">
                                <img src="{{ asset('/public/assets/web/image/about-page-two.jpg') }}"
                                    alt="Team Meeting" class="img-fluid rounded about-img-2">
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
                        <span class="section-subtitle">Our Story</span>
                        <h2 class="section-title">From Idea to <span class="text-warning">Innovation</span></h2>
                        <p class="section-text">{{ \App\CentralLogics\Helpers::get_settings('business_name') }} was born in 2020 during a time when restaurants needed contactless
                            solutions more than ever. What started as a simple idea to help a local restaurant friend has
                            grown into a comprehensive digital menu platform serving hundreds of restaurants across India.
                        </p>
                        <p class="section-text">Our founders, passionate food lovers and tech enthusiasts, saw the
                            challenges restaurants faced with traditional paper menus - high printing costs, inability to
                            update quickly, and hygiene concerns. They set out to create a solution that would be easy to
                            use, affordable, and beautiful.</p>
                        <p class="section-text">Today, {{ \App\CentralLogics\Helpers::get_settings('business_name') }} powers over 500 restaurants, with more than 1 million menu
                            scans every month. But we're just getting started on our mission to digitize every restaurant
                            menu in the country.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="category-section py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="category-card" style="height: 100%;">
                        <div class="category-icon">
                            <i class="bi bi-bullseye"></i>
                        </div>
                        <h4>Our Mission</h4>
                        <p>To empower restaurants of all sizes with affordable, easy-to-use digital menu solutions that
                            enhance customer experience, reduce operational costs, and promote sustainability. We believe
                            every restaurant deserves access to modern technology.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="category-card featured" style="height: 100%;">
                        <div class="category-icon">
                            <i class="bi bi-eye"></i>
                        </div>
                        <h4>Our Vision</h4>
                        <p>To become India's leading digital menu platform, transforming how millions of diners interact
                            with restaurant menus. We envision a future where every restaurant, from street food stalls to
                            fine dining establishments, offers seamless digital experiences.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="why-choose-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-subtitle">Our Impact</span>
                <h2 class="section-title">Numbers That Speak</h2>
                <p class="section-text mx-auto" style="max-width: 600px;">Our growth reflects the trust restaurants place in
                    us</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-3 col-md-6">
                    <div class="benefit-card text-center">
                        <div class="benefit-icon">
                            <i class="bi bi-shop"></i>
                        </div>
                        <h3 class="text-warning mb-2">500+</h3>
                        <p>Restaurants Trust Us</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="benefit-card text-center">
                        <div class="benefit-icon">
                            <i class="bi bi-qr-code-scan"></i>
                        </div>
                        <h3 class="text-warning mb-2">1M+</h3>
                        <p>Monthly Menu Scans</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="benefit-card text-center">
                        <div class="benefit-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <h3 class="text-warning mb-2">50+</h3>
                        <p>Cities Covered</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="benefit-card text-center">
                        <div class="benefit-icon">
                            <i class="bi bi-star"></i>
                        </div>
                        <h3 class="text-warning mb-2">99%</h3>
                        <p>Customer Satisfaction</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values Section -->
    <section class="chef-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-subtitle">What We Believe</span>
                <h2 class="section-title">Our Core Values</h2>
                <p class="section-text mx-auto" style="max-width: 600px;">The principles that guide everything we do at
                    {{ \App\CentralLogics\Helpers::get_settings('business_name') }}</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="bi bi-lightbulb"></i>
                        </div>
                        <h4>Innovation First</h4>
                        <p>We constantly push boundaries to bring the latest technology to restaurants. From AI-powered menu
                            suggestions to real-time analytics, we're always innovating.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="bi bi-hand-thumbs-up"></i>
                        </div>
                        <h4>Simplicity Matters</h4>
                        <p>Technology should make life easier, not harder. We design our products to be intuitive - if you
                            can use a smartphone, you can use {{ \App\CentralLogics\Helpers::get_settings('business_name') }}.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h4>Customer Success</h4>
                        <p>Your success is our success. We provide dedicated support, training, and resources to ensure
                            every restaurant gets the most out of our platform.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="bi bi-tree"></i>
                        </div>
                        <h4>Sustainability</h4>
                        <p>By eliminating paper menus, we help restaurants reduce their environmental footprint. Join us in
                            making dining more eco-friendly.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>Trust & Security</h4>
                        <p>We take data security seriously. Your restaurant's information and customer data are protected
                            with enterprise-grade security measures.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <h4>Affordable for All</h4>
                        <p>Great technology shouldn't break the bank. We offer flexible pricing so restaurants of any size
                            can access our digital menu solutions.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- All Templates Gallery Section -->
    <?php
        if (!isset($menuTemplates)) {
            $menuTemplates = \App\Models\MenuTemplate::where('status', 1)->get();
        }
    ?>
    <section class="template-gallery-section">
        <div class="container">
            <div class="d-flex align-items-end justify-content-between mb-5">
                <div>
                    <span class="section-subtitle">Our Templates</span>
                    <h2 class="section-title mb-0">All Menu <span class="text-warning">Templates</span></h2>
                </div>
                <div class="template-scroll-nav d-none d-md-flex">
                    <button class="template-nav-btn" id="tplScrollLeft"><i class="bi bi-chevron-left"></i></button>
                    <button class="template-nav-btn" id="tplScrollRight"><i class="bi bi-chevron-right"></i></button>
                </div>
            </div>

            <div class="template-scroll-row" id="templateScrollRow">
                @foreach ($menuTemplates as $menuTemplate)
                <div class="template-scroll-item">
                    <div class="template-gallery-card">
                        <div class="template-gallery-img">
                            <img src="{{asset('storage/app/public/menu-template')}}/{{ $menuTemplate->template }}" alt="{{ $menuTemplate->title }}">
                        </div>
                        <div class="template-gallery-info">
                            <h5>{{ $menuTemplate->title }}</h5>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Feature Highlights -->
            <div class="design-features-row mt-5">
                <div class="row g-4 justify-content-center">
                    <div class="col-md-4 col-sm-6">
                        <div class="design-feature">
                            <i class="bi bi-brush"></i>
                            <span>Fully Customizable</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="design-feature">
                            <i class="bi bi-phone"></i>
                            <span>Mobile Responsive</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="design-feature">
                            <i class="bi bi-lightning"></i>
                            <span>Instant Preview</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('script_2')
    <script>
        (function() {
            const row = document.getElementById('templateScrollRow');
            const leftBtn = document.getElementById('tplScrollLeft');
            const rightBtn = document.getElementById('tplScrollRight');
            if (!row) return;

            const scrollAmount = 300;

            if (leftBtn) leftBtn.addEventListener('click', function() {
                row.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
            if (rightBtn) rightBtn.addEventListener('click', function() {
                row.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });

            // Drag to scroll
            let isDown = false, startX, scrollLeft;
            row.addEventListener('mousedown', function(e) {
                isDown = true;
                row.classList.add('dragging');
                startX = e.pageX - row.offsetLeft;
                scrollLeft = row.scrollLeft;
            });
            row.addEventListener('mouseleave', function() { isDown = false; row.classList.remove('dragging'); });
            row.addEventListener('mouseup', function() { isDown = false; row.classList.remove('dragging'); });
            row.addEventListener('mousemove', function(e) {
                if (!isDown) return;
                e.preventDefault();
                var x = e.pageX - row.offsetLeft;
                row.scrollLeft = scrollLeft - (x - startX);
            });
        })();
    </script>
    @endpush

    <!-- Why Choose Us Section -->
    <section class="about-section py-5">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-lg-2">
                    <div class="about-images">
                        <div class="row g-3">
                            <div class="col-6">
                                <img src="{{ asset('/public/assets/web/image/why-menu-scan-one.jpg') }}"
                                    alt="Office Space" class="img-fluid rounded about-img-1">
                            </div>
                            <div class="col-6 mt-5">
                                <img src="{{ asset('/public/assets/web/image/why-menu-scan-two.jpg') }}"
                                    alt="Team Collaboration" class="img-fluid rounded about-img-2">
                            </div>
                        </div>
                        <div class="about-pattern">
                            <span></span><span></span><span></span><span></span><span></span>
                            <span></span><span></span><span></span><span></span><span></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="about-content">
                        <span class="section-subtitle">Why {{ \App\CentralLogics\Helpers::get_settings('business_name') }}</span>
                        <h2 class="section-title">What Makes Us <span class="text-warning">Different</span></h2>
                        <p class="section-text">We're not just another tech company - we understand the restaurant industry
                            because we've been in your shoes.</p>
                        <ul class="about-features">
                            <li><i class="bi bi-check-square-fill text-warning"></i> Built by restaurant industry veterans
                            </li>
                            <li><i class="bi bi-check-square-fill text-warning"></i> 24/7 dedicated customer support</li>
                            <li><i class="bi bi-check-square-fill text-warning"></i> No hidden fees or long-term contracts
                            </li>
                            <li><i class="bi bi-check-square-fill text-warning"></i> Free training and onboarding</li>
                            <li><i class="bi bi-check-square-fill text-warning"></i> Regular feature updates at no extra
                                cost</li>
                            <li><i class="bi bi-check-square-fill text-warning"></i> Local language support available</li>
                        </ul>
                        <a href="{{route('contact-us')}}" class="btn btn-warning">Get in Touch</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="testimonial-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-subtitle">Our Team</span>
                <h2 class="section-title">Meet the People Behind {{ \App\CentralLogics\Helpers::get_settings('business_name') }}</h2>
                <p class="section-text mx-auto" style="max-width: 600px;">Passionate individuals dedicated to transforming
                    the restaurant industry</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-3 col-md-6">
                    <div class="team-card text-center">
                        <div class="team-image">
                            <img src="{{ asset('/public/assets/web/image/our-team-one.jpg') }}"
                                alt="Amit Sharma" class="img-fluid rounded-circle">
                        </div>
                        <h5 class="mt-3 mb-1">Amit Sharma</h5>
                        <p class="text-warning mb-2">Founder & CEO</p>
                        <p class="text-muted small">Former restaurant owner turned tech entrepreneur. Passionate about
                            solving real-world problems.</p>
                        <div class="team-social">
                            <a href="#"><i class="bi bi-linkedin"></i></a>
                            <a href="#"><i class="bi bi-twitter"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="team-card text-center">
                        <div class="team-image">
                            <img src="{{ asset('/public/assets/web/image/our-team-two.jpg') }}"
                                alt="Priya Patel" class="img-fluid rounded-circle">
                        </div>
                        <h5 class="mt-3 mb-1">Priya Patel</h5>
                        <p class="text-warning mb-2">Co-Founder & CTO</p>
                        <p class="text-muted small">10+ years in software development. Leads our engineering team with
                            innovation.</p>
                        <div class="team-social">
                            <a href="#"><i class="bi bi-linkedin"></i></a>
                            <a href="#"><i class="bi bi-github"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="team-card text-center">
                        <div class="team-image">
                            <img src="{{ asset('/public/assets/web/image/our-team-three.jpg') }}"
                                alt="Rahul Verma" class="img-fluid rounded-circle">
                        </div>
                        <h5 class="mt-3 mb-1">Rahul Verma</h5>
                        <p class="text-warning mb-2">Head of Design</p>
                        <p class="text-muted small">Award-winning designer creating beautiful, user-friendly menu
                            templates.</p>
                        <div class="team-social">
                            <a href="#"><i class="bi bi-linkedin"></i></a>
                            <a href="#"><i class="bi bi-dribbble"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="team-card text-center">
                        <div class="team-image">
                            <img src="{{ asset('/public/assets/web/image/our-team-four.jpg') }}" alt="Neha Singh"
                                class="img-fluid rounded-circle">
                        </div>
                        <h5 class="mt-3 mb-1">Neha Singh</h5>
                        <p class="text-warning mb-2">Customer Success Lead</p>
                        <p class="text-muted small">Ensures every restaurant gets the support they need to succeed with
                            {{ \App\CentralLogics\Helpers::get_settings('business_name') }}.</p>
                        <div class="team-social">
                            <a href="#"><i class="bi bi-linkedin"></i></a>
                            <a href="#"><i class="bi bi-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="contact-cta-section py-5">
        <div class="container">
            <div class="cta-box text-center">
                <h2>Ready to Join the {{ \App\CentralLogics\Helpers::get_settings('business_name') }} Family?</h2>
                <p class="mb-4">Start your digital menu journey today and see why 500+ restaurants trust us</p>
                <div class="cta-buttons">
                    <a href="{{route('restaurant.create')}}" class="btn btn-warning btn-lg me-3">Start Free Trial</a>
                    <a href="{{route('contact-us')}}" class="btn btn-outline-light btn-lg">Contact Us</a>
                </div>
            </div>
        </div>
    </section>
    <!-- ==== About Section ==== -->
@endsection
