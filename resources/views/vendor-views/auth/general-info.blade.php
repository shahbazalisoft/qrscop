@extends('layouts.landing.app')
@section('title', translate('messages.vendor_registration'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ asset('public/assets/admin/css/toastr.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/landing/css/auth.css') }}">
@endpush
@section('content')
    <section class="auth-section">
        <div class="container">
            <div class="row align-items-center min-vh-100 py-5">
                <!-- Left Side - Info (SAME ON ALL PAGES) -->
                @include('vendor-views.auth.partials.left-panel')

                <!-- Right Side - Step 1 Form -->
                <div class="col-lg-6">
                    <div class="auth-card">
                        @if (\App\CentralLogics\Helpers::subscription_check())
                            @include('vendor-views.auth.partials.step-indicator', ['current_step' => 1])
                        @endif

                        <div class="auth-card-header">
                            <h2>Create Your Account</h2>
                            <p>Register your restaurant with {{ \App\CentralLogics\Helpers::get_settings('business_name') }}</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
                            </div>
                        @endif

                        <form class="auth-form" action="{{ route('restaurant.store') }}" method="POST" id="registration-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="input-wrapper">
                                            <i class="bi bi-person"></i>
                                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="input-wrapper">
                                            <i class="bi bi-shop"></i>
                                            <input type="text" name="restaurant_name" value="{{ old('restaurant_name') }}" placeholder="Enter restaurant name" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="input-wrapper">
                                            <i class="bi bi-envelope"></i>
                                            <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                                        </div>
                                        <small class="unique-msg" id="email-msg"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="input-wrapper">
                                            <i class="bi bi-phone"></i>
                                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Enter phone number" required>
                                        </div>
                                        <small class="unique-msg" id="phone-msg"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="input-wrapper">
                                            <i class="bi bi-lock"></i>
                                            <input type="password" name="password" placeholder="Create password" required>
                                            <button type="button" class="toggle-password"><i class="bi bi-eye"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="input-wrapper">
                                            <i class="bi bi-lock-fill"></i>
                                            <input type="password" name="password_confirmation" placeholder="Confirm password" required>
                                            <button type="button" class="toggle-password"><i class="bi bi-eye"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-wrapper">
                                    <i class="bi bi-geo-alt"></i>
                                    <input type="text" name="address" value="{{ old('address') }}" placeholder="Enter restaurant address" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="input-wrapper">
                                            <i class="bi bi-gift"></i>
                                            <input type="text" name="apply_referral_code" value="{{ old('apply_referral_code') }}" placeholder="Enter Referral Code">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="otp-box" >
                                <label class="otp-label">Please Enter OTP Here</label>
                                <div class="otp-input-wrapper">
                                    <input type="text" class="otp-field" name="otp1" maxlength="1" pattern="[0-9]" inputmode="numeric" required autofocus>
                                    <input type="text" class="otp-field" name="otp2" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                                    <input type="text" class="otp-field" name="otp3" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                                    <input type="text" class="otp-field" name="otp4" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                                </div>
                                <input type="hidden" name="otp" id="otp-hidden">
                                <div class="auth-footer-link">
                                    please check your email and enter otp
                                </div>
                            </div> --}}

                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="agree_terms">
                                    <span class="checkmark"></span>
                                    <span class="checkbox-label-text">I agree to the <a href="{{ route('terms-and-conditions') }}">Terms & Conditions</a> and <a href="{{ route('privacy-policy') }}">Privacy Policy</a></span>
                                </label>
                            </div>

                            <button class="auth-submit-btn" type="submit" id="submit-btn">
                                <span class="btn-text">{{ \App\CentralLogics\Helpers::subscription_check() ? translate('Continue') : translate('Create Account') }}</span>
                                <span class="btn-loader" style="display: none;"><i class="bi bi-arrow-repeat spin"></i> Processing...</span>
                                <i class="bi bi-arrow-right btn-arrow"></i>
                            </button>

                            <div class="auth-footer-link">
                                Already have an account? <a href="{{ route('login', ['tab' => 'vendor']) }}">Login here</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="auth-bg-elements">
            <div class="bg-shape shape-1"></div>
            <div class="bg-shape shape-2"></div>
            <div class="bg-shape shape-3"></div>
        </div>
    </section>
@endsection

@push('script_2')
@include('vendor-views.auth.partials.scripts')
@endpush
