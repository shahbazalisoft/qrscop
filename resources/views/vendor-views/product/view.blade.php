@extends('layouts.vendor.app')

@section('title', translate('Item Preview'))

@push('css_or_js')
@endpush

@section('content')
    @php($store_data = \App\CentralLogics\Helpers::get_store_data())

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between">
                <h1 class="page-header-title text-break">
                    <span class="page-header-icon">
                        <img src="{{ asset('public/assets/admin/img/items.png') }}" class="w--22" alt="">
                    </span>
                    <span>{{ $product['name'] }}</span>
                </h1>
                <div>

                    
                        <a data-toggle="modal" data-id="{{ $product->id }}" data-target="#update-quantity"
                            class="btn btn--primary update-quantity">
                            {{ translate('messages.Update_Stock') }}
                        </a>
                    <a href="{{ route('vendor.item.edit', [$product['id']]) }}" class="btn btn--primary">
                        <i class="tio-edit"></i> {{ translate('messages.edit') }}
                    </a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="review--information-wrapper mb-3">
            <div class="card h-100">
                <!-- Body -->
                <div class="card-body">
                    <div class="row align-items-md-center">
                        <div class="col-lg-5 col-md-6 mb-3 mb-md-0">
                            <div class="d-flex flex-wrap align-items-center food--media">
                                <img class="avatar avatar-xxl avatar-4by3 mr-4 onerror-image"
                                    src="{{ $product['image_full_url'] }}"
                                    data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                    alt="Image Description">
                                <div class="d-block">
                                    <div class="rating--review">
                                        <h1 class="title">1<span
                                                class="out-of">/5</span></h1>
                                        <div class="rating">
                                            @foreach (range(1, 5) as $i)
                                                <span>
                                                    @if (3 >= $i)
                                                        <i class="tio-star"></i>
                                                    @elseif (2 >= $i - 0.5)
                                                        <i class="tio-star-half"></i>
                                                    @else
                                                        <i class="tio-star-outlined"></i>
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                        <div class="info">
                                            <span>{{ translate('messages.of') }} 4
                                                {{ translate('messages.reviews') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7 col-md-6 mx-auto">
                            <ul class="list-unstyled list-unstyled-py-2 mb-0 rating--review-right py-3">
                                @php($total = 4)
                                <!-- Review Ratings -->
                                <li class="d-flex align-items-center font-size-sm">
                                    @php($five = 5)
                                    <span class="progress-name mr-3">{{ translate('excellent') }}</span>
                                    <div class="progress flex-grow-1">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $total == 0 ? 0 : ($five / $total) * 100 }}%;"
                                            aria-valuenow="{{ $total == 0 ? 0 : ($five / $total) * 100 }}" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                    <span class="ml-3">{{ $five }}</span>
                                </li>
                                <!-- End Review Ratings -->

                                <!-- Review Ratings -->
                                <li class="d-flex align-items-center font-size-sm">
                                    @php($four = 4)
                                    <span class="progress-name mr-3">{{ translate('good') }}</span>
                                    <div class="progress flex-grow-1">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $total == 0 ? 0 : ($four / $total) * 100 }}%;"
                                            aria-valuenow="{{ $total == 0 ? 0 : ($four / $total) * 100 }}" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                    <span class="ml-3">{{ $four }}</span>
                                </li>
                                <!-- End Review Ratings -->

                                <!-- Review Ratings -->
                                <li class="d-flex align-items-center font-size-sm">
                                    @php($three = 3)
                                    <span class="progress-name mr-3">{{ translate('average') }}</span>
                                    <div class="progress flex-grow-1">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $total == 0 ? 0 : ($three / $total) * 100 }}%;"
                                            aria-valuenow="{{ $total == 0 ? 0 : ($three / $total) * 100 }}" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                    <span class="ml-3">{{ $three }}</span>
                                </li>
                                <!-- End Review Ratings -->

                                <!-- Review Ratings -->
                                <li class="d-flex align-items-center font-size-sm">
                                    @php($two = 2)
                                    <span class="progress-name mr-3">{{ translate('below_average') }}</span>
                                    <div class="progress flex-grow-1">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $total == 0 ? 0 : ($two / $total) * 100 }}%;"
                                            aria-valuenow="{{ $total == 0 ? 0 : ($two / $total) * 100 }}" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                    <span class="ml-3">{{ $two }}</span>
                                </li>
                                <!-- End Review Ratings -->

                                <!-- Review Ratings -->
                                <li class="d-flex align-items-center font-size-sm">
                                    @php($one = 1)
                                    <span class="progress-name mr-3">{{ translate('poor') }}</span>
                                    <div class="progress flex-grow-1">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $total == 0 ? 0 : ($one / $total) * 100 }}%;"
                                            aria-valuenow="{{ $total == 0 ? 0 : ($one / $total) * 100 }}" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                    <span class="ml-3">{{ $one }}</span>
                                </li>
                                <!-- End Review Ratings -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->
        @if (\App\CentralLogics\Helpers::get_store_data()->review_permission)
            <!-- Description Card Start -->
            <div class="card mb-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless table-thead-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th class="px-4 border-0">
                                        <h4 class="m-0 text-capitalize">{{ translate('short_description') }}</h4>
                                    </th>
                                        <th class="px-4 border-0">
                                            <h4 class="m-0 text-capitalize">{{ translate('Nutrition') }}</h4>
                                        </th>


                                        <th class="px-4 border-0">
                                            <h4 class="m-0 text-capitalize">{{ translate('Stock') }}</h4>
                                        </th>
                                    
                                    <th class="px-4 border-0">
                                        <h4 class="m-0 text-capitalize">{{ translate('tags') }}</h4>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 max-w--220px">
                                        <div class="">
                                            {!! $product['description'] !!}
                                        </div>
                                    </td>

                                    <td class="px-4">
                                        <span class="d-block mb-1">
                                            <span>{{ translate('messages.price') }} : </span>
                                            <strong>{{ \App\CentralLogics\Helpers::format_currency($product['price']) }}</strong>
                                        </span>
                                        <span class="d-block mb-1">
                                            <span>{{ translate('messages.discount') }} :</span>
                                                         <strong>  {{$product['discount_type'] == 'percent' ? $product['discount'] . ' %' : \App\CentralLogics\Helpers::format_currency($product['discount']) }}   </strong>
                                            
                                        </span>
                                        
                                            <span class="d-block mb-1">
                                                {{ translate('messages.available_time_starts') }} :
                                                <strong>{{ date(config('timeformat'), strtotime($product['available_time_starts'])) }}</strong>
                                            </span>
                                            <span class="d-block mb-1">
                                                {{ translate('messages.available_time_ends') }} :
                                                <strong>{{ date(config('timeformat'), strtotime($product['available_time_ends'])) }}</strong>
                                            </span>
                                    </td>
        @endif
        
        @if ($product->tags)
            <td>
                @foreach ($product->tags as $c)
                    {{ $c->tag . ',' }}
                @endforeach
            </td>
        @endif
        
        </tr>
        </tbody>
        </table>
    </div>
    </div>
    </div>
    <!-- Description Card End -->
    </div>
    {{-- Add Quantity Modal --}}
    <div class="modal fade update-quantity-modal" id="update-quantity" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">

                    <form action="{{ route('vendor.item.stock-update') }}" method="post">
                        @csrf
                        <div class="mt-2 rest-part w-100"></div>
                        <div class="btn--container justify-content-end">
                            <button type="reset" data-dismiss="modal" aria-label="Close"
                                class="btn btn--reset">{{ translate('cancel') }}</button>
                            <button type="submit" id="submit_new_customer"
                                class="btn btn--primary">{{ translate('update_stock') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        "use strict";

        $('.update-quantity').on('click', function() {
            let val = $(this).data('id');
            $.get({
                url: '{{ route('vendor.item.get_stock') }}',
                data: {
                    id: val
                },
                dataType: 'json',
                success: function(data) {
                    $('.rest-part').empty().html(data.view);
                    update_qty();
                },
            });
        })

        function update_qty() {
            let total_qty = 0;
            let qty_elements = $('input[name^="stock_"]');
            for (let i = 0; i < qty_elements.length; i++) {
                total_qty += parseInt(qty_elements.eq(i).val());
            }
            if (qty_elements.length > 0) {

                $('input[name="current_stock"]').attr("readonly", 'readonly');
                $('input[name="current_stock"]').val(total_qty);
            } else {
                $('input[name="current_stock"]').attr("readonly", false);
            }
        }
    </script>
@endpush
