@extends('layouts.admin.app')

@section('title', request()->product_gellary == 1 ? translate('Add item') : translate('Edit item'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('public/assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/admin/css/AI/animation/product/ai-sidebar.css') }}" rel="stylesheet">
@endpush

@section('content')


    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex flex-wrap __gap-15px justify-content-between align-items-center">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/edit.png') }}" class="w--22" alt="">
                </span>
                <span>
                    {{ request()->product_gellary == 1 ? translate('Add_item') : translate('item_update') }}
                </span>
            </h1>
        </div>
        @php($openai_config = \App\CentralLogics\Helpers::get_business_settings('openai_config'))
        <!-- End Page Header -->
        <form id="product_form" enctype="multipart/form-data" class="custom-validation" data-ajax="true">
            @if (request()->product_gellary == 1)
                @php($route = route('admin.item.store', ['product_gellary' => request()->product_gellary]))
                @php($product->price = 0)
            @else
                @php($route = route('admin.item.update', [isset($temp_product) && $temp_product == 1 ? $product['item_id'] : $product['id']]))
            @endif

            <input type="hidden" class="route_url"
                value="{{ $route ?? route('admin.item.update', [isset($temp_product) && $temp_product == 1 ? $product['item_id'] : $product['id']]) }}">
            <input type="hidden" value="{{ $temp_product ?? 0 }}" name="temp_product">
            <input type="hidden" value="{{ $product['id'] ?? null }}" name="item_id">
            <input type="hidden" id="request_type" value="admin">


            <div class="row g-2">

                @includeif('admin-views.product.partials._title_and_discription')
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-wrap align-items-center">
                            <div class="w-100 d-flex gap-3 flex-wrap flex-lg-nowrap">
                                <div class="flex-grow-1 mx-auto overflow-x-auto scrollbar-primary">
                                    <label class="text-dark d-block">
                                        {{ translate('messages.item_image') }}
                                        <small>( {{ translate('messages.ratio') }} 1:1 )</small>
                                    </label>
                                    <div class="d-flex __gap-12px __new-coba overflow-x-auto pb-2" id="coba">

                                        <input type="hidden" id="removedImageKeysInput" name="removedImageKeys"
                                            value="">
                                        @foreach ($product->images as $key => $photo)
                                            @php($photo = is_array($photo) ? $photo : ['img' => $photo, 'storage' => 'public'])
                                            <div id="product_images_{{ $key }}"
                                                class="spartan_item_wrapper min-w-176px max-w-176px">
                                                <img class="img--square onerror-image"
                                                    src="{{ \App\CentralLogics\Helpers::get_full_url('product', $photo['img'] ?? '', $photo['storage']) }}"
                                                    data-onerror-image="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                                    alt="Product image">
                                                <a href="#" data-key={{ $key }}
                                                    data-photo="{{ $photo['img'] }}"
                                                    class="spartan_remove_row function_remove_img"><i
                                                        class="tio-add-to-trash"></i></a>

                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2 btn-gallery-picker" data-picker-target="gallery" data-max-select="{{ 5 - count($product->images) }}">
                                        <i class="tio-album"></i> {{ translate('messages.Choose from Gallery') }}
                                    </button>
                                </div>
                                <div class="flex-grow-1 mx-auto pb-2 flex-shrink-0">
                                    <label class="text-dark d-block">
                                        {{ translate('messages.item_thumbnail') }}
                                        <small class="text-danger">* ( {{ translate('messages.ratio') }} 1:1 )</small>
                                    </label>
                                    <label class="d-inline-block m-0 position-relative error-wrapper">
                                        <img class="img--176 border onerror-image" id="viewer"
                                            src="{{ $product['image_full_url'] ?? asset('public/assets/admin/img/upload-img.png') }}"
                                            data-onerror-image="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                            alt="thumbnail" />
                                        <div class="icon-file-group">
                                            <div class="icon-file">
                                                <input type="file" name="image" id="customFileEg1"
                                                    class="custom-file-input read-url"
                                                    accept=".webp, .jpg, .png, .jpeg, .webp, .gif, .bmp, .tif, .tiff|image/*">
                                                <i class="tio-edit"></i>
                                            </div>
                                        </div>
                                    </label>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-gallery-picker" data-picker-target="thumbnail" data-max-select="1">
                                            <i class="tio-album"></i> {{ translate('messages.Choose from Gallery') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @includeif('admin-views.product.partials._category_and_general')
                @includeif('admin-views.product.partials._price_and_stock')

                <div class="col-md-12">
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn"
                            class="btn btn--reset">{{ translate('messages.reset') }}</button>
                        <button type="submit"
                            class="btn btn--primary">{{ isset($temp_product) && $temp_product == 1 ? translate('Edit_&_Approve') : translate('messages.submit') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal" id="food-modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close foodModalClose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/xG8fO7TXPbk"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="attribute-modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close attributeModalClose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/xG8fO7TXPbk"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span id="message-enter-choice-values" data-text="{{ translate('enter_choice_values') }}"></span>
    @includeif('admin-views.product.partials._ai_sidebar')
    @include('admin-views.product.partials._gallery_picker_modal')

@endsection


@push('script_2')
    <script>
        let count = $('.count_div').length;
    </script>

    <script src="{{ asset('public/assets/admin') }}/js/tags-input.min.js"></script>
    <script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>


    <script src="{{ asset('public/assets/admin/js/AI/products/product-title-autofill.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/AI/products/product-description-autofill.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/AI/products/general-setup-autofill.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/AI/products/product-others-autofill.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/AI/products/seo-section-autofill.js') }}"></script>
    
        <script src="{{ asset('public/assets/admin/js/AI/products/other-variation-setup-auto-fill.js') }}"></script>


    <script src="{{ asset('public/assets/admin/js/AI/products/ai-sidebar.js') }}"></script>

    <script src="{{ asset('/public/assets/admin/js/AI/products/compressor/image-compressor.js') }}"></script>
    <script src="{{ asset('/public/assets/admin/js/AI/products/compressor/compressor.min.js') }}"></script>

    <script src="{{ asset('public/assets/admin/js/gallery-picker.js') }}"></script>

    <script>
        "use strict";

        GalleryPicker.init({
            apiUrl: "{{ route('admin.gallery.api') }}",
            uploadUrl: "{{ route('admin.gallery.image-upload') }}"
        });

        let removedImageKeys = [];
        let element = "";


        $(document).on('click', '.function_remove_img', function() {
            let key = $(this).data('key');
            let photo = $(this).data('photo');
            function_remove_img(key, photo);
        });

        function function_remove_img(key, photo) {
            $('#product_images_' + key).addClass('d-none');
            removedImageKeys.push(photo);
            $('#removedImageKeysInput').val(removedImageKeys.join(','));
        }


        function show_min_max(data) {
            console.log(data);
            $('#min_max1_' + data).removeAttr("readonly");
            $('#min_max2_' + data).removeAttr("readonly");
            $('#min_max1_' + data).attr("required", "true");
            $('#min_max2_' + data).attr("required", "true");
        }

        function hide_min_max(data) {
            console.log(data);
            $('#min_max1_' + data).val(null).trigger('change');
            $('#min_max2_' + data).val(null).trigger('change');
            $('#min_max1_' + data).attr("readonly", "true");
            $('#min_max2_' + data).attr("readonly", "true");
            $('#min_max1_' + data).attr("required", "false");
            $('#min_max2_' + data).attr("required", "false");
        }

        $(document).on('change', '.show_min_max', function() {
            let data = $(this).data('count');
            show_min_max(data);
        });

        $(document).on('change', '#discount_type', function() {
            let data = document.getElementById("discount_type");
            if (data.value === 'amount') {
                $('#symble').text("({{ \App\CentralLogics\Helpers::currency_symbol() }})");
            } else {
                $('#symble').text("(%)");
            }
        });

        $(document).on('change', '.hide_min_max', function() {
            let data = $(this).data('count');
            hide_min_max(data);
        });



        $(document).ready(function() {
            $("#add_new_option_button").click(function(e) {
                add_new_option_button();
            });
        });


        function add_new_option_button() {
            $('#empty-variation').hide();
            count++;
            let add_option_view = `
                                <div class="__bg-F8F9FC-card view_new_option mb-2">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <label class="form-check form--check">
                                                <input id="options[` + count + `][required]" name="options[` + count + `][required]" class="form-check-input" type="checkbox">
                                                <span class="form-check-label">{{ translate('Required') }}</span>
                                            </label>
                                            <div>
                                                <button type="button" class="btn btn-danger btn-sm delete_input_button"
                                                    title="{{ translate('Delete') }}">
                                                    <i class="tio-add-to-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-xl-4 col-lg-6">
                                                <label for="">{{ translate('name') }}</label>
                                                <input required name=options[` + count +
                `][name] class="form-control new_option_name" type="text" data-count="` +
                count +
                `">
                                            </div>

                                            <div class="col-xl-4 col-lg-6">
                                                <div>
                                                    <label class="input-label text-capitalize d-flex align-items-center"><span class="line--limit-1">{{ translate('messages.selcetion_type') }} </span>
                                                    </label>
                                                    <div class="resturant-type-group px-0">
                                                        <label class="form-check form--check mr-2 mr-md-4">
                                                            <input class="form-check-input show_min_max" data-count="` +
                count + `" type="radio" value="multi"
                                                            name="options[` + count + `][type]" id="type` + count +
                `" checked
                                                            >
                                                            <span class="form-check-label">
                                                                {{ translate('Multiple Selection') }}
                                </span>
                            </label>

                            <label class="form-check form--check mr-2 mr-md-4">
                                <input class="form-check-input hide_min_max" data-count="` + count + `" type="radio" value="single"
                                name="options[` + count + `][type]" id="type` + count +
                `"
                                                            >
                                                            <span class="form-check-label">
                                                                {{ translate('Single Selection') }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="row g-2">
                        <div class="col-6">
                            <label for="">{{ translate('Min') }}</label>
                                                        <input id="min_max1_` + count + `" required  name="options[` +
                count + `][min]" class="form-control" type="number" min="1">
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="">{{ translate('Max') }}</label>
                                                        <input id="min_max2_` + count + `"   required name="options[` +
                count + `][max]" class="form-control" type="number" min="1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="option_price_` + count + `" >
                                            <div class="bg-white border rounded p-3 pb-0 mt-3">
                                                <div  id="option_price_view_` + count +
                `">
                                                    <div class="row g-3 add_new_view_row_class mb-3">
                                                        <div class="col-md-4 col-sm-6">
                                                            <label for="">{{ translate('Option_name') }}</label>
                                                            <input class="form-control" required type="text" name="options[` +
                count +
                `][values][0][label]" id="">
                                                        </div>
                                                        <div class="col-md-4 col-sm-6">
                                                            <label for="">{{ translate('Additional_price') }}</label>
                                                            <input class="form-control" required type="number" min="0" step="0.01" name="options[` +
                count + `][values][0][optionPrice]" id="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-3 p-3 mr-1 d-flex "  id="add_new_button_` + count +
                `">
                                                    <button type="button" class="btn btn--primary btn-outline-primary add_new_row_button" data-count="` +
                count + `">{{ translate('Add_New_Option') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;

            $("#add_new_option").append(add_option_view);



        }


        function new_option_name(value, data) {
            $("#new_option_name_" + data).empty();
            $("#new_option_name_" + data).text(value)
            console.log(value);
        }

        function removeOption(e) {
            element = $(e);
            element.parents('.view_new_option').remove();
        }

        $(document).on('click', '.delete_input_button', function() {
            let e = $(this);
            removeOption(e);
        });

        function deleteRow(e) {
            element = $(e);
            element.parents('.add_new_view_row_class').remove();
        }

        $(document).on('click', '.deleteRow', function() {
            let e = $(this);
            deleteRow(e);
        });
        let countRow = 0;

        function add_new_row_button(data) {
            // count = data;
            countRow = 1 + $('#option_price_view_' + data).children('.add_new_view_row_class').length;
            let add_new_row_view = `
            <div class="row add_new_view_row_class mb-3 position-relative pt-3 pt-sm-0">
                <div class="col-md-4 col-sm-5">
                        <label for="">{{ translate('Option_name') }}</label>
                        <input class="form-control" required type="text" name="options[` + data + `][values][` +
                countRow + `][label]" id="">
                    </div>
                    <div class="col-md-4 col-sm-5">
                        <label for="">{{ translate('Additional_price') }}</label>
                        <input class="form-control"  required type="number" min="0" step="0.01" name="options[` +
                data +
                `][values][` + countRow + `][optionPrice]" id="">
                    </div>
                    <div class="col-sm-2 max-sm-absolute">
                        <label class="d-none d-sm-block">&nbsp;</label>
                        <div class="mt-1">
                            <button type="button" class="btn btn-danger btn-sm deleteRow"
                                title="{{ translate('Delete') }}">
                                <i class="tio-add-to-trash"></i>
                            </button>
                        </div>
                </div>
            </div>`;
            $('#option_price_view_' + data).append(add_new_row_view);

        }

        $(document).on('click', '.add_new_row_button', function() {
            let data = $(this).data('count');
            add_new_row_button(data);
        });

        $(document).on('keyup', '.new_option_name', function() {
            let data = $(this).data('count');
            let value = $(this).val();
            new_option_name(value, data);
        });

        $('#store_id').on('change', function() {
            let route = '{{ url('/') }}/admin/store/get-addons?data[]=0&store_id=';
            let store_id = $(this).val();
            let id = 'add_on';
            getStoreData(route, store_id, id);
        });

        function getStoreData(route, store_id, id) {
            $.get({
                url: route + store_id,
                dataType: 'json',
                success: function(data) {
                    $('#' + id).empty().append(data.options);
                },
            });
        }

        function getRequest(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function(data) {
                    $('#' + id).empty().append(data.options);
                },
            });
        }

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
            $('#image-viewer-section').show(1000)
        });

        $(document).ready(function() {
            @if (count(json_decode($product['add_ons'], true)) > 0)
                getStoreData(
                    '{{ url('/') }}/admin/store/get-addons?@foreach (json_decode($product['add_ons'], true) as $addon)data[]={{ $addon }}& @endforeach store_id=',
                    '{{ $product['store_id'] }}', 'add_on');
            @else
                getStoreData('{{ url('/') }}/admin/store/get-addons?data[]=0&store_id=',
                    '{{ $product['store_id'] }}', 'add_on');
            @endif
        });

        let parent_category_id = {{ $category ? $category->id : 0 }};
        

        $('#category_id').on('change', function() {
            parent_category_id = $(this).val();
            let subCategoriesSelect = $('#sub-categories');
            subCategoriesSelect.empty();
            subCategoriesSelect.append(
                '<option value="" selected>{{ translate('messages.select_sub_category') }}</option>');
        });

        $('.foodModalClose').on('click', function() {
            $('#food-modal').hide();
        })

        $('.foodModalShow').on('click', function() {
            $('#food-modal').show();
        })

        $('.attributeModalClose').on('click', function() {
            $('#attribute-modal').hide();
        })

        $('.attributeModalShow').on('click', function() {
            $('#attribute-modal').show();
        })

        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

        $('#condition_id').select2({
            ajax: {
                url: '{{ url('/') }}/admin/common-condition/get-all',
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page,
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                __port: function(params, success, failure) {
                    let $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });

        $('#brand_id').select2({
            ajax: {
                url: '{{ url('/') }}/admin/brand/get-all',
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page,
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                __port: function(params, success, failure) {
                    let $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });

        $('#store_id').select2({
            ajax: {
                url: '{{ url('/') }}/admin/store/get-stores',
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page,
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                __port: function(params, success, failure) {
                    let $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });

        $('#category_id').select2({
            ajax: {
                url: '{{ url('/') }}/admin/item/get-categories?parent_id=0',
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page,
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                __port: function(params, success, failure) {
                    let $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });

        $('#sub-categories').select2({
            ajax: {
                url: '{{ url('/') }}/admin/item/get-categories',
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page,
                        parent_id: parent_category_id,
                        sub_category: true
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                __port: function(params, success, failure) {
                    let $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });

        // $('#product_form').on('keydown', function(e) {
        //        if (e.key === 'Enter') {
        //        e.preventDefault(); // Prevent submission on Enter
        //        }
        //    });

        $('#product_form').on('submit', function() {
            console.log('working');

            let $form = $(this);
            if (!$form.valid()) {
                return false;
            }

            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: $('.route_url').val(),
                data: $('#product_form').serialize(),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    console.log(data);
                    $('#loading').hide();
                    if (data.errors) {
                        for (let i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    }
                    if (data.product_approval) {
                        toastr.success(data.product_approval, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                    if (data.success) {
                        toastr.success(data.success, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href =
                                '{{ route('admin.item.list') }}';
                        }, 2000);
                    }
                }
            });
        });

        $('#reset_btn').click(function() {
            location.reload(true);
        })

        update_qty();

        function update_qty() {
            let total_qty = 0;
            let qty_elements = $('input[name^="stock_"]');
            for (let i = 0; i < qty_elements.length; i++) {
                total_qty += parseInt(qty_elements.eq(i).val());
            }
            if (qty_elements.length > 0) {

                $('input[name="current_stock"]').attr("readonly", true);
                $('input[name="current_stock"]').val(total_qty);
            } else {
                $('input[name="current_stock"]').attr("readonly", false);
            }
        }
        $('input[name^="stock_"]').on('keyup', function() {
            let total_qty = 0;
            let qty_elements = $('input[name^="stock_"]');
            for (let i = 0; i < qty_elements.length; i++) {
                total_qty += parseInt(qty_elements.eq(i).val());
            }
            $('input[name="current_stock"]').val(total_qty);
        });

        function initImagePicker() {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'item_images[]',
                maxCount: 5,
                rowHeight: '176px !important',
                groupClassName: 'spartan_item_wrapper min-w-176px max-w-176px',
                maxFileSize: 1024 * 1024 * 2,
                placeholderImage: {
                    image: "{{ asset('public/assets/admin/img/upload-img.png') }}",
                    width: '176px'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function(index, file) {
                    setTimeout(function() {
                        let $newInput = $("#coba .spartan_item_wrapper").last();
                        if ($newInput.length) {
                            $newInput[0].scrollIntoView({
                                behavior: "smooth",
                                inline: "end",
                                block: "nearest"
                            });
                        }
                    }, 50);
                },
                onExtensionErr: function(index, file) {
                    toastr.error("{{ translate('messages.please_only_input_png_or_jpg_type_file') }}", {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function(index, file) {
                    toastr.error("{{ translate('messages.file_size_too_big') }}", {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        }

        $(function() {
            initImagePicker();
        });

        $('#reset_btn').click(function() {
            $('#store_id').val(null).trigger('change');
            $('#category_id').val(null).trigger('change');
            $('#sub-categories').val(null).trigger('change');
            $('#unit').val(null).trigger('change');
            $('#veg').val(0).trigger('change');
            $('#add_on').val(null).trigger('change');
            $('#discount_type').val(null).trigger('change');
            $('#choice_attributes').val(null).trigger('change');
            $('#customer_choice_options').empty().trigger('change');
            $('#variant_combination').empty().trigger('change');
            $('#viewer').attr('src', "{{ asset('public/assets/admin/img/upload.png') }}");
            $("#coba").empty();
            initImagePicker();
        })
    </script>
@endpush
