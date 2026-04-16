@extends('menu-templates.layouts.base-2')

@section('styles')
<link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/menu_style13.css') }}">
<link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/category-popup-common.css') }}">
@if($store->menu_colors)
@php
    $mc = $store->menu_colors;
    // Darken accent by 10% for hover state
    $accentHex = $mc['accent'] ?? '#10847e';
    $r = max(0, hexdec(substr($accentHex, 1, 2)) - 25);
    $g = max(0, hexdec(substr($accentHex, 3, 2)) - 25);
    $b = max(0, hexdec(substr($accentHex, 5, 2)) - 25);
    $accentHover = sprintf('#%02x%02x%02x', $r, $g, $b);
    // Accent with alpha for light variant
    $accentLight = 'rgba(' . hexdec(substr($accentHex, 1, 2)) . ',' . hexdec(substr($accentHex, 3, 2)) . ',' . hexdec(substr($accentHex, 5, 2)) . ',0.1)';
    // Text color variants (secondary=0.6 opacity, tertiary=0.4 opacity)
    $textHex = $mc['text'] ?? '#1a1a2e';
    $tr = hexdec(substr($textHex, 1, 2));
    $tg = hexdec(substr($textHex, 3, 2));
    $tb = hexdec(substr($textHex, 5, 2));
    $text2 = "rgba($tr,$tg,$tb,0.6)";
    $text3 = "rgba($tr,$tg,$tb,0.4)";
@endphp
<style>
    :root {
        --s14-accent: {{ $mc['accent'] }};
        --s14-accent-hover: {{ $accentHover }};
        --s14-accent-light: {{ $accentLight }};
        --s14-bg: {{ $mc['bg'] }};
        --s14-surface: {{ $mc['surface'] }};
        --s14-surface2: {{ $mc['surface'] }};
        --s14-nav-bg: {{ $mc['surface'] }};
        --s14-text: {{ $mc['text'] }};
        --s14-text2: {{ $text2 }};
        --s14-text3: {{ $text3 }};
    }
</style>
@endif
@endsection

