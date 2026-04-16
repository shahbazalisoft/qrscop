@extends('menu-templates.layouts.base-2')

@section('styles')
<link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/menu_style14.css') }}">
<link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/category-popup-common.css') }}">
{{-- Style-14 uses fixed dark theme - only accent color from store colors --}}
@if($store->menu_colors)
@php
    $mc = $store->menu_colors;
    $accentHex = $mc['accent'] ?? '#ea580c';
    $r = max(0, hexdec(substr($accentHex, 1, 2)) - 25);
    $g = max(0, hexdec(substr($accentHex, 3, 2)) - 25);
    $b = max(0, hexdec(substr($accentHex, 5, 2)) - 25);
    $accentHover = sprintf('#%02x%02x%02x', $r, $g, $b);
    $accentLight = 'rgba(' . hexdec(substr($accentHex, 1, 2)) . ',' . hexdec(substr($accentHex, 3, 2)) . ',' . hexdec(substr($accentHex, 5, 2)) . ',0.15)';
@endphp
<style>
    :root {
        --s15-accent: {{ $accentHex }};
        --s15-accent-hover: {{ $accentHover }};
        --s15-accent-light: {{ $accentLight }};
    }
</style>
@endif
@endsection

@section('content')

    <!-- Header -->
    <header class="s15-header">
        <div class="s15-header-left">
            <img src="{{\App\CentralLogics\Helpers::get_full_url('store', $store->alternative_logo ?? $store->logo, 'public')}}" alt="{{$store->name}}" class="s15-header-logo">
            <div>
                <h1 class="s15-header-name">{{$store->name}}</h1>
                <p class="s15-header-address">{{$store->restaurant_title}}</p>
            </div>
        </div>
        <button class="s15-search-btn" id="s15-search-toggle"><i class="bi bi-search"></i></button>
    </header>

    <!-- ===== HOME TAB ===== -->
    <div class="s15-tab-content active" id="s15-tab-home">

        <!-- Banner Carousel -->
        @if($banners->count() > 0)
        <div class="s15-banner">
            <div class="s15-banner-track">
                @foreach ($banners as $banner)
                <div class="s15-banner-slide">
                    <img src="{{$banner['image_full_url']}}" alt="{{$banner->title_one}}">
                    <div class="s15-banner-overlay">
                        @if($banner->title_one)<span class="s15-banner-tag">{{$banner->title_one}}</span>@endif
                        @if($banner->title_two)<h3>{{$banner->title_two}}</h3>@endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="s15-banner-dots">
                @foreach($banners as $i => $banner)
                <span class="s15-dot {{ $i === 0 ? 'active' : '' }}"></span>
                @endforeach
            </div>
            {{-- <button class="s15-banner-nav prev"><i class="bi bi-chevron-left"></i></button>
            <button class="s15-banner-nav next"><i class="bi bi-chevron-right"></i></button> --}}
        </div>
        @endif

        {{-- Category Tabs (horizontal scroll) - commented for future use
        @if($categories->count() > 0)
        <nav class="s15-cat-nav" id="s15-cat-nav">
            <div class="s15-cat-scroll">
                <button class="s15-cat-tab active" data-category="all">
                    <span class="s15-cat-icon"><i class="bi bi-grid-3x3-gap-fill"></i></span>
                    <span>All</span>
                </button>
                @foreach ($categories as $menu)
                    @if($menu->items->count() > 0)
                    <button class="s15-cat-tab" data-category="{{$menu->slug}}">
                        <span class="s15-cat-thumb">
                            <img src="{{$menu->image_full_url}}" alt="{{$menu->name}}">
                        </span>
                        <span>{{$menu->name}}</span>
                    </button>
                    @endif
                @endforeach
            </div>
        </nav>
        @endif
        --}}

        <!-- Order Again Section (previously ordered items) -->
        <div class="s15-order-again-section" id="s15-order-again" style="display:none;">
            <div class="s15-section-head">
                <h2 class="s15-section-title"><i class="bi bi-arrow-repeat"></i> Order Again</h2>
                <button class="viewall-title-btn s15-oa-viewall-btn" id="s15-oa-viewall" style="display:none;"><i class="bi bi-grid"></i></button>
            </div>
            <div class="s15-order-again-scroll"></div>
            <!-- Expanded view (hidden by default) -->
            <div class="s15-order-again-grid" id="s15-oa-grid" style="display:none;"></div>
        </div>

        <!-- Search Bar -->
        <div class="s15-search-bar" id="s15-search-bar" style="display:none;">
            <div class="s15-search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" class="s15-search-input" placeholder="Search for dishes...">
                <button class="s15-search-close"><i class="bi bi-x"></i></button>
            </div>
            <div class="s15-filter-pills">
                <button class="s15-fpill active" data-filter="all"><i class="bi bi-grid-3x3-gap"></i> All</button>
                <button class="s15-fpill" data-filter="veg"><span class="s15-veg-dot"></span> Veg</button>
                <button class="s15-fpill" data-filter="non-veg"><span class="s15-nonveg-dot"></span> Non-Veg</button>
            </div>
        </div>

        <!-- Category with Images (same as style-8) -->
        @if($categories->count() > 0)
        <div class="category-sticky-wrapper">
            <div class="category-title-bar">
                <h2 class="category-title">Menu</h2>
                <button class="viewall-title-btn" id="viewall-btn" data-category="view-all">View All <i class="bi bi-grid"></i></button>
            </div>
            <nav class="cat-img-nav">
                <div class="cat-img-scroll">
                    <div class="cat-img-item active" data-category="all">
                        <div class="cat-img-circle all-circle">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                        </div>
                        <span>All</span>
                    </div>
                    @foreach ($categories as $menu)
                        @if($menu->items->count() > 0)
                        <div class="cat-img-item" data-category="{{$menu->slug}}">
                            <div class="cat-img-circle">
                                <img src="{{$menu->image_full_url}}" alt="{{$menu->name}}">
                            </div>
                            <span>{{$menu->name}}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
            </nav>
        </div>
        @endif

        <!-- Category Popup -->
        <div class="cat-popup-overlay" id="cat-popup-overlay"></div>
        <div class="cat-popup" id="cat-popup">
            <div class="cat-popup-header">
                <h3>Menu Categories</h3>
                <button class="cat-popup-close" id="cat-popup-close"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="cat-popup-list">
                @foreach ($categories as $menu)
                    @if($menu->items->count() > 0)
                    <button class="cat-popup-item" data-category="{{$menu->slug}}">
                        <img src="{{$menu->image_full_url}}" alt="{{$menu->name}}">
                        <span>{{$menu->name}}</span>
                    </button>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Menu Sections -->
        <main class="s15-menu-main">
            @php $globalIndex = 0; @endphp
            @foreach($categories as $category)
            @if($category->items->count() > 0)
            <section class="s15-menu-section" id="{{ $category->slug }}" data-category="{{ $category->slug }}">
                <div class="s15-section-head">
                    <h2 class="s15-section-title">{{ $category->name }}</h2>
                    <span class="s15-section-count">{{$category->items->count()}} items</span>
                </div>
                <div class="s15-items-list">
                    @foreach($category->items as $item)
                    @php
                        $discount = $item->discount ?? 0;
                        $discountType = $item->discount_type ?? 'percent';
                        if ($discount > 0) {
                            $discountedPrice = $discountType === 'percent'
                                ? round($item->price - ($item->price * $discount / 100))
                                : max(0, $item->price - $discount);
                        } else {
                            $discountedPrice = $item->price;
                        }
                    @endphp
                    <div class="s15-item-row" data-index="{{ $globalIndex }}" data-item-id="{{ $item->id }}">
                        <div class="s15-item-img">
                            <img src="{{$item->image_full_url}}" alt="{{$item->name}}">
                            <span class="s15-veg-badge {{ $item->veg == 1 ? 'veg' : 'nonveg' }}"></span>
                            @if($discount > 0 && $discountedPrice < $item->price)
                            <span class="s15-item-offer">{{ $discountType === 'percent' ? $discount . '%' : \App\CentralLogics\Helpers::format_currency($discount) }} OFF</span>
                            @endif
                        </div>
                        <div class="s15-item-info">
                            <h3 class="s15-item-name">{{$item->name}}</h3>
                            <p class="s15-item-desc">{{$item->description}}</p>
                            <div class="s15-item-bottom">
                                <div class="s15-item-pricing">
                                    <span class="s15-item-price">{{ \App\CentralLogics\Helpers::format_currency($discountedPrice) }}</span>
                                    @if($discount > 0 && $discountedPrice < $item->price)
                                    <span class="s15-item-mrp"><s>{{ \App\CentralLogics\Helpers::format_currency($item->price) }}</s></span>
                                    @endif
                                </div>
                                <button class="s15-add-btn"><i class="bi bi-plus"></i> Add</button>
                            </div>
                        </div>
                    </div>
                    @php $globalIndex++; @endphp
                    @endforeach
                </div>
            </section>
            @endif
            @endforeach
        </main>
    </div>

    <!-- ===== CART TAB ===== -->
    <div class="s15-tab-content" id="s15-tab-cart">
        <div class="s15-page">
            <div class="s15-page-head"><h2><i class="bi bi-bag-fill"></i> Your Cart</h2></div>
            <div class="s15-cart-items-container"></div>
            <div class="s15-cart-summary-section" style="display:none;">
                <div class="s15-cart-summary">
                    <div class="s15-sum-row"><span>Subtotal</span><span class="s15-subtotal">&#8377;0</span></div>
                    <div class="s15-sum-row s15-discount-row" style="display:none;"><span>Discount</span><span class="s15-discount-amount" style="color:#16a34a;">-&#8377;0</span></div>
                    <div class="s15-sum-row s15-delivery-row" style="{{ ($store->delivery_charg ?? 0) > 0 && $store->order_type != 3 ? '' : 'display:none;' }}"><span>Delivery Fee</span><span class="s15-delivery-fee-amount">&#8377;{{ $store->delivery_charg ?? 0 }}</span></div>
                    <div class="s15-sum-row total"><span>Total</span><span class="s15-total">&#8377;0</span></div>
                </div>
                <div class="s15-checkout-buttons">
                    @if($store->order_type == 3)
                    <button class="s15-checkout-btn order-type-btn active" data-type="dine-in"><i class="bi bi-shop"></i> Dine-In</button>
                    <button class="s15-delivery-btn order-type-btn" data-type="delivery"><i class="bi bi-truck"></i> Delivery</button>
                    @elseif($store->order_type == 2)
                    <button class="s15-delivery-btn s15-dinein-only"><i class="bi bi-truck"></i> Delivery</button>
                    @else
                    <button class="s15-checkout-btn s15-dinein-only"><i class="bi bi-shop"></i> Dine-In</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ===== ORDER TAB ===== -->
    <div class="s15-tab-content" id="s15-tab-order">
        <div class="s15-page">
            <div class="s15-page-head"><h2><i class="bi bi-receipt"></i> Your Orders</h2></div>
            <div class="s15-orders-list"></div>
        </div>
    </div>

    <!-- ===== REORDER TAB ===== -->
    <div class="s15-tab-content" id="s15-tab-reorder">
        <div class="s15-page">
            <div class="s15-page-head"><h2><i class="bi bi-arrow-repeat"></i> Reorder</h2></div>
            <div class="s15-reorder-list"></div>
            <div class="s15-reorder-empty" style="display:none;">
                <div class="s15-cart-empty">
                    <i class="bi bi-clock-history"></i>
                    <h3>No previous orders</h3>
                    <p>Items you've ordered before will appear here</p>
                    <button class="s15-browse-btn" onclick="document.querySelector('.s15-nav-item[data-tab=home]').click()">
                        <i class="bi bi-arrow-left"></i> Browse Menu
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== TODAY SPECIAL TAB ===== -->
    <div class="s15-tab-content" id="s15-tab-special">
        <div class="s15-page">
            <div class="s15-page-head"><h2><i class="bi bi-stars"></i> Today's Special</h2></div>
            <div class="s15-special-list">
                @php $specialIndex = 0; @endphp
                @forelse(($todaySpecials ?? collect()) as $special)
                    @if($special->item)
                    @php
                        $spItem = $special->item;
                        $spDiscount = $spItem->discount ?? 0;
                        $spDiscountType = $spItem->discount_type ?? 'percent';
                        if ($spDiscount > 0) {
                            $spDiscountedPrice = $spDiscountType === 'percent'
                                ? round($spItem->price - ($spItem->price * $spDiscount / 100))
                                : max(0, $spItem->price - $spDiscount);
                        } else {
                            $spDiscountedPrice = $spItem->price;
                        }
                    @endphp
                    <div class="s15-special-card" data-special="{{ $specialIndex }}">
                        <img src="{{$spItem->image_full_url}}" alt="{{$spItem->name}}" class="s15-special-img">
                        <div class="s15-special-body">
                            <div class="s15-special-top">
                                <h3>{{$spItem->name}}</h3>
                                <span class="s15-type-tag {{ $spItem->veg == 1 ? 'veg' : 'nonveg' }}"><span class="s15-type-dot"></span></span>
                            </div>
                            <p>{{$spItem->description}}</p>
                            <div class="s15-special-bottom">
                                <div>
                                    <span class="s15-item-price">{{ \App\CentralLogics\Helpers::format_currency($spDiscountedPrice) }}</span>
                                    @if($spDiscount > 0 && $spDiscountedPrice < $spItem->price)
                                    <span class="s15-item-mrp"><s>{{ \App\CentralLogics\Helpers::format_currency($spItem->price) }}</s></span>
                                    @endif
                                </div>
                                <button class="s15-add-btn"><i class="bi bi-plus"></i> Add</button>
                            </div>
                        </div>
                    </div>
                    @php $specialIndex++; @endphp
                    @endif
                @empty
                    <div class="text-center" style="padding:40px 20px; color:var(--s15-text2,#888);">
                        <i class="bi bi-stars" style="font-size:2.5rem; opacity:0.4;"></i>
                        <p style="margin-top:10px;">No specials today. Check back tomorrow!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ===== ITEM DETAIL POPUP ===== -->
    <div class="s15-detail-overlay"></div>
    <div class="s15-detail-popup">
        <div class="s15-detail-img-wrap">
            <div class="s15-detail-slider">
                <div class="s15-detail-slider-track"></div>
                <div class="s15-detail-slider-dots"></div>
                <button class="s15-detail-slider-nav prev"><i class="bi bi-chevron-left"></i></button>
                <button class="s15-detail-slider-nav next"><i class="bi bi-chevron-right"></i></button>
            </div>
            <div class="s15-detail-badge"></div>
            <button class="s15-detail-close"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="s15-detail-body">
            <h2 class="s15-detail-name"></h2>
            <p class="s15-detail-desc"></p>
            <div class="s15-detail-price-row">
                <span class="s15-detail-mrp"></span>
                <span class="s15-detail-price"></span>
                <span class="s15-detail-discount"></span>
            </div>
            <div class="s15-size-options">
                <span class="s15-size-label">Choose Size</span>
                <div class="s15-size-group">
                    <button class="s15-size-btn" data-size="quarter"><span class="s15-size-name">Quarter</span><span class="s15-size-price"></span></button>
                    <button class="s15-size-btn" data-size="half"><span class="s15-size-name">Half</span><span class="s15-size-price"></span></button>
                    <button class="s15-size-btn active" data-size="full"><span class="s15-size-name">Full</span><span class="s15-size-price"></span></button>
                </div>
            </div>
        </div>
        <div class="s15-detail-footer">
            <div class="s15-detail-qty">
                <button class="s15-detail-minus"><i class="bi bi-dash"></i></button>
                <span class="s15-detail-qty-val">1</span>
                <button class="s15-detail-plus"><i class="bi bi-plus"></i></button>
            </div>
            <button class="s15-detail-add"><span>Add to Cart</span><span class="s15-detail-total">&#8377;0</span></button>
        </div>
    </div>

    <!-- ===== SIZE PICKER POPUP ===== -->
    <div class="s15-sizepicker-overlay"></div>
    <div class="s15-sizepicker-popup">
        <div class="s15-sizepicker-header">
            <h3 class="s15-sizepicker-title"><i class="bi bi-sliders"></i> Select Size</h3>
            <button class="s15-sizepicker-close"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="s15-sizepicker-options">
            <button class="s15-sizepicker-btn active" data-size="quarter"><span class="s15-sizepicker-radio"></span><span class="s15-sizepicker-name">Quarter</span><span class="s15-sizepicker-price"></span></button>
            <button class="s15-sizepicker-btn" data-size="half"><span class="s15-sizepicker-radio"></span><span class="s15-sizepicker-name">Half</span><span class="s15-sizepicker-price"></span></button>
            <button class="s15-sizepicker-btn" data-size="full"><span class="s15-sizepicker-radio"></span><span class="s15-sizepicker-name">Full</span><span class="s15-sizepicker-price"></span></button>
        </div>
        <div class="s15-sizepicker-footer">
            <div class="s15-sizepicker-qty">
                <button class="s15-sizepicker-minus"><i class="bi bi-dash"></i></button>
                <span class="s15-sizepicker-qty-val">1</span>
                <button class="s15-sizepicker-plus"><i class="bi bi-plus"></i></button>
            </div>
            <button class="s15-sizepicker-add"><span>Add to Cart</span><span class="s15-sizepicker-total">&#8377;0</span></button>
        </div>
    </div>

    <!-- ===== IMAGE BANNER POPUP (type 1, hidden by default, shows once per tab) ===== -->
    @if($store->banner_popup_type == 1 && !empty($store->banner_popup))
    <div class="s15-welcome-overlay" id="s15-imgbanner-overlay">
        <div class="s15-imgbanner" style="position:relative;">
            <button class="s15-welcome-close"><i class="bi bi-x-lg"></i></button>
            <img class="s15-imgbanner-img" src="{{$store->banner_popup_full_url}}" alt="Offer">
        </div>
    </div>
    @endif

    <!-- ===== WELCOME BANNER POPUP WITH TEXT (type 2, hidden by default) ===== -->
    @if($store->banner_popup_type == 2 && isset($store->text_banner_popup))
    <div class="s15-welcome-overlay" id="s15-welcome-overlay">
        <div class="s15-welcome-popup" style="position:relative;">
            <button class="s15-welcome-close"><i class="bi bi-x-lg"></i></button>
            @if(isset($store->text_banner_popup['image']) && !empty($store->text_banner_popup['image']))<img class="s15-welcome-img" src="{{ $store->text_banner_image_full_url}}" alt="Welcome">@endif
            <div class="s15-welcome-body">
                @if(isset($store->text_banner_popup['heading']) && !empty($store->text_banner_popup['heading']))<span class="s15-welcome-tag">{{ $store->text_banner_popup['heading'] ?? '' }}</span>@endif
                @if(isset($store->text_banner_popup['title']) && !empty($store->text_banner_popup['title']))<h2>{{ $store->text_banner_popup['title'] ?? '' }}</h2>@endif
                @if(isset($store->text_banner_popup['description']) && !empty($store->text_banner_popup['description']))<p>{{ $store->text_banner_popup['description'] ?? '' }}</p>@endif
                @if(isset($store->text_banner_popup['label']) && !empty($store->text_banner_popup['label']))<div class="s15-welcome-code">{{ $store->text_banner_popup['label'] ?? '' }}</div>@endif
                @if(isset($store->text_banner_popup['button']) && !empty($store->text_banner_popup['button']))<button class="s15-welcome-btn">{{ $store->text_banner_popup['button'] ?? '' }}</button>@endif
            </div>
        </div>
    </div>
    @endif

    <!-- Bottom Navigation -->
    <nav class="s15-bottom-nav">
        <div class="s15-nav-pill-bg"></div>
        <button class="s15-nav-item active" data-tab="home">
            <div class="s15-nav-icon-wrap">
                <i class="bi bi-house-door"></i>
                <i class="bi bi-house-door-fill s15-nav-icon-active"></i>
            </div>
            <span>{{$store->menu_buttom_home ?? 'Home'}}</span>
        </button>
        <button class="s15-nav-item" data-tab="special">
            <div class="s15-nav-icon-wrap">
                <i class="bi bi-stars"></i>
                <i class="bi bi-stars s15-nav-icon-active"></i>
            </div>
            <span>{{$store->menu_buttom_special ?? 'Specials'}}</span>
        </button>
        <button class="s15-nav-item" data-tab="cart">
            <div class="s15-nav-icon-wrap">
                <i class="bi bi-bag"></i>
                <i class="bi bi-bag-fill s15-nav-icon-active"></i>
                <span class="s15-cart-badge" style="display:none;">0</span>
            </div>
            <span>{{$store->menu_buttom_cart ?? 'Cart'}}</span>
        </button>
        <button class="s15-nav-item" data-tab="reorder">
            <div class="s15-nav-icon-wrap">
                <i class="bi bi-arrow-repeat"></i>
                <i class="bi bi-arrow-repeat s15-nav-icon-active"></i>
            </div>
            <span>{{$store->menu_buttom_reorder ?? 'Reorder'}}</span>
        </button>
        <button class="s15-nav-item" data-tab="order">
            <div class="s15-nav-icon-wrap">
                <i class="bi bi-receipt"></i>
                <i class="bi bi-receipt-cutoff s15-nav-icon-active"></i>
            </div>
            <span>{{$store->menu_buttom_orders ?? 'Orders'}}</span>
        </button>
    </nav>

    @include('menu-templates.partials.scroll-top')

