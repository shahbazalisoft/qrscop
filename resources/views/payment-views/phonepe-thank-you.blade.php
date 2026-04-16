@extends('layouts.landing.app')
@section('title', $status == 'success' ? translate('Thank You') : translate('Payment Failed'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ asset('public/assets/landing/css/auth.css') }}">
@endpush
@section('content')
    <section class="auth-section">
        <div class="container">
            <div class="row align-items-center justify-content-center min-vh-100 py-5">
                <div class="col-lg-6">
                    <div class="auth-card completion-card">
                        <div class="completion-status">
                            @if($status == 'success')
                                <div class="status-icon success">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <h2 class="status-title success">{{ translate('Thank You!') }}</h2>
                                <p class="status-message">
                                    {{ translate('Your registration and payment were successful! Your restaurant account will be reviewed and activated by our admin team shortly.') }}
                                </p>
                            @else
                                <div class="status-icon failed">
                                    <i class="bi bi-x-circle-fill"></i>
                                </div>
                                <h2 class="status-title failed">{{ translate('Payment Failed') }}</h2>
                                <p class="status-message">
                                    {{ translate('Sorry, your payment could not be completed. Please try again or contact support for assistance.') }}
                                </p>
                            @endif
                        </div>

                        <div class="completion-info">
                            @if($status == 'success')
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="bi bi-envelope-check-fill"></i>
                                    </div>
                                    <div class="info-content">
                                        <h5>{{ translate('Check Your Email') }}</h5>
                                        <p>{{ translate('We have sent a confirmation email with your account details and login credentials.') }}</p>
                                    </div>
                                </div>
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="bi bi-clock-fill"></i>
                                    </div>
                                    <div class="info-content">
                                        <h5>{{ translate('Account Activation') }}</h5>
                                        <p>{{ translate('Your account will be reviewed and activated within 24-48 hours.') }}</p>
                                    </div>
                                </div>
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <div class="info-content">
                                        <h5>{{ translate('Payment Confirmed') }}</h5>
                                        <p>{{ translate('Your payment has been successfully processed via PhonePe.') }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="info-card warning">
                                    <div class="info-icon">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                    </div>
                                    <div class="info-content">
                                        <h5>{{ translate('What to do next?') }}</h5>
                                        <p>{{ translate('Please try registering again with a different payment method, or contact our support team if the issue persists.') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="completion-actions">
                            @if($status == 'success')
                                <a href="{{ route('login', ['tab' => 'vendor']) }}" class="auth-submit-btn">
                                    <span>{{ translate('Go to Dashbaord') }}</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            @else
                                <a href="{{ route('restaurant.create') }}" class="auth-submit-btn">
                                    <i class="bi bi-arrow-repeat"></i>
                                    <span>{{ translate('Try Again') }}</span>
                                </a>
                            @endif
                        </div>

                        <div class="completion-support">
                            <p>{{ translate('Need help?') }} <a href="{{ route('contact-us') }}">{{ translate('Contact Support') }}</a></p>
                        </div>
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if($status == 'success')
        const statusIcon = document.querySelector('.status-icon.success');
        if (statusIcon) {
            statusIcon.classList.add('animate-bounce');
        }
        @endif
    });
</script>
@endpush
