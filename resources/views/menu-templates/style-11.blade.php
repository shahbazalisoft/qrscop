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
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/menu_style11.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/category-popup-common.css') }}">
</head>
<body>
    @if($store->banner_popup_type == 1 || $store->banner_popup_type == 2)
    @include('menu-templates.partials.banner-popup', ['store' => $store])
    @endif
    <!-- Full Width Header -->
    <header class="s19-header">
        <div class="s19-header-content">
            <div class="s19-logo">
                <!-- Icon Logo (commented for future use)
                <span class="s19-logo-icon"><i class="bi bi-fire"></i></span>
                -->
                <!-- Image Logo - size: 45px height, auto width -->
                <img src="{{\App\CentralLogics\Helpers::get_full_url('store', $store->alternative_logo ?? $store->logo, 'public')}}" alt="{{$store->name}}" class="logo-img" style="height: 45px; width: auto; object-fit: contain;">
                <div class="s19-logo-text">
                    <h1>{{$store->name}}</h1>
                    <span>{{$store->restaurant_title}}</span>
                </div>
            </div>
            <button class="s19-search-btn"><i class="bi bi-search"></i></button>
        </div>
    </header>

    <!-- Banner Carousel -->
    @if($banners->count() > 0)
    <div class="s19-banner">
        <div class="s19-banner-carousel">
            <div class="s19-banner-track">
                @foreach ($banners as $banner)
                <div class="s19-banner-slide">
                    <img src="{{$banner['image_full_url']}}" alt="{{$banner->title_one}}">
                    <div class="s19-banner-overlay">
                        @if($banner->title_one)<span class="s19-banner-tag">{{$banner->title_one}}</span>@endif
                        @if($banner->title_two)<h3>{{$banner->title_two}}</h3>@endif
                    </div>
                </div>
                @endforeach
            </div>
            @if($banners->count() > 1)
            <div class="s19-banner-dots">
                @foreach ($banners as $i => $banner)
                <span class="s19-dot {{ $i === 0 ? 'active' : '' }}"></span>
                @endforeach
            </div>
            @endif
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

    <!-- Main Layout with Left Sidebar -->
    <div class="s19-main-layout">
        <!-- Left Category Sidebar -->
        @if($categories->count() > 0)
        <aside class="s19-sidebar">
            <div class="s19-cat-list">
                <button class="s19-cat-item s19-oa-cat-btn" id="s19-oa-cat-btn" data-category="order-again" style="display:none;">
                    <div class="s19-cat-icon s19-oa-icon">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <span class="s19-cat-name">Reorder</span>
                </button>
                <button class="s19-cat-item active" data-category="all">
                    <div class="s19-cat-icon all-icon">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                    </div>
                    <span class="s19-cat-name">All</span>
                </button>
                @foreach ($categories as $menu)
                    @if($menu->items->count() > 0)
                    <button class="s19-cat-item" data-category="{{$menu->slug}}">
                        <div class="s19-cat-icon">
                            <img src="{{$menu->image_full_url}}" alt="{{$menu->name}}">
                        </div>
                        <span class="s19-cat-name">{{$menu->name}}</span>
                    </button>
                    @endif
                @endforeach
            </div>
        </aside>
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
        <!-- Content Area -->
        <div class="s19-content-area">
            <!-- Order Again Section (hidden by default) -->
            <div class="s19-oa-section" id="s19-oa-section" style="display:none;">
                <div class="s19-section-header">
                    <h2><i class="bi bi-arrow-repeat"></i> Order Again</h2>
                </div>
                <div class="s19-oa-grid" id="s19-oa-grid"></div>
            </div>

            <div class="category-title-bar">
                <h2 class="category-title">Menu</h2>
                <button class="viewall-title-btn" id="viewall-btn" data-category="view-all">View All <i class="bi bi-grid"></i></button>
            </div>
            <!-- Main Content -->
            <main class="s19-main-content">
                <!-- Recommended Section -->
                @foreach($categories as $category)
                @if($category->items->count() > 0)
                <section class="s19-menu-section" id="{{ $category->slug }}" data-category="{{ $category->slug }}">
                    <div class="s19-section-header">
                        <h2><i class="bi bi-star-fill"></i> {{ $category->name }}</h2>
                        <span class="s19-item-count">{{$category->items->count()}} Items</span>
                    </div>
                    <div class="s19-menu-list">
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
                        <div class="s19-menu-item" data-index="{{ $globalIndex }}" data-item-id="{{ $item->id }}">
                            <div class="s19-item-image">
                                <img src="{{\App\CentralLogics\Helpers::get_full_url('product', $item['image'], 'public')}}" alt="{{$item->name}}">
                                <div class="s19-item-badge {{ $item->veg ? 'veg' : 'non-veg' }}"></div>
                            </div>
                            <div class="s19-item-content">
                                <div class="s19-item-info">
                                    <h3 class="s19-item-name">{{$item->name}}</h3>
                                    <p class="s19-item-desc">{{$item->description}}</p>
                                </div>
                                <div class="s19-item-footer">
                                    <div class="s19-price-section">
                                        <span class="s19-item-price">{{ \App\CentralLogics\Helpers::format_currency($discountedPrice) }}</span>
                                        @if($discount > 0)
                                        <div class="s19-item-pricing">
                                            <span class="s19-item-mrp"><s>{{ \App\CentralLogics\Helpers::format_currency($item->price) }}</s></span>
                                            <span class="s19-item-discount">{{ $discount }}{{ $discountType === 'percent' ? '%' : '₹' }} OFF</span>
                                        </div>
                                        @endif
                                    </div>
                                    <button class="s19-add-btn"><i class="bi bi-bag-plus-fill"></i></button>
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
    </div>

    <!-- Search Overlay -->
    <div class="s19-search-overlay">
        <div class="s19-search-container">
            <div class="s19-search-header">
                <div class="s19-search-input-wrapper">
                    <i class="bi bi-search s19-search-icon"></i>
                    <input type="text" class="s19-search-input" placeholder="Search dishes...">
                    <button class="s19-search-clear-btn"><i class="bi bi-x"></i></button>
                </div>
                <button class="s19-search-close-btn">Cancel</button>
            </div>
            <div class="s19-search-results">
                <div class="s19-search-placeholder">
                    <i class="bi bi-search"></i>
                    <p>Search for your favorite dishes</p>
                </div>
            </div>
        </div>
    </div>

    @include('menu-templates.partials.cart', ['store' => $store])

    <script src="{{ asset('public/assets/menu-templates/js/cart-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/item-detail-common.js') }}"></script>
    @include('menu-templates.partials.menu-scripts', ['store' => $store, 'categories' => $categories])
    <script src="{{ asset('public/assets/menu-templates/js/sizepicker-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/menu-init-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/category-popup-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/menu_style11.js') }}"></script>
@include('menu-templates.partials.scroll-top')
</body>
</html>
