@extends('layouts.kitchen.app')
@section('title', 'Kitchen Items')

@section('content')
    <div class="k-section-title">
        <span>Menu Items</span>
    </div>

    {{-- Search --}}
    <form action="{{ route('kitchen.items') }}" method="GET" class="k-search">
        <div class="k-search-wrap">
            <svg class="k-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" name="search" class="k-search-input" id="k-item-search" placeholder="Search items by name..." value="{{ $search ?? '' }}">
            @if(!empty($search))
                <a href="{{ route('kitchen.items') }}" class="k-search-clear">&times;</a>
            @endif
            <button type="submit" class="k-search-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </button>
        </div>
    </form>

    {{-- Items List --}}
    @forelse($items as $item)
        <div class="k-item-card">
            <img src="{{ $item->image_full_url }}"
                 alt="{{ $item->name }}"
                 class="k-item-img"
                 onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'">
            <div class="k-item-info">
                <div class="k-item-name">{{ $item->name }}</div>
                <div class="k-item-meta">
                    <span class="k-item-price">{{ number_format($item->price, 2) }}</span>
                    @if($item->category)
                        <span>&middot; {{ $item->category->name ?? '' }}</span>
                    @endif
                    @if($item->veg !== null)
                        <span class="k-badge {{ $item->veg ? 'k-badge-veg' : 'k-badge-nonveg' }}">
                            {{ $item->veg ? 'Veg' : 'Non-Veg' }}
                        </span>
                    @endif
                </div>
            </div>
            <label class="k-toggle">
                <input type="checkbox"
                       class="k-item-toggle"
                       data-item-id="{{ $item->id }}"
                       {{ $item->status ? 'checked' : '' }}>
                <span class="k-toggle-slider"></span>
            </label>
        </div>
    @empty
        <div class="k-empty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <line x1="8" y1="6" x2="21" y2="6"/>
                <line x1="8" y1="12" x2="21" y2="12"/>
                <line x1="8" y1="18" x2="21" y2="18"/>
                <line x1="3" y1="6" x2="3.01" y2="6"/>
                <line x1="3" y1="12" x2="3.01" y2="12"/>
                <line x1="3" y1="18" x2="3.01" y2="18"/>
            </svg>
            <p>No items found</p>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($items->hasPages())
        <div class="k-pagination">
            @if($items->onFirstPage())
                <span>&laquo;</span>
            @else
                <a href="{{ $items->appends(['search' => $search])->previousPageUrl() }}">&laquo;</a>
            @endif

            @foreach($items->appends(['search' => $search])->getUrlRange(1, $items->lastPage()) as $page => $url)
                @if($page == $items->currentPage())
                    <span class="current">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if($items->hasMorePages())
                <a href="{{ $items->appends(['search' => $search])->nextPageUrl() }}">&raquo;</a>
            @else
                <span>&raquo;</span>
            @endif
        </div>
    @endif
@endsection
