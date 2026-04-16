<div class="row">
    <div class="col-md-7">
        <!-- Order Items -->
        <h6 class="mb-2">{{ translate('messages.order_items') }} <span class="badge badge-soft-dark">{{ $order->items->count() }}</span></h6>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>{{ translate('messages.item') }}</th>
                        <th class="text-center">{{ translate('messages.qty') }}</th>
                        <th class="text-right">{{ translate('messages.price') }}</th>
                        <th class="text-right">{{ translate('messages.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->image)
                                        <img src="{{ $item->image }}" class="rounded mr-2"
                                             width="40" height="40" style="object-fit:cover;"
                                             onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'">
                                    @endif
                                    <div>
                                        <span class="d-block">{{ $item->item_name }}</span>
                                        @if($item->size && $item->size !== 'default')
                                            <small class="text-muted">{{ $item->size }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">{{ \App\CentralLogics\Helpers::format_currency($item->item_price) }}</td>
                            <td class="text-right">{{ \App\CentralLogics\Helpers::format_currency($item->item_price * $item->quantity) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Order Summary -->
        <div class="border rounded p-3 mt-2">
            <div class="d-flex justify-content-between mb-1">
                <span>{{ translate('messages.subtotal') }}</span>
                <span>{{ \App\CentralLogics\Helpers::format_currency($order->subtotal) }}</span>
            </div>
            @if($order->discount > 0)
                <div class="d-flex justify-content-between mb-1 text-danger">
                    <span>{{ translate('messages.discount') }}</span>
                    <span>- {{ \App\CentralLogics\Helpers::format_currency($order->discount) }}</span>
                </div>
            @endif
            @if($order->delivery_fee > 0)
                <div class="d-flex justify-content-between mb-1">
                    <span>{{ translate('messages.delivery_fee') }}</span>
                    <span>{{ \App\CentralLogics\Helpers::format_currency($order->delivery_fee) }}</span>
                </div>
            @endif
            <hr class="my-2">
            <div class="d-flex justify-content-between font-weight-bold">
                <span>{{ translate('messages.total') }}</span>
                <span>{{ \App\CentralLogics\Helpers::format_currency($order->total) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <!-- Store Info -->
        @if($order->store)
            <h6 class="mb-2">{{ translate('messages.store') }}</h6>
            <div class="border rounded p-3 mb-3">
                <div class="font-weight-bold">{{ $order->store->name }}</div>
            </div>
        @endif

        <!-- Order Info -->
        <h6 class="mb-2">{{ translate('messages.order_info') }}</h6>
        <div class="border rounded p-3 mb-3">
            <div class="mb-2">
                <small class="text-muted">{{ translate('messages.order_id') }}</small>
                <div class="font-weight-bold">{{ $order->order_id }}</div>
            </div>
            <div class="mb-2">
                <small class="text-muted">{{ translate('messages.placed_at') }}</small>
                <div>{{ $order->created_at->format('d M Y, h:i A') }}</div>
            </div>
            <div class="mb-2">
                <small class="text-muted">{{ translate('messages.order_type') }}</small>
                <div>
                    @if($order->order_type == 'delivery')
                        <span class="badge badge-soft-info">{{ translate('messages.delivery') }}</span>
                    @else
                        <span class="badge badge-soft-primary">{{ translate('messages.dine_in') }}</span>
                    @endif
                </div>
            </div>
            <div>
                <small class="text-muted">{{ translate('messages.status') }}</small>
                <div>
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
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <h6 class="mb-2">{{ translate('messages.customer_information') }}</h6>
        <div class="border rounded p-3">
            <div class="mb-2">
                <small class="text-muted">{{ translate('messages.name') }}</small>
                <div>{{ $order->customer_name ?? 'N/A' }}</div>
            </div>
            @if($order->customer_phone)
                <div class="mb-2">
                    <small class="text-muted">{{ translate('messages.phone') }}</small>
                    <div>{{ $order->customer_phone }}</div>
                </div>
            @endif
            @if($order->delivery_address)
                <div class="mb-2">
                    <small class="text-muted">{{ translate('messages.address') }}</small>
                    <div>{{ $order->delivery_address }}</div>
                </div>
            @endif
            @if($order->instructions)
                <div>
                    <small class="text-muted">{{ translate('messages.instructions') }}</small>
                    <div>{{ $order->instructions }}</div>
                </div>
            @endif
        </div>
    </div>
</div>
