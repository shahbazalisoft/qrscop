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

                <!-- Right Side - Completion Card -->
                <div class="col-lg-6">
                    <div class="auth-card completion-card">
                        @include('vendor-views.auth.partials.step-indicator', [
                            'current_step' => 3,
                            'payment_failed' => isset($payment_status) && $payment_status == 'fail'
                        ])

                        <!-- Status Icon & Message -->
                        <div class="completion-status">
                            @if(isset($payment_status) && $payment_status == 'fail')
                                <div class="status-icon failed">
                                    <i class="bi bi-x-circle-fill"></i>
                                </div>
                                <h2 class="status-title failed">{{ translate('Payment Failed') }}</h2>
                                <p class="status-message">
                                    {{ translate('Sorry, your transaction could not be completed. Please try again with a different payment method.') }}
                                </p>
                            @else
                                <div class="status-icon success">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <h2 class="status-title success">{{ translate('Congratulations!') }}</h2>

                                @if(isset($type) && $type == 'commission')
                                    <p class="status-message">
                                        {{ translate('You have successfully registered with our commission-based plan. Our admin team will review your application and activate your account shortly.') }}
                                    </p>
                                @elseif(isset($package->id) && ($package->id == 1))
                                    <p class="status-message">
                                       Thank you for registering! Your account is now active and you’ve received a <strong> {{ $transaction->validity }}-{{ translate('days') }} free trial</strong>. Enjoy exploring our services, and feel free to contact support if you need any help.
                                    </p>
                                @elseif(isset($package->id) && ($package->id != 1))
                                    <p class="status-message">
                                        Thank you for your subscription. Your payment was successful and your account is now activated.If you need any assistance, feel free to contact our support team.
                                    </p>
                                @else
                                    <p class="status-message">
                                        Thank you for registering! Your account is now activated.If you need any assistance, feel free to contact our support team.
                                    </p>
                                @endif
                            @endif
                        </div>

                        <!-- Info Cards -->
                        <div class="completion-info">
                            @if(isset($payment_status) && $payment_status == 'fail')
                                <div class="info-card warning">
                                    <div class="info-icon">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                    </div>
                                    <div class="info-content">
                                        <h5>{{ translate('What to do next?') }}</h5>
                                        <p>{{ translate('Go back and try a different payment method or contact support if the issue persists.') }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="bi bi-envelope-check-fill"></i>
                                    </div>
                                    <div class="info-content">
                                        <h5>{{ translate('Check Your Email') }}</h5>
                                        <p>We’ve sent your account details to your email. Please check your inbox</p>
                                    </div>
                                </div>
                                {{-- <div class="info-card">
                                    <div class="info-icon">
                                        <i class="bi bi-clock-fill"></i>
                                    </div>
                                    <div class="info-content">
                                        <h5>{{ translate('Account Activation') }}</h5>
                                        <p>{{ translate('Your account will be reviewed and activated within 24-48 hours.') }}</p>
                                    </div>
                                </div> --}}
                            @endif
                        </div>

                        {{-- Transaction & Subscription Details --}}
                        @if(!(isset($payment_status) && $payment_status == 'fail') && isset($transaction) && $transaction)
                        <div class="subscription-details">
                            <h4 class="details-title">
                                <i class="bi bi-receipt"></i>
                                {{ translate('Subscription Details') }}
                            </h4>

                            <div class="details-table">
                                <div class="detail-row">
                                    <span class="detail-label">{{ translate('Transaction ID') }}</span>
                                    <span class="detail-value">#{{ $transaction->id }}</span>
                                </div>

                                @if(isset($package) && $package)
                                <div class="detail-row">
                                    <span class="detail-label">{{ translate('Package') }}</span>
                                    <span class="detail-value">{{ $package->package_name }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">{{ translate('Validity') }}</span>
                                    <span class="detail-value">{{ $transaction->validity }} {{ translate('days') }}</span>
                                </div>
                                @endif

                                <div class="detail-row">
                                    <span class="detail-label">{{ translate('Amount Paid') }}</span>
                                    <span class="detail-value amount-highlight">
                                        {{ $transaction->is_trial ? translate('Free Trial') : \App\CentralLogics\Helpers::format_currency($transaction->paid_amount) }}
                                    </span>
                                </div>

                                <div class="detail-row">
                                    <span class="detail-label">{{ translate('Payment Method') }}</span>
                                    <span class="detail-value">
                                        @if($transaction->payment_method == 'free_trial')
                                            <span class="badge-trial">{{ translate('Free Trial') }}</span>
                                        @else
                                            {{ ucwords(str_replace('_', ' ', $transaction->payment_method)) }}
                                        @endif
                                    </span>
                                </div>

                                <div class="detail-row">
                                    <span class="detail-label">{{ translate('Status') }}</span>
                                    <span class="detail-value">
                                        <span class="badge-status {{ $transaction->payment_status == 'success' ? 'success' : 'pending' }}">
                                            {{ ucfirst($transaction->payment_status) }}
                                        </span>
                                    </span>
                                </div>

                                @if(isset($subscription) && $subscription)
                                <div class="detail-row">
                                    <span class="detail-label">{{ translate('Expires On') }}</span>
                                    <span class="detail-value">{{ \Carbon\Carbon::parse($subscription->expiry_date)->format('d M, Y') }}</span>
                                </div>
                                @endif
                            </div>

                            {{-- Plan Features --}}
                            {{-- @if(isset($subscription) && $subscription)
                            <div class="plan-features">
                                <h5 class="features-label">{{ translate('Plan Features') }}</h5>
                                <div class="features-grid">
                                    <div class="feature-item {{ $subscription->pos ? 'active' : 'inactive' }}">
                                        <i class="bi {{ $subscription->pos ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                        <span>{{ translate('POS') }}</span>
                                    </div>
                                    <div class="feature-item {{ $subscription->mobile_app ? 'active' : 'inactive' }}">
                                        <i class="bi {{ $subscription->mobile_app ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                        <span>{{ translate('Mobile App') }}</span>
                                    </div>
                                    <div class="feature-item {{ $subscription->chat ? 'active' : 'inactive' }}">
                                        <i class="bi {{ $subscription->chat ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                        <span>{{ translate('Chat') }}</span>
                                    </div>
                                    <div class="feature-item {{ $subscription->review ? 'active' : 'inactive' }}">
                                        <i class="bi {{ $subscription->review ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                        <span>{{ translate('Reviews') }}</span>
                                    </div>
                                    <div class="feature-item {{ $subscription->self_delivery ? 'active' : 'inactive' }}">
                                        <i class="bi {{ $subscription->self_delivery ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                        <span>{{ translate('Self Delivery') }}</span>
                                    </div>
                                </div>

                                <div class="limits-row">
                                    <div class="limit-item">
                                        <i class="bi bi-bag-fill"></i>
                                        <span>{{ $subscription->max_order == 'unlimited' ? translate('Unlimited') : $subscription->max_order }} {{ translate('Orders') }}</span>
                                    </div>
                                    <div class="limit-item">
                                        <i class="bi bi-box-seam-fill"></i>
                                        <span>{{ $subscription->max_product == 'unlimited' ? translate('Unlimited') : $subscription->max_product }} {{ translate('Products') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif --}}
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="completion-actions">
                            @if(isset($payment_status) && $payment_status == 'fail')
                                <a href="{{ route('restaurant.back') }}" class="auth-submit-btn">
                                    <i class="bi bi-arrow-repeat"></i>
                                    <span>{{ translate('Try Again') }}</span>
                                </a>
                            @else
                                <a href="{{ route('login', ['tab' => 'vendor']) }}" class="auth-submit-btn">
                                    <span>{{ translate('Go_to_dashboard') }}</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            @endif
                        </div>

                        <!-- Support Link -->
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
@include('vendor-views.auth.partials.scripts')
<script>
    @if(!(isset($payment_status) && $payment_status == 'fail'))
    document.addEventListener("DOMContentLoaded", function() {
        const statusIcon = document.querySelector('.status-icon.success');
        if (statusIcon) {
            statusIcon.classList.add('animate-bounce');
        }
    });
    @endif
</script>
<style>
/* Completion Card Styles */
.completion-card {
    text-align: center;
}

.completion-status {
    padding: 20px 0 30px;
}

.status-icon {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.status-icon.success {
    background: rgba(34, 197, 94, 0.1);
}

.status-icon.success i {
    font-size: 50px;
    color: #22c55e;
}

.status-icon.failed {
    background: rgba(239, 68, 68, 0.1);
}

.status-icon.failed i {
    font-size: 50px;
    color: #ef4444;
}

.status-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 12px;
}

.status-title.success {
    color: #22c55e;
}

.status-title.failed {
    color: #ef4444;
}

.status-message {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.7);
    line-height: 1.6;
    max-width: 400px;
    margin: 0 auto;
}

/* Info Cards */
.completion-info {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 24px;
}

.info-card {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    text-align: left;
}

.info-card.warning {
    background: rgba(245, 158, 11, 0.1);
    border-color: rgba(245, 158, 11, 0.3);
}

.info-card .info-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: rgba(34, 197, 94, 0.1);
}

.info-card .info-icon i {
    font-size: 18px;
    color: #22c55e;
}

.info-card.warning .info-icon {
    background: rgba(245, 158, 11, 0.2);
}

.info-card.warning .info-icon i {
    color: #f59e0b;
}

.info-card .info-content h5 {
    font-size: 14px;
    font-weight: 600;
    color: var(--white-clr);
    margin-bottom: 4px;
}

.info-card .info-content p {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.6);
    margin: 0;
    line-height: 1.5;
}

/* Action Buttons */
.completion-actions {
    margin-bottom: 20px;
}

.completion-actions .auth-submit-btn {
    display: inline-flex;
    width: auto;
    padding: 14px 30px;
}

/* Support Link */
.completion-support {
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.completion-support p {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.5);
    margin: 0;
}

.completion-support a {
    color: var(--primary-clr);
    text-decoration: none;
    font-weight: 500;
}

.completion-support a:hover {
    text-decoration: underline;
}

/* Animation */
.animate-bounce {
    animation: bounce 1s ease-in-out;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-20px);
    }
    60% {
        transform: translateY(-10px);
    }
}

