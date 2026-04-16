<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$store->name}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Common Menu CSS (load first) -->
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/menu-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/menu_style4.css') }}">
    <!-- Common Cart CSS -->
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/cart-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/item-detail-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/sizepicker-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/desktop-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/category-popup-common.css') }}">
</head>
<body>
    @if($store->banner_popup_type == 1 || $store->banner_popup_type == 2)
    @include('menu-templates.partials.banner-popup', ['store' => $store])
    @endif
    <!-- Sticky Header (appears on scroll) -->
    <header class="sticky-header">
        <div class="sticky-header-content">
            <div class="sticky-logo">
                <img src="{{\App\CentralLogics\Helpers::get_full_url('store', $store->alternative_logo ?? $store->logo, 'public')}}" alt="{{$store->name}}" class="sticky-logo-img">
                <div class="sticky-logo-text">
                    <h1>{{$store->name}}</h1>
                    <span>{{$store->restaurant_title}}</span>
                </div>
            </div>
            <button class="sticky-search-btn"><i class="bi bi-search"></i></button>
        </div>
    </header>

    <!-- Hero Section -->
    
    <header class="hero-section">
        @if($banners->count() > 0)
        <div class="hero-slider">
            @foreach ($banners as $banner)
                <img src="{{$banner['image_full_url']}}" alt="Cafe Background" class="hero-bg {{ $loop->first ? 'active' : '' }}">
            @endforeach
        </div>
        @endif
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <img src="{{\App\CentralLogics\Helpers::get_full_url('store', $store->alternative_logo ?? $store->logo, 'public')}}" alt="Logo" class="hero-logo-img">
            <h1 class="cafe-name">{{$store->name}}</h1>
            <p class="cafe-location">{{$store->address}}</p>
        </div>
    </header>

    <!-- Category Section -->
    @if($categories->count() > 0)
    <div class="category-sticky-wrapper">
        <div class="category-title-bar">
            <h2 class="category-title">Menu</h2>
            <button class="viewall-title-btn" id="viewall-btn" data-category="view-all">View All <i class="bi bi-grid"></i></button>
        </div>
        <nav class="category-tabs">
             @foreach ($categories as $menu)
                @if($menu->items->count() > 0)
                <button class="tab-btn {{ $loop->first ? 'active' : '' }}" data-category="{{$menu->name}}">{{$menu->name}}</button>
                @endif
            @endforeach
        </nav>
    </div>

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
                <button class="cat-popup-item" data-category="{{$menu->name}}">
                    <img src="{{$menu->image_full_url}}" alt="{{$menu->name}}">
                    <span>{{$menu->name}}</span>
                </button>
                @endif
            @endforeach
        </div>
    </div>
    @endif
    <!-- Search Bar Section (Hidden by default, shows on search button click) -->
    <div class="search-bar-section" style="display: none;">
        <div class="search-bar-container">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" class="search-field" placeholder="Search dishes...">
                <button class="clear-search-btn" style="display: none;"><i class="bi bi-x"></i></button>
            </div>
            <div class="filter-dropdown">
                <button class="filter-btn">
                    <span class="filter-icon all-icon"></span>
                    <span class="filter-text">All</span>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="filter-menu">
                    <div class="filter-option active" data-filter="all">
                        <span class="filter-icon all-icon"></span>
                        <span>All</span>
                    </div>
                    <div class="filter-option" data-filter="veg">
                        <span class="filter-icon veg-icon"></span>
                        <span>Veg</span>
                    </div>
                    <div class="filter-option" data-filter="non-veg">
                        <span class="filter-icon non-veg-icon"></span>
                        <span>Non-Veg</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Again Section (previously ordered items) -->
    <div class="s4-order-again-section" id="s4-order-again" style="display:none;">
        <div class="s4-oa-head">
            <h2 class="s4-oa-title"><i class="bi bi-arrow-repeat"></i> Order Again</h2>
            <button class="viewall-title-btn s4-oa-viewall-btn" id="s4-oa-viewall" style="display:none;"><i class="bi bi-grid"></i></button>
        </div>
        <div class="s4-order-again-scroll"></div>
        <div class="s4-order-again-grid" id="s4-oa-grid" style="display:none;"></div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Items Section -->
        @foreach($categories as $category)
        @if($category->items->count() > 0)
        <section class="menu-section" id="{{ $category->name }}">
            <div class="section-header">
                <h2 class="section-title">{{ $category->name }}</h2>
                {{-- <p class="section-subtitle">Chef's special picks for you</p> --}}
            </div>
            @php $globalIndex = $globalIndex ?? 0; @endphp
            @forelse($category->items as $item)
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
            <div class="menu-item" data-index="{{ $globalIndex }}" data-item-id="{{ $item->id }}">
                <div class="item-info">
                    <h3 class="item-name">{{$item->name}}</h3>
                    <p class="item-desc">{{$item->description}}</p>
                    <div class="price-row">
                        <div class="price-tag">{{ \App\CentralLogics\Helpers::format_currency($discountedPrice) }}</div>
                        @if($discount > 0)
                            <span class="original-price">{{ \App\CentralLogics\Helpers::format_currency($item->price) }}</span>
                            <span class="discount-tag">{{ $discount }}{{ $discountType === 'percent' ? '%' : '₹' }} OFF</span>
                        @endif
                    </div>
                </div>
                <div class="item-image">
                    <img src="{{\App\CentralLogics\Helpers::get_full_url('product', $item['image'], 'public')}}" alt="{{$item->name}}">
                    <button class="add-btn"><i class="bi bi-plus"></i></button>
                </div>
            </div>
            @php $globalIndex++; @endphp
            @endforeach
        </section>
        @endif
        @endforeach
    </main>

    @include('menu-templates.partials.cart', ['store' => $store])
    <script src="{{ asset('public/assets/menu-templates/js/menu-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/cart-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/item-detail-common.js') }}"></script>
    @include('menu-templates.partials.menu-scripts', ['store' => $store, 'categories' => $categories])
    <script src="{{ asset('public/assets/menu-templates/js/sizepicker-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/menu-init-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/category-popup-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/menu_style4.js') }}"></script>
@include('menu-templates.partials.scroll-top')
</body>
</html>