@endsection

@section('scripts')
<script src="{{ asset('public/assets/menu-templates/js/category-popup-common.js') }}"></script>
<script src="{{ asset('public/assets/menu-templates/js/menu_style14.js') }}"></script>
<script>
    window.s15StoreId = {{ $store->id }};
    window.storePhone = @json($store->phone ?? '');
    window.trackingPhone = @json($store->tracking_order_mobile_no ?? $store->phone);
    window.storeDeliveryCharge = {{ $store->delivery_charg ?? 0 }};
    window.storeOrderType = {{ $store->order_type ?? 0 }};
    window.poweredBy = @json(\App\CentralLogics\Helpers::get_business_settings('business_name'));
    window.s15SpecialItems = [
        @foreach(($todaySpecials ?? collect()) as $special)
            @if($special->item)
            @php
                $spIt = $special->item;
                $spD = $spIt->discount ?? 0;
                $spDT = $spIt->discount_type ?? 'percent';
                $spDP = $spD > 0 ? ($spDT === 'percent' ? round($spIt->price - ($spIt->price * $spD / 100)) : max(0, $spIt->price - $spD)) : $spIt->price;
            @endphp
            { id: {{ $spIt->id }}, name: @json($spIt->name), price: {{ $spDP }}, mrp: {{ $spIt->price }}, isVeg: {{ $spIt->veg == 1 ? 'true' : 'false' }}, img: "{{ $spIt->image_full_url }}", desc: @json($spIt->description ?? '') },
            @endif
        @endforeach
    ];
    window.s15MenuItems = [
        @php $globalIndex = 0; @endphp
        @foreach($categories as $category)
            @foreach($category->items as $item)
                @php
                    $disc = $item->discount ?? 0;
                    $discType = $item->discount_type ?? 'percent';
                    $discPrice = $disc > 0 ? ($discType === 'percent' ? round($item->price - ($item->price * $disc / 100)) : max(0, $item->price - $disc)) : $item->price;
                @endphp
                { id: {{ $item->id }}, name: @json($item->name), price: {{ $discPrice }}, mrp: {{ $item->price }}, isVeg: {{ $item->veg == 1 ? 'true' : 'false' }}, category: @json($category->slug), img: "{{ $item->image_full_url }}", desc: @json($item->description ?? ''), discount: {{ $disc }}, discountType: @json($discType), foodVariations: @json($item->food_variations ? json_decode($item->food_variations, true) : []), tags: @json($item->tags ? $item->tags->pluck('tag')->join(',') : '') },
                @php $globalIndex++; @endphp
            @endforeach
        @endforeach
    ];
</script>
@endsection
