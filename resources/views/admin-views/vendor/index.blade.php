@extends('layouts.admin.app')

@section('title', translate('messages.add_store_name'))



@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/store.png') }}" class="w--26" alt="">
                </span>
                <span>
                    {{ translate('messages.add_new_store') }}
                </span>
            </h1>
        </div>

        
        <!-- End Page Header -->
        <form enctype="multipart/form-data" class="custom-validation" data-ajax="true" id="vendor_form">
            <div class="row g-2">
                <div class="col-lg-6">
                    <div class="card shadow--card-2">
                        <div class="card-body">
                                <div id="default-form">
                                    <div class="form-group error-wrapper">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.name') }}</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="{{ translate('messages.store_name') }}" required>

                                    </div>
                                    <div class="form-group mb-0 error-wrapper">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.address') }}
                                        </label>
                                        <textarea type="text" name="address" placeholder="{{ translate('messages.store') }}"
                                            class="form-control min-h-90px ckeditor"></textarea>

                                    </div>
                                </div>
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
                            <div class="d-flex flex-wrap flex-sm-nowrap __gap-12px gap-lg-5">
                                <div class="error-wrapper">
                                    <div class="__custom-upload-img">

                                        <label class="form-label">
                                            {{ translate('logo') }} <span class="text--primary">({{ translate('1:1') }})
                                                <span class="form-label-secondary text-danger" data-toggle="tooltip"
                                                    data-placement="right"
                                                    data-original-title="{{ translate('messages.Required.') }}"> *
                                                </span></span>
                                        </label>
                                        <label class="text-center position-relative">
                                            <img class="img--110 min-height-170px min-width-170px onerror-image image--border"
                                                id="viewer"
                                                data-onerror-image="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                                src="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                                alt="logo image" />
                                            <div class="icon-file-group">
                                                <div class="icon-file">
                                                    <i class="tio-edit"></i>
                                                    <input type="file" name="logo" id="customFileEg1"
                                                        class="custom-file-input" required
                                                         accept="{{ IMAGE_EXTENSION }}">
                                                </div>
                                            </div>
                                        </label>

                                    </div>
                                </div>

                                <div class="error-wrapper">
                                    <div class="__custom-upload-img">

                                        <label class="form-label">
                                            {{ translate('Store Cover') }} <span
                                                class="text--primary">({{ translate('2:1') }}) </span>
                                        </label>
                                        <label class="text-center position-relative">
                                            <img class="img--vertical min-height-170px min-width-170px onerror-image image--border"
                                                id="coverImageViewer"
                                                data-onerror-image="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                                src="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                                alt="Fav icon" />
                                            <div class="icon-file-group">
                                                <div class="icon-file">
                                                    <i class="tio-edit"></i>
                                                    <input type="file" name="cover_photo" id="coverImageUpload"
                                                        class="custom-file-input"
                                                        accept=".jpg, .jpeg, .png, .gif, .webp"
                                                        data-max-size="2mb">
                                                </div>
                                                {{-- {{ IMAGE_EXTENSION }} --}}
                                            </div>
                                        </label>
                                    </div>
                                </div>

                            </div>
                            <small class="d-flex fs-10 justify-content-center">
                                     <span>{{ translate('jpg, jpeg, png, gif, webp. Less Than 2MB') }}</span>
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
                                        <label class="input-label" for="f_name">{{ translate('messages.first_name') }}
                                            <span class="form-label-secondary text-danger" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('messages.Required.') }}"> *
                                            </span></label>
                                        <input type="text" name="f_name" class="form-control"
                                            placeholder="{{ translate('messages.first_name') }}"
                                            value="{{ old('f_name') }}" required>

                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0 error-wrapper">
                                        <label class="input-label"
                                            for="l_name">{{ translate('messages.last_name') }}<span
                                                class="form-label-secondary text-danger" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('messages.Required.') }}"> *
                                            </span></label>
                                        <input type="text" name="l_name" class="form-control"
                                            placeholder="{{ translate('messages.last_name') }}"
                                            value="{{ old('l_name') }}" required>

                                    </div>

                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0 error-wrapper">
                                        <label class="input-label" for="phone">{{ translate('messages.phone') }}<span
                                                class="form-label-secondary text-danger" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('messages.Required.') }}"> *
                                            </span></label>
                                        <input type="tel" id="phone" name="phone" class="form-control"
                                            placeholder="{{ translate('messages.Ex:') }} 017********" required>
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
                                <div class="col-md-4 col-12">
                                    <div class="form-group mb-0 error-wrapper">
                                        <label class="input-label" for="email">{{ translate('messages.email') }}<span
                                                class="form-label-secondary text-danger" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('messages.Required.') }}"> *
                                            </span></label>
                                        <input type="email" name="email" class="form-control"
                                            placeholder="{{ translate('messages.Ex:') }} ex@example.com"
                                            value="{{ old('email') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group error-wrapper mb-0">
                                        <label class="input-label"
                                            for="signupSrPassword">{{ translate('messages.password') }}<span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img
                                                    src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span>
                                            <span class="form-label-secondary text-danger" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('messages.Required.') }}"> *
                                            </span></label>

                                        <div class="input-group input-group-merge">
                                            <input type="password" class="js-toggle-password form-control"
                                                name="password" id="signupSrPassword"
                                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                                title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                                placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                                aria-label="8+ characters required" required
                                                data-msg="Your password is invalid. Please try again."
                                                data-hs-toggle-password-options='{
                                            "target": [".js-toggle-password-target-1", ".js-toggle-password-target-2"],
                                            "defaultClass": "tio-hidden-outlined",
                                            "showClass": "tio-visible-outlined",
                                            "classChangeTarget": ".js-toggle-passowrd-show-icon-1"
                                            }'>
                                            <div class="js-toggle-password-target-1 input-group-append">
                                                <a class="input-group-text" href="javascript:">
                                                    <i class="js-toggle-passowrd-show-icon-1 tio-visible-outlined"></i>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group error-wrapper mb-0">
                                        <label class="input-label"
                                            for="signupSrConfirmPassword">{{ translate('messages.confirm_password') }}<span
                                                class="form-label-secondary text-danger" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('messages.Required.') }}"> *
                                            </span></label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" class="js-toggle-password form-control"
                                                name="confirmPassword" id="signupSrConfirmPassword"
                                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                                title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                                placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                                aria-label="8+ characters required" required
                                                data-msg="Password does not match the confirm password."
                                                data-hs-toggle-password-options='{
                                                    "target": [".js-toggle-password-target-1", ".js-toggle-password-target-2"],
                                                    "defaultClass": "tio-hidden-outlined",
                                                    "showClass": "tio-visible-outlined",
                                                    "classChangeTarget": ".js-toggle-passowrd-show-icon-2"
                                                    }'>
                                            <div class="js-toggle-password-target-2 input-group-append">
                                                <a class="input-group-text" href="javascript:">
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
                        <button type="reset" id="reset_btn"
                            class="btn btn--reset">{{ translate('messages.reset') }}</button>
                        <button type="submit" id="submitButton"
                            class="btn btn--primary">{{ translate('messages.submit') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin/js/file-preview/pdf.min.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/file-preview/pdf-worker.min.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/file-preview/add-multiple-document-upload.js') }}"></script>

    <script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    {{-- <script
        src="https://maps.googleapis.com/maps/api/js?key={{ \App\CentralLogics\Helpers::get_business_settings('map_api_key') }}&libraries=places,marker&callback=initMap&v=3.61">
    </script> --}}

    <script>
        "use strict";

        $('#vendor_form').on('submit', function(e) {
            $('#submitButton').attr('disabled', true);
            e.preventDefault();
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
                url: '{{ route('admin.store.store') }}',
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
                        toastr.success("{{ translate('store_added_successfully') }}", {
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


        $(document).on('ready', function() {
            $('.offcanvas').on('click', function() {
                $('.offcanvas, .floating--date').removeClass('active')
            })
            $('.floating-date-toggler').on('click', function() {
                $('.offcanvas, .floating--date').toggleClass('active')
            })
            @if (isset(auth('admin')->user()->zone_id))
                $('#choice_zones').trigger('change');
            @endif
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


        $("#vendor_form").on('keydown', function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        })

        $('#reset_btn').click(function() {
            $('#viewer').attr('src', "{{ asset('public/assets/admin/img/upload-img.png') }}");
            $('#customFileEg1').val(null);
            $('#coverImageViewer').attr('src', "{{ asset('public/assets/admin/img/upload-img.png') }}");
            $('#coverImageUpload').val(null);
            $('#choice_zones').val(null).trigger('change');
            zonePolygon.setMap(null);
            $('#coordinates').val(null);
            $('#latitude').val(null);
            $('#longitude').val(null);
        })

        let zone_id = 0;
        $('#choice_zones').on('change', function() {
            if ($(this).val()) {
                zone_id = $(this).val();
            }
        });



        $('.delivery-time').on('click', function() {
            let min = $("#minimum_delivery_time").val();
            let max = $("#maximum_delivery_time").val();
            let type = $("#delivery_time_type").val();
            $("#floating--date").removeClass('active');
            $("#time_view").val(min + ' to ' + max + ' ' + type);

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

                // Handle drag-and-drop functionality
                const dropZone = inputElement.closest('.image--border');

                dropZone.on('dragover', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                });

                dropZone.on('dragleave', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                });

                dropZone.on('drop', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const file = e.originalEvent.dataTransfer.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            $(imgViewerSelector).attr('src', e.target.result).show();
                            $(textBoxSelector).hide();
                        };
                        reader.readAsDataURL(file);
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
