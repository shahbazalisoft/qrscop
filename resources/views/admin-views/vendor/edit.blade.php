@extends('layouts.admin.app')

@section('title', 'Update restaurant info')
@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/edit.png') }}" class="w--26" alt="">
                </span>
                <span>{{ translate('messages.update_store') }}</span>
            </h1>
        </div>
        @php
            $delivery_time_start = preg_match('([0-9]+[\-][0-9]+\s[min|hours|days])', $store->delivery_time ?? '')
                ? explode('-', $store->delivery_time)[0]
                : 10;
            $delivery_time_end = preg_match('([0-9]+[\-][0-9]+\s[min|hours|days])', $store->delivery_time ?? '')
                ? explode(' ', explode('-', $store->delivery_time)[1])[0]
                : 30;
            $delivery_time_type = preg_match('([0-9]+[\-][0-9]+\s[min|hours|days])', $store->delivery_time ?? '')
                ? explode(' ', explode('-', $store->delivery_time)[1])[1]
                : 'min';
        @endphp
        @php($language = \App\CentralLogics\Helpers::get_business_settings('language'))

        <!-- End Page Header -->
        <form class="custom-validation" enctype="multipart/form-data" data-ajax="true" id="vendor_form">


            <div class="row g-2">
                <div class="col-lg-6">
                    <div class="card shadow--card-2">
                        <div class="card-body">
                            @if ($language)
                                <ul class="nav nav-tabs mb-4">
                                    <li class="nav-item">
                                        <a class="nav-link lang_link active" href="#"
                                            id="default-link">{{ translate('Default') }}</a>
                                    </li>
                                    @foreach ($language as $lang)
                                        <li class="nav-item">
                                            <a class="nav-link lang_link" href="#"
                                                id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            @if ($language)
                                <div class="lang_form" id="default-form">
                                    <div class="form-group error-wrapper">
                                        <label class="input-label" for="default_name">{{ translate('messages.name') }}
                                            ({{ translate('messages.Default') }}) <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name[]" id="default_name" class="form-control"
                                            placeholder="{{ translate('messages.store_name') }}"
                                            value="{{ $store->getRawOriginal('name') }}" required>


                                    </div>
                                    <input type="hidden" name="lang[]" value="default">
                                    <div class="form-group mb-0 error-wrapper">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.address') }}
                                            ({{ translate('messages.default') }})  <span class="text-danger">*</span></label>
                                        <textarea type="text" name="address[]" placeholder="{{ translate('messages.store') }}" required
                                            class="form-control min-h-90px ckeditor">{{ $store->getRawOriginal('address') }}</textarea>

                                    </div>
                                </div>
                                @foreach ($language as $lang)
                                    <?php
                                    if (count($store['translations'])) {
                                        $translate = [];
                                        foreach ($store['translations'] as $t) {
                                            if ($t->locale == $lang && $t->key == 'name') {
                                                $translate[$lang]['name'] = $t->value;
                                            }
                                            if ($t->locale == $lang && $t->key == 'address') {
                                                $translate[$lang]['address'] = $t->value;
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="d-none lang_form" id="{{ $lang }}-form">
                                        <div class="form-group error-wrapper">
                                            <label class="input-label"
                                                for="{{ $lang }}_name">{{ translate('messages.name') }}
                                                ({{ strtoupper($lang) }})
                                            </label>
                                            <input type="text" name="name[]" id="{{ $lang }}_name"
                                                class="form-control" value="{{ $translate[$lang]['name'] ?? '' }}"
                                                placeholder="{{ translate('messages.store_name') }}">

                                        </div>
                                        <input type="hidden" name="lang[]" value="{{ $lang }}">
                                        <div class="form-group mb-0 error-wrapper">
                                            <label class="input-label"
                                                for="exampleFormControlInput1">{{ translate('messages.address') }}
                                                ({{ strtoupper($lang) }})</label>
                                            <textarea type="text" name="address[]" placeholder="{{ translate('messages.store') }}"
                                                class="form-control min-h-90px ckeditor">{{ $translate[$lang]['address'] ?? '' }}</textarea>

                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div id="default-form">
                                    <div class="form-group error-wrapper">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.name') }}
                                            ({{ translate('messages.default') }})</label>
                                        <input type="text" name="name[]" class="form-control"
                                            placeholder="{{ translate('messages.store_name') }}" required>

                                    </div>
                                    <input type="hidden" name="lang[]" value="default">
                                    <div class="form-group mb-0 error-wrapper">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.address') }}
                                        </label>
                                        <textarea type="text" name="address[]" placeholder="{{ translate('messages.store') }}"
                                            class="form-control min-h-90px ckeditor"></textarea>

                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow--card-2">
                        <div class="card-header">
                            <h5 class="card-title">
                                <span class="card-header-icon mr-1"><i class="tio-dashboard"></i></span>
                                <span>{{ translate('Store Logo & Covers') }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap flex-sm-nowrap __gap-12px">
                                <div class="__custom-upload-img mr-lg-5 error-wrapper">
                                    @php($logo = \App\Models\BusinessSetting::where('key', 'logo')->first())
                                    @php($logo = $logo->value ?? '')
                                    <label class="form-label">
                                        {{ translate('logo') }} <span
                                            class="text--primary">({{ translate('1:1') }})  <span class="text-danger">*</span></span>
                                    </label>
                                    <label class="text-center position-relative">
                                        <img class="img--110 min-height-170px min-width-170px onerror-image image--border"
                                            id="viewer"
                                            data-onerror-image="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                            src="{{ $store->logo_full_url ?? asset('public/assets/admin/img/upload-img.png') }}"
                                            data-max-size="2mb" alt="logo image" />
                                        <div class="icon-file-group">
                                            <div class="icon-file">
                                                <i class="tio-edit"></i>
                                                <input type="file" name="logo" id="customFileEg1"
                                                    class="custom-file-input"
                                                     accept="{{ IMAGE_EXTENSION }}">
                                            </div>
                                        </div>
                                    </label>

                                </div>

                                <div class="__custom-upload-img error-wrapper">
                                    @php($icon = \App\Models\BusinessSetting::where('key', 'icon')->first())
                                    @php($icon = $icon->value ?? '')
                                    <label class="form-label">
                                        {{ translate('Store Cover') }} <span
                                            class="text--primary">({{ translate('2:1') }})</span>
                                    </label>
                                    <label class="text-center position-relative">
                                        <img class="img--vertical min-height-170px min-width-170px onerror-image image--border"
                                            id="coverImageViewer"
                                            data-onerror-image="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                            src="{{ $store->cover_photo_full_url ?? asset('public/assets/admin/img/upload-img.png') }}"
                                            alt="Fav icon" />
                                        <div class="icon-file-group">
                                            <div class="icon-file">
                                                <i class="tio-edit"></i>
                                                <input type="file" name="cover_photo" id="coverImageUpload"
                                                    class="custom-file-input"
                                                     accept="{{ IMAGE_EXTENSION }}"
                                                    data-max-size="2mb">
                                            </div>
                                        </div>
                                    </label>

                                </div>
                            </div>
                            <small class="d-flex fs-10 justify-content-center">
                                        <span>{{ translate(IMAGE_FORMAT.'. ' . 'Less Than 2MB') }}</span>
                                    </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title m-0 d-flex align-items-center">
                                <span class="card-header-icon mr-2"><i class="tio-user"></i></span>
                                <span>{{ translate('messages.owner_information') }}</span>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0 error-wrapper">
                                        <label class="input-label"
                                            for="f_name">{{ translate('messages.first_name') }}  <span class="text-danger">*</span></label>
                                        <input type="text" name="f_name" class="form-control"
                                            placeholder="{{ translate('messages.first_name') }}"
                                            value="{{ $store->vendor->f_name }}" required>

                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0 error-wrapper">
                                        <label class="input-label"
                                            for="l_name">{{ translate('messages.last_name') }}  <span class="text-danger">*</span></label>
                                        <input type="text" name="l_name" class="form-control"
                                            placeholder="{{ translate('messages.last_name') }}"
                                            value="{{ $store->vendor->l_name }}" required>

                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 error-wrapper">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="phone">{{ translate('messages.phone') }}  <span class="text-danger">*</span></label>
                                        <input type="tel" id="phone" name="phone" class="form-control"
                                            placeholder="{{ translate('messages.Ex:') }} 017********"
                                            value="{{ $store->vendor->phone }}" required>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title m-0 d-flex align-items-center">
                                <span class="card-header-icon mr-2"><i class="tio-user"></i></span>
                                <span>{{ translate('messages.account_information') }}</span>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0 error-wrapper">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.email') }}  <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control"
                                            placeholder="{{ translate('messages.Ex:') }} ex@example.com"
                                            value="{{ $store->email }}" required>

                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="js-form-message form-group mb-0 error-wrapper">
                                        <label class="input-label"
                                            for="signupSrPassword">{{ translate('password') }}<span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img
                                                    src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span></label>

                                        <div class="input-group input-group-merge">
                                            <input type="password" class="js-toggle-password form-control"
                                                name="password" id="signupSrPassword"
                                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                                title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                                placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                                aria-label="8+ characters required"
                                                data-msg="Your password is invalid. Please try again."
                                                data-hs-toggle-password-options='{
                                            "target": [".js-toggle-password-target-1", ".js-toggle-password-target-2"],
                                            "defaultClass": "tio-hidden-outlined",
                                            "showClass": "tio-visible-outlined",
                                            "classChangeTarget": ".js-toggle-passowrd-show-icon-1"
                                            }'>
                                            <div class="js-toggle-password-target-1 input-group-append">
                                                <a class="input-group-text" href="javascript:;">
                                                    <i class="js-toggle-passowrd-show-icon-1 tio-visible-outlined"></i>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="js-form-message form-group mb-0 error-wrapper">
                                        <label class="input-label"
                                            for="signupSrConfirmPassword">{{ translate('messages.Confirm Password') }}</label>

                                        <div class="input-group input-group-merge">
                                            <input type="password" class="js-toggle-password form-control"
                                                name="confirmPassword" id="signupSrConfirmPassword"
                                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                                title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                                placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                                aria-label="8+ characters required"
                                                data-msg="Password does not match the confirm password."
                                                data-hs-toggle-password-options='{
                                                    "target": [".js-toggle-password-target-1", ".js-toggle-password-target-2"],
                                                    "defaultClass": "tio-hidden-outlined",
                                                    "showClass": "tio-visible-outlined",
                                                    "classChangeTarget": ".js-toggle-passowrd-show-icon-2"
                                                    }'>
                                            <div class="js-toggle-password-target-2 input-group-append">
                                                <a class="input-group-text" href="javascript:;">
                                                    <i class="js-toggle-passowrd-show-icon-2 tio-visible-outlined"></i>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <div class="col-lg-12">
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                        <button type="submit" id="submitButton" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin/js/file-preview/pdf.min.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/file-preview/pdf-worker.min.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/file-preview/edit-multiple-document-upload.js') }}"></script>

    <script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script  src="https://maps.googleapis.com/maps/api/js?key={{ \App\CentralLogics\Helpers::get_business_settings('map_api_key') }}&libraries=places,marker&callback=initMap&v=3.61">
    </script>
    <script>
        "use strict";

        $("#vendor_form").on('keydown', function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        })
        $(document).on('ready', function() {
            $('.offcanvas').on('click', function() {
                $('.offcanvas, .floating--date').removeClass('active')
            })
            $('.floating-date-toggler').on('click', function() {
                $('.offcanvas, .floating--date').toggleClass('active')
            })
        });

        function readURL(input, viewer) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function(e) {
                    $('#' + viewer).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function() {
            readURL(this, 'viewer');
        });

        $("#coverImageUpload").change(function() {
            readURL(this, 'coverImageViewer');
        });
    

        $('#reset_btn').click(function() {
            $('#viewer').attr('src', "{{ asset('public/assets/admin/img/upload.png') }}");
            $('#customFileEg1').val(null);
            $('#coverImageViewer').attr('src', "{{ asset('public/assets/admin/img/upload-img.png') }}");
            $('#coverImageUpload').val(null);

            $('#coordinates').val(null);
        })






        $('.delivery-time').on('click', function() {
            let min = $("#minimum_delivery_time").val();
            let max = $("#maximum_delivery_time").val();
            let type = $("#delivery_time_type").val();
            $("#floating--date").removeClass('active');
            $("#time_view").val(min + ' to ' + max + ' ' + type);

        })
        $(document).ready(function() {
            function previewFile(inputSelector, previewImgSelector, textBoxSelector) {
                const input = $(inputSelector);
                const imagePreview = $(previewImgSelector);
                const textBox = $(textBoxSelector);

                input.on('change', function() {
                    const file = this.files[0];
                    if (!file) return;

                    const fileType = file.type;
                    const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

                    if (validImageTypes.includes(fileType)) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.attr('src', e.target.result).removeClass('display-none');
                            textBox.hide();
                        };
                        reader.readAsDataURL(file);
                    } else {
                        imagePreview.attr('src', '{{ asset('public/assets/admin/img/file-icon.png') }}')
                            .removeClass('display-none');
                        textBox.hide();
                    }
                });
            }

            previewFile('#tin_certificate_image', '#logoImageViewer2', '.upload-file__textbox');
        });


        $('#vendor_form').on('submit', function(e) {
            e.preventDefault();
            $('#submitButton').attr('disabled', true);
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
                url: '{{ route('admin.store.update', [$store['id']]) }}',
                data: $('#vendor_form').serialize(),
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
                        toastr.success("{{ translate('store_added_updated') }}", {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href =
                                "{{ route('admin.store.list') }}";
                        }, 1000);
                    }
                }
            });
        });
    </script>
            <script>
        // ---- file upload with textbox
        $(document).ready(function () {
            function handleImageUpload(inputSelector, imgViewerSelector, textBoxSelector) {
                const inputElement = $(inputSelector);

                // Handle input change for file selection
                inputElement.on('change', function () {
                    const file = this.files[0];
                    if (file) {

                            let acceptAttr = $(this).attr('accept') || '';
                            let validTypes = [];

                            if (acceptAttr) {
                                validTypes = acceptAttr.split(',').map(type => type.trim().toLowerCase());
                            }

                            // Fallback if nothing found in accept attribute
                            if (validTypes.length === 0) {
                                validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                            }

                            // ✅ Check file validity by MIME or extension
                            const fileType = file.type.toLowerCase();
                            const fileExt = '.' + file.name.split('.').pop().toLowerCase();

                            const isValidType = validTypes.some(type => {
                                // Wildcard MIME type (e.g. image/*)
                                if (type.endsWith('/*')) {
                                    return fileType.startsWith(type.replace('/*', ''));
                                }

                                // Exact MIME type match
                                if (type.startsWith('image/') || type.includes('/')) {
                                    return fileType === type;
                                }

                                // File extension match (e.g. .jpg, .png)
                                return fileExt === type;
                            });

                            if (!isValidType) {
                                if (typeof toastr !== 'undefined') {
                                    toastr.error("{{ translate('messages.Invalid file type. Please upload a supported image.') }}");
                                }

                                $(this).val('');
                                $(imgViewerSelector)
                                    .attr('src', '{{ asset('public/assets/admin/img/upload-img.png') }}')
                                    .hide();
                                $(textBoxSelector).show();
                                return;
                            }

                        const maxSize = 2 * 1024 * 1024; // 2 MB in bytes
                        if (file.size > maxSize) {
                            if (typeof toastr !== 'undefined') {
                                toastr.error("{{ translate('messages.Image size must be less than 2 MB') }}");
                            }

                            $(this).val('');
                            $(imgViewerSelector)
                                .attr('src', '{{ asset('public/assets/admin/img/upload-img.png') }}')
                                .hide();
                            $(textBoxSelector).show();
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function (e) {
                            $(imgViewerSelector).attr('src', e.target.result).show();
                            $(textBoxSelector).hide();
                        };
                        reader.readAsDataURL(file);
                    } else {
                        $(imgViewerSelector)
                            .attr('src', '{{ asset('public/assets/admin/img/upload-img.png') }}')
                            .hide();
                        $(textBoxSelector).show();
                    }
                });
            }

            handleImageUpload(
                '#coverImageUpload',
            );

            handleImageUpload(
                '#customFileEg1',
            );
        });
        // ---- file upload with textbox ends
    </script>
@endpush
