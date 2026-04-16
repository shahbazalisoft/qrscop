@extends('layouts.landing.app')

@section('title',translate('messages.terms_and_condition'))

@section('content')
    <!-- Terms of Service Hero Section -->
    <section class="contact-hero-section">
        <div class="hero-bg-elements">
            <div class="floating-bean bean-1">
                <i class="bi bi-file-earmark-text" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
            <div class="floating-bean bean-2">
                <i class="bi bi-journal-check" style="font-size: 35px; opacity: 0.3;"></i>
            </div>
            <div class="floating-bean bean-3">
                <i class="bi bi-clipboard-check" style="font-size: 45px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="hero-badge">
                        <i class="bi bi-file-earmark-ruled me-2"></i>Legal Agreement
                    </span>
                    <h1 class="hero-title">Terms of Service</h1>
                    <p class="hero-text">Please read these terms carefully before using {{ \App\CentralLogics\Helpers::get_settings('business_name') }}. By accessing or using our platform, you agree to be bound by these terms and conditions.</p>
                    <p class="last-updated mt-3"><i class="bi bi-calendar3 me-2"></i>Effective Date: January 2024</p>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="policy-hero-image">
                        <div class="hero-img-wrapper">
                            <img src="{{ asset('/public/assets/web/image/term-condition-banner.jpg') }}" alt="Terms Agreement" class="img-fluid main-hero-img">
                            <div class="floating-qr-card">
                                <div class="qr-code-mini">
                                    <img src="{{ asset('/public/assets/web/image/demo-qr.svg') }}" alt="QR Code">
                                </div>
                                <span>Scan & Agree</span>
                            </div>
                            <div class="floating-shield-badge">
                                <i class="bi bi-check-circle"></i>
                                <span>Verified</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Terms Highlights Section -->
    <section class="contact-info-section py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="bi bi-hand-thumbs-up"></i>
                        </div>
                        <h5>Fair Usage</h5>
                        <p>Clear guidelines for using our platform responsibly and effectively</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5>Your Rights</h5>
                        <p>Understanding your rights as a {{ \App\CentralLogics\Helpers::get_settings('business_name') }} user or restaurant partner</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <h5>Billing Terms</h5>
                        <p>Transparent pricing, billing cycles, and refund policies</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <h5>Limitations</h5>
                        <p>Service limitations and liability information you should know</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Terms of Service Content Section -->
    <section class="policy-content-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="policy-wrapper">

                        <!-- Agreement to Terms -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-hand-index-thumb"></i>
                            </div>
                            <h3>1. Agreement to Terms</h3>
                            <p>By accessing or using {{ \App\CentralLogics\Helpers::get_settings('business_name') }}'s website, mobile applications, or any of our services (collectively, the "Services"), you agree to be bound by these Terms of Service ("Terms"). If you disagree with any part of these terms, you may not access the Services.</p>
                            <p>These Terms apply to all visitors, users, restaurant partners, and others who access or use the Services. By using {{ \App\CentralLogics\Helpers::get_settings('business_name') }}, you represent that you are at least 18 years old and have the legal capacity to enter into these Terms.</p>
                        </div>

                        <!-- Description of Services -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-grid-3x3-gap"></i>
                            </div>
                            <h3>2. Description of Services</h3>
                            <p>{{ \App\CentralLogics\Helpers::get_settings('business_name') }} provides a digital menu platform that allows restaurants to:</p>

                            <div class="use-case-grid">
                                <div class="use-case-item">
                                    <i class="bi bi-qr-code"></i>
                                    <div>
                                        <h6>QR Code Generation</h6>
                                        <p>Create unique QR codes for contactless menu access</p>
                                    </div>
                                </div>
                                <div class="use-case-item">
                                    <i class="bi bi-pencil-square"></i>
                                    <div>
                                        <h6>Menu Management</h6>
                                        <p>Create, edit, and manage digital menus in real-time</p>
                                    </div>
                                </div>
                                <div class="use-case-item">
                                    <i class="bi bi-palette"></i>
                                    <div>
                                        <h6>Customization</h6>
                                        <p>Customize menu appearance with templates and branding</p>
                                    </div>
                                </div>
                                <div class="use-case-item">
                                    <i class="bi bi-bar-chart"></i>
                                    <div>
                                        <h6>Analytics</h6>
                                        <p>Access insights about menu views and customer engagement</p>
                                    </div>
                                </div>
                                <div class="use-case-item">
                                    <i class="bi bi-translate"></i>
                                    <div>
                                        <h6>Multi-Language</h6>
                                        <p>Support for multiple languages to serve diverse customers</p>
                                    </div>
                                </div>
                                <div class="use-case-item">
                                    <i class="bi bi-headset"></i>
                                    <div>
                                        <h6>Support</h6>
                                        <p>Customer support and technical assistance</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Registration -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <h3>3. Account Registration</h3>
                            <p>To access certain features of our Services, you must register for an account. When you register, you agree to:</p>

                            <ul class="policy-list">
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Provide accurate, current, and complete information during registration</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Maintain and promptly update your account information</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Keep your password secure and confidential</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Accept responsibility for all activities under your account</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Notify us immediately of any unauthorized access or security breach</li>
                            </ul>

                            <div class="alert-box mt-4">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <p><strong>Important:</strong> You are responsible for all activities that occur under your account. {{ \App\CentralLogics\Helpers::get_settings('business_name') }} reserves the right to suspend or terminate accounts that violate these Terms.</p>
                            </div>
                        </div>

                        <!-- Subscription & Payments -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-credit-card-2-front"></i>
                            </div>
                            <h3>4. Subscription Plans & Payments</h3>

                            <h5 class="mt-4 text-warning">4.1 Subscription Plans</h5>
                            <p>{{ \App\CentralLogics\Helpers::get_settings('business_name') }} offers various subscription plans:</p>
                            <ul class="policy-list">
                                <li><i class="bi bi-check-circle-fill text-warning"></i> <strong>Starter (Free):</strong> Basic features with limited menu items and 1 QR code</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> <strong>Professional:</strong> Unlimited menus, premium templates, and analytics</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> <strong>Enterprise:</strong> All features plus API access, multiple locations, and priority support</li>
                            </ul>

                            <h5 class="mt-4 text-warning">4.2 Billing</h5>
                            <ul class="policy-list">
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Paid subscriptions are billed in advance on a monthly or annual basis</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> All fees are exclusive of applicable taxes unless stated otherwise</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> You authorize us to charge your payment method for all fees due</li>
                            </ul>

                            <h5 class="mt-4 text-warning">4.3 Refunds</h5>
                            <ul class="policy-list">
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Monthly subscriptions: No refunds for partial months</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Annual subscriptions: Pro-rata refund within first 30 days if service is unsatisfactory</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Refund requests must be submitted via email to {{ \App\CentralLogics\Helpers::get_settings('email_address') }}</li>
                            </ul>
                        </div>

                        <!-- User Content -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-file-earmark-image"></i>
                            </div>
                            <h3>5. User Content</h3>

                            <h5 class="mt-4 text-warning">5.1 Your Content</h5>
                            <p>You retain ownership of all content you upload, including menu items, descriptions, images, and logos ("User Content"). By uploading User Content, you grant {{ \App\CentralLogics\Helpers::get_settings('business_name') }} a non-exclusive, worldwide, royalty-free license to use, display, and distribute your content solely for the purpose of providing our Services.</p>

                            <h5 class="mt-4 text-warning">5.2 Content Guidelines</h5>
                            <p>You agree that your User Content will not:</p>
                            <ul class="policy-list">
                                <li><i class="bi bi-x-circle-fill text-danger"></i> Violate any applicable laws or regulations</li>
                                <li><i class="bi bi-x-circle-fill text-danger"></i> Infringe on intellectual property rights of others</li>
                                <li><i class="bi bi-x-circle-fill text-danger"></i> Contain false, misleading, or deceptive information</li>
                                <li><i class="bi bi-x-circle-fill text-danger"></i> Include offensive, harmful, or inappropriate material</li>
                                <li><i class="bi bi-x-circle-fill text-danger"></i> Contain malware, viruses, or malicious code</li>
                            </ul>
                        </div>

                        <!-- Acceptable Use -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-check2-square"></i>
                            </div>
                            <h3>6. Acceptable Use Policy</h3>
                            <p>When using {{ \App\CentralLogics\Helpers::get_settings('business_name') }}, you agree NOT to:</p>

                            <div class="security-features">
                                <div class="security-item">
                                    <div class="security-icon" style="background: rgba(220, 53, 69, 0.15);">
                                        <i class="bi bi-bug" style="color: #dc3545;"></i>
                                    </div>
                                    <div>
                                        <h6>No Hacking</h6>
                                        <p>Attempt to gain unauthorized access to our systems or other users' accounts</p>
                                    </div>
                                </div>
                                <div class="security-item">
                                    <div class="security-icon" style="background: rgba(220, 53, 69, 0.15);">
                                        <i class="bi bi-robot" style="color: #dc3545;"></i>
                                    </div>
                                    <div>
                                        <h6>No Automation Abuse</h6>
                                        <p>Use bots, scrapers, or automated tools to access our Services without permission</p>
                                    </div>
                                </div>
                                <div class="security-item">
                                    <div class="security-icon" style="background: rgba(220, 53, 69, 0.15);">
                                        <i class="bi bi-envelope-x" style="color: #dc3545;"></i>
                                    </div>
                                    <div>
                                        <h6>No Spam</h6>
                                        <p>Send unsolicited communications or spam through our platform</p>
                                    </div>
                                </div>
                                <div class="security-item">
                                    <div class="security-icon" style="background: rgba(220, 53, 69, 0.15);">
                                        <i class="bi bi-lightning" style="color: #dc3545;"></i>
                                    </div>
                                    <div>
                                        <h6>No Service Disruption</h6>
                                        <p>Interfere with or disrupt the integrity or performance of our Services</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Intellectual Property -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-award"></i>
                            </div>
                            <h3>7. Intellectual Property Rights</h3>
                            <p>The {{ \App\CentralLogics\Helpers::get_settings('business_name') }} platform, including its original content, features, functionality, design, logos, and trademarks, is owned by {{ \App\CentralLogics\Helpers::get_settings('business_name') }} and protected by international copyright, trademark, and other intellectual property laws.</p>

                            <ul class="policy-list">
                                <li><i class="bi bi-check-circle-fill text-warning"></i> You may not copy, modify, or distribute our platform without written consent</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Our templates are licensed for use within the {{ \App\CentralLogics\Helpers::get_settings('business_name') }} platform only</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> "{{ \App\CentralLogics\Helpers::get_settings('business_name') }}" and related marks are our registered trademarks</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Feedback and suggestions you provide may be used to improve our Services</li>
                            </ul>
                        </div>

                        <!-- Termination -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-x-octagon"></i>
                            </div>
                            <h3>8. Termination</h3>

                            <h5 class="mt-4 text-warning">8.1 Termination by You</h5>
                            <p>You may terminate your account at any time by contacting us or using the account settings. Upon termination:</p>
                            <ul class="policy-list">
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Your access to the Services will be immediately revoked</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Your QR codes will stop working within 24 hours</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Your data will be deleted within 30 days (except as required by law)</li>
                            </ul>

                            <h5 class="mt-4 text-warning">8.2 Termination by {{ \App\CentralLogics\Helpers::get_settings('business_name') }}</h5>
                            <p>We may terminate or suspend your account immediately, without prior notice, if:</p>
                            <ul class="policy-list">
                                <li><i class="bi bi-check-circle-fill text-warning"></i> You breach these Terms of Service</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> You fail to pay applicable fees</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Your use poses a security risk to our platform or other users</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Required by law or regulatory authority</li>
                            </ul>
                        </div>

                        <!-- Limitation of Liability -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-exclamation-diamond"></i>
                            </div>
                            <h3>9. Limitation of Liability</h3>
                            <p>To the maximum extent permitted by law:</p>

                            <div class="alert-box mt-3" style="background: rgba(220, 53, 69, 0.1); border-color: #dc3545;">
                                <i class="bi bi-exclamation-circle-fill" style="color: #dc3545;"></i>
                                <div>
                                    <p style="color: var(--text-light);"><strong>Disclaimer:</strong> {{ \App\CentralLogics\Helpers::get_settings('business_name') }} provides the Services "as is" and "as available" without warranties of any kind, either express or implied, including but not limited to implied warranties of merchantability, fitness for a particular purpose, and non-infringement.</p>
                                </div>
                            </div>

                            <ul class="policy-list mt-4">
                                <li><i class="bi bi-check-circle-fill text-warning"></i> We are not liable for any indirect, incidental, special, or consequential damages</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Our total liability shall not exceed the amount paid by you in the past 12 months</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> We are not responsible for third-party services or content</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> We do not guarantee uninterrupted or error-free service</li>
                            </ul>
                        </div>

                        <!-- Indemnification -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-shield-exclamation"></i>
                            </div>
                            <h3>10. Indemnification</h3>
                            <p>You agree to defend, indemnify, and hold harmless {{ \App\CentralLogics\Helpers::get_settings('business_name') }}, its officers, directors, employees, and agents from any claims, damages, losses, or expenses (including reasonable attorney's fees) arising from:</p>
                            <ul class="policy-list">
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Your use of the Services</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Your User Content</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Your violation of these Terms</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Your violation of any rights of another party</li>
                            </ul>
                        </div>

                        <!-- Governing Law -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-globe-asia-australia"></i>
                            </div>
                            <h3>11. Governing Law & Disputes</h3>
                            <p>These Terms shall be governed by and construed in accordance with the laws of India, without regard to conflict of law principles.</p>
                            <ul class="policy-list">
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Any disputes shall be resolved through arbitration in Mumbai, India</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> The arbitration shall be conducted in English</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Each party shall bear their own costs unless otherwise determined</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> The arbitrator's decision shall be final and binding</li>
                            </ul>
                        </div>

                        <!-- Changes to Terms -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-pencil-square"></i>
                            </div>
                            <h3>12. Changes to These Terms</h3>
                            <p>We reserve the right to modify these Terms at any time. When we make changes:</p>
                            <ul class="policy-list">
                                <li><i class="bi bi-check-circle-fill text-warning"></i> We will update the "Effective Date" at the top of this page</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> We will notify you via email for material changes</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Continued use of Services constitutes acceptance of new Terms</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> You may terminate your account if you disagree with changes</li>
                            </ul>
                        </div>

                        <!-- Contact Us -->
                        <div class="policy-section contact-section">
                            <div class="policy-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <h3>13. Contact Us</h3>
                            <p>If you have any questions about these Terms of Service, please contact us:</p>

                            <div class="contact-details">
                                <div class="contact-item">
                                    <i class="bi bi-envelope-fill text-warning"></i>
                                    <div>
                                        <strong>Email</strong>
                                        <p>{{ \App\CentralLogics\Helpers::get_settings('email_address') }}</p>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <i class="bi bi-geo-alt-fill text-warning"></i>
                                    <div>
                                        <strong>Address</strong>
                                        <p>{{ \App\CentralLogics\Helpers::get_settings('address') }}</p>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <i class="bi bi-telephone-fill text-warning"></i>
                                    <div>
                                        <strong>Phone</strong>
                                        <p>{{ \App\CentralLogics\Helpers::get_settings('phone') }}</p>
                                    </div>
                                </div>
                            </div>
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
                <h2>Ready to Get Started with {{ \App\CentralLogics\Helpers::get_settings('business_name') }}?</h2>
                <p class="mb-4">Join 500+ restaurants already using our digital menu platform</p>
                <div class="cta-buttons">
                    <a href="register.html" class="btn btn-warning btn-lg me-3">Start Free Trial</a>
                    <a href="contact.html" class="btn btn-outline-light btn-lg">Contact Us</a>
                </div>
            </div>
        </div>
    </section>
@endsection
