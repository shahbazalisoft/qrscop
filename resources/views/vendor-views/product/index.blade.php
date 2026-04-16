@extends('layouts.vendor.app')

@section('title', translate('messages.add_new_item'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('public/assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/admin/css/AI/animation/product/ai-sidebar.css') }}" rel="stylesheet">
    <style>
        .item-suggest-wrap { position: relative; }
        .item-suggest-list {
            position: absolute; top: 100%; left: 0; right: 0; z-index: 1050;
            background: #fff; border: 1px solid #e7eaf3; border-top: 0;
            border-radius: 0 0 .3125rem .3125rem; max-height: 220px; overflow-y: auto;
            box-shadow: 0 4px 12px rgba(0,0,0,.1); display: none;
        }
        .item-suggest-list .suggest-item {
            padding: 8px 14px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f5f5f5;
        }
        .item-suggest-list .suggest-item:hover,
        .item-suggest-list .suggest-item.active { background: #f0f4ff; }
        .item-suggest-list .suggest-item .suggest-price { color: #999; font-size: 12px; float: right; }
    </style>
@endpush

@section('content')
    
    @php($openai_config = \App\CentralLogics\Helpers::get_business_settings('openai_config'))

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/items.png') }}" class="w--22" alt="">
                </span>
                <span>
                    {{ translate('messages.add_new_item') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <form id="item_form" enctype="multipart/form-data" class="custom-validation" data-ajax="true">
            <input type="hidden" id="request_type" value="vendor">
            <input type="hidden" id="store_id" value="{{ \App\CentralLogics\Helpers::get_store_id() }}">
            <input type="hidden" id="module_type" value="1">

            <div class="row g-2">
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
                                                    accept=".webp, .jpg, .png, .webp, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
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

                @includeif('admin-views.product.partials._food_variations')
                {{-- @if ($module_type == 'food')
                    @includeif('admin-views.product.partials._food_variations')
                @else
                    @includeif('admin-views.product.partials._other_variations')
                @endif --}}

                {{-- @includeif('admin-views.product.partials._ai_sidebar') --}}

                <div class="col-12">
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn"
                            class="btn btn--reset">{{ translate('messages.reset') }}</button>
                        <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <span id="message-enter-choice-values" data-text="{{ translate('enter_choice_values') }}"></span>
    @include('admin-views.product.partials._gallery_picker_modal')

@endsection

@push('script')
@endpush

@push('script_2')
    <script src="{{ asset('public/assets/admin') }}/js/tags-input.min.js"></script>
    <script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script src="{{ asset('public/assets/admin') }}/js/view-pages/vendor/product-index.js"></script>


    <script src="{{ asset('public/assets/admin/js/AI/products/product-title-autofill.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/AI/products/product-description-autofill.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/AI/products/general-setup-autofill.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/AI/products/product-others-autofill.js') }}"></script>
    {{-- @if ($module_type == 'food') --}}
        <script src="{{ asset('public/assets/admin/js/AI/products/variation-setup-auto-fill.js') }}"></script>
    {{-- @else
        <script src="{{ asset('public/assets/admin/js/AI/products/other-variation-setup-auto-fill.js') }}"></script>
    @endif --}}
    <script src="{{ asset('public/assets/admin/js/AI/products/seo-section-autofill.js') }}"></script>

    <script src="{{ asset('public/assets/admin/js/AI/products/ai-sidebar.js') }}"></script>

    <script src="{{ asset('/public/assets/admin/js/AI/products/compressor/image-compressor.js') }}"></script>
    <script src="{{ asset('/public/assets/admin/js/AI/products/compressor/compressor.min.js') }}"></script>

    <script src="{{ asset('public/assets/admin/js/gallery-picker.js') }}"></script>

    <script>
        "use strict";

        GalleryPicker.init({
            apiUrl: "{{ route('vendor.gallery.api') }}",
            uploadUrl: "{{ route('vendor.gallery.image-upload') }}"
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


        $(document).ready(function() {
            $("#add_new_option_button").click(function(e) {
                add_new_option_button();
            });

        });

        // INITIALIZATION OF SELECT2
        // =======================================================
        $('.js-select2-custom').each(function() {
            let select2 = $.HSCore.components.HSSelect2.init($(this));
        });

        function add_new_option_button() {
            $('#empty-variation').hide();
            count++;
            let add_option_view = `
                    <div class="__bg-F8F9FC-card view_new_option mb-2">
                        <div>

                            <div id="option_price_` + count + `" >
                                <div class="bg-white border rounded p-3 pb-0 mt-3">
                                    <div  id="option_price_view_` + count + `">
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
                                            <div class="col-sm-2 max-sm-absolute">
                                            <label class="d-none d-sm-block">&nbsp;</label>
                                            <div class="mt-1">
                                            <button type="button" class="btn btn-danger btn-sm delete_input_button"
                                                title="{{ translate('Delete') }}">
                                                <i class="tio-add-to-trash"></i>
                                            </button>
                                            </div>
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

        function add_more_customer_choice_option(i, name) {
            let n = name;

            $('#customer_choice_options').append(
                `<div class="__choos-item"><div><input type="hidden" name="choice_no[]" value="${i}"><input type="text" class="form-control d-none" name="choice[]" value="${n}" placeholder="{{ translate('messages.choice_title') }}" readonly> <label class="form-label">${n}</label> </div><div><input type="text" class="form-control combination_update" name="choice_options_${i}[]" placeholder="{{ translate('messages.enter_choice_values') }}" data-role="tagsinput"></div></div>`
            );
            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }


        // $('#item_form').on('keydown', function(e) {
        //         if (e.key === 'Enter') {
        //         e.preventDefault(); // Prevent submission on Enter
        //         }
        //     });




        $('#brand_id').select2({
            ajax: {
                url: '{{ route('vendor.item.getBrandList') }}',
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



        $('#item_form').on('submit', function() {

            // Remove required from thumbnail if gallery thumbnail was picked
            let hasGalleryThumbnail = $('input[name="gallery_thumbnail"]').length > 0;
            if (hasGalleryThumbnail) {
                $('#customFileEg1').removeAttr('required');
                try { $('#customFileEg1').rules('remove', 'required'); } catch(ex) {}
            }

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
                url: '{{ route('vendor.item.store') }}',
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
                    }
                    if (data.product_approval) {
                        toastr.success(data.product_approval, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href = '{{ route('vendor.item.pending_item_list') }}';
                        }, 2000);
                    }
                    if (data.success) {
                        toastr.success(data.success, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href = '{{ route('vendor.item.list') }}';
                        }, 2000);
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
            $('#category_id').val(null).trigger('change');
            $('#sub-categories').val(null).trigger('change');
            $('#veg').val(0).trigger('change');
            $('#addons').val(null).trigger('change');
            $('#discount_type').val(null).trigger('change');
            $('#choice_attributes').val(null).trigger('change');
            $('#customer_choice_options').empty().trigger('change');
            $('#variant_combination').empty().trigger('change');
            $('#viewer').attr('src', "{{ asset('public/assets/admin/img/upload.png') }}");
            $('input[name="gallery_thumbnail"]').remove();
            $('input[name="gallery_images[]"]').closest('.spartan_item_wrapper').remove();
            $("#coba").empty();
            initImagePicker();
        })
    </script>

    {{-- Item name autocomplete suggestion --}}
    <script>
        (function() {
            "use strict";
            var $nameInput = $('#default_name');
            var suggestTimer = null;

            // Disable browser native autocomplete
            $nameInput.attr('autocomplete', 'off');

            // Wrap input in a relative container and append dropdown
            $nameInput.closest('.outline-wrapper').addClass('item-suggest-wrap')
                .append('<div class="item-suggest-list" id="item-suggest-list"></div>');

            var $list = $('#item-suggest-list');

            $nameInput.on('input', function() {
                var q = $(this).val().trim();
                clearTimeout(suggestTimer);
                if (q.length < 2) { $list.hide().empty(); return; }

                suggestTimer = setTimeout(function() {
                    $.get({
                        url: "{{ route('vendor.item.suggest') }}",
                        data: { q: q },
                        dataType: 'json',
                        success: function(items) {
                            $list.empty();
                            if (!items.length) { $list.hide(); return; }
                            $.each(items, function(i, item) {
                                $list.append(
                                    '<div class="suggest-item" data-index="' + i + '">' +
                                        '<span>' + $('<span>').text(item.name).html() + '</span>' +
                                        '<span class="suggest-price">{{ \App\CentralLogics\Helpers::currency_symbol() }}' + parseFloat(item.price).toFixed(2) + '</span>' +
                                    '</div>'
                                );
                            });
                            $list.data('items', items);
                            $list.show();
                        }
                    });
                }, 300);
            });

            // Click on suggestion
            $list.on('click', '.suggest-item', function() {
                var idx = $(this).data('index');
                var item = $list.data('items')[idx];
                $list.hide().empty();
                fillFormFromItem(item);
            });

            // Hide on outside click
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.item-suggest-wrap').length) {
                    $list.hide();
                }
            });

            // Hide on Escape
            $nameInput.on('keydown', function(e) {
                if (e.key === 'Escape') $list.hide();
            });

            function fillFormFromItem(item) {
                // Name
                $nameInput.val(item.name);

                // Description
                if (item.description) {
                    var $desc = $('#description-default');
                    if ($desc.length) {
                        // Handle CKEditor
                        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['description-default']) {
                            CKEDITOR.instances['description-default'].setData(item.description);
                        } else {
                            $desc.val(item.description);
                        }
                    }
                }

                // Category - find the right parent category
                var parentCatId = null;
                var subCatId = null;
                if (item.category_ids && item.category_ids.length) {
                    // category_ids format: [{id:X, position:1}, {id:Y, position:2}]
                    $.each(item.category_ids, function(i, cat) {
                        if (cat.position == 1) parentCatId = cat.id;
                        if (cat.position == 2) subCatId = cat.id;
                    });
                }
                if (parentCatId) {
                    $('#category_id').val(parentCatId).trigger('change');
                    // After category change loads sub-categories, set sub-category
                    if (subCatId) {
                        setTimeout(function() {
                            $('#sub-categories').val(subCatId).trigger('change');
                        }, 800);
                    }
                }

                // Price
                $('#unit_price').val(item.price);

                // Discount
                if (item.discount_type) {
                    $('#discount_type').val(item.discount_type).trigger('change');
                }
                $('#discount').val(item.discount || 0);

                // Veg
                if (item.veg !== null && item.veg !== undefined) {
                    $('#veg').val(item.veg).trigger('change');
                }

                // Tags
                if (item.tags) {
                    var $tags = $('#tags');
                    $tags.tagsinput('removeAll');
                    var tagArr = item.tags.split(',');
                    $.each(tagArr, function(i, tag) {
                        tag = tag.trim();
                        if (tag) $tags.tagsinput('add', tag);
                    });
                }

                // Available time
                if (item.available_time_starts) {
                    $('#available_time_starts').val(item.available_time_starts);
                }
                if (item.available_time_ends) {
                    $('#available_time_ends').val(item.available_time_ends);
                }

                // Food variations
                if (item.food_variations && item.food_variations.length) {
                    // Clear existing variations
                    $('#add_new_option').empty();
                    count = 0;

                    $.each(item.food_variations, function(vi, variation) {
                        add_new_option_button();
                        var $lastOption = $('#add_new_option .view_new_option').last();

                        // Set variation name
                        $lastOption.find('.new_option_name').val(variation.name || '');

                        // Set required
                        if (variation.required === 'on' || variation.required === true) {
                            $lastOption.find('input[name$="[required]"]').prop('checked', true);
                        }

                        // Set type
                        if (variation.type === 'single') {
                            $lastOption.find('.hide_min_max').prop('checked', true).trigger('click');
                        } else {
                            $lastOption.find('.show_min_max').prop('checked', true);
                        }

                        // Set min/max
                        if (variation.min) {
                            $lastOption.find('input[name$="[min]"]').val(variation.min);
                        }
                        if (variation.max) {
                            $lastOption.find('input[name$="[max]"]').val(variation.max);
                        }



                        // Set option values
                        if (variation.values && variation.values.length) {
                            var $priceView = $lastOption.find('[id^="option_price_view_"]');
                            // First row already exists, fill it
                            $.each(variation.values, function(oi, opt) {
                                if (oi === 0) {
                                    var $firstRow = $priceView.find('.add_new_view_row_class').first();
                                    $firstRow.find('input[name$="[label]"]').val(opt.label || '');
                                    $firstRow.find('input[name$="[optionPrice]"]').val(opt.optionPrice || 0);
                                } else {
                                    add_new_row_button(count);
                                    var $newRow = $priceView.find('.add_new_view_row_class').last();
                                    $newRow.find('input[name$="[label]"]').val(opt.label || '');
                                    $newRow.find('input[name$="[optionPrice]"]').val(opt.optionPrice || 0);
                                }
                            });
                        }
                    });
                }

                toastr.info('{{ translate("messages.item_data_loaded_you_can_modify_before_saving") }}');
            }
        })();
    </script>
@endpush
