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

    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/category-popup-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/menu_style10.css') }}">
</head>
<body>
    @if($store->banner_popup_type == 1 || $store->banner_popup_type == 2)
    @include('menu-templates.partials.banner-popup', ['store' => $store])
    @endif
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <!-- Icon Logo (Commented - Uncomment this and comment image logo to use icon) -->
                <!-- <span class="logo-icon"><i class="bi bi-fire"></i></span> -->

                <!--
                    Image Logo
                    Recommended size: Width: 48px, Height: 48px
                    Supported formats: PNG, JPG, SVG, WebP
                    The image will be displayed as a rounded square
                -->
                <img src="{{\App\CentralLogics\Helpers::get_full_url('store', $store->alternative_logo ?? $store->logo, 'public')}}" alt="{{$store->name}}" class="logo-img">

                <div class="logo-text">
                    <h1>{{$store->name}}</h1>
                    <span>{{$store->restaurant_title}}</span>
                </div>
            </div>
            <button class="search-btn" id="search-toggle-btn"><i class="bi bi-search"></i></button>
        </div>
    </header>

    <!-- Banner Carousel -->
    @if($banners->count() > 0)
    <div class="banner-section">
        <div class="banner-carousel">
            <div class="banner-track">
                @foreach ($banners as $banner)
                <div class="banner-slide">
                    <img src="{{$banner['image_full_url']}}" alt="{{$banner->title_one}}">
                    <div class="banner-overlay">
                        @if($banner->title_one)<span class="banner-tag">{{$banner->title_one}}</span>@endif
                        @if($banner->title_two)<h3>{{$banner->title_two}}</h3>@endif
                        @if($banner->title_one)<p>Use code: FIRST50</p>@endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="banner-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
            <button class="banner-nav prev"><i class="bi bi-chevron-left"></i></button>
            <button class="banner-nav next"><i class="bi bi-chevron-right"></i></button>
        </div>
    </div>
    @endif
    <!-- Search Box (Hidden by default) -->
    <div class="search-section" id="search-section">
        <div class="search-filter-wrapper">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Search for dishes, cuisines..." id="search-input">
                <button class="clear-btn" id="clear-search"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="filter-dropdown" id="filter-dropdown">
                <button class="filter-btn" id="filter-btn">
                    <span class="filter-icon" id="filter-icon"><i class="bi bi-funnel"></i></span>
                    <span class="filter-text" id="filter-text">All</span>
                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </button>
                <div class="filter-menu">
                    <button class="filter-option selected" data-filter="all">
                        <span class="option-icon"><i class="bi bi-grid-3x3-gap"></i></span>
                        <span class="option-text">All</span>
                        <i class="bi bi-check2 check-icon"></i>
                    </button>
                    <button class="filter-option" data-filter="veg">
                        <span class="option-icon veg"></span>
                        <span class="option-text">Veg</span>
                        <i class="bi bi-check2 check-icon"></i>
                    </button>
                    <button class="filter-option" data-filter="non-veg">
                        <span class="option-icon non-veg"></span>
                        <span class="option-text">Non-Veg</span>
                        <i class="bi bi-check2 check-icon"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content with Sidebar -->
    <main class="main-content">
        <!-- Left Category Sidebar -->
        @if($categories->count() > 0)
        <aside class="category-sidebar">
            <div class="category-list">
                <button class="category-item oa-cat-btn" id="oa-cat-btn" data-category="order-again" style="display:none;">
                    <div class="category-icon oa-icon">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <span class="category-name">Reorder</span>
                </button>
                <button class="category-item active" data-category="all">
                    <div class="category-icon all-icon">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                    </div>
                    <span class="category-name">All</span>
                </button>
                @foreach ($categories as $menu)
                    @if($menu->items->count() > 0)
                    <button class="category-item" data-category="{{$menu->slug}}">
                        <div class="category-icon">
                            <img src="{{$menu->image_full_url}}" alt="{{$menu->name}}">
                        </div>
                        <span class="category-name">{{$menu->name}}</span>
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
        <!-- Products Area -->
        <div class="products-area">
            <!-- Order Again Section (hidden by default) -->
            <div class="oa-section" id="oa-section" style="display:none;">
                <div class="section-header">
                    <h2><i class="bi bi-arrow-repeat"></i> Order Again</h2>
                </div>
                <div class="oa-grid" id="oa-grid"></div>
            </div>

            <div class="category-title-bar">
                <h2 class="category-title">Menu</h2>
                <button class="viewall-title-btn" id="viewall-btn" data-category="view-all">View All <i class="bi bi-grid"></i></button>
            </div>
            <!-- Recommended Section -->
            @foreach($categories as $category)
                @if($category->items->count() > 0)
            <section class="menu-section" id="{{ $category->slug }}" data-category="{{ $category->slug }}">
                <div class="section-header">
                    <h2><i class="bi {{ \App\CentralLogics\Helpers::random_icon() }}"></i> {{ $category->name }}</h2>
                    <span class="item-count">{{$category->items->count()}} Items</span>
                </div>
                <div class="menu-grid">
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
                    <div class="menu-card" data-category="{{ $category->slug }}" data-index="{{ $globalIndex }}" data-item-id="{{ $item->id }}">
                        <div class="card-image">
                            <img src="{{\App\CentralLogics\Helpers::get_full_url('product', $item['image'], 'public')}}" alt="{{$item->name}}">
                            <div class="item-type {{ $item->veg ? 'veg' : 'non-veg' }}"></div>
                            @if($discount > 0)
                                <span class="discount">{{ $discount }}{{ $discountType === 'percent' ? '%' : '₹' }} OFF</span>
                            @endif
                        </div>
                        <div class="card-content">
                            <h3 class="card-title">{{$item->name}}</h3>
                            <p class="card-desc">{{$item->description}}</p>
                            <div class="card-bottom">
                                <div class="card-pricing">
                                    @if($discount > 0)
                                        <span class="mrp"><s>{{ \App\CentralLogics\Helpers::format_currency($item->price) }}</s></span>
                                    @endif
                                    <span class="card-price">{{ \App\CentralLogics\Helpers::format_currency($discountedPrice) }}</span>
                                </div>
                                <button class="add-cart-btn"><i class="bi bi-bag-plus-fill"></i></button>
                            </div>
                        </div>
                    </div>
                    @php $globalIndex++; @endphp
                    @endforeach
                </div>
            </section>
            @endif
            @endforeach
        </div>
    </main>

    @include('menu-templates.partials.cart', ['store' => $store])

    <script src="{{ asset('public/assets/menu-templates/js/cart-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/item-detail-common.js') }}"></script>
    @include('menu-templates.partials.menu-scripts', ['store' => $store, 'categories' => $categories])
    <script src="{{ asset('public/assets/menu-templates/js/sizepicker-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/menu-init-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/category-popup-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/menu_style10.js') }}"></script>
@include('menu-templates.partials.scroll-top')
</body>
</html>
