<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$store->name}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/menu_style1.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/cart-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/item-detail-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/sizepicker-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/category-popup-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/desktop-common.css') }}">
    <style>
        .today-special-section {
            border: 1px dashed rgba(16, 132, 126, 0.4);
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, rgba(16, 132, 126, 0.05), rgba(16, 132, 126, 0.02));
        }
        .today-special-section .section-title {
            display: flex;
            align-items: center;
            gap: 6px;
        }
    </style>
</head>
<body>
    <!-- Banner Popup -->
    @if($store->banner_popup_type == 1 || $store->banner_popup_type == 2)
    @include('menu-templates.partials.banner-popup', ['store' => $store])
    @endif
    <!-- Header -->
    <header class="header">
        <div class="logo">
            <img src="{{\App\CentralLogics\Helpers::get_full_url('store', $store->alternative_logo ?? $store->logo, 'public')}}" alt="{{$store->name}}" class="logo-img">
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
        {{-- <div class="category-header">
            <h2 class="category-header-title">What are you craving?</h2>
            <button class="viewall-btn" id="viewall-btn">View All <i class="bi bi-chevron-right"></i></button>
        </div> --}}
        <nav class="category-nav">
            {{-- <button class="cat-btn active" data-category="all">All</button> --}}
            @foreach ($categories as $menu)
                @if($menu->items->count() > 0)
                <button class="cat-btn" data-category="{{$menu->name}}">{{$menu->name}}</button>
                @endif
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
        <!-- Today's Special Section -->
        @php
            $itemIndexMap = [];
            $idx = 0;
            foreach ($categories as $cat) {
                foreach ($cat->items as $itm) {
                    $itemIndexMap[$itm->id] = $idx;
                    $idx++;
                }
            }
        @endphp
        @if(isset($todaySpecials) && $todaySpecials->count() > 0)
        <section class="menu-section today-special-section">
            <h2 class="section-title"><i class="bi bi-stars" style="color: #10847E;"></i> Today's Special</h2>
            @foreach($todaySpecials as $special)
                @if($special->item && isset($itemIndexMap[$special->item->id]))
                @php
                    $spItem = $special->item;
                    $spIdx = $itemIndexMap[$spItem->id];
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
                <div class="menu-item" data-index="{{ $spIdx }}" data-item-id="{{ $spItem->id }}">
                    <div class="item-image">
                        <img src="{{\App\CentralLogics\Helpers::get_full_url('product', $spItem->image, 'public')}}" alt="{{$spItem->name}}">
                        @if($spDiscount > 0 && $spDiscountedPrice < $spItem->price)
                            <span class="item-discount-badge">{{ $spDiscountType === 'percent' ? $spDiscount . '% OFF' : \App\CentralLogics\Helpers::format_currency($spDiscount) . ' OFF' }}</span>
                        @endif
                    </div>
                    <div class="item-details">
                        <div class="item-header">
                            <span class="{{ $spItem->veg ? 'veg-badge' : 'non-veg-badge' }}"></span>
                            <h3 class="item-name">{{$spItem->name}}</h3>
                        </div>
                        <p class="item-desc">{{ Str::limit($spItem->description, 40) }}</p>
                    </div>
                    <div class="item-actions">
                        <div class="item-price">
                            @if($spDiscount > 0 && $spDiscountedPrice < $spItem->price)
                                <span class="item-mrp"><s>{{ \App\CentralLogics\Helpers::format_currency($spItem->price) }}</s></span>
                            @endif
                            <span class="price">{{ \App\CentralLogics\Helpers::format_currency($spDiscountedPrice) }}</span>
                        </div>
                        <button class="add-btn">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </div>
                @endif
            @endforeach
        </section>
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
                <div class="item-image">
                    <img src="{{\App\CentralLogics\Helpers::get_full_url('product', $item['image'], 'public')}}" alt="{{$item->name}}">
                    @if($discount > 0 && $discountedPrice < $item->price)
                        <span class="item-discount-badge">{{ $discountType === 'percent' ? $discount . '% OFF' : \App\CentralLogics\Helpers::format_currency($discount) . ' OFF' }}</span>
                    @endif
                </div>
                <div class="item-details">
                    <div class="item-header">
                        <span class="{{ $item->veg ? 'veg-badge' : 'non-veg-badge' }}"></span>
                        <h3 class="item-name">{{$item->name}}</h3>
                    </div>
                    <p class="item-desc">{{ Str::limit($item->description, 40) }}</p>
                </div>
                <div class="item-actions">
                    <div class="item-price">
                        @if($discount > 0 && $discountedPrice < $item->price)
                            <span class="item-mrp"><s>{{ \App\CentralLogics\Helpers::format_currency($item->price) }}</s></span>
                        @endif
                        <span class="price">{{ \App\CentralLogics\Helpers::format_currency($discountedPrice) }}</span>
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
    <script src="{{ asset('public/assets/menu-templates/js/menu_style1.js') }}"></script>
@include('menu-templates.partials.scroll-top')
</body>
</html>
