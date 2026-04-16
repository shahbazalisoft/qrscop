@extends('layouts.kitchen.app')
@section('title', 'Kitchen Dashboard')

@section('content')
    {{-- Active Orders First --}}
    <div class="k-section-title" data-auto-refresh="orders">
        <span>Active Orders ({{ $activeOrders->count() }})</span>
        <a href="{{ route('kitchen.orders') }}" class="k-btn k-btn-sm k-btn-outline">View All</a>
    </div>

    @forelse($activeOrders as $order)
        <div class="k-order-card" data-order-card="{{ $order->id }}">
            {{-- Row 1: ID + Time + View --}}
            <div class="k-card-row-between">
                <span class="k-order-id">#{{ $order->order_id }}</span>
                @if(!empty($order->table_no))<span class="k-order-id">TableNo- {{ $order->table_no }}</span>@endif

                <span class="k-order-time">{{ $order->created_at->diffForHumans() }}</span>
            </div>
            {{-- Row 2: Badges + Items/Total + View --}}
            <div class="k-card-row-between">
                <div class="k-card-badges">
                    <span class="k-badge k-badge-{{ $order->status }} k-order-status-badge">{{ ucfirst($order->status) }}</span>
                    @if($order->order_type)
                        <span class="k-badge k-badge-{{ $order->order_type == 'dine-in' ? 'dinein' : 'delivery' }}">{{ ucfirst($order->order_type) }}</span>
                    @endif
                    <span class="k-card-summary">{{ $order->items->count() }} item{{ $order->items->count() > 1 ? 's' : '' }} &middot; {{ number_format($order->total, 2) }}</span>
                </div>
                <button class="k-view-btn" data-view-order="{{ $order->id }}" title="View Details">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            {{-- Row 3: Status Change Buttons --}}
            <div class="k-status-btns">
                @foreach(['confirmed','preparing','completed','cancelled'] as $s)
                    <button class="k-status-chip {{ $order->status == $s ? 'active' : '' }}"
                            data-order-id="{{ $order->id }}"
                            data-status-action="{{ $s }}"
                            {{ $order->status == $s ? 'disabled' : '' }}>{{ ucfirst($s) }}</button>
                @endforeach
            </div>
        </div>
    @empty
        <div class="k-empty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p>No active orders right now</p>
        </div>
    @endforelse

    {{-- Stats Cards --}}
    <div class="k-section-title" style="margin-top:20px;">
        <span>Today's Summary</span>
    </div>
    <div class="k-stats">
        <div class="k-stat-card pending">
            <div class="k-stat-value">{{ $pendingCount }}</div>
            <div class="k-stat-label">Pending</div>
        </div>
        <div class="k-stat-card confirmed">
            <div class="k-stat-value">{{ $confirmedCount }}</div>
            <div class="k-stat-label">Confirmed</div>
        </div>
        <div class="k-stat-card preparing">
            <div class="k-stat-value">{{ $preparingCount }}</div>
            <div class="k-stat-label">Preparing</div>
        </div>
        <div class="k-stat-card completed">
            <div class="k-stat-value">{{ $completedToday }}</div>
            <div class="k-stat-label">Completed</div>
        </div>
    </div>

    {{-- Today's Orders Chart --}}
    <div class="k-chart-area" id="k-hourly-chart">
        <div class="k-section-title">
            <span>Hourly Orders</span>
            <span style="font-size:12px;color:var(--k-text-muted)">{{ $totalToday }} total</span>
        </div>
        <div class="k-chart-bars"></div>
        <div class="k-chart-labels"></div>
    </div>

    {{-- Hidden order data for modal --}}
    @foreach($activeOrders as $order)
        <script type="application/json" id="order-data-{{ $order->id }}">
            {
                "id": {{ $order->id }},
                "order_id": "{{ $order->order_id ?? '' }}",
                "table_no": "{{ $order->table_no ?? '' }}",
                "status": "{{ $order->status }}",
                "order_type": "{{ $order->order_type ?? '' }}",
                "customer_name": "{{ $order->customer_name ?? '' }}",
                "customer_phone": "{{ $order->customer_phone ?? '' }}",
                "delivery_address": "{{ $order->delivery_address ?? '' }}",
                "instructions": {!! json_encode($order->instructions ?? '') !!},
                "subtotal": {{ $order->subtotal ?? 0 }},
                "discount": {{ $order->discount ?? 0 }},
                "delivery_fee": {{ $order->delivery_fee ?? 0 }},
                "total": {{ $order->total ?? 0 }},
                "created_at": "{{ $order->created_at->format('d M Y, h:i A') }}",
                "time_ago": "{{ $order->created_at->diffForHumans() }}",
                "items": [
                    @foreach($order->items as $item)
                    {
                        "name": {!! json_encode($item->item_name) !!},
                        "quantity": {{ $item->quantity }},
                        "price": {{ $item->item_price }},
                        "size": "{{ $item->size ?? '' }}"
                    }@if(!$loop->last),@endif
                    @endforeach
                ]
            }
        </script>
    @endforeach
@endsection
