@extends('layouts.vendor.app')

@section('title', translate('messages.settings'))

@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title mr-3">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/config.png') }}" class="w--26" alt="">
                </span>
                <span>
                    {{ translate('messages.store_setup') }}
                </span>
            </h1>
            @includeif('vendor-views.business-settings.partials._header')
        </div>
        <!-- End Page Header -->
        <div class="card mb-3">
            <div class="card-body py-3">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <h4 class="card-title align-items-center d-flex">
                        <img src="{{ asset('public/assets/admin/img/store.png') }}" class="w--20 mr-1" alt="">
                        <span>{{ translate('messages.store_temporarily_closed_title') }}</span>
                    </h4>
                    <label class="switch toggle-switch-lg m-0" for="restaurant-open-status">
                        <input type="checkbox" id="restaurant-open-status"
                            class="toggle-switch-input restaurant-open-status" {{ $store->active ? '' : 'checked' }}>
                        <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                        </span>
                    </label>
                </div>
            </div>
        </div>
        <form action="{{ route('vendor.business-settings.menu-template-cusomize-update', [$store['id']]) }}" method="post"
            enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-lg-12">
                    <h4 class="card-title mb-3 mt-1">
                        <span class="card-header-icon mr-2"><i class="tio-restaurant"></i></span>
                        <span>{{ translate('menu_template_setting') }}</span>
                    </h4>
                    <div class="card">
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-sm-6 col-lg-6">
                                        {{-- <div class="form-group mb-0"> --}}
                                            <label class="form-label"
                                                for="banner_popup_type">{{ translate('messages.popup_model') }}
                                                <span class="form-label-secondary" data-toggle="tooltip"
                                                    data-placement="right"
                                                    data-original-title="{{ translate('you want a popup modal on the menu page that appears when a customer visits the menu template.') }}">
                                                    <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                        alt="">
                                                </span>
                                            </label>
                                            <div class="resturant-type-group p-0">
                                                <label class="form-check form--check mr-2 mr-md-4">
                                                    <input class="form-check-input limit-input" type="radio"
                                                        name="banner_popup_type" value="0"
                                                        {{ $store->banner_popup_type == 0 ? 'checked' : '' }}>
                                                    <span class="form-check-label">
                                                        OFF
                                                    </span>
                                                </label>
                                                <label class="form-check form--check mr-2 mr-md-4">
                                                    <input class="form-check-input limit-input" type="radio"
                                                        name="banner_popup_type" value="1"
                                                        {{ $store->banner_popup_type == 1 ? 'checked' : '' }}>
                                                    <span class="form-check-label">
                                                        Only Banner Popup
                                                    </span>
                                                </label>
                                                <label class="form-check form--check mr-2 mr-md-4">
                                                    <input class="form-check-input limit-input" type="radio"
                                                        name="banner_popup_type" value="2"
                                                        {{ $store->banner_popup_type == 2 ? 'checked' : '' }}>
                                                    <span class="form-check-label">
                                                        Banner With Text Popup
                                                    </span>
                                                </label>
                                            </div>
                                        {{-- </div> --}}
                                    </div>
                                    <div class="col-sm-6 col-lg-6 ">
                                        {{-- <div class="form-group mb-0"> --}}
                                            <label class="form-label"
                                                for="order_type">{{ translate('messages.Customer_Order_Mode') }}</label>
                                            <div class="resturant-type-group p-0">
                                                <label class="form-check form--check mr-2 mr-md-4">
                                                    <input class="form-check-input limit-input" type="radio"
                                                        name="order_type" value="1"
                                                        {{ $store->order_type == 1 ? 'checked' : '' }}>
                                                    <span class="form-check-label">
                                                        Dine-In
                                                    </span>
                                                </label>
                                                <label class="form-check form--check mr-2 mr-md-4">
                                                    <input class="form-check-input limit-input" type="radio"
                                                        name="order_type" value="2"
                                                        {{ $store->order_type == 2 ? 'checked' : '' }}>
                                                    <span class="form-check-label">
                                                        Delivery Order
                                                    </span>
                                                </label>
                                                <label class="form-check form--check mr-2 mr-md-4">
                                                    <input class="form-check-input limit-input" type="radio"
                                                        name="order_type" value="3"
                                                        {{ $store->order_type == 3 ? 'checked' : '' }}>
                                                    <span class="form-check-label">
                                                        Both
                                                    </span>
                                                </label>
                                            </div>
                                        {{-- </div> --}}
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row g-3">
                                <div class="col-sm-6 col-md-4 col-xl-3">
                                    <div class="form-group mb-0">
                                        <label class="form-label"
                                            for="delivery_charg">{{ translate('messages.delivery_charges') }}</label>
                                        <input type="text" id="delivery_charg" name="delivery_charg"
                                            value="{{ $store->delivery_charg }}" class="form-control"
                                            placeholder="Delivery charges will be added to Delivery Order mode">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-xl-3">
                                    <div class="form-group mb-0">
                                        <label class="form-label"
                                            for="restaurant_secondary_name">{{ translate('messages.restaurant_secondary_name') }}</label>
                                        <input type="text" id="restaurant_secondary_name"
                                            name="restaurant_secondary_name"
                                            value="{{ $store->restaurant_secondary_name }}" class="form-control"
                                            placeholder="{{ translate('messages.you_can_add_alternative_name_for_only_menu_template_on_top') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-xl-3">
                                    <div class="form-group mb-0">
                                        <label class="form-label"
                                            for="restaurant_title">{{ translate('messages.restaurant_title') }}</label>
                                        <input type="text" id="restaurant_title" name="restaurant_title"
                                            value="{{ $store->restaurant_title }}" class="form-control"
                                            placeholder="Ex-All you can eat · Happy-hour food">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-xl-3">
                                    <div class="form-group mb-0">
                                        <label class="form-label"
                                            for="tracking_order_mobile_no">{{ translate('messages.tracking_order_mobile_no') }}</label>
                                        <input type="text" id="tracking_order_mobile_no"
                                            name="tracking_order_mobile_no"
                                            value="{{ $store->tracking_order_mobile_no }}" class="form-control"
                                            placeholder="Ex- ***********">
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <div class="row g-3">
                                        <div class="col-sm-12">
                                            <div class="form-group mb-0">
                                                <label class="input-label"
                                                for="default_image">{{ translate('messages.alternative_logo') }} <small
                                                    class="text-danger">* ( {{ translate('messages.ratio') }}
                                                    1:1)</small></label>
                                            <label class="text-center my-auto position-relative d-inline-block">
                                                <img class="img--176 border" id="viewer"
                                                    src="{{\App\CentralLogics\Helpers::get_full_url('store', $store?->alternative_logo ?? '', 'upload_image')}}"
                                                    alt="image" />
                                
                                                <div class="icon-file-group">
                                                    <div class="icon-file">
                                                        <input type="file" name="image" id="image"
                                                            class="custom-file-input this-url  read-url"
                                                            accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                        <i class="tio-edit"></i>
                                                    </div>
                                                </div>
                                            </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                <h4 class="card-title mb-3">
                    <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>
                    <span>{{ translate('Menu_Bottom_Navigation') }}</span>
                </h4>
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="form-group mb-0">
                                    <label for="timezone"
                                        class="form-label text-capitalize">{{ translate('messages.menu_buttom_home') }}</label>
                                        <input type="text" id="menu_buttom_home" name="menu_buttom_home"
                                                    value="{{ $store->menu_buttom_home }}" class="form-control"
                                                    placeholder="First Navi Button, Ex-Home">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="form-group mb-0">
                                    <label for="menu_buttom_special"
                                        class="form-label text-capitalize">{{ translate('messages.menu_buttom_special') }}</label>
                                        <input type="text" id="menu_buttom_special" name="menu_buttom_special"
                                                    value="{{ $store->menu_buttom_special }}" class="form-control"
                                                    placeholder="Second Navi Button, Ex-Today Specials">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-2">
                                <div class="form-group mb-0">
                                    <label for="menu_buttom_cart"
                                        class="form-label text-capitalize">{{ translate('messages.menu_buttom_cart') }}</label>
                                        <input type="text" id="menu_buttom_cart" name="menu_buttom_cart"
                                                    value="{{ $store->menu_buttom_cart }}" class="form-control"
                                                    placeholder="Third Navi Button, Ex-Cart">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-2">
                                <div class="form-group mb-0">
                                    <label for="menu_buttom_reorder"
                                        class="form-label text-capitalize">{{ translate('messages.menu_buttom_reorder') }}</label>
                                        <input type="text" id="menu_buttom_reorder" name="menu_buttom_reorder"
                                                    value="{{ $store->menu_buttom_reorder }}" class="form-control"
                                                    placeholder="Fourth Navi Button, Ex-Reorders">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-2">
                                <div class="form-group mb-0">
                                    <label for="menu_buttom_orders"
                                        class="form-label text-capitalize">{{ translate('messages.menu_buttom_orders') }}</label>
                                        <input type="text" id="menu_buttom_orders" name="menu_buttom_orders"
                                                    value="{{ $store->menu_buttom_orders }}" class="form-control"
                                                    placeholder="Five Navi Button, Ex-Orders">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="col-12">
                            <div class="justify-content-end btn--container">
                                <button type="submit" class="btn btn--primary">{{ translate('save_changes') }}</button>
                            </div>
                        </div>
            </div>
        </form>
    </div>

