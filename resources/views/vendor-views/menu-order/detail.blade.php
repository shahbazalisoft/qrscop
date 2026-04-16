@extends('layouts.vendor.app')

@section('title', translate('messages.order_details') . ' - ' . $order->order_id)

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">
                        <span class="page-header-icon">
                            <img src="{{ asset('/public/assets/admin/img/shopping-basket.png') }}" class="w--20" alt="">
                        </span>
                        <span>
                            {{ translate('messages.order_detail') }}
                            <span class="badge badge-soft-dark ml-1">#{{ $order->order_id }}</span>@if($order->table_no) |
                            <span class="badge badge-soft-dark ml-1"><i class="tio-chair" style="color: #24bac3"></i>Table-No {{$order->table_no}}</span>@endif
                        </span>
                    </h1>
                </div>
                <div class="col-sm-auto">
                    <a class="btn btn-sm btn--primary" href="{{ route('vendor.menu-order.list', 'all') }}">
                        <i class="tio-back-ui mr-1"></i> {{ translate('messages.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Order Info -->
            <div class="col-lg-8 mb-3">
                <!-- Order Items Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">{{ translate('messages.order_items') }}
                            <span class="badge badge-soft-dark ml-1">{{ $order->items->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless table-thead-bordered table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ translate('messages.#') }}</th>
                                        <th>{{ translate('messages.item') }}</th>
                                        <th>{{ translate('messages.size') }}</th>
                                        <th class="text-right">{{ translate('messages.price') }}</th>
                                        <th class="text-center">{{ translate('messages.qty') }}</th>
                                        <th class="text-right">{{ translate('messages.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->image)
                                                        <img src="{{ $item->image }}"
                                                             class="avatar avatar-sm mr-2 onerror-image"
                                                             data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                                             alt="{{ $item->item_name }}"
                                                             onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'">
                                                    @endif
                                                    <span>{{ $item->item_name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $item->size ?? '-' }}</td>
                                            <td class="text-right">{{ \App\CentralLogics\Helpers::format_currency($item->item_price) }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-right">{{ \App\CentralLogics\Helpers::format_currency($item->item_price * $item->quantity) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ translate('messages.order_summary') }}</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-6">{{ translate('messages.subtotal') }}</dt>
                            <dd class="col-sm-6 text-right">{{ \App\CentralLogics\Helpers::format_currency($order->subtotal) }}</dd>

                            @if($order->discount > 0)
                                <dt class="col-sm-6">{{ translate('messages.discount') }}</dt>
                                <dd class="col-sm-6 text-right text-danger">- {{ \App\CentralLogics\Helpers::format_currency($order->discount) }}</dd>
                            @endif

                            @if($order->delivery_fee > 0)
                                <dt class="col-sm-6">{{ translate('messages.delivery_fee') }}</dt>
                                <dd class="col-sm-6 text-right">{{ \App\CentralLogics\Helpers::format_currency($order->delivery_fee) }}</dd>
                            @endif

                            <dt class="col-sm-6 border-top pt-2"><strong>{{ translate('messages.total') }}</strong></dt>
                            <dd class="col-sm-6 text-right border-top pt-2">
                                <strong>{{ \App\CentralLogics\Helpers::format_currency($order->total) }}</strong>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">{{ translate('messages.order_status') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            @if($order->status == 'pending')
                                <span class="badge badge-soft-warning p-2 px-3" style="font-size: 14px;">{{ translate('messages.pending') }}</span>
                            @elseif($order->status == 'confirmed')
                                <span class="badge badge-soft-info p-2 px-3" style="font-size: 14px;">{{ translate('messages.confirmed') }}</span>
                            @elseif($order->status == 'preparing')
                                <span class="badge badge-soft-primary p-2 px-3" style="font-size: 14px;">{{ translate('messages.preparing') }}</span>
                            @elseif($order->status == 'completed')
                                <span class="badge badge-soft-success p-2 px-3" style="font-size: 14px;">{{ translate('messages.completed') }}</span>
                            @elseif($order->status == 'cancelled')
                                <span class="badge badge-soft-danger p-2 px-3" style="font-size: 14px;">{{ translate('messages.cancelled') }}</span>
                            @endif
                        </div>

                        <form id="status-update-form" action="{{ route('vendor.menu-order.status-update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label>{{ translate('messages.change_status') }}</label>
                                <select name="status" id="order-status-select" class="form-control">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>{{ translate('messages.pending') }}</option>
                                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>{{ translate('messages.confirmed') }}</option>
                                    <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>{{ translate('messages.preparing') }}</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>{{ translate('messages.completed') }}</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>{{ translate('messages.cancelled') }}</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Customer Info Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">{{ translate('messages.customer_information') }}</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-5">{{ translate('messages.name') }}</dt>
                            <dd class="col-7">{{ $order->customer_name ?? 'N/A' }}</dd>

                            <dt class="col-5">{{ translate('messages.phone') }}</dt>
                            <dd class="col-7">{{ $order->customer_phone ?? 'N/A' }}</dd>

                            <dt class="col-5">{{ translate('messages.order_type') }}</dt>
                            <dd class="col-7">
                                @if($order->order_type == 'delivery')
                                    <span class="badge badge-soft-info">{{ translate('messages.delivery') }}</span>
                                @else
                                    <span class="badge badge-soft-primary">{{ translate('messages.dine_in') }}</span>
                                @endif
                            </dd>

                            @if($order->delivery_address)
                                <dt class="col-5">{{ translate('messages.address') }}</dt>
                                <dd class="col-7">{{ $order->delivery_address }}</dd>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Instructions Card -->
                @if($order->instructions)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">{{ translate('messages.instructions') }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $order->instructions }}</p>
                        </div>
                    </div>
                @endif

                <!-- Order Meta -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ translate('messages.order_info') }}</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-5">{{ translate('messages.order_id') }}</dt>
                            <dd class="col-7">{{ $order->order_id }}</dd>

                            <dt class="col-5">{{ translate('messages.placed_at') }}</dt>
                            <dd class="col-7">{{ $order->created_at->format('d M Y, h:i A') }}</dd>

                            @if($order->device_id)
                                <dt class="col-5">{{ translate('messages.device') }}</dt>
                                <dd class="col-7"><small class="text-muted">{{ Str::limit($order->device_id, 20) }}</small></dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
<script>
    "use strict";
    var previousStatus = '{{ $order->status }}';
    $('#order-status-select').on('change', function () {
        var newStatus = $(this).val();
        var selectEl = $(this);
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
                $('#status-update-form').submit();
            } else {
                selectEl.val(previousStatus);
            }
        });
    });
</script>
@endpush
