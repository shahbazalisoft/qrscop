@extends('layouts.vendor.app')

@section('title', translate('messages.menu_orders'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title text-capitalize">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/order.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{ translate('messages.menu_orders') }}
                    <span class="badge badge-soft-dark ml-2">{{ $statusCounts['all'] }}</span>
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <!-- Status Filter Tabs -->
        <div class="card mb-3">
            <div class="card-body p-2">
                <ul class="nav nav-pills">
                    @foreach(['all' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'preparing' => 'Preparing', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $key => $label)
                        <li class="nav-item">
                            <a class="nav-link {{ $status == $key ? 'active' : '' }}"
                               href="{{ route('vendor.menu-order.list', $key) }}">
                                {{ translate('messages.' . $key) }}
                                <span class="badge badge-soft-dark ml-1">{{ $statusCounts[$key] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper justify-content-end">
                    <form class="search-form min--260" method="GET" action="{{ route('vendor.menu-order.list', $status) }}">
                        <div class="input-group input--group">
                            <input type="search" value="{{ request()->search ?? null }}" name="search"
                                   class="form-control"
                                   placeholder="{{ translate('messages.search_by_order_id_or_customer') }}"
                                   aria-label="{{ translate('messages.search') }}">
                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Header -->

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">{{ translate('messages.#') }}</th>
                                <th class="border-0">{{ translate('messages.order_id') }}</th>
                                <th class="border-0">{{ translate('messages.order_date') }}</th>
                                <th class="border-0">{{ translate('messages.customer') }}</th>
                                <th class="border-0">{{ translate('messages.order_type') }}</th>
                                <th class="border-0">{{ translate('messages.items') }}</th>
                                <th class="border-0">{{ translate('messages.total_amount') }}</th>
                                <th class="border-0 text-center">{{ translate('messages.status') }}</th>
                                <th class="border-0 text-center">{{ translate('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $key => $order)
                                <tr>
                                    <td>{{ $key + $orders->firstItem() }}</td>
                                    <td>
                                        <a href="{{ route('vendor.menu-order.details', $order->id) }}" class="text-primary font-weight-bold">
                                            {{ $order->order_id }}
                                        </a>
                                    </td>
                                    <td>
                                        <div>{{ $order->created_at->format('d M Y') }}</div>
                                        <div class="small text-muted">{{ $order->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <strong>{{ $order->customer_name ?? 'N/A' }}</strong>
                                        @if($order->customer_phone)
                                            <div class="small text-muted">{{ $order->customer_phone }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->order_type == 'delivery')
                                            <span class="badge badge-soft-info">{{ translate('messages.delivery') }}</span>
                                        @else
                                            <span class="badge badge-soft-primary">{{ translate('messages.dine_in') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-dark">{{ $order->items->count() }}</span>
                                    </td>
                                    <td>
                                        <div class="text-right mw--85px">
                                            {{ \App\CentralLogics\Helpers::format_currency($order->total) }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($order->status == 'pending')
                                            <span class="badge badge-soft-warning">{{ translate('messages.pending') }}</span>
                                        @elseif($order->status == 'confirmed')
                                            <span class="badge badge-soft-info">{{ translate('messages.confirmed') }}</span>
                                        @elseif($order->status == 'preparing')
                                            <span class="badge badge-soft-primary">{{ translate('messages.preparing') }}</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge badge-soft-success">{{ translate('messages.completed') }}</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="badge badge-soft-danger">{{ translate('messages.cancelled') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center align-items-center gap-2">
                                            <form id="status-form-{{ $order->id }}" action="{{ route('vendor.menu-order.status-update', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="form-control form-control-sm order-status-select" data-id="{{ $order->id }}" data-previous="{{ $order->status }}">
                                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>{{ translate('messages.pending') }}</option>
                                                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>{{ translate('messages.confirmed') }}</option>
                                                    <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>{{ translate('messages.preparing') }}</option>
                                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>{{ translate('messages.completed') }}</option>
                                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>{{ translate('messages.cancelled') }}</option>
                                                </select>
                                            </form>
                                            {{-- <a class="btn btn-sm btn--warning btn-outline-warning action-btn"
                                               href="{{ route('vendor.menu-order.details', $order->id) }}">
                                                <i class="tio-visible-outlined"></i>
                                            </a> --}}
                                            <button class="btn btn-sm btn--warning btn-outline-warning action-btn view-order-btn" data-id="{{ $order->id }}">
                                                <i class="tio-visible-outlined"></i>
                                            </button>
                                        <a class="btn btn-sm btn--primary btn-outline-primary action-btn" target="_blank" href="{{route('vendor.menu-order.generate-invoice',[$order['id']])}}"><i class="tio-print"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if(count($orders) === 0)
                        <div class="empty--data">
                            <img src="{{ asset('/public/assets/admin/svg/illustrations/sorry.svg') }}" alt="">
                            <h5>{{ translate('no_data_found') }}</h5>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="card-footer">
                {!! $orders->appends(request()->query())->links() !!}
            </div>
        </div>
    </div>


    <!-- Order Detail Modal -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalTitle">{{ translate('messages.order_details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="orderModalBody">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
<script>
    "use strict";
    $('.view-order-btn').on('click', function () {
        var orderId = $(this).data('id');
        $('#orderModalTitle').text('{{ translate('messages.order_details') }}');
        $('#orderModalBody').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>');
        $('#orderDetailModal').modal('show');
        $.get("{{ url('vendor/menu-order/quick-view') }}/" + orderId, function (data) {
            $('#orderModalBody').html(data.html);
        }).fail(function () {
            $('#orderModalBody').html('<div class="text-center py-3"><p class="text-danger">{{ translate('messages.failed_to_load') }}</p></div>');
        });
    });

    $('.order-status-select').on('change', function () {
        var selectEl = $(this);
        var orderId = selectEl.data('id');
        var previousStatus = selectEl.data('previous');
        Swal.fire({
            title: '{{ translate('messages.Are you sure?') }}',
            text: '{{ translate('messages.Want to change the order status') }}',
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#005555',
            cancelButtonText: '{{ translate('messages.no') }}',
            confirmButtonText: '{{ translate('messages.Yes') }}',
            reverseButtons: true
        }).then(function (result) {
            if (result.value) {
                $('#status-form-' + orderId).submit();
            } else {
                selectEl.val(previousStatus);
            }
        });
    });
</script>
@endpush
