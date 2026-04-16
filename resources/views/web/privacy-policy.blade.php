@extends('layouts.landing.app')

@section('title', translate('messages.privacy_policy'))

@section('content')
    <!-- ==== Privacy Section ==== -->
    <!-- Privacy Policy Hero Section -->
    <section class="contact-hero-section">
        <div class="hero-bg-elements">
            <div class="floating-bean bean-1">
                <i class="bi bi-shield-lock" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
            <div class="floating-bean bean-2">
                <i class="bi bi-file-earmark-lock" style="font-size: 35px; opacity: 0.3;"></i>
            </div>
            <div class="floating-bean bean-3">
                <i class="bi bi-lock" style="font-size: 45px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="hero-badge">
                        <i class="bi bi-shield-check me-2"></i>Your Privacy Matters
                    </span>
                    <h1 class="hero-title">Privacy Policy</h1>
                    <p class="hero-text">We are committed to protecting your privacy and ensuring the security of your
                        personal information. This policy explains how we collect, use, and safeguard your data.</p>
                    <p class="last-updated mt-3"><i class="bi bi-calendar3 me-2"></i>Last Updated: January 2024</p>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="policy-hero-image">
                        <div class="hero-img-wrapper">
                            <img src="{{ asset('/public/assets/web/image/privacy-policy-banner.jpg') }}" alt="Secure Data"
                                class="img-fluid main-hero-img">
                            <div class="floating-qr-card">
                                <div class="qr-code-mini">
                                    <img src="{{ asset('/public/assets/web/image/demo-qr.svg') }}"
                                        alt="QR Code">
                                </div>
                                <span>Secure Scan</span>
                            </div>
                            <div class="floating-shield-badge">
                                <i class="bi bi-shield-check"></i>
                                <span>Protected</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Privacy Highlights Section -->
    <section class="contact-info-section py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5>Data Protection</h5>
                        <p>Your data is encrypted and stored securely using industry-standard protocols</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="bi bi-eye-slash"></i>
                        </div>
                        <h5>No Data Selling</h5>
                        <p>We never sell your personal information to third parties</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="bi bi-toggle-on"></i>
                        </div>
                        <h5>Your Control</h5>
                        <p>You have full control over your data and can request deletion anytime</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="bi bi-patch-check"></i>
                        </div>
                        <h5>GDPR Compliant</h5>
                        <p>We comply with international data protection regulations</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Privacy Policy Content Section -->
    <section class="policy-content-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="policy-wrapper">

                        <!-- Introduction -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <h3>1. Introduction</h3>
                            <p>Welcome to {{ \App\CentralLogics\Helpers::get_settings('business_name') }} ("we," "our," or "us"). We operate the {{ \App\CentralLogics\Helpers::get_settings('business_name') }} platform, which provides
                                digital QR menu solutions for restaurants. This Privacy Policy explains how we collect, use,
                                disclose, and safeguard your information when you use our website, mobile application, and
                                services.</p>
                            <p>By accessing or using {{ \App\CentralLogics\Helpers::get_settings('business_name') }}, you agree to this Privacy Policy. If you do not agree with
                                the terms of this policy, please do not access or use our services.</p>
                        </div>

                        <!-- Information We Collect -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-collection"></i>
                            </div>
                            <h3>2. Information We Collect</h3>

                            <h5 class="mt-4 text-warning">2.1 Information from Restaurant Partners</h5>
                            <p>When you register as a restaurant partner, we collect:</p>
                            <ul class="policy-list">
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Restaurant name, address, and
                                    contact information</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Business owner or manager name and
                                    email</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Phone number for account
                                    verification</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Payment and billing information
                                </li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Menu items, descriptions, prices,
                                    and images you upload</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Restaurant logo and branding
                                    materials</li>
                            </ul>

                            <h5 class="mt-4 text-warning">2.2 Information from Menu Viewers (Customers)</h5>
                            <p>When customers scan a QR code to view a menu, we may collect:</p>
                            <ul class="policy-list">
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Device type and browser information
                                </li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Approximate location (city/region
                                    level only)</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Menu viewing patterns and
                                    preferences</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Time and date of menu access</li>
                            </ul>
                            <p class="text-muted"><strong>Note:</strong> We do not require customers to create an account or
                                provide personal information to view menus.</p>

                            <h5 class="mt-4 text-warning">2.3 Automatically Collected Information</h5>
                            <p>When you use our services, we automatically collect:</p>
                            <ul class="policy-list">
                                <li><i class="bi bi-check-circle-fill text-warning"></i> IP address and device identifiers
                                </li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Browser type and operating system
                                </li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Pages visited and features used
                                </li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Referring website or application
                                </li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Usage statistics and analytics data
                                </li>
                            </ul>
                        </div>

                        <!-- How We Use Your Information -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-gear"></i>
                            </div>
                            <h3>3. How We Use Your Information</h3>
                            <p>We use the collected information for the following purposes:</p>

                            <div class="use-case-grid">
                                <div class="use-case-item">
                                    <i class="bi bi-display"></i>
                                    <div>
                                        <h6>Service Delivery</h6>
                                        <p>To create, display, and manage your digital menus and QR codes</p>
                                    </div>
                                </div>
                                <div class="use-case-item">
                                    <i class="bi bi-graph-up"></i>
                                    <div>
                                        <h6>Analytics</h6>
                                        <p>To provide you with insights about menu views and customer engagement</p>
                                    </div>
                                </div>
                                <div class="use-case-item">
                                    <i class="bi bi-headset"></i>
                                    <div>
                                        <h6>Customer Support</h6>
                                        <p>To respond to your inquiries and provide technical assistance</p>
                                    </div>
                                </div>
                                <div class="use-case-item">
                                    <i class="bi bi-megaphone"></i>
                                    <div>
                                        <h6>Communication</h6>
                                        <p>To send service updates, newsletters, and promotional offers (with consent)</p>
                                    </div>
                                </div>
                                <div class="use-case-item">
                                    <i class="bi bi-shield"></i>
                                    <div>
                                        <h6>Security</h6>
                                        <p>To detect and prevent fraud, abuse, and security threats</p>
                                    </div>
                                </div>
                                <div class="use-case-item">
                                    <i class="bi bi-tools"></i>
                                    <div>
                                        <h6>Improvement</h6>
                                        <p>To enhance our services and develop new features</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Sharing -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-share"></i>
                            </div>
                            <h3>4. Data Sharing and Disclosure</h3>
                            <p>We do not sell your personal information. We may share your information only in the following
                                circumstances:</p>

                            <ul class="policy-list">
                                <li><i class="bi bi-check-circle-fill text-warning"></i> <strong>Service
                                        Providers:</strong> With trusted third-party vendors who help us operate our
                                    platform (hosting, payment processing, analytics)</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> <strong>Legal
                                        Requirements:</strong> When required by law, court order, or government request</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> <strong>Business
                                        Transfers:</strong> In connection with a merger, acquisition, or sale of assets</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> <strong>With Your
                                        Consent:</strong> When you explicitly authorize us to share your information</li>
                            </ul>

                            <div class="alert-box mt-4">
                                <i class="bi bi-info-circle-fill"></i>
                                <p><strong>Restaurant Partners:</strong> Menu information you provide (items, prices,
                                    descriptions) is publicly displayed to customers who scan your QR codes. Please do not
                                    include sensitive information in your menu content.</p>
                            </div>
                        </div>

                        <!-- Data Security -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-lock"></i>
                            </div>
                            <h3>5. Data Security</h3>
                            <p>We implement robust security measures to protect your information:</p>

                            <div class="security-features">
                                <div class="security-item">
                                    <div class="security-icon">
                                        <i class="bi bi-shield-lock-fill"></i>
                                    </div>
                                    <div>
                                        <h6>256-bit SSL Encryption</h6>
                                        <p>All data transmitted between your device and our servers is encrypted</p>
                                    </div>
                                </div>
                                <div class="security-item">
                                    <div class="security-icon">
                                        <i class="bi bi-database-lock"></i>
                                    </div>
                                    <div>
                                        <h6>Secure Data Storage</h6>
                                        <p>Your data is stored in encrypted databases with restricted access</p>
                                    </div>
                                </div>
                                <div class="security-item">
                                    <div class="security-icon">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                    <div>
                                        <h6>Access Controls</h6>
                                        <p>Only authorized personnel can access sensitive information</p>
                                    </div>
                                </div>
                                <div class="security-item">
                                    <div class="security-icon">
                                        <i class="bi bi-clipboard-check"></i>
                                    </div>
                                    <div>
                                        <h6>Regular Audits</h6>
                                        <p>We conduct regular security assessments and vulnerability testing</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cookies -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-browser-chrome"></i>
                            </div>
                            <h3>6. Cookies and Tracking Technologies</h3>
                            <p>We use cookies and similar technologies to enhance your experience:</p>

                            <div class="cookie-table">
                                <div class="cookie-row header">
                                    <span>Cookie Type</span>
                                    <span>Purpose</span>
                                    <span>Duration</span>
                                </div>
                                <div class="cookie-row">
                                    <span><strong>Essential</strong></span>
                                    <span>Required for basic site functionality and security</span>
                                    <span>Session</span>
                                </div>
                                <div class="cookie-row">
                                    <span><strong>Functional</strong></span>
                                    <span>Remember your preferences and settings</span>
                                    <span>1 year</span>
                                </div>
                                <div class="cookie-row">
                                    <span><strong>Analytics</strong></span>
                                    <span>Help us understand how you use our services</span>
                                    <span>2 years</span>
                                </div>
                                <div class="cookie-row">
                                    <span><strong>Marketing</strong></span>
                                    <span>Deliver relevant advertisements (with consent)</span>
                                    <span>90 days</span>
                                </div>
                            </div>

                            <p class="mt-3">You can manage cookie preferences through your browser settings. Note that
                                disabling certain cookies may affect site functionality.</p>
                        </div>

                        <!-- Your Rights -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <h3>7. Your Rights and Choices</h3>
                            <p>You have the following rights regarding your personal information:</p>

                            <div class="rights-grid">
                                <div class="right-item">
                                    <i class="bi bi-eye"></i>
                                    <h6>Access</h6>
                                    <p>Request a copy of your personal data</p>
                                </div>
                                <div class="right-item">
                                    <i class="bi bi-pencil"></i>
                                    <h6>Correction</h6>
                                    <p>Update or correct inaccurate information</p>
                                </div>
                                <div class="right-item">
                                    <i class="bi bi-trash"></i>
                                    <h6>Deletion</h6>
                                    <p>Request deletion of your data</p>
                                </div>
                                <div class="right-item">
                                    <i class="bi bi-download"></i>
                                    <h6>Portability</h6>
                                    <p>Export your data in a readable format</p>
                                </div>
                                <div class="right-item">
                                    <i class="bi bi-x-circle"></i>
                                    <h6>Opt-Out</h6>
                                    <p>Unsubscribe from marketing communications</p>
                                </div>
                                <div class="right-item">
                                    <i class="bi bi-hand-thumbs-down"></i>
                                    <h6>Object</h6>
                                    <p>Object to certain data processing</p>
                                </div>
                            </div>

                            <p class="mt-4">To exercise these rights, please contact us at <a
                                    href="mailto:{{ \App\CentralLogics\Helpers::get_settings('email_address') }}" class="text-warning">{{ \App\CentralLogics\Helpers::get_settings('email_address') }}</a>. We
                                will respond to your request within 30 days.</p>
                        </div>

                        <!-- Data Retention -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <h3>8. Data Retention</h3>
                            <p>We retain your information for as long as necessary to provide our services and fulfill the
                                purposes outlined in this policy:</p>

                            <ul class="policy-list">
                                <li><i class="bi bi-check-circle-fill text-warning"></i> <strong>Account Data:</strong>
                                    Retained while your account is active, plus 2 years after closure</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> <strong>Menu Data:</strong>
                                    Deleted within 30 days of account termination</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> <strong>Analytics Data:</strong>
                                    Aggregated and anonymized after 24 months</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> <strong>Transaction
                                        Records:</strong> Retained for 7 years as required by law</li>
                            </ul>
                        </div>

                        <!-- Children's Privacy -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <h3>9. Children's Privacy</h3>
                            <p>{{ \App\CentralLogics\Helpers::get_settings('business_name') }} is not intended for use by children under the age of 16. We do not knowingly collect
                                personal information from children. If you believe we have collected information from a
                                child, please contact us immediately, and we will take steps to delete such information.</p>
                        </div>

                        <!-- International Transfers -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-globe"></i>
                            </div>
                            <h3>10. International Data Transfers</h3>
                            <p>Your information may be transferred to and processed in countries other than your country of
                                residence. We ensure appropriate safeguards are in place to protect your information in
                                accordance with applicable data protection laws.</p>
                        </div>

                        <!-- Changes to Policy -->
                        <div class="policy-section">
                            <div class="policy-icon">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>
                            <h3>11. Changes to This Policy</h3>
                            <p>We may update this Privacy Policy from time to time. We will notify you of any material
                                changes by:</p>
                            <ul class="policy-list">
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Posting the updated policy on our
                                    website</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Sending an email notification to
                                    registered users</li>
                                <li><i class="bi bi-check-circle-fill text-warning"></i> Displaying a prominent notice on
                                    our platform</li>
                            </ul>
                            <p>Your continued use of {{ \App\CentralLogics\Helpers::get_settings('business_name') }} after any changes indicates your acceptance of the updated
                                policy.</p>
                        </div>

                        <!-- Contact Us -->
                        <div class="policy-section contact-section">
                            <div class="policy-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <h3>12. Contact Us</h3>
                            <p>If you have any questions, concerns, or requests regarding this Privacy Policy or our data
                                practices, please contact us:</p>

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
                <h2>Have Questions About Your Privacy?</h2>
                <p class="mb-4">Our team is here to help you understand how we protect your data</p>
                <div class="cta-buttons">
                    <a href="contact.html" class="btn btn-warning btn-lg me-3">Contact Us</a>
                    <a href="index.html#pricing" class="btn btn-outline-light btn-lg">Get Started</a>
                </div>
            </div>
        </div>
    </section>
    <!-- ==== Privacy Section ==== -->
@endsection
