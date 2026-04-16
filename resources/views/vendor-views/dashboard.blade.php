@extends('layouts.vendor.app')

@section('title',translate('messages.dashboard'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .dashboard-stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
            height: 100%;
        }
        .dashboard-stat-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon i {
            font-size: 22px;
            color: #fff;
        }
        .stat-icon.bg-primary-soft { background: #e8f4fd; }
        .stat-icon.bg-primary-soft i { color: #3f8ce8; }
        .stat-icon.bg-success-soft { background: #e6f7f0; }
        .stat-icon.bg-success-soft i { color: #00aa6d; }
        .stat-icon.bg-warning-soft { background: #fff7e6; }
        .stat-icon.bg-warning-soft i { color: #ffa800; }
        .stat-icon.bg-danger-soft { background: #ffecec; }
        .stat-icon.bg-danger-soft i { color: #ff6d6d; }
        .stat-icon.bg-info-soft { background: #e6f3ff; }
        .stat-icon.bg-info-soft i { color: #6c63ff; }
        .stat-icon.bg-teal-soft { background: #e0f5f5; }
        .stat-icon.bg-teal-soft i { color: #00AA96; }
        .stat-content h3 {
            font-size: 22px;
            font-weight: 700;
            color: #334257;
            margin: 0 0 2px 0;
        }
        .stat-content p {
            font-size: 13px;
            color: #9d9d9d;
            margin: 0;
            font-weight: 500;
        }
        .top-items-table {
            width: 100%;
        }
        .top-items-table thead th {
            font-size: 12px;
            font-weight: 600;
            color: #9d9d9d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 12px;
            border-bottom: 1px solid #f0f0f0;
        }
        .top-items-table tbody td {
            padding: 12px;
            border-bottom: 1px solid #f8f8f8;
            vertical-align: middle;
            font-size: 14px;
            color: #334257;
        }
        .top-items-table tbody tr:hover {
            background: #fafafa;
        }
        .item-rank {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12px;
            color: #334257;
        }
        .item-rank.rank-1 { background: #fff7e6; color: #ffa800; }
        .item-rank.rank-2 { background: #f0f0f0; color: #666; }
        .item-rank.rank-3 { background: #fdf0e6; color: #e67e22; }
        .item-img {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
        }
        .chart-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }
        .chart-card-header h5 {
            font-size: 16px;
            font-weight: 700;
            color: #334257;
            margin: 0;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">

        @if(\App\CentralLogics\Helpers::employee_module_permission_check('dashboard'))
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm">
                    <h1 class="page-header-title">
                        <span class="page-header-icon">
                            <img src="{{asset('public/assets/admin/img/category.png')}}" class="w--20" alt="">
                        </span>
                        <span>{{translate('messages.dashboard')}}</span>
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Stats Cards Row 1 -->
        <div class="row g-3 mb-3">
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <div class="dashboard-stat-card">
                    <div class="stat-icon bg-primary-soft">
                        <i class="tio-fastfood"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $total_menu }}</h3>
                        <p>{{ translate('Total Menu') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <div class="dashboard-stat-card">
                    <div class="stat-icon bg-success-soft">
                        <i class="tio-shopping-basket"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $total_items }}</h3>
                        <p>{{ translate('Total Items') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <div class="dashboard-stat-card">
                    <div class="stat-icon bg-info-soft">
                        <i class="tio-visible"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ number_format($total_visits) }}</h3>
                        <p>{{ translate('Total Visits') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <div class="dashboard-stat-card">
                    <div class="stat-icon bg-warning-soft">
                        <i class="tio-receipt"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ number_format($total_orders) }}</h3>
                        <p>{{ translate('Total Orders') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <div class="dashboard-stat-card">
                    <div class="stat-icon bg-danger-soft">
                        <i class="tio-time"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $package_days_left }}</h3>
                        <p>{{ translate('Package Days Left') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <div class="dashboard-stat-card">
                    <div class="stat-icon bg-teal-soft">
                        <i class="tio-user"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ number_format($total_customers) }}</h3>
                        <p>{{ translate('Total Customers') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status Cards -->
        <div class="row g-2 mb-3">
            <div class="col-sm-6 col-lg-3">
                <a class="order--card h-100" href="javascript:;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                            <img src="{{asset('/public/assets/admin/img/dashboard/food/unassigned.svg')}}" alt="" class="oder--card-icon">
                            <span>{{ translate('Pending') }}</span>
                        </h6>
                        <span class="card-title text-3F8CE8">{{ $order_pending }}</span>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-lg-3">
                <a class="order--card h-100" href="javascript:;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                            <img src="{{asset('/public/assets/admin/img/dashboard/food/packaging.svg')}}" alt="" class="oder--card-icon">
                            <span>{{ translate('Preparing') }}</span>
                        </h6>
                        <span class="card-title text-FFA800">{{ $order_preparing }}</span>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-lg-3">
                <a class="order--card h-100" href="javascript:;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                            <img src="{{asset('/public/assets/admin/img/dashboard/food/accepted.svg')}}" alt="" class="oder--card-icon">
                            <span>{{ translate('Completed') }}</span>
                        </h6>
                        <span class="card-title text-success">{{ $order_completed }}</span>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-lg-3">
                <a class="order--card h-100" href="javascript:;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                            <img src="{{asset('/public/assets/admin/img/dashboard/food/out-for.svg')}}" alt="" class="oder--card-icon">
                            <span>{{ translate('Cancelled') }}</span>
                        </h6>
                        <span class="card-title text-danger">{{ $order_cancelled }}</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Dine-in Tables -->
        @if($tables->count())
        <div class="row g-3 mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="chart-card-header mb-3">
                            <h5><i class="tio-restaurant mr-1"></i> {{ translate('Dine-in Tables') }}</h5>
                            <div class="d-flex align-items-center gap-3" style="font-size:13px;">
                                <span class="d-flex align-items-center gap-1"><span style="width:12px;height:12px;border-radius:3px;background:#e6f7f0;border:1px solid #00AA96;display:inline-block;"></span> {{ translate('Available') }}</span>
                                <span class="d-flex align-items-center gap-1"><span style="width:12px;height:12px;border-radius:3px;background:#fff0e6;border:1px solid #ffa800;display:inline-block;"></span> {{ translate('Booked') }}</span>
                                <span class="d-flex align-items-center gap-1"><span style="width:12px;height:12px;border-radius:3px;background:#ffecec;border:1px solid #ff6d6d;display:inline-block;"></span> {{ translate('Reserved') }}</span>
                            </div>
                        </div>
                        <div class="row g-2">
                            @php
                                // $tables = [
                                //     ['no' => 1, 'seats' => 2, 'status' => 'available'],
                                //     ['no' => 2, 'seats' => 2, 'status' => 'booked'],
                                //     ['no' => 3, 'seats' => 4, 'status' => 'available'],
                                //     ['no' => 4, 'seats' => 4, 'status' => 'booked'],
                                //     ['no' => 5, 'seats' => 6, 'status' => 'available'],
                                //     ['no' => 6, 'seats' => 6, 'status' => 'reserved'],
                                // ];

                                $statusConfig = [
                                    'available' => ['bg' => '#e6f7f0', 'border' => '#00AA96', 'color' => '#00AA96', 'icon' => 'tio-checkmark-circle'],
                                    'booked'    => ['bg' => '#fff0e6', 'border' => '#ffa800', 'color' => '#ffa800', 'icon' => 'tio-user'],
                                    'reserved'  => ['bg' => '#ffecec', 'border' => '#ff6d6d', 'color' => '#ff6d6d', 'icon' => 'tio-time'],
                                ];
                            @endphp
                            @foreach($tables as $table)
                                @php $cfg = $statusConfig[$table['status']]; @endphp
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                    <div class="text-center p-3 rounded" style="background:{{ $cfg['bg'] }};border:1.5px solid {{ $cfg['border'] }};transition:all 0.2s;">
                                        <div style="font-size:22px;color:{{ $cfg['color'] }};margin-bottom:4px;">
                                            <i class="{{ $cfg['icon'] }}"></i>
                                        </div>
                                        <h6 class="mb-1" style="color:#334257;font-size:23px;">T-{{ $table['no'] }}</h6>
                                        {{-- <div style="font-size:11px;color:#9d9d9d;">{{ $table['seats'] }} {{ translate('Seats') }}</div> --}}
                                        <span class="badge mt-1" style="background:{{ $cfg['color'] }};color:#fff;font-size:10px;text-transform:capitalize;">{{ translate($table['status']) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- Charts Row -->
        <div class="row g-3 mb-3">
            <!-- Orders Chart -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="chart-card-header">
                            <h5><i class="tio-chart-bar-4 mr-1"></i> {{ translate('Order Statistics') }}</h5>
                            <span class="badge badge-soft-primary">{{ translate('This Year') }}</span>
                        </div>
                        <div class="chartjs-custom">
                            <canvas id="orderChart" style="height: 280px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Revenue Chart -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="chart-card-header">
                            <h5><i class="tio-chart-bar-4 mr-1"></i> {{ translate('Revenue Statistics') }}</h5>
                            <span class="badge badge-soft-success">{{ translate('This Year') }}</span>
                        </div>
                        <div class="chartjs-custom">
                            <canvas id="revenueChart" style="height: 280px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Ordered Items -->
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="chart-card-header">
                            <h5><i class="tio-star mr-1"></i> {{ translate('Top Ordered Items') }}</h5>
                            <span class="text-muted" style="font-size: 13px;">{{ translate('Based on orders') }}</span>
                        </div>
                        <div class="table-responsive">
                            <table class="top-items-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ translate('Item') }}</th>
                                        <th>{{ translate('Orders') }}</th>
                                        <th>{{ translate('Revenue') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($top_items as $key => $item)
                                    <tr>
                                        <td><span class="item-rank {{ $key == 0 ? 'rank-1' : ($key == 1 ? 'rank-2' : ($key == 2 ? 'rank-3' : '')) }}">{{ $key + 1 }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $item->image_full_url ?? asset('/public/assets/admin/img/100x100/food-default-image.png') }}" class="item-img" alt="" onerror="this.src='{{ asset('/public/assets/admin/img/100x100/food-default-image.png') }}'">
                                                <span class="font-weight-semibold">{{ $item->name }}</span>
                                            </div>
                                        </td>
                                        <td><span class="font-weight-semibold">{{ $item->order_count }}</span></td>
                                        <td><span class="text-success font-weight-semibold">{{ \App\CentralLogics\Helpers::format_currency($item->price * $item->order_count) }}</span></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">{{ translate('No ordered items yet') }}</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="chart-card-header">
                            <h5><i class="tio-user mr-1"></i> {{ translate('Top Ordered Customers') }}</h5>
                            <span class="text-muted" style="font-size: 13px;">{{ translate('Based on orders') }}</span>
                        </div>
                        <div class="table-responsive">
                            <table class="top-items-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ translate('Name') }}</th>
                                        <th>{{ translate('Phone') }}</th>
                                        <th>{{ translate('TotalOrder') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($top_customers as $key => $customer)
                                    <tr>
                                        <td><span class="item-rank {{ $key == 0 ? 'rank-1' : ($key == 1 ? 'rank-2' : ($key == 2 ? 'rank-3' : '')) }}">{{ $key + 1 }}</span></td>
                                        
                                        <td><span class="font-weight-semibold">{{ $customer->name ?? 'N/A' }}</span></td>

                                        <td><span class="font-weight-semibold">{{ $customer->phone }}</span></td>
                                        <td><span class="font-weight-semibold">{{ $customer->total_order }}</span></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">{{ translate('No ordered items yet') }}</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary Card -->
            {{-- <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="chart-card-header">
                            <h5><i class="tio-chart-pie-1 mr-1"></i> {{ translate('Order Summary') }}</h5>
                        </div>
                        <div class="chartjs-custom mb-3">
                            <canvas id="orderSummaryChart" style="height: 200px;"></canvas>
                        </div>
                        @php
                            $summary_total = $order_completed + $order_pending + $order_preparing + $order_cancelled;
                            $pct = function($val) use ($summary_total) {
                                return $summary_total > 0 ? round(($val / $summary_total) * 100) : 0;
                            };
                        @endphp
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <span style="width: 10px; height: 10px; border-radius: 50%; background: #00AA96; display: inline-block;"></span>
                                    <span class="text-muted" style="font-size: 13px;">{{ translate('Completed') }}</span>
                                </div>
                                <span class="font-weight-semibold">{{ $order_completed }} ({{ $pct($order_completed) }}%)</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <span style="width: 10px; height: 10px; border-radius: 50%; background: #ffa800; display: inline-block;"></span>
                                    <span class="text-muted" style="font-size: 13px;">{{ translate('Pending') }}</span>
                                </div>
                                <span class="font-weight-semibold">{{ $order_pending }} ({{ $pct($order_pending) }}%)</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <span style="width: 10px; height: 10px; border-radius: 50%; background: #3f8ce8; display: inline-block;"></span>
                                    <span class="text-muted" style="font-size: 13px;">{{ translate('Preparing') }}</span>
                                </div>
                                <span class="font-weight-semibold">{{ $order_preparing }} ({{ $pct($order_preparing) }}%)</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span style="width: 10px; height: 10px; border-radius: 50%; background: #ff6d6d; display: inline-block;"></span>
                                    <span class="text-muted" style="font-size: 13px;">{{ translate('Cancelled') }}</span>
                                </div>
                                <span class="font-weight-semibold">{{ $order_cancelled }} ({{ $pct($order_cancelled) }}%)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>

        @else
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{translate('messages.welcome')}}, {{auth('vendor_employee')->user()->f_name}}.</h1>
                    <p class="page-header-text">{{translate('messages.employee_welcome_message')}}</p>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        @endif
    </div>
@endsection

@push('script')
    <script src="{{asset('public/assets/admin')}}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{asset('public/assets/admin')}}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script src="{{asset('public/assets/admin')}}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js"></script>
@endpush

@push('script_2')
    <script>
    "use strict";

    Chart.plugins.unregister(ChartDataLabels);

    // Orders Bar Chart
    var orderCtx = document.getElementById('orderChart').getContext('2d');
    new Chart(orderCtx, {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{
                label: 'Orders',
                data: @json($order_chart_data),
                backgroundColor: '#00AA96',
                hoverBackgroundColor: '#009985',
                borderRadius: 4,
                barThickness: 14
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            scales: {
                yAxes: [{
                    gridLines: { color: '#f0f0f0', drawBorder: false, zeroLineColor: '#f0f0f0' },
                    ticks: { beginAtZero: true, stepSize: 10, fontColor: '#97a4af', fontSize: 12, padding: 10 }
                }],
                xAxes: [{
                    gridLines: { display: false, drawBorder: false },
                    ticks: { fontColor: '#97a4af', fontSize: 12, padding: 5 }
                }]
            },
            tooltips: { mode: 'index', intersect: false }
        }
    });

    // Revenue Line Chart
    var revenueCtx = document.getElementById('revenueChart').getContext('2d');
    var gradient = revenueCtx.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(63, 140, 232, 0.2)');
    gradient.addColorStop(1, 'rgba(63, 140, 232, 0)');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{
                label: 'Revenue',
                data: @json($revenue_chart_data),
                borderColor: '#3f8ce8',
                backgroundColor: gradient,
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: '#3f8ce8',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            scales: {
                yAxes: [{
                    gridLines: { color: '#f0f0f0', drawBorder: false, zeroLineColor: '#f0f0f0' },
                    ticks: {
                        beginAtZero: true,
                        fontColor: '#97a4af',
                        fontSize: 12,
                        padding: 10,
                        callback: function(value) { return '{{\App\CentralLogics\Helpers::currency_symbol()}}' + (value/1000) + 'k'; }
                    }
                }],
                xAxes: [{
                    gridLines: { display: false, drawBorder: false },
                    ticks: { fontColor: '#97a4af', fontSize: 12, padding: 5 }
                }]
            },
            tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                    label: function(item) { return '{{\App\CentralLogics\Helpers::currency_symbol()}}' + item.yLabel.toLocaleString(); }
                }
            }
        }
    });

    // Order Summary Doughnut Chart
    var summaryCtx = document.getElementById('orderSummaryChart').getContext('2d');
    new Chart(summaryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Pending', 'Preparing', 'Cancelled'],
            datasets: [{
                data: [{{ $order_completed }}, {{ $order_pending }}, {{ $order_preparing }}, {{ $order_cancelled }}],
                backgroundColor: ['#00AA96', '#ffa800', '#3f8ce8', '#ff6d6d'],
                borderWidth: 0,
                hoverBorderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            cutoutPercentage: 65,
            tooltips: {
                callbacks: {
                    label: function(item, data) {
                        var label = data.labels[item.index];
                        var val = data.datasets[0].data[item.index];
                        return label + ': ' + val;
                    }
                }
            }
        }
    });
    </script>
@endpush
