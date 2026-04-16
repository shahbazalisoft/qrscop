@extends('layouts.admin.app')

@section('title', \App\Models\BusinessSetting::where(['key'=>'business_name'])->first()->value ?? translate('messages.dashboard'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .dashboard-stat-card {
            border-radius: 10px;
            padding: 20px;
            color: #fff;
            position: relative;
            overflow: hidden;
            min-height: 120px;
        }
        .dashboard-stat-card .stat-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 48px;
            opacity: 0.2;
        }
        .dashboard-stat-card h3 { font-size: 28px; font-weight: 700; margin-bottom: 2px; }
        .dashboard-stat-card .stat-label { font-size: 14px; opacity: 0.9; }
        .dashboard-stat-card .stat-sub { font-size: 12px; opacity: 0.75; margin-top: 4px; }
        .bg-stat-stores { background: linear-gradient(135deg, #005555, #00aa96); }
        .bg-stat-items { background: linear-gradient(135deg, #6366f1, #818cf8); }
        .bg-stat-orders { background: linear-gradient(135deg, #f59e0b, #f97316); }
        .bg-stat-customers { background: linear-gradient(135deg, #ec4899, #f43f5e); }
        .order-status-card {
            border-left: 4px solid;
            border-radius: 8px;
            padding: 15px;
            background: #fff;
            transition: transform 0.2s;
        }
        .order-status-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .order-status-card .count { font-size: 24px; font-weight: 700; }
        .order-status-card .label { font-size: 13px; color: #6b7280; }
        .border-pending { border-color: #f59e0b; }
        .border-confirmed { border-color: #3b82f6; }
        .border-preparing { border-color: #8b5cf6; }
        .border-completed { border-color: #10b981; }
        .border-cancelled { border-color: #ef4444; }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center py-2">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title mb-0">{{ translate('messages.Dashboard') }}</h1>
                    <p class="page-header-text m-0">{{ translate('Hello') }}, {{ auth('admin')->user()->f_name }}! {{ translate('Here is your business overview.') }}</p>
                </div>
            </div>
        </div>

        <!-- Statistics Type Filter -->
        <div class="card mb-3">
            <div class="card-body py-2">
                <div class="d-flex flex-wrap align-items-center justify-content-end">
                    <div class="statistics-btn-grp">
                        <label>
                            <input type="radio" name="statistics" value="overall" {{ $statisticsType == 'overall' ? 'checked' : '' }} class="stats-filter" hidden>
                            <span>{{ translate('Overall') }}</span>
                        </label>
                        <label>
                            <input type="radio" name="statistics" value="this_year" {{ $statisticsType == 'this_year' ? 'checked' : '' }} class="stats-filter" hidden>
                            <span>{{ translate('This Year') }}</span>
                        </label>
                        <label>
                            <input type="radio" name="statistics" value="this_month" {{ $statisticsType == 'this_month' ? 'checked' : '' }} class="stats-filter" hidden>
                            <span>{{ translate('This Month') }}</span>
                        </label>
                        <label>
                            <input type="radio" name="statistics" value="this_week" {{ $statisticsType == 'this_week' ? 'checked' : '' }} class="stats-filter" hidden>
                            <span>{{ translate('This Week') }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Stat Cards -->
        <div class="row g-2 mb-3">
            <div class="col-sm-6 col-lg-3">
                <div class="dashboard-stat-card bg-stat-stores">
                    <span class="stat-icon"><i class="tio-shop"></i></span>
                    <h3>{{ $totalStores }}</h3>
                    <div class="stat-label">{{ translate('messages.restaurants') }}</div>
                    <div class="stat-sub">{{ $newStores }} {{ translate('newly added') }}</div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="dashboard-stat-card bg-stat-items">
                    <span class="stat-icon"><i class="tio-fastfood"></i></span>
                    <h3>{{ $totalItems }}</h3>
                    <div class="stat-label">{{ translate('messages.food_items') }}</div>
                    <div class="stat-sub">{{ $newItems }} {{ translate('newly added') }}</div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <a href="{{ route('admin.menu-order.list', 'all') }}">
                    <div class="dashboard-stat-card bg-stat-orders">
                        <span class="stat-icon"><i class="tio-shopping-cart"></i></span>
                        <h3>{{ $totalMenuOrders }}</h3>
                        <div class="stat-label">{{ translate('messages.menu_orders') }}</div>
                        <div class="stat-sub">{{ $newMenuOrders }} {{ translate('newly added') }}</div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="dashboard-stat-card bg-stat-customers">
                    <span class="stat-icon"><i class="tio-user"></i></span>
                    <h3>{{ $totalCustomers }}</h3>
                    <div class="stat-label">{{ translate('messages.customers') }}</div>
                    <div class="stat-sub">{{ $newCustomers }} {{ translate('newly added') }}</div>
                </div>
            </div>
        </div>

        <!-- Menu Order Status Cards -->
        <div class="row g-2 mb-3">
            <div class="col">
                <a href="{{ route('admin.menu-order.list', 'pending') }}">
                    <div class="order-status-card border-pending">
                        <div class="count text-warning">{{ $menuOrderPending }}</div>
                        <div class="label">{{ translate('messages.pending') }}</div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.menu-order.list', 'confirmed') }}">
                    <div class="order-status-card border-confirmed">
                        <div class="count text-primary">{{ $menuOrderConfirmed }}</div>
                        <div class="label">{{ translate('messages.confirmed') }}</div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.menu-order.list', 'preparing') }}">
                    <div class="order-status-card border-preparing">
                        <div class="count" style="color:#8b5cf6">{{ $menuOrderPreparing }}</div>
                        <div class="label">{{ translate('messages.preparing') }}</div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.menu-order.list', 'completed') }}">
                    <div class="order-status-card border-completed">
                        <div class="count text-success">{{ $menuOrderCompleted }}</div>
                        <div class="label">{{ translate('messages.completed') }}</div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.menu-order.list', 'cancelled') }}">
                    <div class="order-status-card border-cancelled">
                        <div class="count text-danger">{{ $menuOrderCancelled }}</div>
                        <div class="label">{{ translate('messages.cancelled') }}</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <!-- Revenue Chart -->
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header border-0 d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <h5 class="card-header-title mb-0">{{ translate('messages.revenue_overview') }}</h5>
                            <h3 class="mt-1 mb-0">{{ \App\CentralLogics\Helpers::format_currency($totalRevenue) }}</h3>
                        </div>
                        <select class="custom-select border w-auto commission-filter" name="commission_overview">
                            <option value="this_year" {{ $commissionOverview == 'this_year' ? 'selected' : '' }}>{{ translate('This Year') }}</option>
                            <option value="this_month" {{ $commissionOverview == 'this_month' ? 'selected' : '' }}>{{ translate('This Month') }}</option>
                            <option value="this_week" {{ $commissionOverview == 'this_week' ? 'selected' : '' }}>{{ translate('This Week') }}</option>
                        </select>
                    </div>
                    <div class="card-body pt-0">
                        <div id="revenue-chart"></div>
                    </div>
                </div>
            </div>

            <!-- Top Stores by Orders -->
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header border-0">
                        <h5 class="card-header-title">{{ translate('messages.top_restaurants') }}</h5>
                    </div>
                    <div class="card-body pt-0">
                        @if($topStores->count() > 0)
                            @foreach($topStores as $store)
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <img class="rounded-circle" width="36" height="36" style="object-fit:cover"
                                             src="{{ $store->logo_full_url ?? asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                             onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'">
                                        <div>
                                            <span class="d-block font-weight-semibold text-truncate" style="max-width:160px">{{ $store->name }}</span>
                                            <small class="text-muted">{{ $store->total_order }} {{ translate('orders') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="tio-restaurant" style="font-size:40px;opacity:0.3"></i>
                                <p class="mt-2">{{ translate('no_data_found') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <!-- Top Selling Items -->
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header border-0">
                        <h5 class="card-header-title">{{ translate('messages.top_selling_items') }}</h5>
                    </div>
                    <div class="card-body pt-0">
                        @if($topSellingItems->count() > 0)
                            @foreach($topSellingItems as $item)
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <img class="rounded" width="40" height="40" style="object-fit:cover"
                                             src="{{ $item->image_full_url ?? asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                             onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'">
                                        <div>
                                            <span class="d-block font-weight-semibold text-truncate" style="max-width:200px">{{ $item->name }}</span>
                                            <small class="text-muted">{{ \App\CentralLogics\Helpers::format_currency($item->price) }}</small>
                                        </div>
                                    </div>
                                    <span class="badge badge-soft-success">{{ $item->order_count }} {{ translate('sold') }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="tio-fastfood" style="font-size:40px;opacity:0.3"></i>
                                <p class="mt-2">{{ translate('no_data_found') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Most Visited Stores -->
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header border-0">
                        <h5 class="card-header-title">{{ translate('messages.most_visited_restaurants') }}</h5>
                    </div>
                    <div class="card-body pt-0">
                        @if($topVisitedStores->count() > 0)
                            @foreach($topVisitedStores as $store)
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <img class="rounded-circle" width="36" height="36" style="object-fit:cover"
                                             src="{{ $store->logo_full_url ?? asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                             onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'">
                                        <div>
                                            <span class="d-block font-weight-semibold text-truncate" style="max-width:180px">{{ $store->name }}</span>
                                            <small class="text-muted">{{ $store->total_order }} {{ translate('orders') }}</small>
                                        </div>
                                    </div>
                                    <span class="badge badge-soft-info">
                                        <i class="tio-visible"></i> {{ $store->total_visits }}
                                    </span>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="tio-visible" style="font-size:40px;opacity:0.3"></i>
                                <p class="mt-2">{{ translate('no_data_found') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- New Stores -->
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header border-0">
                        <h5 class="card-header-title">{{ translate('messages.newly_joined_restaurants') }}</h5>
                    </div>
                    <div class="card-body pt-0">
                        @if($newJoinedStores->count() > 0)
                            @foreach($newJoinedStores as $store)
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <img class="rounded-circle" width="36" height="36" style="object-fit:cover"
                                             src="{{ $store->logo_full_url ?? asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                             onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'">
                                        <div>
                                            <span class="d-block font-weight-semibold text-truncate" style="max-width:180px">{{ $store->name }}</span>
                                            <small class="text-muted">{{ translate('joined') }} {{ $store->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <span class="badge badge-soft-info">{{ $store->total_order }} {{ translate('orders') }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="tio-shop" style="font-size:40px;opacity:0.3"></i>
                                <p class="mt-2">{{ translate('no_data_found') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-2">
            <!-- Top Orders by Store -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-0 d-flex justify-content-between align-items-center">
                        <h5 class="card-header-title">{{ translate('messages.top_orders_by_store') }}</h5>
                        <a href="{{ route('admin.menu-order.list', 'all') }}" class="btn btn-sm btn-outline-primary">{{ translate('messages.view_all') }}</a>
                    </div>
                    <div class="card-body pt-0">
                        @if($topOrderStores->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless table-thead-bordered table-align-middle">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ translate('messages.#') }}</th>
                                            <th>{{ translate('messages.store') }}</th>
                                            <th class="text-center">{{ translate('messages.menu_orders') }}</th>
                                            <th class="text-center">{{ translate('messages.total_orders') }}</th>
                                            <th class="text-center">{{ translate('messages.total_visits') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topOrderStores as $key => $store)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <img class="rounded-circle" width="36" height="36" style="object-fit:cover"
                                                             src="{{ $store->logo_full_url ?? asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                                             onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'">
                                                        <span class="font-weight-semibold">{{ $store->name }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-soft-info">{{ $store->menu_orders_count }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-soft-success">{{ $store->total_order }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-soft-dark">{{ $store->total_visits }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="tio-shop" style="font-size:40px;opacity:0.3"></i>
                                <p class="mt-2">{{ translate('no_data_found') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('/public/assets/admin/js/apex-charts/apexcharts.js') }}"></script>
@endpush

@push('script_2')
<script>
    "use strict";

    // Revenue Chart
    var revenueOptions = {
        series: [{
            name: '{{ translate('Revenue') }}',
            data: [{{ implode(',', $menuRevenue) }}]
        }],
        chart: {
            height: 320,
            type: 'area',
            toolbar: { show: false },
        },
        colors: ['#005555'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2, colors: ['#005555'] },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
            },
            colors: ['#005555'],
        },
        xaxis: {
            categories: [{!! implode(',', $label) !!}]
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return '{{ \App\CentralLogics\Helpers::currency_symbol() }}' + val.toFixed(2);
                }
            }
        },
    };
    var revenueChart = new ApexCharts(document.querySelector("#revenue-chart"), revenueOptions);
    revenueChart.render();

    // Stats filter - reload page with param
    $('.stats-filter').on('change', function() {
        var url = new URL(window.location.href);
        url.searchParams.set('statistics_type', $(this).val());
        window.location.href = url.toString();
    });

    // Commission overview filter - reload page with param
    $('.commission-filter').on('change', function() {
        var url = new URL(window.location.href);
        url.searchParams.set('commission_overview', $(this).val());
        window.location.href = url.toString();
    });
</script>
@endpush
