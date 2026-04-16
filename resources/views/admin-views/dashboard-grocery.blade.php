@extends('layouts.admin.app')

@section('title', \App\Models\BusinessSetting::where(['key' => 'business_name'])->first()->value ??
    translate('messages.dashboard'))

    @push('css_or_js')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center py-2">
                <div class="col-sm mb-2 mb-sm-0">
                    <div class="d-flex align-items-center">
                        <img class="onerror-image" data-onerror-image="{{ asset('/public/assets/admin/img/grocery.svg') }}"
                            src="{{ asset('/public/assets/admin/img/grocery.svg') }}" width="38" alt="img">
                        <div class="w-0 flex-grow pl-2">
                            <h1 class="page-header-title mb-0">{{ translate('messages.Dashboard') }}.</h1>
                            <p class="page-header-text m-0">{{ translate('Hello, Here You Can Manage Your') }}
                                {{ translate('orders by Zone.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-auto min--280">
                    <select name="zone_id" class="form-control js-select2-custom fetch_data_zone_wise">
                        <option value="all">{{ translate('messages.All') }}</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Stats -->
        <div class="card mb-3">
            <div class="card-body pt-0">
                <div class="d-flex flex-wrap align-items-center justify-content-end">
                    <div class="status-filter-wrap">
                        <div class="statistics-btn-grp">
                            <label>
                                <input type="radio" name="statistics" value="this_year"
                                    {{ 1 == 'this_year' ? 'checked' : '' }} class="order_stats_update" hidden>
                                <span>{{ translate('This_Year') }}</span>
                            </label>
                            <label>
                                <input type="radio" name="statistics" value="this_month"
                                    {{ 3 == 'this_month' ? 'checked' : '' }} class="order_stats_update" hidden>
                                <span>{{ translate('This_Month') }}</span>
                            </label>
                            <label>
                                <input type="radio" name="statistics" value="this_week"
                                    {{ 2 == 'this_week' ? 'checked' : '' }} class="order_stats_update" hidden>
                                <span>{{ translate('This_Week') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row g-2" id="order_stats">
                    <div class="col-12">
                        <div class="row g-3 mb-3">
                            <div class="col-xl-3 col-sm-6">
                                <div class="resturant-card card--bg-1">
                                    <h4 class="title">22</h4>
                                    <span class="subtitle">{{ translate('messages.total_restaurants') }}</span>
                                    <img class="resturant-icon"
                                        src="{{ asset('/public/assets/admin/img/total-store.png') }}" alt="store">
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="resturant-card card--bg-2">
                                    <h4 class="title">45</h4>
                                    <span class="subtitle">{{ translate('messages.active_restaurants') }}</span>
                                    <img class="resturant-icon"
                                        src="{{ asset('/public/assets/admin/img/active-store.png') }}" alt="store">
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="resturant-card card--bg-3">
                                    <h4 class="title">12</h4>
                                    <span class="subtitle">{{ translate('messages.inactive_restaurants') }}</span>
                                    <img class="resturant-icon"
                                        src="{{ asset('/public/assets/admin/img/close-store.png') }}" alt="store">
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="resturant-card card--bg-4">
                                    <h4 class="title">12</h4>
                                    <span class="subtitle">{{ translate('messages.newly_joined_restaurants') }}</span>
                                    <img class="resturant-icon" src="{{ asset('/public/assets/admin/img/add-store.png') }}"
                                        alt="store">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row g-2">
                            <div class="col-sm-6 col-lg-3">
                                <a class="order--card h-100" href="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                            <img src="{{ asset('/public/assets/admin/img/dashboard/grocery/unassigned.svg') }}"
                                                alt="dashboard" class="oder--card-icon">
                                            <span>{{ translate('messages.unassigned_orders') }}</span>
                                        </h6>
                                        <span class="card-title text-3F8CE8">

                                        </span>
                                    </div>
                                </a>
                            </div>

                            <div class="col-sm-6 col-lg-3">
                                <a class="order--card h-100" href="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                            <img src="{{ asset('/public/assets/admin/img/dashboard/grocery/accepted.svg') }}"
                                                alt="dashboard" class="oder--card-icon">
                                            <span>{{ translate('Accepted by Delivery Man') }}</span>
                                        </h6>
                                        <span class="card-title text-success">
                                            4
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <a class="order--card h-100" href="5">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                            <img src="{{ asset('/public/assets/admin/img/dashboard/grocery/packaging.svg') }}"
                                                alt="dashboard" class="oder--card-icon">
                                            <span>{{ translate('Packaging') }}</span>
                                        </h6>
                                        <span class="card-title text-FFA800">
                                            7
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <div class="col-sm-6 col-lg-3">
                                <a class="order--card h-100" href="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                            <img src="{{ asset('/public/assets/admin/img/dashboard/grocery/out-for.svg') }}"
                                                alt="dashboard" class="oder--card-icon">
                                            <span>{{ translate('Out for Delivery') }}</span>
                                        </h6>
                                        <span class="card-title text-success">
                                            34567
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <div class="col-sm-6 col-lg-3">
                                <a class="order--card h-100" href="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                            <img src="{{ asset('/public/assets/admin/img/dashboard/grocery/delivered.svg') }}"
                                                alt="dashboard" class="oder--card-icon">
                                            <span>{{ translate('messages.delivered') }}</span>
                                        </h6>
                                        <span class="card-title text-success">
                                            78
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <div class="col-sm-6 col-lg-3">
                                <a class="order--card h-100" href="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                            <img src="{{ asset('/public/assets/admin/img/order-status/canceled.svg') }}"
                                                alt="dashboard" class="oder--card-icon">
                                            <span>{{ translate('messages.canceled') }}</span>
                                        </h6>
                                        <span class="card-title text-danger">
                                            4
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <div class="col-sm-6 col-lg-3">
                                <a class="order--card h-100" href="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                            <img src="{{ asset('/public/assets/admin/img/order-status/refunded.svg') }}"
                                                alt="dashboard" class="oder--card-icon">
                                            <span>{{ translate('messages.refunded') }}</span>
                                        </h6>
                                        <span class="card-title text-danger">
                                            2
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <div class="col-sm-6 col-lg-3">
                                <a class="order--card h-100" href="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                            <img src="{{ asset('/public/assets/admin/img/order-status/payment-failed.svg') }}"
                                                alt="dashboard" class="oder--card-icon">
                                            <span>{{ translate('messages.payment_failed') }}</span>
                                        </h6>
                                        <span class="card-title text-danger">
                                            3
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- End Stats -->

        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{ translate('messages.welcome') }},
                        {{ auth('admin')->user()->f_name }}.</h1>
                    <p class="page-header-text">{{ translate('messages.employee_welcome_message') }}</p>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
    </div>
@endsection

@push('script')
    <script src="{{ asset('public/assets/admin') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('public/assets/admin') }}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script
        src="{{ asset('public/assets/admin') }}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js">
    </script>

    <!-- Apex Charts -->
    <script src="{{ asset('/public/assets/admin/js/apex-charts/apexcharts.js') }}"></script>
    <!-- Apex Charts -->
@endpush


@push('script_2')
    <!-- Dognut Pie Chart -->
    <script>
        "use strict";
        let options;
        let chart;
        options = {
            series: [2, 3, 4],
            chart: {
                width: 320,
                type: 'donut',
            },
            labels: ['{{ translate('Customer') }}', '{{ translate('Store') }}', '{{ translate('Delivery man') }}'],
            dataLabels: {
                enabled: false,
                style: {
                    colors: ['#005555', '#00aa96', '#b9e0e0', ]
                }
            },
            responsive: [{
                breakpoint: 1650,
                options: {
                    chart: {
                        width: 250
                    },
                }
            }],
            colors: ['#005555', '#00aa96', '#111'],
            fill: {
                colors: ['#005555', '#00aa96', '#b9e0e0']
            },
            legend: {
                show: false
            },
        };

        chart = new ApexCharts(document.querySelector("#dognut-pie"), options);
        chart.render();


        options = {
            series: [{
                name: '{{ translate('Gross Sale') }}',
                data: [2]
            }, {
                name: '{{ translate('Admin Comission') }}',
                data: [2]
            }, {
                name: '{{ translate('Delivery Comission') }}',
                data: [3]
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: false
                },
                colors: ['#76ffcd', '#ff6d6d', '#005555'],
            },
            colors: ['#76ffcd', '#ff6d6d', '#005555'],
            dataLabels: {
                enabled: false,
                colors: ['#76ffcd', '#ff6d6d', '#005555'],
            },
            stroke: {
                curve: 'smooth',
                width: 2,
                colors: ['#76ffcd', '#ff6d6d', '#005555'],
            },
            fill: {
                type: 'gradient',
                colors: ['#76ffcd', '#ff6d6d', '#005555'],
            },
            xaxis: {
                //   type: 'datetime',
                categories: [2]
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
        };

        chart = new ApexCharts(document.querySelector("#grow-sale-chart"), options);
        chart.render();


        <
        !--Dognut Pie Chart-- >

        // INITIALIZATION OF CHARTJS
        // =======================================================
        Chart.plugins.unregister(ChartDataLabels);

        $('.js-chart').each(function() {
            $.HSCore.components.HSChartJS.init($(this));
        });

        let updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));


        $('.order_stats_update').on('change', function() {
            let type = $(this).val();
            order_stats_update(type);
        })

        function order_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '',
                data: {
                    statistics_type: type
                },
                beforeSend: function() {
                    $('#loading').show()
                },
                success: function(data) {
                    insert_param('statistics_type', type);
                    $('#order_stats').html(data.view)
                },
                complete: function() {
                    $('#loading').hide()
                }
            });
        }

        $('.fetch_data_zone_wise').on('change', function() {
            let zone_id = $(this).val();
            fetch_data_zone_wise(zone_id);
        })


        function fetch_data_zone_wise(zone_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '',
                data: {
                    zone_id: zone_id
                },
                beforeSend: function() {
                    $('#loading').show()
                },
                success: function(data) {
                    insert_param('zone_id', zone_id);
                    $('#order_stats').html(data.order_stats);
                    $('#user-overview-boarde').html(data.user_overview);
                    $('#monthly-earning-graph').html(data.monthly_graph);
                    $('#popular-restaurants-view').html(data.popular_restaurants);
                    $('#top-deliveryman-view').html(data.top_deliveryman);
                    $('#top-rated-foods-view').html(data.top_rated_foods);
                    $('#top-restaurants-view').html(data.top_restaurants);
                    $('#top-selling-foods-view').html(data.top_selling_foods);
                    $('#stat_zone').html(data.stat_zone);
                },
                complete: function() {
                    $('#loading').hide()
                }
            });
        }

        $('.user_overview_stats_update').on('change', function() {
            let type = $(this).val();
            user_overview_stats_update(type);
        })


        function user_overview_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '',
                data: {
                    user_overview: type
                },
                beforeSend: function() {
                    $('#loading').show()
                },
                success: function(data) {
                    insert_param('user_overview', type);
                    $('#user-overview-board').html(data.view)
                },
                complete: function() {
                    $('#loading').hide()
                }
            });
        }

        $('.commission_overview_stats_update').on('change', function() {
            let type = $(this).val();
            commission_overview_stats_update(type);
        })


        function commission_overview_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '',
                data: {
                    commission_overview: type
                },
                beforeSend: function() {
                    $('#loading').show()
                },
                success: function(data) {
                    insert_param('commission_overview', type);
                    $('#commission-overview-board').html(data.view)
                    $('#gross_sale').html(data.gross_sale)
                },
                complete: function() {
                    $('#loading').hide()
                }
            });
        }

        function insert_param(key, value) {
            key = encodeURIComponent(key);
            value = encodeURIComponent(value);
            // kvp looks like ['key1=value1', 'key2=value2', ...]
            let kvp = document.location.search.substr(1).split('&');
            let i = 0;

            for (; i < kvp.length; i++) {
                if (kvp[i].startsWith(key + '=')) {
                    let pair = kvp[i].split('=');
                    pair[1] = value;
                    kvp[i] = pair.join('=');
                    break;
                }
            }
            if (i >= kvp.length) {
                kvp[kvp.length] = [key, value].join('=');
            }
            // can return this or...
            let params = kvp.join('&');
            // change url page with new params
            window.history.pushState('page2', 'Title', '{{ url()->current() }}?' + params);
        }
    </script>
@endpush
