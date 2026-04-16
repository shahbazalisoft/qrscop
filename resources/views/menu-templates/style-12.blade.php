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

    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/menu-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/cart-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/item-detail-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/sizepicker-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/desktop-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/menu_style12.css') }}">
</head>
<body class="style20-body">
    @if($store->banner_popup_type == 1 || $store->banner_popup_type == 2)
    @include('menu-templates.partials.banner-popup', ['store' => $store])
    @endif
    <!-- Header -->
    <header class="s20-header">
        <div class="s20-header-content">
            <div class="s20-logo">
                <!-- Icon Logo (commented for future use)
                <span class="s20-logo-icon"><i class="bi bi-fire"></i></span>
                -->
                <!-- Image Logo - size: 45px height, auto width -->
                <img src="{{\App\CentralLogics\Helpers::get_full_url('store', $store->alternative_logo ?? $store->logo, 'public')}}" alt="{{$store->name}}" class="logo-img" style="height: 45px; width: auto; object-fit: contain;">
                <div class="s20-logo-text">
                    <h1>{{$store->name}}</h1>
                    <span>{{$store->restaurant_title}}</span>
                </div>
            </div>
            <button class="s20-search-btn"><i class="bi bi-search"></i></button>
        </div>
    </header>

    <!-- Banner Carousel -->
    @if($banners->count() > 0)
    <div class="s20-banner-section">
        <div class="s20-banner-carousel">
            <div class="s20-banner-track">
                @foreach ($banners as $banner)
                <div class="s20-banner-slide">
                    <img src="{{$banner['image_full_url']}}" alt="{{$banner->title_one}}">
                    <div class="s20-banner-overlay">
                        @if($banner->title_one)<span class="s20-banner-tag">{{$banner->title_one}}</span>@endif
                        @if($banner->title_two)<h3>{{$banner->title_two}}</h3>@endif
                        {{-- @if($banner->title_one)<p>Use code: FIRST50</p>@endif --}}
                    </div>
                </div>
                @endforeach
            </div>
            <div class="s20-banner-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
            {{-- <button class="s20-banner-nav prev"><i class="bi bi-chevron-left"></i></button>
            <button class="s20-banner-nav next"><i class="bi bi-chevron-right"></i></button> --}}
        </div>
    </div>
    @endif
    <!-- Search Bar Section (appears after clicking search button) -->
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

    <!-- Order Again Section -->
    <div class="s20-order-again-section" id="s20-order-again" style="display:none;">
        <div class="s20-oa-head">
            <h2 class="s20-oa-title"><i class="bi bi-arrow-repeat"></i> Order Again</h2>
            <button class="s20-oa-viewall-btn" id="s20-oa-viewall" style="display:none;"><i class="bi bi-grid"></i></button>
        </div>
        <div class="s20-order-again-scroll"></div>
        <div class="s20-order-again-grid" id="s20-oa-grid" style="display:none;"></div>
    </div>

    <!-- Category Scroll with Images -->
    @if($categories->count() > 0)
    <nav class="s20-category-nav">
        <div class="s20-category-header">
            <h2 class="s20-category-title">What are you craving?</h2>
            <button class="s20-viewall-btn" id="s20-viewall-btn">View All <i class="bi bi-chevron-right"></i></button>
        </div>
        <div class="s20-category-scroll">
            <button class="s20-cat-item active" data-category="all">
                <div class="s20-cat-img all-img">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                    <div class="s20-cat-check"><i class="bi bi-check-lg"></i></div>
                </div>
                <span class="s20-cat-name">All</span>
            </button>
            @foreach ($categories as $menu)
                @if($menu->items->count() > 0)
                <button class="s20-cat-item" data-category="{{$menu->slug}}">
                    <div class="s20-cat-img">
                        <img src="{{$menu->image_full_url}}" alt="{{$menu->name}}">
                        <div class="s20-cat-check"><i class="bi bi-check-lg"></i></div>
                    </div>
                    <span class="s20-cat-name">{{$menu->name}}</span>
                </button>
                @endif
            @endforeach
        </div>
    </nav>

    <!-- Category Popup -->
    <div class="s20-cat-popup-overlay" id="s20-cat-popup-overlay"></div>
    <div class="s20-cat-popup" id="s20-cat-popup">
        <div class="s20-cat-popup-header">
            <h3>Menu Categories</h3>
            <button class="s20-cat-popup-close" id="s20-cat-popup-close"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="s20-cat-popup-list">
            @foreach ($categories as $menu)
                @if($menu->items->count() > 0)
                <button class="s20-cat-popup-item" data-category="{{$menu->slug}}">
                    <img src="{{$menu->image_full_url}}" alt="{{$menu->name}}">
                    <span>{{$menu->name}}</span>
                </button>
                @endif
            @endforeach
        </div>
    </div>
    @endif
    <!-- Main Content -->
    <main class="s20-main-content">
        <!-- Items Section -->
        @foreach($categories as $category)
                @if($category->items->count() > 0)
        <section class="s20-menu-section" id="{{ $category->slug }}" data-category="{{ $category->slug }}">
            <div class="s20-section-header">
                <h2><i class="bi bi-star-fill"></i> {{ $category->name }}</h2>
                <span class="s20-item-count">{{$category->items->count()}} Items</span>
            </div>
            <div class="s20-menu-grid">
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
                <div class="s20-menu-card" data-category="{{ $category->slug }}" data-index="{{ $globalIndex }}" data-item-id="{{ $item->id }}">
                    <div class="s20-card-image">
                        <img src="{{\App\CentralLogics\Helpers::get_full_url('product', $item['image'], 'public')}}" alt="{{$item->name}}">
                        <div class="s20-item-type {{ $item->veg ? 'veg' : 'non-veg' }}"></div>
                        @if($discount > 0)
                            <span class="s20-discount-badge">{{ $discount }}{{ $discountType === 'percent' ? '%' : '₹' }} OFF</span>
                        @endif
                    </div>
                    <div class="s20-card-content">
                        <h3 class="s20-card-title">{{$item->name}}</h3>
                        <p class="s20-card-desc">{{$item->description}}</p>
                        <div class="s20-card-bottom">
                            <div class="s20-card-pricing">
                                <span class="s20-card-price">{{ \App\CentralLogics\Helpers::format_currency($discountedPrice) }}</span>
                                @if($discount > 0)
                                    <span class="s20-mrp"><s>{{ \App\CentralLogics\Helpers::format_currency($item->price) }}</s></span>
                                @endif
                            </div>
                            <button class="s20-add-cart-btn"><i class="bi bi-bag-plus-fill"></i></button>
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

    @include('menu-templates.partials.cart', ['store' => $store])

    <!-- Search Overlay -->
    <div class="s20-search-overlay">
        <div class="s20-search-container">
            <div class="s20-search-header">
                <div class="s20-search-input-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text" class="s20-search-input" placeholder="Search for dishes...">
                    <button class="s20-search-clear-btn"><i class="bi bi-x"></i></button>
                </div>
                <button class="s20-search-close-btn">Cancel</button>
            </div>
            <div class="s20-search-results">
                <div class="s20-search-placeholder">
                    <i class="bi bi-search"></i>
                    <p>Search for your favorite dishes</p>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('public/assets/menu-templates/js/cart-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/item-detail-common.js') }}"></script>
    @include('menu-templates.partials.menu-scripts', ['store' => $store, 'categories' => $categories])
    <script src="{{ asset('public/assets/menu-templates/js/sizepicker-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/menu-init-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/menu_style12.js') }}"></script>

@include('menu-templates.partials.scroll-top')
</body>
</html>