@section('content')

    <!-- Header -->
    <header class="s14-header">
        <div class="s14-header-content">
            <div class="s14-logo">
                <img src="{{\App\CentralLogics\Helpers::get_full_url('store', $store->alternative_logo ?? $store->logo, 'public')}}" alt="{{$store->name}}" class="s14-logo-img">
                <div class="s14-logo-text">
                    <h1>{{$store->name}}</h1>
                    <span>{{$store->restaurant_title}}</span>
                </div>
            </div>
            <button class="s14-search-toggle"><i class="bi bi-search"></i></button>
        </div>
    </header>

    <!-- ===== HOME TAB ===== -->
    <div class="s14-tab-content active" id="s14-tab-home">

        <!-- Banner Carousel -->
        @if($banners->count() > 0)
        <div class="s14-banner">
            <div class="s14-banner-track">
                @foreach ($banners as $banner)
                <div class="s14-banner-slide">
                    <img src="{{$banner['image_full_url']}}" alt="{{$banner->title_one}}">
                    <div class="s14-banner-overlay">
                        @if($banner->title_one)<span class="s14-banner-tag">{{$banner->title_one}}</span>@endif
                        @if($banner->title_two)<h3>{{$banner->title_two}}</h3>@endif
                        {{-- @if($banner->title_one)<p>Use code: FIRST50</p>@endif --}}
                    </div>
                </div>
                @endforeach
            </div>
            <div class="s14-banner-dots">
                <span class="s14-dot active"></span>
                <span class="s14-dot"></span>
                <span class="s14-dot"></span>
            </div>
            {{-- <button class="s14-banner-nav prev"><i class="bi bi-chevron-left"></i></button>
            <button class="s14-banner-nav next"><i class="bi bi-chevron-right"></i></button> --}}
        </div>
        @endif
        <!-- Search Bar (hidden by default) -->
        <div class="s14-search-section" style="display: none;">
            <div class="s14-search-container">
                <div class="s14-search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" class="s14-search-input" placeholder="Search dishes...">
                    <button class="s14-clear-search" style="display: none;"><i class="bi bi-x"></i></button>
                </div>
                <div class="s14-filter-dropdown">
                    <button class="s14-filter-btn">
                        <span class="s14-filter-icon all-icon"></span>
                        <span class="s14-filter-text">All</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="s14-filter-menu">
                        <div class="s14-filter-option active" data-filter="all">
                            <span class="s14-filter-icon all-icon"></span>
                            <span>All</span>
                        </div>
                        <div class="s14-filter-option" data-filter="veg">
                            <span class="s14-filter-icon veg-icon"></span>
                            <span>Veg</span>
                        </div>
                        <div class="s14-filter-option" data-filter="non-veg">
                            <span class="s14-filter-icon non-veg-icon"></span>
                            <span>Non-Veg</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Again Section (previously ordered items) -->
        <div class="s14-order-again-section" id="s14-order-again" style="display:none;">
            <div class="s14-section-header">
                <h2><i class="bi bi-arrow-repeat"></i> Order Again</h2>
                <button class="viewall-title-btn s14-oa-viewall-btn" id="s14-oa-viewall" style="display:none;"><i class="bi bi-grid"></i></button>
            </div>
            <div class="s14-order-again-scroll"></div>
            <div class="s14-order-again-grid" id="s14-oa-grid" style="display:none;"></div>
        </div>

        <!-- Category Title Bar -->
        @if($categories->count() > 0)
        <div class="category-title-bar">
            <h2 class="category-title">What are you craving?</h2>
            <button class="viewall-title-btn" id="s14-viewall-btn" data-category="view-all">View All <i class="bi bi-grid"></i></button>
        </div>
        <!-- Category with Images -->
        <nav class="s14-cat-img-nav">
            <div class="s14-cat-img-scroll">
                <div class="s14-cat-img-item active" data-category="all">
                    <div class="s14-cat-img-circle all-icon">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                    </div>
                    <span>All</span>
                </div>
                @foreach ($categories as $menu)
                    @if($menu->items->count() > 0)
                    <div class="s14-cat-img-item" data-category="{{$menu->slug}}">
                        <div class="s14-cat-img-circle">
                            <img src="{{$menu->image_full_url}}" alt="{{$menu->name}}">
                        </div>
                        <span>{{$menu->name}}</span>
                    </div>
                    @endif
                @endforeach
            </div>
        </nav>
        @endif
        <!-- Category Pills (commented for later use)
        <nav class="s14-pill-nav">
            <div class="s14-pill-scroll">
                <button class="s14-pill active" data-category="all">All</button>
                <button class="s14-pill" data-category="recommended">Recommended</button>
                <button class="s14-pill s14-pill-viewall" id="s14-viewall-btn"><i class="bi bi-grid-fill"></i> View All</button>
            </div>
        </nav>
        -->

        <!-- Category Popup (icon version, commented for later use)
        <div class="s14-cat-popup-overlay" id="s14-cat-overlay"></div>
        <div class="s14-cat-popup" id="s14-cat-popup">
            <div class="s14-cat-popup-header">
                <h3>Menu Categories</h3>
                <button class="s14-cat-popup-close" id="s14-cat-close"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="s14-cat-popup-list">
                <button class="s14-cat-popup-item active" data-category="all">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                    <span>All</span>
                </button>
                <button class="s14-cat-popup-item" data-category="recommended">
                    <i class="bi bi-star-fill"></i>
                    <span>Recommended</span>
                </button>
                <button class="s14-cat-popup-item" data-category="starters">
                    <i class="bi bi-lightning-fill"></i>
                    <span>Starters</span>
                </button>
                <button class="s14-cat-popup-item" data-category="mains">
                    <i class="bi bi-egg-fried"></i>
                    <span>Main Course</span>
                </button>
                <button class="s14-cat-popup-item" data-category="desserts">
                    <i class="bi bi-cake2-fill"></i>
                    <span>Desserts</span>
                </button>
                <button class="s14-cat-popup-item" data-category="drinks">
                    <i class="bi bi-cup-straw"></i>
                    <span>Drinks</span>
                </button>
                <button class="s14-cat-popup-item" data-category="extras">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>Extras</span>
                </button>
            </div>
        </div>
        -->

        <!-- Category Popup (with images) -->
        <div class="s14-cat-popup-overlay" id="s14-cat-overlay"></div>
        <div class="s14-cat-popup" id="s14-cat-popup">
            <div class="s14-cat-popup-header">
                <h3>Menu Categories</h3>
                <button class="s14-cat-popup-close" id="s14-cat-close"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="s14-cat-popup-list">
                @foreach ($categories as $menu)
                    @if($menu->items->count() > 0)
                    <button class="s14-cat-popup-item" data-category="{{$menu->slug}}">
                        <img src="{{$menu->image_full_url}}" alt="{{$menu->name}}">
                        <span>{{$menu->name}}</span>
                    </button>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Menu Content -->
        <main class="s14-menu-content">
            <!-- Recommended -->
            @foreach($categories as $category)
            <section class="s14-menu-section" data-category="{{ $category->slug }}">
                <div class="s14-section-header">
                    <h2><i class="bi {{ \App\CentralLogics\Helpers::random_icon() }}"></i> {{ $category->name }}</h2>
                    <span class="s14-item-count">{{$category->items->count()}} Items</span>
                </div>
                <div class="s14-menu-grid">
                    @forelse($category->items as $item)
                    @php $globalIndex = $globalIndex ?? 0; @endphp
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
                    <div class="s14-menu-card" data-index="{{ $globalIndex }}" data-item-id="{{ $item->id }}">
                        <div class="s14-card-img">
                            <img src="{{$item->image_full_url}}" alt="{{$item->name}}">
                            <div class="s14-badge {{ $item->veg == 1 ? 'veg' : 'non-veg' }}"></div>
                            @if($discount > 0 && $discountedPrice < $item->price)
                                <span class="s14-card-discount">{{ $discountType === 'percent' ? $discount . '% OFF' : \App\CentralLogics\Helpers::format_currency($discount) . ' OFF' }}</span>
                            @endif
                        </div>
                        <div class="s14-card-body">
                            <h3 class="s14-card-name">{{$item->name}}</h3>
                            <p class="s14-card-desc">{{$item->description}}</p>
                            <div class="s14-card-footer">
                                <div class="s14-price-group">
                                    <span class="s14-card-price">{{ \App\CentralLogics\Helpers::format_currency($discountedPrice) }}</span>
                                    @if($discount > 0 && $discountedPrice < $item->price)
                                        <span class="s14-card-mrp"><s>{{ \App\CentralLogics\Helpers::format_currency($item->price) }}</s></span>
                                    @endif
                                </div>
                                <button class="s14-add-btn"><i class="bi bi-bag-plus-fill"></i></button>
                            </div>
                        </div>
                    </div>
                    @php $globalIndex++; @endphp
                    @endforeach
                </div>
            </section>
            @endforeach
            <!-- Extras (no image items) -->
            {{-- <section class="s14-menu-section" data-category="extras">
                <div class="s14-section-header">
                    <h2><i class="bi bi-plus-circle-fill"></i> Extras</h2>
                    <span class="s14-item-count">6 items</span>
                </div>
                <div class="s14-noimg-list">
                    <div class="s14-noimg-item" data-index="19">
                        <div class="s14-noimg-badge veg"></div>
                        <div class="s14-noimg-info">
                            <span class="s14-noimg-name">Papad (Roasted)</span>
                            <span class="s14-noimg-desc">Crispy roasted papadum</span>
                        </div>
                        <div class="s14-noimg-right">
                            <span class="s14-noimg-price">&#8377;29</span>
                            <button class="s14-noimg-add"><i class="bi bi-plus"></i></button>
                        </div>
                    </div>
                    <div class="s14-noimg-item" data-index="20">
                        <div class="s14-noimg-badge veg"></div>
                        <div class="s14-noimg-info">
                            <span class="s14-noimg-name">Green Salad</span>
                            <span class="s14-noimg-desc">Fresh seasonal vegetables</span>
                        </div>
                        <div class="s14-noimg-right">
                            <span class="s14-noimg-price">&#8377;49</span>
                            <button class="s14-noimg-add"><i class="bi bi-plus"></i></button>
                        </div>
                    </div>
                    <div class="s14-noimg-item" data-index="21">
                        <div class="s14-noimg-badge veg"></div>
                        <div class="s14-noimg-info">
                            <span class="s14-noimg-name">Raita</span>
                            <span class="s14-noimg-desc">Yogurt with cucumber & mint</span>
                        </div>
                        <div class="s14-noimg-right">
                            <span class="s14-noimg-price">&#8377;39</span>
                            <button class="s14-noimg-add"><i class="bi bi-plus"></i></button>
                        </div>
                    </div>
                    <div class="s14-noimg-item" data-index="22">
                        <div class="s14-noimg-badge veg"></div>
                        <div class="s14-noimg-info">
                            <span class="s14-noimg-name">Pickle (Achar)</span>
                            <span class="s14-noimg-desc">Homestyle mango pickle</span>
                        </div>
                        <div class="s14-noimg-right">
                            <span class="s14-noimg-price">&#8377;19</span>
                            <button class="s14-noimg-add"><i class="bi bi-plus"></i></button>
                        </div>
                    </div>
                    <div class="s14-noimg-item" data-index="23">
                        <div class="s14-noimg-badge non-veg"></div>
                        <div class="s14-noimg-info">
                            <span class="s14-noimg-name">Chicken Gravy (Extra)</span>
                            <span class="s14-noimg-desc">Extra portion of chicken gravy</span>
                        </div>
                        <div class="s14-noimg-right">
                            <span class="s14-noimg-price">&#8377;89</span>
                            <button class="s14-noimg-add"><i class="bi bi-plus"></i></button>
                        </div>
                    </div>
                    <div class="s14-noimg-item" data-index="24">
                        <div class="s14-noimg-badge veg"></div>
                        <div class="s14-noimg-info">
                            <span class="s14-noimg-name">Butter (Extra)</span>
                            <span class="s14-noimg-desc">Extra butter on the side</span>
                        </div>
                        <div class="s14-noimg-right">
                            <span class="s14-noimg-price">&#8377;15</span>
                            <button class="s14-noimg-add"><i class="bi bi-plus"></i></button>
                        </div>
                    </div>
                </div>
            </section> --}}
        </main>
    </div>

    <!-- ===== CART TAB ===== -->
    <div class="s14-tab-content" id="s14-tab-cart">
        <div class="s14-cart-page">
            <div class="s14-page-header">
                <h2><i class="bi bi-bag-fill"></i> Your Cart</h2>
            </div>
            <div class="s14-cart-items-container"></div>
            <div class="s14-cart-summary-section" style="display: none;">
                <div class="s14-cart-summary">
                    <div class="s14-summary-row">
                        <span>Subtotal</span>
                        <span class="s14-subtotal">&#8377;0</span>
                    </div>
                    <div class="s14-summary-row s14-discount-row" style="display:none;">
                        <span>Discount</span>
                        <span class="s14-discount-amount" style="color:#16a34a;font-weight:600;">-&#8377;0</span>
                    </div>
                    <div class="s14-summary-row s14-delivery-row" style="{{ ($store->delivery_charg ?? 0) > 0 && $store->order_type != 3 ? '' : 'display:none;' }}">
                        <span>Delivery Fee</span>
                        <span class="s14-delivery-fee-amount">&#8377;{{ $store->delivery_charg ?? 0 }}</span>
                    </div>
                    <div class="s14-summary-row total">
                        <span>Total</span>
                        <span class="s14-total">&#8377;0</span>
                    </div>
                </div>
                <div class="s14-checkout-buttons">
                    @if($store->order_type == 3)
                    <button class="s14-checkout-btn order-type-btn active" data-type="dine-in"><i class="bi bi-shop"></i> Dine-In</button>
                    <button class="s14-delivery-btn order-type-btn" data-type="delivery"><i class="bi bi-truck"></i> Delivery</button>
                    @elseif($store->order_type == 2)
                    <button class="s14-delivery-btn"><i class="bi bi-truck"></i> Delivery</button>
                    @else
                    <button class="s14-checkout-btn"><i class="bi bi-shop"></i> Dine-In</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ===== ORDER TAB ===== -->
    <div class="s14-tab-content" id="s14-tab-order">
        <div class="s14-orders-page">
            <div class="s14-page-header">
                <h2><i class="bi bi-receipt"></i> Your Orders</h2>
            </div>
            <div class="s14-orders-list"></div>
        </div>
    </div>

    <!-- ===== REORDER TAB ===== -->
    <div class="s14-tab-content" id="s14-tab-reorder">
        <div class="s14-orders-page">
            <div class="s14-page-header">
                <h2><i class="bi bi-arrow-repeat"></i> Reorder</h2>
            </div>
            <div class="s14-reorder-list"></div>
            <div class="s14-reorder-empty" style="display:none;">
                <div class="s14-cart-empty">
                    <i class="bi bi-clock-history"></i>
                    <h3>No previous orders</h3>
                    <p>Items you've ordered before will appear here</p>
                    <button class="s14-browse-btn" onclick="document.querySelector('.s14-nav-item[data-tab=home]').click()">
                        <i class="bi bi-arrow-left"></i> Browse Menu
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== TODAY SPECIAL TAB ===== -->
    <div class="s14-tab-content" id="s14-tab-special">
        <div class="s14-special-page">
            <div class="s14-page-header">
                <h2><i class="bi bi-stars"></i> Today's Special</h2>
            </div>
            <div class="s14-special-list">
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
                    <div class="s14-special-card" data-special="{{ $specialIndex }}">
                        <div class="s14-special-img">
                            <img src="{{$spItem->image_full_url}}" alt="{{$spItem->name}}">
                            @if($spDiscount > 0 && $spDiscountedPrice < $spItem->price)
                                <div class="s14-special-badge">{{ $spDiscountType === 'percent' ? $spDiscount . '% OFF' : \App\CentralLogics\Helpers::format_currency($spDiscount) . ' OFF' }}</div>
                            @else
                                <div class="s14-special-badge">Special</div>
                            @endif
                        </div>
                        <div class="s14-special-info">
                            <div class="s14-special-title-row">
                                <h3>{{$spItem->name}}</h3>
                                <span class="s14-type-tag {{ $spItem->veg == 1 ? 'veg' : 'non-veg' }}"><span class="s14-type-dot"></span> {{ $spItem->veg == 1 ? 'Veg' : 'Non-Veg' }}</span>
                            </div>
                            <p>{{$spItem->description}}</p>
                            <div class="s14-special-bottom">
                                <div class="s14-special-pricing">
                                    @if($spDiscount > 0 && $spDiscountedPrice < $spItem->price)
                                        <span class="s14-special-mrp"><s>{{ \App\CentralLogics\Helpers::format_currency($spItem->price) }}</s></span>
                                    @endif
                                    <span class="s14-special-price">{{ \App\CentralLogics\Helpers::format_currency($spDiscountedPrice) }}</span>
                                    @if($spDiscount > 0 && $spDiscountType === 'percent')
                                        <span class="s14-special-off">{{ $spDiscount }}% OFF</span>
                                    @endif
                                </div>
                                <button class="s14-special-add-btn"><i class="bi bi-plus"></i> Add</button>
                            </div>
                            <span class="s14-special-timer"><i class="bi bi-clock"></i> Today only</span>
                        </div>
                    </div>
                    @php $specialIndex++; @endphp
                    @endif
                @empty
                    <div class="text-center" style="padding: 40px 20px; color: var(--s14-text2, #888);">
                        <i class="bi bi-stars" style="font-size: 2.5rem; opacity: 0.4;"></i>
                        <p style="margin-top: 10px;">No specials today. Check back tomorrow!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ===== ITEM DETAIL POPUP ===== -->
    <div class="s14-detail-overlay"></div>
    <div class="s14-detail-popup">
        <div class="s14-detail-img-section">
            <div class="s14-detail-slider">
                <div class="s14-detail-slider-track"></div>
                <div class="s14-detail-slider-dots"></div>
                <button class="s14-detail-slider-nav prev"><i class="bi bi-chevron-left"></i></button>
                <button class="s14-detail-slider-nav next"><i class="bi bi-chevron-right"></i></button>
            </div>
            <div class="s14-detail-badge"></div>
            <button class="s14-detail-close"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="s14-detail-body">
            <h2 class="s14-detail-name"></h2>
            <p class="s14-detail-desc"></p>
            <div class="s14-detail-price-row">
                <span class="s14-detail-mrp"></span>
                <span class="s14-detail-price"></span>
                <span class="s14-detail-discount"></span>
            </div>
            <!-- Size Options -->
            <div class="s14-size-options">
                <span class="s14-size-label">Choose Size</span>
                <div class="s14-size-group">
                    <button class="s14-size-btn" data-size="quarter">
                        <span class="s14-size-name">Quarter</span>
                        <span class="s14-size-price"></span>
                    </button>
                    <button class="s14-size-btn" data-size="half">
                        <span class="s14-size-name">Half</span>
                        <span class="s14-size-price"></span>
                    </button>
                    <button class="s14-size-btn active" data-size="full">
                        <span class="s14-size-name">Full</span>
                        <span class="s14-size-price"></span>
                    </button>
                </div>
            </div>
        </div>
        <div class="s14-detail-footer">
            <div class="s14-detail-qty">
                <button class="s14-detail-minus"><i class="bi bi-dash"></i></button>
                <span class="s14-detail-qty-val">1</span>
                <button class="s14-detail-plus"><i class="bi bi-plus"></i></button>
            </div>
            <button class="s14-detail-add">
                <span>Add to Cart</span>
                <span class="s14-detail-total">&#8377;0</span>
            </button>
        </div>
    </div>

    <!-- ===== SIZE PICKER POPUP (for quick add from card) ===== -->
    <div class="s14-sizepicker-overlay"></div>
    <div class="s14-sizepicker-popup">
        <div class="s14-sizepicker-header">
            <h3 class="s14-sizepicker-title"><i class="bi bi-sliders"></i> Select Size</h3>
            <button class="s14-sizepicker-close"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="s14-sizepicker-options">
            <button class="s14-sizepicker-btn active" data-size="quarter">
                <span class="s14-sizepicker-radio"></span>
                <span class="s14-sizepicker-name">Quarter</span>
                <span class="s14-sizepicker-price"></span>
            </button>
            <button class="s14-sizepicker-btn" data-size="half">
                <span class="s14-sizepicker-radio"></span>
                <span class="s14-sizepicker-name">Half</span>
                <span class="s14-sizepicker-price"></span>
            </button>
            <button class="s14-sizepicker-btn" data-size="full">
                <span class="s14-sizepicker-radio"></span>
                <span class="s14-sizepicker-name">Full</span>
                <span class="s14-sizepicker-price"></span>
            </button>
        </div>
        <div class="s14-sizepicker-footer">
            <div class="s14-sizepicker-qty">
                <button class="s14-sizepicker-minus"><i class="bi bi-dash"></i></button>
                <span class="s14-sizepicker-qty-val">1</span>
                <button class="s14-sizepicker-plus"><i class="bi bi-plus"></i></button>
            </div>
            <button class="s14-sizepicker-add">
                <span>Add to Cart</span>
                <span class="s14-sizepicker-total">&#8377;0</span>
            </button>
        </div>
    </div>
    
    <!-- ===== IMAGE BANNER POPUP (type 1, hidden by default, shows once per tab) ===== -->
    @if($store->banner_popup_type == 1 && !empty($store->banner_popup))
    <div class="s14-welcome-overlay" id="s14-imgbanner-overlay">
        <div class="s14-imgbanner" style="position:relative;">
            <button class="s14-welcome-close"><i class="bi bi-x-lg"></i></button>
            <img class="s14-imgbanner-img" src="{{$store->banner_popup_full_url}}" alt="Offer">
        </div>
    </div>
    @endif

    <!-- ===== WELCOME BANNER POPUP WITH TEXT (type 2, hidden by default) ===== -->
    @if($store->banner_popup_type == 2 && isset($store->text_banner_popup))
    <div class="s14-welcome-overlay" id="s14-welcome-overlay">
        <div class="s14-welcome-popup" style="position:relative;">
            <button class="s14-welcome-close"><i class="bi bi-x-lg"></i></button>
            @if(isset($store->text_banner_popup['image']) && !empty($store->text_banner_popup['image']))<img class="s14-welcome-img" src="{{ $store->text_banner_image_full_url}}" alt="Welcome">@endif
            <div class="s14-welcome-body">
                @if(isset($store->text_banner_popup['heading']) && !empty($store->text_banner_popup['heading']))<span class="s14-welcome-tag">{{ $store->text_banner_popup['heading'] ?? '' }}</span>@endif
                @if(isset($store->text_banner_popup['title']) && !empty($store->text_banner_popup['title']))<h2>{{ $store->text_banner_popup['title'] ?? '' }}</h2>@endif
                @if(isset($store->text_banner_popup['description']) && !empty($store->text_banner_popup['description']))<p>{{ $store->text_banner_popup['description'] ?? '' }}</p>@endif
                @if(isset($store->text_banner_popup['label']) && !empty($store->text_banner_popup['label']))<div class="s14-welcome-code">{{ $store->text_banner_popup['label'] ?? '' }}</div>@endif
                @if(isset($store->text_banner_popup['button']) && !empty($store->text_banner_popup['button']))<button class="s14-welcome-btn">{{ $store->text_banner_popup['button'] ?? '' }}</button>@endif
            </div>
        </div>
    </div>
    @endif
    <!-- ===== BOTTOM NAVIGATION ===== -->
    <nav class="s14-bottom-nav">
        <div class="s14-nav-pill-bg"></div>
        <button class="s14-nav-item active" data-tab="home">
            <div class="s14-nav-icon-wrap">
                <i class="bi bi-house-door"></i>
                <i class="bi bi-house-door-fill s14-nav-icon-active"></i>
            </div>
            <span>{{$store->menu_buttom_home ?? 'Home'}}</span>
        </button>
        <button class="s14-nav-item" data-tab="special">
            <div class="s14-nav-icon-wrap">
                <i class="bi bi-stars"></i>
                <i class="bi bi-stars s14-nav-icon-active"></i>
            </div>
            <span>{{$store->menu_buttom_special ?? 'Specials'}}</span>
        </button>
        <button class="s14-nav-item" data-tab="cart">
            <div class="s14-nav-icon-wrap">
                <i class="bi bi-bag"></i>
                <i class="bi bi-bag-fill s14-nav-icon-active"></i>
                <span class="s14-cart-badge" style="display: none;">0</span>
            </div>
            <span>{{$store->menu_buttom_cart ?? 'Cart'}}</span>
        </button>
        <button class="s14-nav-item" data-tab="reorder">
            <div class="s14-nav-icon-wrap">
                <i class="bi bi-arrow-repeat"></i>
                <i class="bi bi-arrow-repeat s14-nav-icon-active"></i>
            </div>
            <span>{{$store->menu_buttom_reorder ?? 'Reorder'}}</span>
        </button>
        <button class="s14-nav-item" data-tab="order">
            <div class="s14-nav-icon-wrap">
                <i class="bi bi-receipt"></i>
                <i class="bi bi-receipt-cutoff s14-nav-icon-active"></i>
            </div>
            <span>{{$store->menu_buttom_orders ?? 'Orders'}}</span>
        </button>
    </nav>

    @include('menu-templates.partials.scroll-top')

