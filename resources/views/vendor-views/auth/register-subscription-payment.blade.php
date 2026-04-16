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

                <!-- Right Side - Step 3 Payment Form -->
                <div class="col-lg-6">
                    <div class="auth-card">
                        @include('vendor-views.auth.partials.step-indicator', ['current_step' => 3])

                        <div class="auth-card-header">
                            <h2>Payment Method</h2>
                            <p>Select how you'd like to pay</p>
                        </div>

                        <form action="{{ route('restaurant.payment') }}" method="POST" id="payment-form" class="auth-form">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $package_id }}">

                            @php
                                if(data_get($free_trial_settings, 'subscription_free_trial_type') == 'year'){
                                    $trial_period = data_get($free_trial_settings, 'subscription_free_trial_days') > 0 ? data_get($free_trial_settings, 'subscription_free_trial_days') / 365 : 0;
                                    $trial_type = 'year';
                                } else if(data_get($free_trial_settings, 'subscription_free_trial_type') == 'month'){
                                    $trial_period = data_get($free_trial_settings, 'subscription_free_trial_days') > 0 ? data_get($free_trial_settings, 'subscription_free_trial_days') / 30 : 0;
                                    $trial_type = 'month';
                                } else{
                                    $trial_period = data_get($free_trial_settings, 'subscription_free_trial_days') > 0 ? data_get($free_trial_settings, 'subscription_free_trial_days') : 0;
                                    $trial_type = 'days';
                                }
                            @endphp

                            <div class="payment-options">
                                <!-- Free Trial Option -->
                                @if (data_get($free_trial_settings,'subscription_free_trial_status') == 1 && data_get($free_trial_settings,'subscription_free_trial_days') > 0)
                                <label class="payment-option featured full-width">
                                    <input type="radio" name="payment" value="free_trial" checked>
                                    <div class="payment-option-inner">
                                        <div class="payment-icon trial">
                                            <i class="bi bi-gift"></i>
                                        </div>
                                        <div class="payment-details">
                                            <h4>Start Free Trial</h4>
                                            <p>{{ $trial_period }} {{ $trial_type }} free - No card required</p>
                                        </div>
                                        <div class="payment-radio"></div>
                                    </div>
                                    <div class="featured-badge">Recommended</div>
                                </label>
                                @endif

                                <!-- Online Payment Methods -->
                                @if(count($payment_methods) > 0)
                                <div class="payment-divider">
                                    <span>Or pay now</span>
                                </div>

                                <div class="payment-grid">
                                    @foreach ($payment_methods as $item)
                                    <label class="payment-option">
                                        <input type="radio" name="payment" value="{{ $item['gateway'] }}"
                                            {{ !data_get($free_trial_settings,'subscription_free_trial_status') && $loop->first ? 'checked' : '' }}>
                                        <div class="payment-option-inner">
                                            <div class="payment-icon gateway">
                                                <img src="{{ \App\CentralLogics\Helpers::get_full_url('payment_modules/gateway_image', $item['gateway_image'], $item['storage'] ?? 'public') }}"
                                                    alt="{{ $item['gateway_title'] }}">
                                            </div>
                                            <div class="payment-details">
                                                <h4>{{ $item['gateway_title'] }}</h4>
                                                <p>Secure payment</p>
                                            </div>
                                            <div class="payment-radio"></div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="form-actions">
                                <a href="{{ route('restaurant.back') }}" class="btn-back">
                                    <i class="bi bi-arrow-left"></i>
                                    <span>Back</span>
                                </a>
                                <button type="submit" class="auth-submit-btn flex-grow-1" id="submit-btn">
                                    <span class="btn-text">Complete Registration</span>
                                    <span class="btn-loader" style="display: none;"><i class="bi bi-arrow-repeat spin"></i></span>
                                    <i class="bi bi-arrow-right btn-arrow"></i>
                                </button>
                            </div>
                        </form>

                        <div class="secure-notice">
                            <i class="bi bi-shield-check"></i>
                            <span>Your payment information is secure and encrypted</span>
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
<style>
/* Payment Options */
.payment-options {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.payment-option {
    cursor: pointer;
    position: relative;
}

.payment-option.full-width {
    width: 100%;
}

.payment-option input {
    display: none;
}

.payment-option-inner {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px;
    background: rgba(255, 255, 255, 0.03);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    transition: all 0.2s ease;
    position: relative;
}

.payment-option:hover .payment-option-inner {
    border-color: var(--primary-clr);
}

.payment-option input:checked + .payment-option-inner {
    border-color: var(--primary-clr);
    background: rgba(249, 115, 22, 0.05);
}

.payment-option.featured .payment-option-inner {
    border-color: #22c55e;
    background: rgba(34, 197, 94, 0.05);
}

.payment-option.featured input:checked + .payment-option-inner {
    background: rgba(34, 197, 94, 0.1);
}

.featured-badge {
    position: absolute;
    top: -10px;
    left: 16px;
    background: #22c55e;
    color: white;
    font-size: 10px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
}

/* Payment Grid - 2 per row */
.payment-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.payment-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.payment-icon.trial {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
}

.payment-icon.trial i {
    font-size: 20px;
    color: white;
}

.payment-icon.gateway {
    background: white;
    padding: 5px;
}

.payment-icon.gateway img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.payment-details {
    flex: 1;
    min-width: 0;
}

.payment-details h4 {
    font-size: 13px;
    font-weight: 600;
    color: var(--white-clr);
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.payment-details p {
    font-size: 11px;
    color: rgba(255, 255, 255, 0.5);
    margin: 0;
}

/* Radio dot indicator */
.payment-radio {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.25);
    transition: all 0.2s ease;
    flex-shrink: 0;
    position: relative;
}

.payment-radio::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22c55e;
    transition: transform 0.2s ease;
}

.payment-option input:checked + .payment-option-inner .payment-radio {
    border-color: #22c55e;
}

.payment-option input:checked + .payment-option-inner .payment-radio::after {
    transform: translate(-50%, -50%) scale(1);
}

.payment-option.featured input:checked + .payment-option-inner .payment-radio {
    border-color: #22c55e;
}

.payment-divider {
    text-align: center;
    position: relative;
    margin: 14px 0;
}

.payment-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: rgba(255, 255, 255, 0.1);
}

.payment-divider span {
    position: relative;
    background: #16161f;
    padding: 0 14px;
    font-size: 12px;
    color: rgba(255, 255, 255, 0.5);
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 20px;
}

.btn-back {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 20px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.1);
    color: var(--white-clr);
}

.flex-grow-1 {
    flex-grow: 1;
}

.secure-notice {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    font-size: 12px;
    color: rgba(255, 255, 255, 0.5);
}

.secure-notice i {
    color: #22c55e;
}

@media (max-width: 767px) {
    .payment-grid {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .btn-back {
        width: 100%;
    }
}
</style>
@endpush
