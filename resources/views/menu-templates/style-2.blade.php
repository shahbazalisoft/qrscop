<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$store->name}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/menu_style2.css') }}">
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
    <!-- Header -->
    <header class="header">
        <div class="logo">
            <img src="{{asset('storage/app/public/store')}}/{{$store->alternative_logo ?? $store->logo}}" alt="{{$store->name}}" class="logo-img">
            <div class="logo-text">
                <h1>{{$store->name}}</h1>
                <span>{{$store->restaurant_title}}</span>
            </div>
        </div>
        <button class="search-btn">
            <i class="bi bi-search"></i>
        </button>
    </header>

    <!-- Banners Section -->
    @if($banners->count() > 0)
    <div class="banners-scroll">
        @foreach ($banners as $banner)
        <div class="banner-img">
            <img src="{{$banner['image_full_url']}}" alt="Special Offer">
            <div class="banner-overlay">
                @if($banner->title_one)<span class="banner-tag">{{$banner->title_one}}</span>@endif
                @if($banner->title_two)<p class="banner-label">{{$banner->title_two}}</p>@endif
            </div>
        </div>
        @endforeach
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

    <!-- Main Content -->
    <main class="main-content">
        <!-- Category Navigation -->
        @if($categories->count() > 0)
        <nav class="category-nav">
            @foreach ($categories as $menu)
                <button class="cat-btn {{ $loop->first ? 'active' : '' }}" data-category="{{$menu->name}}">{{$menu->name}}</button>
            @endforeach
            <button class="cat-btn" id="viewall-btn" data-category="view-all">View All</button>
        </nav>

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
        <!-- Items Section -->
        @foreach($categories as $category)
            @if($category->items->count() > 0)
            <section class="menu-section" id="{{ $category->name }}">
                <h2 class="section-title">{{ $category->name }}</h2>
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
                <div class="menu-item {{ $item->is_special ? 'special' : '' }}" data-index="{{ $globalIndex }}" data-item-id="{{ $item->id }}">
                    <div class="item-details">
                        <div class="item-header">
                            <span class="{{ $item->veg ? 'veg-badge' : 'non-veg-badge' }}"></span>
                            <h3 class="item-name">{{$item->name}}</h3>
                            @if($discount > 0)
                                <span class="discount-tag">{{ $discount }}{{ $discountType === 'percent' ? '%' : '₹' }} OFF</span>
                            @endif
                        </div>
                        @if($item->description)<p class="item-desc">{{$item->description}}</p>@endif
                    </div>
                    <div class="item-actions">
                        <div class="item-price">
                            <div class="price-row">
                                <span class="price">{{ \App\CentralLogics\Helpers::format_currency($discountedPrice) }}</span>
                                @if($discount > 0)
                                    <span class="original-price">{{ \App\CentralLogics\Helpers::format_currency($item->price) }}</span>
                                @endif
                            </div>
                        </div>
                        <button class="add-btn">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </div>
                @php $globalIndex++; @endphp
                @endforeach
            </section>
            @endif
        @endforeach
    </main>

    @include('menu-templates.partials.cart', ['store' => $store])

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('public/assets/menu-templates/js/cart-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/item-detail-common.js') }}"></script>
    @include('menu-templates.partials.menu-scripts', ['store' => $store, 'categories' => $categories])
    <script src="{{ asset('public/assets/menu-templates/js/sizepicker-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/menu-init-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/category-popup-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/menu_style2.js') }}"></script>
@include('menu-templates.partials.scroll-top')
</body>
</html>
