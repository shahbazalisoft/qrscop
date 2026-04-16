@extends('menu-templates.layouts.base')

@section('styles')
<link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/menu_style7.css') }}">
@endsection

@section('content')
    <!-- Header -->
    <header class="header">
        <button class="menu-btn">
            <i class="bi bi-list"></i>
        </button>
        <div class="logo">
            {{-- Vendor Logo: Recommended size 200x200px (square, PNG/JPG) --}}
            @if(isset($store->logo))
                <img src="{{ asset('storage/app/public/store/' . $store->logo) }}" alt="{{ $store->name }}" class="logo-img" style="height: 40px;">
            @else
                <span class="logo-icon">🍽️</span>
            @endif
            <span class="logo-text">{{ $store->name ?? 'Restaurant' }}</span>
        </div>
        <button class="search-btn">
            <i class="bi bi-search"></i>
        </button>
    </header>

    <!-- Banners Section -->
    {{-- Banner Image: Recommended size 750x320px (landscape, PNG/JPG) --}}
    @if(isset($banners) && count($banners) > 0)
    <div class="banners-scroll">
        @foreach($banners as $banner)
        <div class="banner-img">
            <img src="{{ asset('storage/app/public/banner/' . $banner->image) }}" alt="{{ $banner->title }}">
            <div class="banner-overlay">
                <span class="banner-tag">{{ $banner->title }}</span>
                <p class="banner-label">{{ $banner->description }}</p>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Main Content -->
    <main class="main-content">
        <!-- Category Navigation -->
        <nav class="category-nav">
            @if(isset($categories) && count($categories) > 0)
                @foreach($categories as $index => $category)
                <button class="cat-btn {{ $index == 0 ? 'active' : '' }}" data-category="category-{{ $category->id }}">
                    {{ $category->name }}
                </button>
                @endforeach
            @endif
        </nav>

        <!-- Menu Sections by Category -->
        @if(isset($categories) && count($categories) > 0)
            @foreach($categories as $category)
            <section class="menu-section" id="category-{{ $category->id }}">
                <h2 class="section-title">{{ $category->name }}</h2>

                {{-- Product Image: Recommended size 300x300px (square, PNG/JPG) --}}
                @foreach($items->where('category_id', $category->id) as $item)
                <div class="menu-item {{ $item->is_special ? 'special' : '' }}" data-item-id="{{ $item->id }}">
                    <div class="item-image">
                        <img src="{{\App\CentralLogics\Helpers::get_full_url('product', $item->image, 'public')}}" alt="{{ $item->name }}"
                             onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'">
                    </div>
                    <div class="item-details">
                        <div class="item-header">
                            <span class="{{ $item->veg ? 'veg-badge' : 'non-veg-badge' }}"></span>
                            <h3 class="item-name">{{ $item->name }}</h3>
                            @if($item->is_special)
                            <span class="special-tag">Chef's Special</span>
                            @endif
                        </div>
                        <p class="item-desc">{{ Str::limit($item->description, 80) }}</p>
                    </div>
                    <div class="item-actions">
                        <div class="item-price">
                            <span class="price">₹{{ number_format($item->price, 0) }}</span>
                        </div>
                        <button class="add-btn" data-item="{{ htmlspecialchars(json_encode(['id' => $item->id, 'name' => $item->name, 'price' => $item->price, 'image' => \App\CentralLogics\Helpers::get_full_url('product', $item->image, 'public')]), ENT_QUOTES, 'UTF-8') }}">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </div>
                @endforeach

            </section>
            @endforeach
        @endif
    </main>
@endsection

@section('scripts')
<script src="{{ asset('public/assets/menu-templates/js/menu_style7.js') }}"></script>
@endsection