@endsection

@section('scripts')
<script src="{{ asset('public/assets/menu-templates/js/menu_style13.js') }}"></script>
<script>
        window.s14StoreId = {{ $store->id }};
        window.storePhone = @json($store->phone ?? '');
        window.trackingPhone = @json($store->tracking_order_mobile_no ?? $store->phone);
        window.storeDeliveryCharge = {{ $store->delivery_charg ?? 0 }};
        window.storeOrderType = {{ $store->order_type ?? 0 }};
        window.poweredBy = @json(\App\CentralLogics\Helpers::get_business_settings('business_name'));
        window.s14SpecialItems = [
            @foreach(($todaySpecials ?? collect()) as $special)
                @if($special->item)
                @php
                    $spIt = $special->item;
                    $spD = $spIt->discount ?? 0;
                    $spDT = $spIt->discount_type ?? 'percent';
                    if ($spD > 0) {
                        $spDP = $spDT === 'percent'
                            ? round($spIt->price - ($spIt->price * $spD / 100))
                            : max(0, $spIt->price - $spD);
                    } else {
                        $spDP = $spIt->price;
                    }
                @endphp
                {
                    id: {{ $spIt->id }},
                    name: @json($spIt->name),
                    price: {{ $spDP }},
                    mrp: {{ $spIt->price }},
                    isVeg: {{ $spIt->veg == 1 ? 'true' : 'false' }},
                    img: "{{ $spIt->image_full_url }}",
                    desc: @json($spIt->description ?? '')
                },
                @endif
            @endforeach
        ];
        window.s14MenuItems = [
            @php $globalIndex = 0; @endphp
            @foreach($categories as $category)
                @foreach($category->items as $item)
                    @php
                        $disc = $item->discount ?? 0;
                        $discType = $item->discount_type ?? 'percent';
                        if ($disc > 0) {
                            $discPrice = $discType === 'percent'
                                ? round($item->price - ($item->price * $disc / 100))
                                : max(0, $item->price - $disc);
                        } else {
                            $discPrice = $item->price;
                        }
                    @endphp
                    {
                        id: {{ $item->id }},
                        name: @json($item->name),
                        price: {{ $discPrice }},
                        mrp: {{ $item->price }},
                        isVeg: {{ $item->veg == 1 ? 'true' : 'false' }},
                        category: @json($category->slug),
                        img: "{{ $item->image_full_url }}",
                        desc: @json($item->description ?? ''),
                        discount: {{ $disc }},
                        discountType: @json($discType),
                        foodVariations: @json($item->food_variations ? json_decode($item->food_variations, true) : []),
                        tags: @json($item->tags ? $item->tags->pluck('tag')->join(',') : '')
                    },
                    @php $globalIndex++; @endphp
                @endforeach
            @endforeach
        ];
    </script>
@endsection
