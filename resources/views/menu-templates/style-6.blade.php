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
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/menu_style6.css') }}">
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
                <img src="{{\App\CentralLogics\Helpers::get_full_url('store', $store->alternative_logo ?? $store->logo, 'public')}}" alt="{{$store->name}}" class="logo-img">
                <div class="logo-text">
                    <h1>{{$store->name}}</h1>
                    <span>{{$store->restaurant_title}}</span>
                </div>
            </div>
            <button class="search-btn"><i class="bi bi-search"></i></button>
        </div>
    </header>

    <!-- Multiple Banner Carousel -->
    @if($banners->count() > 0)
    <div class="banner-carousel">
        <div class="banner-track">
            @foreach ($banners as $banner)
            <div class="banner-slide">
                <img src="{{$banner['image_full_url']}}" alt="Special Offer 1">
                <div class="banner-overlay">
                    @if($banner->title_one)<h3>{{$banner->title_one}}</h3>@endif
                    @if($banner->title_two)<p>{{$banner->title_two}}</p>@endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="banner-dots">
            <span class="dot active" data-index="0"></span>
            <span class="dot" data-index="1"></span>
            <span class="dot" data-index="2"></span>
            <span class="dot" data-index="3"></span>
        </div>
        <button class="banner-nav prev"><i class="bi bi-chevron-left"></i></button>
        <button class="banner-nav next"><i class="bi bi-chevron-right"></i></button>
    </div>
    @endif
    <!-- Search Bar Section -->
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

    <!-- Order Again Section (previously ordered items) -->
    <div class="s6-order-again-section" id="s6-order-again" style="display:none;">
        <div class="s6-oa-head">
            <h2 class="s6-oa-title"><i class="bi bi-arrow-repeat"></i> Order Again</h2>
            <button class="viewall-title-btn s6-oa-viewall-btn" id="s6-oa-viewall" style="display:none;"><i class="bi bi-grid"></i></button>
        </div>
        <div class="s6-order-again-scroll"></div>
        <div class="s6-order-again-grid" id="s6-oa-grid" style="display:none;"></div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Recommended Section -->
        @foreach($categories as $category)
        @if($category->items->count() > 0)
        <section class="menu-section" id="{{ $category->slug }}">
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
                <div class="menu-item {{ $discount > 0 ? 'has-discount' : '' }}" data-index="{{ $globalIndex }}" data-item-id="{{ $item->id }}">
                    <div class="item-image">
                        <img src="{{\App\CentralLogics\Helpers::get_full_url('product', $item['image'], 'public')}}" alt="{{$item->name}}">
                        <div class="item-badge {{ $item->veg ? 'veg' : 'non-veg' }}"></div>
                    </div>
                    <div class="item-content">
                        <div class="item-info">
                            <h3 class="item-name">{{$item->name}}</h3>
                            <p class="item-desc">{{$item->description}}</p>
                        </div>
                        <div class="item-footer">
                            <div class="price-group">
                                <span class="item-price">{{ \App\CentralLogics\Helpers::format_currency($discountedPrice) }}</span>
                                @if($discount > 0)
                                    <span class="item-mrp">{{ \App\CentralLogics\Helpers::format_currency($item->price) }}</span>
                                    <span class="discount-tag">{{ $discount }}{{ $discountType === 'percent' ? '%' : '₹' }} OFF</span>
                                @endif
                            </div>
                            <button class="add-btn"><i class="bi bi-plus"></i></button>
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

    <!-- Size Selection Popup -->
    <div class="size-popup-overlay" id="sizePopupOverlay"></div>
    <div class="size-popup" id="sizePopup">
        <div class="popup-header">
            <h4 id="popupItemName">Select Size</h4>
            <button class="popup-close" id="popupClose"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="popup-body">
            <div class="size-options">
                <div class="size-option" data-size="quarter">
                    <div class="size-icon quarter">
                        <i class="bi bi-circle"></i>
                    </div>
                    <div class="size-info">
                        <span class="size-name">Quarter</span>
                        <span class="size-desc">Single serving</span>
                    </div>
                    <span class="size-price" id="quarterPrice">₹199</span>
                    <button class="size-add-btn"><i class="bi bi-plus"></i></button>
                </div>
                <div class="size-option" data-size="half">
                    <div class="size-icon half">
                        <i class="bi bi-circle-half"></i>
                    </div>
                    <div class="size-info">
                        <span class="size-name">Half</span>
                        <span class="size-desc">For 1-2 persons</span>
                    </div>
                    <span class="size-price" id="halfPrice">₹299</span>
                    <button class="size-add-btn"><i class="bi bi-plus"></i></button>
                </div>
                <div class="size-option" data-size="full">
                    <div class="size-icon full">
                        <i class="bi bi-circle-fill"></i>
                    </div>
                    <div class="size-info">
                        <span class="size-name">Full</span>
                        <span class="size-desc">For 2-3 persons</span>
                    </div>
                    <span class="size-price" id="fullPrice">₹399</span>
                    <button class="size-add-btn"><i class="bi bi-plus"></i></button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('public/assets/menu-templates/js/cart-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/item-detail-common.js') }}"></script>
    @include('menu-templates.partials.menu-scripts', ['store' => $store, 'categories' => $categories])
    <script src="{{ asset('public/assets/menu-templates/js/sizepicker-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/menu-init-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/category-popup-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/menu_style6.js') }}"></script>
@include('menu-templates.partials.scroll-top')
</body>
</html>