/* Subscription Details Section */
.subscription-details {
    text-align: left;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
}

.details-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--white-clr);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.details-title i {
    color: var(--primary-clr);
    font-size: 18px;
}

.details-table {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.5);
}

.detail-value {
    font-size: 13px;
    font-weight: 600;
    color: var(--white-clr);
}

.detail-value.amount-highlight {
    color: var(--primary-clr);
    font-size: 15px;
}

.badge-trial {
    background: rgba(34, 197, 94, 0.15);
    color: #22c55e;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
}

.badge-status {
    font-size: 11px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
}

.badge-status.success {
    background: rgba(34, 197, 94, 0.15);
    color: #22c55e;
}

.badge-status.pending {
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
}

/* Plan Features */
.plan-features {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}

.features-label {
    font-size: 13px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 12px;
}

.features-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 12px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    padding: 5px 10px;
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.03);
}

.feature-item.active {
    color: #22c55e;
}

.feature-item.active i {
    color: #22c55e;
}

.feature-item.inactive {
    color: rgba(255, 255, 255, 0.3);
}

.feature-item.inactive i {
    color: rgba(255, 255, 255, 0.2);
}

.limits-row {
    display: flex;
    gap: 16px;
}

.limit-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: rgba(255, 255, 255, 0.7);
}

.limit-item i {
    color: var(--primary-clr);
    font-size: 14px;
}

/* Step indicator adjustments for completion */
.step.failed .step-number {
    background: #ef4444 !important;
    border-color: #ef4444 !important;
}
</style>
@endpush