@endsection

@push('script_2')
    <script>
        "use strict";

        $(document).on('click', '.restaurant-open-status', function(event) {


            event.preventDefault();
            Swal.fire({
                title: '{{ translate('messages.are_you_sure') }}',
                text: '{{ $store->active ? translate('messages.you_want_to_temporarily_close_this_store') : translate('messages.you_want_to_open_this_store') }}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#00868F',
                cancelButtonText: '{{ translate('messages.no') }}',
                confirmButtonText: '{{ translate('messages.yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {

                    $.get({
                        url: '{{ route('vendor.business-settings.update-active-status') }}',
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        success: function(data) {
                            toastr.success(data.message);
                        },
                        complete: function() {
                            location.reload();
                            $('#loading').hide();
                        },
                    });
                } else {
                    event.checked = !event.checked;
                }
            })

        });



        $(document).on('click', '.delete-schedule', function() {
            let route = $(this).data('url');
            Swal.fire({
                title: '{{ translate('Want_to_delete_this_schedule?') }}',
                text: '{{ translate('If_you_select_Yes,_the_time_schedule_will_be_deleted.') }}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#00868F',
                cancelButtonText: '{{ translate('messages.no') }}',
                confirmButtonText: '{{ translate('messages.yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get({
                        url: route,
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        success: function(data) {
                            if (data.errors) {
                                for (let i = 0; i < data.errors.length; i++) {
                                    toastr.error(data.errors[i].message, {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                                }
                            } else {
                                $('#schedule').empty().html(data.view);
                                toastr.success(
                                    '{{ translate('messages.Schedule removed successfully') }}', {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                            }
                        },
                        error: function() {
                            toastr.error('{{ translate('messages.Schedule not found') }}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        },
                        complete: function() {
                            $('#loading').hide();
                        },
                    });
                }
            })
        });


        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#customFileEg1").change(function() {
            readURL(this);
        });

        $(document).on('ready', function() {
            $("#gst_status").on('change', function() {
                if ($("#gst_status").is(':checked')) {
                    $('#gst').removeAttr('readonly');
                } else {
                    $('#gst').attr('readonly', true);
                }
            });
        });

        $('#exampleModal').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget);
            let day_name = button.data('day');
            let day_id = button.data('dayid');
            let modal = $(this);
            modal.find('.modal-title').text('{{ translate('messages.Create Schedule For ') }} ' + day_name);
            modal.find('.modal-body input[name=day]').val(day_id);
        })

        $('#add-schedule').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('vendor.business-settings.add-schedule') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    if (data.errors) {
                        for (let i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        $('#schedule').empty().html(data.view);
                        $('#exampleModal').modal('hide');
                        toastr.success('{{ translate('messages.Schedule added successfully') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                error: function(XMLHttpRequest) {
                    toastr.error(XMLHttpRequest.responseText, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
