@extends('layouts.landing.app')

@section('title', translate('messages.contact_us'))

@section('content')
    <!-- ==== Contact Section ==== -->
    @php($contact_us_title = \App\Models\DataSetting::where(['key' => 'contact_us_title'])->first())
    @php($contact_us_title = isset($contact_us_title->value) ? $contact_us_title->value : null)
    @php($contact_us_sub_title = \App\Models\DataSetting::where(['key' => 'contact_us_sub_title'])->first())
    @php($contact_us_sub_title = isset($contact_us_sub_title->value) ? $contact_us_sub_title->value : null)
    @php($contact_us_image = \App\Models\DataSetting::where(['key' => 'contact_us_image'])->first())
    @php($business_phone = \App\Models\BusinessSetting::where('key', 'phone')->first()?->value)
    @php($business_email = \App\Models\BusinessSetting::where('key', 'email_address')->first()?->value)
    @php($business_address = \App\Models\BusinessSetting::where('key', 'address')->first()?->value)
    @php($business_name = \App\Models\BusinessSetting::where('key', 'business_name')->first()?->value)
    <!-- Contact Hero Section -->
    <section class="contact-hero-section">
        <div class="hero-bg-elements">
            <div class="floating-bean bean-1">
                <i class="bi bi-envelope" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
            <div class="floating-bean bean-2">
                <i class="bi bi-chat-dots" style="font-size: 35px; opacity: 0.3;"></i>
            </div>
            <div class="floating-bean bean-3">
                <i class="bi bi-headset" style="font-size: 45px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <span class="hero-badge">
                        <i class="bi bi-headset me-2"></i>{{translate('Get In Touch')}}
                    </span>
                    <h1 class="hero-title">{{ $contact_us_title ?? translate("We'd Love to Hear From You") }}</h1>
                    <p class="hero-text">{{ $contact_us_sub_title ?? translate('Have questions about our QR menu solutions? Need help getting started? Our team is here to help you transform your restaurant\'s dining experience.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form & Map Section -->
    <section class="contact-form-section py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Contact Form -->
                <div class="col-lg-6">
                    <div class="contact-form-wrapper">
                        <div class="section-header mb-4">
                            <span class="hero-badge mb-3">
                                <i class="bi bi-send me-2"></i>Send Message
                            </span>
                            <h2 class="section-title">Let's Start a <span class="text-highlight">Conversation</span></h2>
                            <p class="section-text">Fill out the form below and we'll get back to you within 24 hours.</p>
                        </div>
                        <form class="contact-form" id="contactForm" method="post" action="{{ route('store_contactus') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{-- <label for="firstName" class="form-label">First Name</label> --}}
                                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="John">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{-- <label for="lastName" class="form-label">Last Name</label> --}}
                                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Doe">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="john@restaurant.com">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="tel" class="form-control" id="phone" name="phone"
                                            placeholder="+91 98765 43210">
                                    </div>
                                </div>
                                {{-- <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="restaurant" name="restaurant"
                                            placeholder="Your Restaurant Name">
                                    </div>
                                </div> --}}
                                {{-- <div class="col-12">
                                    <div class="form-group">
                                        <select class="form-select" id="subject" name="subject">
                                            <option value="" selected disabled>Select a subject</option>
                                            <option value="general">General Inquiry</option>
                                            <option value="demo">Request a Demo</option>
                                            <option value="pricing">Pricing Questions</option>
                                            <option value="support">Technical Support</option>
                                            <option value="partnership">Partnership Opportunities</option>
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-12">
                                    <div class="form-group">
                                        <textarea class="form-control" id="message" name="message" rows="5"
                                            placeholder="Tell us about your restaurant and how we can help..." ></textarea>
                                    </div>
                                </div>
                                {{-- Captcha --}}
                                <div class="col-12">
                                    @php($recaptcha_status = isset($recaptcha) && $recaptcha['status'] == 1)
                                    @if($recaptcha_status)
                                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                                        <input type="hidden" name="set_default_captcha" id="set_default_captcha_value" value="0">
                                        <div class="row g-2 d-none mb-3" id="reload-captcha">
                                            <div class="col-6">
                                                <input type="text" class="form-control" name="custome_recaptcha" id="custome_recaptcha"
                                                    placeholder="{{ translate('Enter captcha value') }}" autocomplete="off">
                                            </div>
                                            <div class="col-5 rounded d-flex align-items-center">
                                                <img src="<?php echo $custome_recaptcha->inline(); ?>" class="rounded w-100" style="max-height:45px;" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center justify-content-center">
                                                <div class="reloadCaptcha" style="cursor:pointer; color:#10847E;" title="Reload captcha">
                                                    <i class="bi bi-arrow-clockwise fs-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row g-2 mb-3" id="reload-captcha">
                                            <div class="col-6">
                                                <input type="text" class="form-control" name="custome_recaptcha" id="custome_recaptcha"
                                                    placeholder="{{ translate('Enter captcha value') }}" autocomplete="off">
                                            </div>
                                            <div class="col-5 rounded d-flex align-items-center">
                                                <img src="<?php echo $custome_recaptcha->inline(); ?>" class="rounded w-100" style="max-height:45px;" />
                                            </div>
                                            <div class="col-1 d-flex align-items-center justify-content-center">
                                                <div class="reloadCaptcha" style="cursor:pointer; color:#10847E;" title="Reload captcha">
                                                    <i class="bi bi-arrow-clockwise fs-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Terms & Conditions --}}
                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="terms" id="termsCheck" value="1">
                                        <label class="form-check-label" for="termsCheck">
                                            {{ translate('I agree to the') }}
                                            <a href="{{ route('terms-and-conditions') }}" target="_blank" class="text-warning fw-bold">
                                                {{ translate('Terms & Conditions') }}
                                            </a>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" id="signInBtn" class="btn btn-warning btn-lg w-100">
                                        <i class="bi bi-send me-2"></i>Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Map & Additional Info -->
                <div class="col-lg-6">
                    <div class="contact-map-wrapper">
                        <div class="section-header mb-4">
                            <span class="hero-badge mb-3">
                                <i class="bi bi-pin-map me-2"></i>Find Us
                            </span>
                            <h2 class="section-title">Our <span class="text-highlight">Location</span></h2>
                            <p class="section-text">Visit our office or reach out online - we're always happy to help.</p>
                        </div>
                        <div class="map-container">
                            @if($business_address)
                            <iframe
                                src="https://maps.google.com/maps?q={{ urlencode($business_address) }}&output=embed"
                                width="100%" height="300" style="border:0; border-radius: 15px;" allowfullscreen=""
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                            @endif
                        </div>
                        
                        <!-- Quick Contact Options -->
                        <div class="quick-contact-options mt-4">
                            <h5 class="mb-3">Quick Connect</h5>
                            <div class="row g-3">
                                @if($business_phone)
                                <div class="col-6">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business_phone) }}" class="quick-contact-btn whatsapp" target="_blank">
                                        <i class="bi bi-whatsapp"></i>
                                        <span>WhatsApp</span>
                                    </a>
                                </div>
                                @endif
                                @if($business_phone)
                                <div class="col-6">
                                    <a href="tel:{{ $business_phone }}" class="quick-contact-btn phone">
                                        <i class="bi bi-telephone"></i>
                                        <span>Call Now</span>
                                    </a>
                                </div>
                                @endif
                                @if($business_email)
                                <div class="col-6">
                                    <a href="mailto:{{ $business_email }}" class="quick-contact-btn email">
                                        <i class="bi bi-envelope"></i>
                                        <span>Email Us</span>
                                    </a>
                                </div>
                                @endif
                                <div class="col-6">
                                    <a href="#contactForm" class="quick-contact-btn chat">
                                        <i class="bi bi-chat-dots"></i>
                                        <span>Live Chat</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Info Cards -->
    <section class="contact-info-section py-5">
        <div class="container">
            <div class="row g-4">
                @if($business_address)
                <div class="col-lg-3 col-md-6">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <h5>{{translate('Visit Us')}}</h5>
                        <p>{{ $business_address }}</p>
                    </div>
                </div>
                @endif
                @if($business_email)
                <div class="col-lg-3 col-md-6">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <h5>{{translate('Email Us')}}</h5>
                        <p>{{ $business_email }}</p>
                    </div>
                </div>
                @endif
                @if($business_phone)
                <div class="col-lg-3 col-md-6">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <h5>{{translate('Call Us')}}</h5>
                        <p>{{ $business_phone }}</p>
                    </div>
                </div>
                @endif
                <div class="col-lg-3 col-md-6">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <h5>{{translate('Working Hours')}}</h5>
                        <p>24/7 Support</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="contact-faq-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="hero-badge mb-3">
                    <i class="bi bi-question-circle me-2"></i>FAQ
                </span>
                <h2 class="section-title">Frequently Asked <span class="text-highlight">Questions</span></h2>
                <p class="section-text mx-auto" style="max-width: 600px;">{{translate('Find quick answers to common questions about')}}
                    {{ $business_name ?? 'Qrscop' }}</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion faq-accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq1">
                                    <i class="bi bi-qr-code me-3 text-warning"></i>
                                    How quickly can I set up my digital menu?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    You can have your digital QR menu up and running in less than 30 minutes! Simply sign
                                    up, choose a template, add your menu items, and generate your QR code. It's that easy.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq2">
                                    <i class="bi bi-credit-card me-3 text-warning"></i>
                                    What payment methods do you accept?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We accept all major credit/debit cards, UPI payments, net banking, and popular wallets
                                    like Paytm and PhonePe. All transactions are secured with 256-bit encryption.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq3">
                                    <i class="bi bi-phone me-3 text-warning"></i>
                                    Do customers need to download an app?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    No app required! Customers simply scan the QR code with their phone camera, and the menu
                                    opens directly in their web browser. It works on all smartphones - iPhone, Android, or
                                    any device with a camera.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq4">
                                    <i class="bi bi-pencil-square me-3 text-warning"></i>
                                    Can I update my menu anytime?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Absolutely! You can update your menu items, prices, descriptions, and images in
                                    real-time from your dashboard. Changes appear instantly - no need to reprint QR codes or
                                    menus.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq5">
                                    <i class="bi bi-headset me-3 text-warning"></i>
                                    What kind of support do you offer?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We offer email support for all plans, with priority support and dedicated account
                                    managers for Business and Enterprise plans. Our team is available Monday to Saturday, 9
                                    AM to 6 PM IST.
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
                <h2>{{translate('Ready to Transform Your Restaurant?')}}</h2>
                <p class="mb-4">{{translate('Join restaurants already using')}} {{ $business_name ?? 'Qrscop' }} {{translate('to enhance their customer experience')}}</p>
                <div class="cta-buttons">
                    <a href="{{route('restaurant.create')}}" class="btn btn-warning btn-lg me-3">Start Free Trial</a>
                    <a href="#contactForm" class="btn btn-outline-light btn-lg">Schedule a Demo</a>
                </div>
            </div>
        </div>
    </section>
    <!-- ==== Contact Section ==== -->
@endsection

@push('script_2')
<script>
    // Contact Form Validation
    (function() {
        const form = document.getElementById('contactForm');
        if (!form) return;

        // Validation rules
        const validators = {
            first_name: {
                validate: (value) => value.trim().length >= 2,
                message: 'Please enter your first name'
            },
            email: {
                validate: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
                message: 'Please enter a valid email address'
            },
            phone: {
                validate: (value) => !value || /^[\d\s\+\-\(\)]{10,20}$/.test(value),
                message: 'Please enter a valid phone number'
            },
            
            message: {
                validate: (value) => value.trim().length >= 10,
                message: 'Please enter your message (at least 10 characters)'
            },
            custome_recaptcha: {
                validate: (value) => value.trim().length >= 2,
                message: 'Please enter captcha'
            },
            terms: {
                validate: () => document.getElementById('termsCheck') && document.getElementById('termsCheck').checked,
                message: 'You must agree to the terms and conditions'
            }
        };

        // Show error message
        function showError(field, message) {
            clearError(field);
            field.classList.add('is-invalid');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = message;
            field.parentNode.appendChild(errorDiv);
        }

        // Clear error message
        function clearError(field) {
            field.classList.remove('is-invalid');
            field.classList.remove('is-valid');
            const existingError = field.parentNode.querySelector('.invalid-feedback');
            if (existingError) existingError.remove();
        }

        // Show success state
        function showSuccess(field) {
            clearError(field);
            field.classList.add('is-valid');
        }

        // Validate single field
        function validateField(fieldName, field) {
            const validator = validators[fieldName];
            if (!validator) return true;

            const value = field.value;
            if (validator.validate(value)) {
                showSuccess(field);
                return true;
            } else {
                showError(field, validator.message);
                return false;
            }
        }

        // Real-time validation on blur
        Object.keys(validators).forEach(fieldName => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.addEventListener('blur', () => validateField(fieldName, field));
                field.addEventListener('input', () => {
                    if (field.classList.contains('is-invalid')) {
                        validateField(fieldName, field);
                    }
                });
            }
        });

        // Form submit validation
        function submitForm() {
            const btn = form.querySelector('button[type="submit"]');
            const btnOriginal = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Sending...';
            btn.disabled = true;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(form)
            })
            .then(res => {
                if (!res.ok) return res.json().then(data => { throw data; });
                return res.json();
            })
            .then(data => {
                toastr.success(data.message);
                form.reset();
                form.querySelectorAll('.is-valid').forEach(f => f.classList.remove('is-valid'));
            })
            .catch(data => {
                if (data && data.errors) {
                    Object.values(data.errors).forEach(err => toastr.error(err[0]));
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }
                // Reload captcha after failed attempt
                reloadCaptchaImage();
            })
            .finally(() => {
                btn.innerHTML = btnOriginal;
                btn.disabled = false;
            });
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            let isValid = true;
            let firstInvalidField = null;

            // Validate all fields
            Object.keys(validators).forEach(fieldName => {
                const field = form.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    if (!validateField(fieldName, field)) {
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = field;
                    }
                }
            });

            // Terms checkbox validation
            if (!validators.terms.validate()) {
                isValid = false;
                const termsCheck = document.getElementById('termsCheck');
                if (termsCheck) {
                    termsCheck.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = termsCheck;
                }
            }

            if (!isValid) {
                if (firstInvalidField) {
                    firstInvalidField.focus();
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                toastr.error('Please fix the errors in the form');
                return false;
            }

            @if(isset($recaptcha) && $recaptcha['status'] == 1)
                // Google reCAPTCHA v3
                if (document.getElementById('set_default_captcha_value').value == '1') {
                    submitForm();
                    return;
                }
                if (typeof grecaptcha === 'undefined') {
                    toastr.error('Invalid reCAPTCHA key. Please use the default captcha.');
                    document.getElementById('reload-captcha').classList.remove('d-none');
                    document.getElementById('set_default_captcha_value').value = '1';
                    var captchaInput = document.getElementById('custome_recaptcha');
                    if (captchaInput) captchaInput.required = true;
                    return;
                }
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ $recaptcha["site_key"] ?? "" }}', { action: 'contact' }).then(function(token) {
                        document.getElementById('g-recaptcha-response').value = token;
                        submitForm();
                    });
                });
                window.onerror = function(message) {
                    document.getElementById('reload-captcha').classList.remove('d-none');
                    document.getElementById('set_default_captcha_value').value = '1';
                    var captchaInput = document.getElementById('custome_recaptcha');
                    if (captchaInput) captchaInput.required = true;
                    toastr.error('reCAPTCHA error. Please use the default captcha.');
                    return true;
                };
            @else
                submitForm();
            @endif
        });

        // Reload captcha
        function reloadCaptchaImage() {
            $.ajax({
                url: "{{ route('reload-captcha') }}",
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    // Parse the response and rebuild with our own styling
                    var tmp = $('<div>').html(data.view);
                    var imgSrc = tmp.find('img').attr('src');
                    var html = '<div class="col-6">' +
                        '<input type="text" class="form-control" name="custome_recaptcha" id="custome_recaptcha" required placeholder="{{ translate('Enter captcha value') }}" autocomplete="off">' +
                        '</div>' +
                        '<div class="col-5 rounded d-flex align-items-center">' +
                        '<img src="' + imgSrc + '" class="rounded w-100" style="max-height:45px;" />' +
                        '</div>' +
                        '<div class="col-1 d-flex align-items-center justify-content-center">' +
                        '<div class="reloadCaptcha" style="cursor:pointer; color:#10847E;" title="Reload captcha">' +
                        '<i class="bi bi-arrow-clockwise fs-4"></i>' +
                        '</div></div>';
                    $('#reload-captcha').html(html);
                }
            });
        }

        $(document).on('click', '.reloadCaptcha', function() {
            reloadCaptchaImage();
        });

        // Clear terms invalid state on change
        var termsCheck = document.getElementById('termsCheck');
        if (termsCheck) {
            termsCheck.addEventListener('change', function() {
                this.classList.remove('is-invalid');
            });
        }
    })();
</script>
@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptcha['site_key'] ?? '' }}"></script>
@endif
@endpush
