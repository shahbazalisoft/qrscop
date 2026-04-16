@extends('layouts.kitchen.app')
@section('title', 'Kitchen Orders')

@section('content')
    <div class="k-section-title">
        <span>Orders</span>
        <button class="k-filter-toggle" id="k-filter-toggle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="4" y1="6" x2="20" y2="6"/>
                <line x1="8" y1="12" x2="20" y2="12"/>
                <line x1="12" y1="18" x2="20" y2="18"/>
                <circle cx="4" cy="12" r="1" fill="currentColor"/>
                <circle cx="8" cy="18" r="1" fill="currentColor"/>
            </svg>
            @if(($status ?? 'all') !== 'all' || ($orderType ?? 'all') !== 'all')
                <span class="k-filter-dot"></span>
            @endif
        </button>
    </div>

    {{-- Filters (hidden by default) --}}
    <div class="k-filter-panel" id="k-filter-panel" style="{{ (($status ?? 'all') !== 'all' || ($orderType ?? 'all') !== 'all') ? '' : 'display:none;' }}">
        <div class="k-filter-group">
            <div class="k-filter-label">Status</div>
            <div class="k-filter-tabs">
                @foreach(['all' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'preparing' => 'Preparing', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $key => $label)
                    <a href="{{ route('kitchen.orders', ['status' => $key, 'order_type' => $orderType ?? 'all']) }}"
                       class="k-filter-tab {{ ($status ?? 'all') == $key ? 'active' : '' }}">
                        {{ $label }}
                        <span class="k-filter-count">{{ $statusCounts[$key] ?? 0 }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="k-filter-group">
            <div class="k-filter-label">Order Type</div>
            <div class="k-filter-tabs">
                @foreach(['all' => 'All Types', 'dine-in' => 'Dine-in', 'delivery' => 'Delivery'] as $key => $label)
                    <a href="{{ route('kitchen.orders', ['status' => $status ?? 'all', 'order_type' => $key]) }}"
                       class="k-filter-tab k-filter-tab-sm {{ ($orderType ?? 'all') == $key ? 'active' : '' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Orders List --}}
    @forelse($orders as $order)
        <div class="k-order-card k-order-border-{{ $order->status }}" data-order-card="{{ $order->id }}">
            <div class="k-order-header">
                <span class="k-order-id">#{{ $order->id }}</span>
                <span class="k-order-time">{{ $order->created_at->diffForHumans() }}</span>
            </div>
            <div class="k-order-meta">
                <span class="k-badge k-badge-{{ $order->status }} k-order-status-badge">{{ ucfirst($order->status) }}</span>
                @if($order->order_type)
                    <span class="k-badge k-badge-{{ $order->order_type == 'dine-in' ? 'dinein' : 'delivery' }}">
                        {{ ucfirst($order->order_type) }}
                    </span>
                @endif
            </div>
            @if($order->customer_phone || $order->customer_name)
                <div class="k-customer-info">
                    @if($order->customer_name)<span>{{ $order->customer_name }}</span> &middot; @endif
                    {{ $order->customer_phone }}
                    @if($order->delivery_address) &middot; {{ $order->delivery_address }}@endif
                </div>
            @endif
            @if($order->instructions)
                <div class="k-customer-info" style="font-style:italic;">
                    Note: {{ $order->instructions }}
                </div>
            @endif
            <ul class="k-order-items">
                @foreach($order->items as $item)
                    <li>
                        <span><span class="qty">{{ $item->quantity }}x</span> {{ $item->item_name }}@if($item->size) ({{ $item->size }})@endif</span>
                        <span>{{ number_format($item->item_price * $item->quantity, 2) }}</span>
                    </li>
                @endforeach
            </ul>
            <div class="k-order-footer">
                <span class="k-order-total">{{ number_format($order->total, 2) }}</span>
                <div class="k-order-actions">
                    <select class="k-status-select" data-order-id="{{ $order->id }}">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>
        </div>
    @empty
        <div class="k-empty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p>No orders found</p>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($orders->hasPages())
        @php $pgParams = ['status' => $status ?? 'all', 'order_type' => $orderType ?? 'all']; @endphp
        <div class="k-pagination">
            @if($orders->onFirstPage())
                <span>&laquo;</span>
            @else
                <a href="{{ $orders->appends($pgParams)->previousPageUrl() }}">&laquo;</a>
            @endif

            @foreach($orders->appends($pgParams)->getUrlRange(1, $orders->lastPage()) as $page => $url)
                @if($page == $orders->currentPage())
                    <span class="current">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if($orders->hasMorePages())
                <a href="{{ $orders->appends($pgParams)->nextPageUrl() }}">&raquo;</a>
            @else
                <span>&raquo;</span>
            @endif
        </div>
    @endif
@endsection
