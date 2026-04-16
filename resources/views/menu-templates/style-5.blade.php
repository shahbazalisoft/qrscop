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
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/menu_style5.css') }}">
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
        <div class="header-content">
            <div class="logo">
                <img src="{{asset('storage/app/public/store')}}/{{$store->alternative_logo ?? $store->logo}}" alt="{{$store->name}}" class="logo-img">
                <div class="logo-text">
                    <h1>{{$store->name}}</h1>
                    <span>{{$store->restaurant_title}}</span>
                </div>
            </div>
            <button class="search-btn"><i class="bi bi-search"></i></button>
        </div>
    </header>

    <!-- Banner -->
    @if($banners->count() > 0)
    <div class="banner-section">
        <img src="{{$banners[0]['image_full_url']}}" alt="Special Offer">
    </div>
    @endif
    <!-- Search Bar Section (Hidden by default) -->
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

    <!-- Category Pills -->
    @if($categories->count() > 0)
    <div class="category-sticky-wrapper">
        <div class="category-title-bar">
            <h2 class="category-title">Menu</h2>
            <button class="viewall-title-btn" id="viewall-btn" data-category="view-all">View All <i class="bi bi-grid"></i></button>
        </div>
        <nav class="category-nav">
            <div class="category-pills">
                <button class="pill active" data-category="all">All</button>
                <button class="pill" data-category="{{$categories[0]->name}}">{{$categories[0]->name}}</button>

                <div class="more-menu">
                    <button class="more-btn"><i class="bi bi-grid"></i> More</button>
                    <div class="more-dropdown">
                        @foreach ($categories as $menu)
                            @if($menu->items->count() > 0 && !$loop->first)
                            <button class="dropdown-item" data-category="{{$menu->name}}"><i class="{{ $menu->icon ?? 'bi bi-dot' }}"></i> {{$menu->name}}</button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
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
    <!-- Main Content -->
    <main class="main-content">
        <!-- Items Section -->
        @foreach($categories as $category)
        @if($category->items->count() > 0)
        <section class="menu-section" id="{{ $category->name }}">
            <div class="section-header">
                <h2><i class="bi bi-star-fill"></i> {{ $category->name }}</h2>
                <span class="item-count">{{$category->items->count()}} Items</span>
            </div>
            <div class="menu-list">
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
                    <div class="item-left-col">
                        <div class="item-badge {{ $item->veg ? 'veg' : 'non-veg' }}"></div>
                        @if($discount > 0)
                            <span class="discount-tag">{{ $discount }}{{ $discountType === 'percent' ? '%' : '₹' }} OFF</span>
                        @endif
                    </div>
                    <div class="item-info">
                        <h3 class="item-name">{{$item->name}}</h3>
                        <p class="item-desc">{{$item->description}}</p>
                    </div>
                    <div class="item-right">
                        <div class="price-row">
                            @if($discount > 0)
                                <span class="original-price">{{ \App\CentralLogics\Helpers::format_currency($item->price) }}</span>
                            @endif
                            <span class="item-price">{{ \App\CentralLogics\Helpers::format_currency($discountedPrice) }}</span>
                        </div>
                        <button class="add-btn"><i class="bi bi-plus"></i></button>
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
    
    <script src="{{ asset('public/assets/menu-templates/js/cart-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/item-detail-common.js') }}"></script>
    @include('menu-templates.partials.menu-scripts', ['store' => $store, 'categories' => $categories])
    <script src="{{ asset('public/assets/menu-templates/js/sizepicker-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/menu-init-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/category-popup-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/menu_style5.js') }}"></script>
@include('menu-templates.partials.scroll-top')
</body>
</html>
