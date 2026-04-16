@extends('layouts.landing.app')

@section('title', 'Pricing - Qrscop')

@section('content')
    <!-- Pricing Hero Section -->
    <section class="contact-hero-section">
        <div class="hero-bg-elements">
            <div class="floating-bean bean-1">
                <i class="bi bi-tag" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
            <div class="floating-bean bean-2">
                <i class="bi bi-credit-card" style="font-size: 35px; opacity: 0.3;"></i>
            </div>
            <div class="floating-bean bean-3">
                <i class="bi bi-gem" style="font-size: 45px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <span class="hero-badge">
                        <i class="bi bi-tag me-2"></i>Pricing
                    </span>
                    <h1 class="hero-title">Choose Your Plan</h1>
                    <p class="hero-text">Flexible pricing plans to suit restaurants of all sizes. Start free and upgrade as you grow.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Cards Section -->
    <section class="menu-section py-5">
        <div class="container">
            <div class="pricing-scroll-wrapper">
                <div class="pricing-scroll-track">
                    <div class="pricing-scroll-item">
                        <div class="pricing-card">
                            <div class="pricing-header">
                                <h4>Starter</h4>
                                <p class="price"><span class="currency">₹</span>199<span class="period">/90 days</span></p>
                                <p class="price-desc">Perfect for trying out</p>
                            </div>
                            <div class="pricing-features">
                                <ul>
                                    {{-- <li><i class="bi bi-check-circle-fill"></i> 1 QR Code</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Up to 20 Menu Items</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Basic Menu Design</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Mobile Responsive</li>
                                    <li><i class="bi bi-x-circle"></i> <span class="disabled">Analytics Dashboard</span></li>
                                    <li><i class="bi bi-x-circle"></i> <span class="disabled">Custom Branding</span></li> --}}
                                    <li><i class="bi bi-check-circle-fill"></i> Simple QR</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Table-wise QR System</li>
                                    <li><i class="bi bi-check-circle-fill"></i> All Menu Templates</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Kitchen Dashboard</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Unlimited Orders</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Unlimited Products</li>
                                </ul>
                            </div>
                            <a href="{{route('restaurant.create')}}" class="btn btn-outline-warning w-100">Get Started</a>
                        </div>
                    </div>
                    <div class="pricing-scroll-item">
                        <div class="pricing-card featured">
                            <div class="popular-badge">Most Popular</div>
                            <div class="pricing-header">
                                <h4>Professional</h4>
                                <p class="price"><span class="currency">₹</span>299<span class="period">/180 days</span></p>
                                <p class="price-desc">Best for growing restaurants</p>
                            </div>
                            <div class="pricing-features">
                                <ul>
                                    {{-- <li><i class="bi bi-check-circle-fill"></i> Unlimited QR Codes</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Unlimited Menu Items</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Premium Designs</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Analytics Dashboard</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Custom Branding</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Multi-Language Support</li> --}}
                                    <li><i class="bi bi-check-circle-fill"></i> Simple QR</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Table-wise QR System</li>
                                    <li><i class="bi bi-check-circle-fill"></i> All Menu Templates</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Kitchen Dashboard</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Unlimited Orders</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Unlimited Products</li>
                                </ul>
                            </div>
                            <a href="{{route('restaurant.create')}}" class="btn btn-warning w-100">Start Free Trial</a>
                        </div>
                    </div>
                    <div class="pricing-scroll-item">
                        <div class="pricing-card">
                            <div class="pricing-header">
                                <h4>Enterprise</h4>
                                <p class="price"><span class="currency">₹</span>500<span class="period">/360 days</span></p>
                                <p class="price-desc">For restaurant chains</p>
                            </div>
                            <div class="pricing-features">
                                <ul>
                                    {{-- <li><i class="bi bi-check-circle-fill"></i> Everything in Professional</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Multiple Locations</li>
                                    <li><i class="bi bi-check-circle-fill"></i> API Access</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Priority Support</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Custom Integration</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Dedicated Manager</li> --}}
                                    <li><i class="bi bi-check-circle-fill"></i> Simple QR</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Table-wise QR System</li>
                                    <li><i class="bi bi-check-circle-fill"></i> All Menu Templates</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Kitchen Dashboard</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Unlimited Orders</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Unlimited Products</li>
                                </ul>
                            </div>
                            <a href="{{route('restaurant.create')}}" class="btn btn-outline-warning w-100">Contact Sales</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
