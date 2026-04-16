<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kitchen Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('public/assets/kitchen/css/kitchen.css') }}">
    @stack('css')
</head>
<body>
<div class="k-app-wrap">
    {{-- Header --}}
    <header class="k-header">
        <div class="k-header-left">
            @if(isset($store) && $store->logo_full_url)
                <img src="{{ $store->logo_full_url }}" alt="Logo" class="k-header-logo">
            @endif
            <span class="k-header-title">{{ $store->name ?? 'Kitchen' }}</span>
        </div>
        <button class="k-header-btn" id="k-logout-btn">Logout</button>
    </header>

    {{-- Toast Container --}}
    <div id="k-toast-container" class="k-toast-container"></div>

    {{-- Content --}}
    <main class="k-content">
        @yield('content')
    </main>

    {{-- Bottom Navigation --}}
    <nav class="k-bottom-nav">
        <a href="{{ route('kitchen.dashboard') }}" class="k-nav-item {{ request()->routeIs('kitchen.dashboard') ? 'active' : '' }}">
            <div class="k-nav-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <span>Home</span>
        </a>
        <a href="{{ route('kitchen.orders') }}" class="k-nav-item {{ request()->routeIs('kitchen.orders') ? 'active' : '' }}">
            <div class="k-nav-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10 9 9 9 8 9"/>
                </svg>
                @php
                    $pendingNav = \App\Models\MenuOrder::where('store_id', auth('kitchen')->user()->store_id)
                        ->whereIn('status', ['pending','confirmed','preparing'])->count();
                @endphp
                @if($pendingNav > 0)
                    <span class="k-nav-badge">{{ $pendingNav }}</span>
                @endif
            </div>
            <span>Orders</span>
        </a>
        <a href="{{ route('kitchen.items') }}" class="k-nav-item {{ request()->routeIs('kitchen.items') ? 'active' : '' }}">
            <div class="k-nav-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="8" y1="6" x2="21" y2="6"/>
                    <line x1="8" y1="12" x2="21" y2="12"/>
                    <line x1="8" y1="18" x2="21" y2="18"/>
                    <line x1="3" y1="6" x2="3.01" y2="6"/>
                    <line x1="3" y1="12" x2="3.01" y2="12"/>
                    <line x1="3" y1="18" x2="3.01" y2="18"/>
                </svg>
            </div>
            <span>Items</span>
        </a>
    </nav>
</div>{{-- end k-app-wrap --}}

    {{-- Logout Confirmation Modal --}}
    <div class="k-alert-overlay" id="k-logout-modal" style="display:none;">
        <div class="k-alert-modal">
            <div class="k-alert-icon-wrap" style="background:rgba(225,112,85,0.15);">
                <svg style="width:32px;height:32px;color:var(--k-danger);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </div>
            <div class="k-alert-title" style="color:var(--k-danger);">Logout?</div>
            <div class="k-alert-text">Are you sure you want to logout from the kitchen dashboard?</div>
            <div class="k-alert-buttons">
                <a href="{{ route('kitchen.logout') }}" class="k-btn k-btn-danger">Yes, Logout</a>
                <button class="k-btn k-btn-outline" id="k-logout-cancel">Cancel</button>
            </div>
        </div>
    </div>

    {{-- New Order Alert Modal --}}
    <div class="k-alert-overlay" id="k-new-order-alert" style="display:none;">
        <div class="k-alert-modal">
            <div class="k-alert-icon-wrap">
                <svg class="k-alert-icon-ring" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 01-3.46 0"/>
                </svg>
            </div>
            <div class="k-alert-title">New Order!</div>
            <div class="k-alert-text" id="k-alert-text">You have a new order</div>
            <div class="k-alert-buttons">
                <button class="k-btn k-btn-primary" id="k-alert-view-btn">View Order</button>
                <button class="k-btn k-btn-outline" id="k-alert-dismiss-btn">Dismiss</button>
            </div>
        </div>
    </div>

    {{-- New Order Detail Modal (reused across all pages) --}}
    <div class="k-modal-overlay" id="k-order-modal" style="display:none;">
        <div class="k-modal">
            <div class="k-modal-header">
                <span class="k-modal-title" id="k-modal-title">Order Details</span>
                <button class="k-modal-close" id="k-modal-close">&times;</button>
            </div>
            <div class="k-modal-body" id="k-modal-body"></div>
        </div>
    </div>

    {{-- Notification Sound --}}
    <audio id="k-notification-sound" preload="auto">
        <source src="{{ asset('public/assets/admin/sound/notification.mp3') }}" type="audio/mpeg">
    </audio>

    @php
        $lastOrderId = \App\Models\MenuOrder::where('store_id', auth('kitchen')->user()->store_id)->max('id') ?? 0;
    @endphp
    <script>
        var kitchenConfig = {
            checkNewOrdersUrl: "{{ route('kitchen.check-new-orders') }}",
            lastKnownOrderId: {{ $lastOrderId }}
        };
    </script>
    <script src="{{ asset('public/assets/kitchen/js/kitchen.js') }}"></script>
    @stack('js')
</body>
</html>
