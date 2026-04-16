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

                <!-- Right Side - Step 2 Form -->
                <div class="col-lg-6">
                    <div class="auth-card">
                        @include('vendor-views.auth.partials.step-indicator', ['current_step' => 2])

                        <div class="auth-card-header">
                            <h2>Account Created Successfully</h2>
                            {{-- Select Your Plan --}}
                            <p>Choose a plan to manage your menu, orders, and customers easily — or skip to explore QRScop in trial mode.</p>
                        </div>

                        <form action="{{ route('restaurant.business_plan') }}" method="POST" id="plan-form" class="auth-form">
                            @csrf
                            <input type="hidden" name="business_plan" value="subscription-base">

                            <!-- Packages Section - Horizontal Layout -->
                            <div class="packages-section">
                                <h5 class="packages-title">Choose Your Package</h5>
                                <div class="packages-row">
                                    @forelse ($packages as $key => $package)
                                    <label class="package-card {{ $key == 0 ? 'recommended' : '' }}">
                                        <input type="radio" name="package_id" value="{{ $package->id }}" {{ $key == 0 ? 'checked' : '' }}>
                                        {{-- @if($key == 0)
                                        <div class="package-badge">Popular</div>
                                        @endif --}}
                                        <div class="package-card-inner">
                                            <div class="package-header">
                                                <h4>{{ $package->package_name }}</h4>
                                                <div class="package-price">
                                                    <span class="amount">{{ \App\CentralLogics\Helpers::format_currency($package->price) }}</span>
                                                    <span class="period">/ {{ $package->validity }} days</span>
                                                </div>
                                            </div>
                                            <ul class="package-features">
                                                {{-- <li><i class="bi bi-check2"></i> Unlimited Menu And Items</li> --}}
                                                <li><i class="bi bi-check2"></i> Simple QR </li>
                                                <li><i class="bi bi-check2"></i> Table-wise QR System</li>
                                                <li><i class="bi bi-check2"></i> All Menu Templates</li>
                                                <li><i class="bi bi-check2"></i> Kitchen Dashboard</li>
                                                <li><i class="bi bi-check2"></i> Unlimited Orders</li>
                                                <li><i class="bi bi-check2"></i> <span class="disabled">Unlimited Scan</span></li>

                                                {{-- @if ($package->pos)
                                                <li><i class="bi bi-check2"></i> POS System</li>
                                                @endif
                                                @if ($package->mobile_app)
                                                <li><i class="bi bi-check2"></i> Mobile App</li>
                                                @endif
                                                @if ($package->chat)
                                                <li><i class="bi bi-check2"></i> Chat Support</li>
                                                @endif
                                                @if ($package->review)
                                                <li><i class="bi bi-check2"></i> Reviews</li>
                                                @endif
                                                @if ($package->self_delivery)
                                                <li><i class="bi bi-check2"></i> Self Delivery</li>
                                                @endif --}}
                                                {{-- <li><i class="bi bi-check2"></i> {{ $package->max_order == 'unlimited' ? 'Unlimited' : $package->max_order }} Orders</li> --}}
                                                <li><i class="bi bi-check2"></i> {{ $package->max_product == 'unlimited' ? 'Unlimited' : $package->max_product }} Products</li>
                                            </ul>
                                            <div class="package-radio"></div>
                                        </div>
                                    </label>
                                    @empty
                                    <div class="no-packages">
                                        <i class="bi bi-inbox"></i>
                                        <p>No packages available</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="form-actions">
                                {{-- <a href="{{ route('restaurant.create') }}" class="btn-back">
                                    <i class="bi bi-arrow-left"></i>
                                    <span>Back</span>
                                </a> --}}
                                <a href="{{ route('restaurant.final_step') }}" class="btn-back">
                                    <i class="bi bi-skip-forward"></i>
                                    <span>Skip</span>
                                </a>
                                <button type="submit" class="auth-submit-btn flex-grow-1" id="submit-btn">
                                    <span class="btn-text">Continue</span>
                                    <span class="btn-loader" style="display: none;"><i class="bi bi-arrow-repeat spin"></i></span>
                                    <i class="bi bi-arrow-right btn-arrow"></i>
                                </button>
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
<style>
/* Packages Section */
.packages-section {
    margin-bottom: 20px;
}

.packages-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--white-clr);
    margin-bottom: 14px;
}

/* Horizontal Package Layout */
.packages-row {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    padding: 10px 2px 8px;
}

.package-card {
    cursor: pointer;
    position: relative;
    flex: 1;
    min-width: 180px;
}

.package-card input {
    display: none;
}

.package-badge {
    position: absolute;
    top: -8px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--primary-clr);
    color: white;
    font-size: 10px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
    z-index: 2;
    white-space: nowrap;
}

.package-card-inner {
    padding: 16px 14px;
    background: rgba(255, 255, 255, 0.03);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    transition: all 0.2s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}

.package-card:hover .package-card-inner {
    border-color: var(--primary-clr);
}

.package-card input:checked + .package-badge + .package-card-inner,
.package-card input:checked + .package-card-inner {
    border-color: var(--primary-clr);
    background: rgba(249, 115, 22, 0.05);
}

.package-header {
    text-align: center;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.package-header h4 {
    font-size: 15px;
    font-weight: 700;
    color: var(--white-clr);
    margin-bottom: 8px;
}

.package-price {
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 3px;
}

.package-price .amount {
    font-size: 22px;
    font-weight: 700;
    color: var(--primary-clr);
}

.package-price .period {
    font-size: 11px;
    color: rgba(255, 255, 255, 0.5);
}

.package-features {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 1;
}

.package-features li {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: rgba(255, 255, 255, 0.75);
}

.package-features li i {
    color: #22c55e;
    font-size: 12px;
    flex-shrink: 0;
}

/* Radio dot indicator */
.package-radio {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.25);
    transition: all 0.2s ease;
}

.package-radio::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #22c55e;
    transition: transform 0.2s ease;
}

.package-card input:checked + .package-badge + .package-card-inner .package-radio,
.package-card input:checked + .package-card-inner .package-radio {
    border-color: #22c55e;
}

.package-card input:checked + .package-badge + .package-card-inner .package-radio::after,
.package-card input:checked + .package-card-inner .package-radio::after {
    transform: translate(-50%, -50%) scale(1);
}

.no-packages {
    width: 100%;
    text-align: center;
    padding: 30px;
    color: rgba(255, 255, 255, 0.5);
}

.no-packages i {
    font-size: 36px;
    margin-bottom: 10px;
    display: block;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 10px;
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

/* Scrollbar styling */
.packages-row::-webkit-scrollbar {
    height: 5px;
}

.packages-row::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 3px;
}

.packages-row::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
}

.packages-row::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}

@media (max-width: 767px) {
    .packages-row {
        flex-direction: column;
    }

    .package-card {
        min-width: 100%;
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
