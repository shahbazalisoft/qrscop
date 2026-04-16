<script>
    window.storeId = {{ $store->id }};
    window.storePhone = @json($store->phone ?? '');
    window.trackingPhone = @json($store->tracking_order_mobile_no ?? $store->phone);
    window.storeDeliveryCharge = {{ $store->delivery_charg ?? 0 }};
    window.storeOrderType = {{ $store->order_type ?? 0 }};
    window.poweredBy = @json(\App\CentralLogics\Helpers::get_business_settings('business_name'));
    window.menuItemsData = [
        @php $globalIndex = 0; @endphp
        @foreach($categories as $category)
            @foreach($category->items as $item)
                @php
                    $disc = $item->discount ?? 0;
                    $discType = $item->discount_type ?? 'percent';
                    if ($disc > 0) {
                        $discPrice = $discType === 'percent'
                            ? round($item->price - ($item->price * $disc / 100))
                            : max(0, $item->price - $disc);
                    } else {
                        $discPrice = $item->price;
                    }
                @endphp
                {
                    id: {{ $item->id }},
                    name: @json($item->name),
                    price: {{ $discPrice }},
                    mrp: {{ $item->price }},
                    isVeg: {{ $item->veg == 1 ? 'true' : 'false' }},
                    category: @json($category->name),
                    img: "{{\App\CentralLogics\Helpers::get_full_url('product', $item['image'], 'public')}}",
                    desc: @json($item->description ?? ''),
                    discount: {{ $disc }},
                    discountType: @json($discType),
                    foodVariations: @json($item->food_variations ? json_decode($item->food_variations, true) : []),
                    tags: @json($item->tags ? $item->tags->pluck('tag')->join(',') : '')
                },
                @php $globalIndex++; @endphp
            @endforeach
        @endforeach
    ];
    window.todaySpecialIds = [
        @if(isset($todaySpecials))
            @foreach($todaySpecials as $special)
                @if($special->item)
                    {{ $special->item->id }},
                @endif
            @endforeach
        @endif
    ];
</script>
