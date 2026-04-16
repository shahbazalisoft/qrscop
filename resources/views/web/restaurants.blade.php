@extends('layouts.landing.app')

@section('title', translate('Restaurants'))

@push('css_or_js')
<style>
    .restaurant-hero-section {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        padding: 120px 0 60px;
        color: #fff;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .restaurant-hero-section .hero-badge {
        display: inline-flex;
        align-items: center;
        background: rgba(255, 193, 7, 0.15);
        color: #10847E;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 20px;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }
    .restaurant-hero-section h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }
    .restaurant-hero-section p {
        font-size: 1.1rem;
        opacity: 0.85;
        max-width: 600px;
        margin: 0 auto 30px;
    }
    .restaurant-search-box {
        max-width: 500px;
        margin: 0 auto;
    }
    .restaurant-search-box .input-group {
        background: #fff;
        border-radius: 50px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }
    .restaurant-search-box .form-control {
        border: none;
        padding: 14px 24px;
        font-size: 15px;
    }
    .restaurant-search-box .form-control:focus {
        box-shadow: none;
    }
    .restaurant-search-box .btn {
        border: none;
        padding: 14px 24px;
        font-size: 16px;
    }
    .restaurant-card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
    }
    .restaurant-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.15);
    }
    .restaurant-card .card-img-top {
        height: 180px;
        object-fit: cover;
        background: #f0f0f0;
    }
    .restaurant-card .card-body {
        padding: 20px;
    }
    .restaurant-card .restaurant-logo {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-top: -44px;
        position: relative;
        background: #fff;
    }
    .restaurant-card .restaurant-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 6px;
    }
    .restaurant-card .restaurant-address {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .restaurant-card .btn-view-menu {
        background: linear-gradient(135deg, #10847E, #0c6b66);
        color: #1a1a2e;
        border: none;
        border-radius: 50px;
        padding: 8px 20px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .restaurant-card .btn-view-menu:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
    }
    .no-restaurants {
        text-align: center;
        padding: 60px 20px;
    }
    .no-restaurants i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 20px;
    }
    .restaurant-count-badge {
        background: rgba(255,255,255,0.15);
        color: #fff;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="restaurant-hero-section">
        <div class="container">
            <span class="hero-badge">
                <i class="bi bi-shop me-2"></i>{{translate('Our Restaurants')}}
            </span>
            <h1>{{translate('Explore Our Restaurants')}}</h1>
            <p>{{translate('Discover amazing restaurants and explore their digital menus with a simple scan')}}</p>
            <div class="restaurant-search-box">
                <form action="{{ route('restaurants') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ $search ?? '' }}" placeholder="{{translate('Search restaurants by name or location...')}}">
                        <button class="btn btn-warning" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
            @if($stores->total() > 0)
                <div class="mt-3">
                    <span class="restaurant-count-badge">{{ $stores->total() }} {{translate('restaurants found')}}</span>
                </div>
            @endif
        </div>
    </section>

    <!-- Restaurant Listing -->
    <section class="py-5" style="background: #f8f9fa;">
        <div class="container">
            @if($search)
                <div class="mb-4 d-flex align-items-center justify-content-between">
                    <p class="mb-0 text-muted">{{translate('Showing results for')}}: <strong>"{{ $search }}"</strong></p>
                    <a href="{{ route('restaurants') }}" class="btn btn-sm btn-outline-secondary">{{translate('Clear Search')}}</a>
                </div>
            @endif

            @if($stores->count() > 0)
                <div class="row g-4">
                    @foreach($stores as $store)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="card restaurant-card">
                                <img src="{{ $store->cover_photo_full_url }}"
                                     class="card-img-top"
                                     alt="{{ $store->name }}"
                                     onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'">
                                <div class="card-body">
                                    <img src="{{ $store->logo_full_url }}"
                                         class="restaurant-logo"
                                         alt="{{ $store->name }}"
                                         onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.jpg') }}'">
                                    <h5 class="restaurant-name mt-2">{{ Str::limit($store->name, 25) }}</h5>
                                    @if($store->address)
                                        <p class="restaurant-address">
                                            <i class="bi bi-geo-alt text-warning me-1"></i>{{ $store->address }}
                                        </p>
                                    @endif
                                    @if($store->slug)
                                        <a href="{{ route('store.menu', $store->slug) }}" class="btn btn-view-menu w-100">
                                            <i class="bi bi-qr-code me-1"></i>{{translate('View Menu')}}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($stores->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $stores->appends(['search' => $search])->links() }}
                    </div>
                @endif
            @else
                <div class="no-restaurants">
                    <i class="bi bi-shop-window d-block"></i>
                    <h4 class="text-muted">{{translate('No restaurants found')}}</h4>
                    @if($search)
                        <p class="text-muted">{{translate('Try searching with different keywords')}}</p>
                        <a href="{{ route('restaurants') }}" class="btn btn-warning mt-2">{{translate('View All Restaurants')}}</a>
                    @endif
                </div>
            @endif
        </div>
    </section>
@endsection
