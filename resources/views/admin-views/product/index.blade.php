@extends('layouts.admin.app')

@section('title', translate('messages.add_new_item'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('public/assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/admin/css/AI/animation/product/ai-sidebar.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        @php($openai_config = \App\CentralLogics\Helpers::get_business_settings('openai_config'))
        <!-- Page Header -->
        <div class="page-header d-flex flex-wrap __gap-15px justify-content-between align-items-center">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/items.png') }}" class="w--22" alt="">
                </span>
                <span>
                    {{ translate('messages.add_new_item') }}
                </span>
            </h1>
            {{-- <div class=" d-flex flex-sm-nowrap flex-wrap  align-items-end">
                <div class="text--primary-2 d-flex flex-wrap align-items-center mr-2">
                    <a href="{{ route('admin.item.product_gallery') }}"
                        class="btn btn-outline-primary btn--primary d-flex align-items-center bg-not-hover-primary-ash rounded-8 gap-2">
                        <img src="{{ asset('public/assets/admin/img/product-gallery.png') }}" class="w--22" alt="">
                        <span>{{ translate('Add Info From Gallery') }}</span>
                    </a>
                </div>
            </div> --}}
        </div>
        <!-- End Page Header -->
        <form id="item_form" enctype="multipart/form-data" class="custom-validation" data-ajax="true">

            <div class="row g-2">

                <input type="hidden" id="request_type" value="admin">
                <input type="hidden" id="module_type" value="{{ Config::get('module.current_module_type') }}">

                @includeif('admin-views.product.partials._title_and_discription')

                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-wrap align-items-center">
                            <div class="w-100 d-flex gap-3 flex-wrap flex-lg-nowrap">
                                <div class="flex-grow-1 mx-auto overflow-x-auto scrollbar-primary">
                                    <label class="text-dark d-block mb-4 mb-xl-5">
                                        {{ translate('messages.item_image') }}
                                        <small class="">( {{ translate('messages.ratio') }} 1:1 )</small>
                                    </label>
                                    <div class="d-flex __gap-12px __new-coba overflow-x-auto pb-2" id="coba"></div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2 btn-gallery-picker" data-picker-target="gallery" data-max-select="5">
                                        <i class="tio-album"></i> {{ translate('messages.Choose from Gallery') }}
                                    </button>
                                </div>

                                <div class="flex-grow-1 mx-auto pb-2 flex-shrink-0">
                                    <label class="text-dark d-block mb-4 mb-xl-5">
                                        {{ translate('messages.item_thumbnail') }}
                                        @if (Config::get('module.current_module_type') == 'food')
                                            <small class="">( {{ translate('messages.ratio') }} 1:1 )</small>
                                        @else
                                            <small class="text-danger">* ( {{ translate('messages.ratio') }} 1:1 )</small>
                                        @endif
                                    </label>
                                    <label class="d-inline-block m-0 position-relative error-wrapper">
                                        <img class="img--176 border" id="viewer"
                                            src="{{ asset('public/assets/admin/img/upload-img.png') }}" alt="thumbnail" />
                                        <div class="icon-file-group">
                                            <div class="icon-file"><input type="file" name="image" id="customFileEg1"
                                                    class="custom-file-input d-none"
                                                    accept=".webp, .jpg, .png, .webp, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                    required>
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
                        <button type="submit" id="submitButton"
                            class="btn btn--primary">{{ translate('messages.submit') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @include('admin-views.product.partials._gallery_picker_modal')

@endsection


@push('script_2')
    <script src="{{ asset('public/assets/admin') }}/js/tags-input.min.js"></script>
    <script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script src="{{ asset('public/assets/admin') }}/js/view-pages/product-index.js"></script>



    <script src="{{ asset('public/assets/admin/js/AI/products/product-title-autofill.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/AI/products/product-description-autofill.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/AI/products/general-setup-autofill.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/AI/products/product-others-autofill.js') }}"></script>
    @if (Config::get('module.current_module_type') == 'food')
        <script src="{{ asset('public/assets/admin/js/AI/products/variation-setup-auto-fill.js') }}"></script>
    @else
        <script src="{{ asset('public/assets/admin/js/AI/products/other-variation-setup-auto-fill.js') }}"></script>
    @endif
    <script src="{{ asset('public/assets/admin/js/AI/products/seo-section-autofill.js') }}"></script>

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

        function validateImageSize(inputSelector, imageType = "Image", maxSizeMB = 2) {
            let fileInput = $(inputSelector)[0];
            if (fileInput && fileInput.files.length > 0) {
                let fileSize = fileInput.files[0].size;
                if (fileSize > maxSizeMB * 1024 * 1024) {
                    toastr.error(`${imageType} size should not exceed ${maxSizeMB}MB`, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    return false;
                }
            }
            return true;
        }


        $(document).on('change', '#discount_type', function() {
            let data = document.getElementById("discount_type");
            if (data.value === 'amount') {
                $('#symble').text("({{ \App\CentralLogics\Helpers::currency_symbol() }})");
            } else {
                $('#symble').text("(%)");
            }
        });

        function add_new_row_button(data) {
            count = data;
            countRow = 1 + $('#option_price_view_' + data).children('.add_new_view_row_class').length;
            let add_new_row_view = `
            <div class="row add_new_view_row_class mb-3 position-relative pt-3 pt-sm-0">
                <div class="col-md-4 col-sm-5">
                        <label for="">{{ translate('Option_name') }}</label>
                        <input class="form-control" required type="text" name="options[` + count + `][values][` +
                countRow + `][label]" id="">
                    </div>
                    <div class="col-md-4 col-sm-5">
                        <label for="">{{ translate('Additional_price') }}</label>
                        <input class="form-control"  required type="number" min="0" step="0.01" name="options[` +
                count +
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

        function add_more_customer_choice_option(i, name) {
            let n = name;

            $('#customer_choice_options').append(
                `<div class="__choos-item"><div><input type="hidden" name="choice_no[]" value="${i}"><input type="text" class="form-control d-none" name="choice[]" value="${n}" placeholder="{{ translate('messages.choice_title') }}" readonly> <label class="form-label">${n}</label> </div><div><input type="text" class="form-control combination_update" name="choice_options_${i}[]" placeholder="{{ translate('messages.enter_choice_values') }}" data-role="tagsinput"></div></div>`
            );
            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }

        $('#item_form').on('submit', function(e) {
            $('#submitButton').attr('disabled', true);
            e.preventDefault();

            // Remove required from thumbnail input if gallery thumbnail was picked (before validation)
            let hasGalleryThumbnail = $('input[name="gallery_thumbnail"]').length > 0;
            if (hasGalleryThumbnail) {
                $('#customFileEg1').removeAttr('required');
                try { $('#customFileEg1').rules('remove', 'required'); } catch(ex) {}
            }

            let $form = $(this);
            if (!$form.valid()) {
                return false;
            }

            if (!validateImageSize('#customFileEg1', "Item image")) {
                return;
            }

            let fileInput = $('#customFileEg1')[0];
            if (fileInput.files.length > 0) {
                let fileSize = fileInput.files[0].size;
                if (fileSize > 1024 * 1024) {
                    toastr.error('Image size should not exceed 2MB', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    return;
                }
            }

            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.item.store') }}',
                data: $('#item_form').serialize(),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#loading').hide();
                    if (data.errors) {
                        for (let i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success("{{ translate('messages.product_added_successfully') }}", {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href =
                                "{{ route('admin.item.list') }}";
                        }, 1000);
                    }
                }
            });
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
            $('#module_id').val(null).trigger('change');
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
            $('#customFileEg1').val(null).trigger('change');
            $('input[name="gallery_thumbnail"]').remove();
            $('input[name="gallery_images[]"]').closest('.spartan_item_wrapper').remove();
            $("#coba").empty();
            initImagePicker();
        })
    </script>
@endpush
