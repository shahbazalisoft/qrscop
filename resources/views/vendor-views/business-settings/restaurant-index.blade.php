@extends('layouts.vendor.app')

@section('title', translate('messages.settings'))



@section('content')
    <div class="content container-fluid config-inline-remove-class">
        <!-- Page Heading -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/config.png') }}" class="w--30" alt="">
                </span>
                <span>
                    {{ translate('messages.banner_with_text_popup') }}
                </span>
            </h1>
        </div>
            @includeif('vendor-views.business-settings.partials._header')
        
        <!-- Page Heading -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title">
                    <span class="card-header-icon">
                        <img class="w--22" src="{{ asset('public/assets/admin/img/store.png') }}" alt="">
                    </span>
                    <span class="p-md-1"> {{ translate('messages.text_banner_setting') }}</span>
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('vendor.business-settings.update-meta-data', $store->id) }}" method="post"
                    enctype="multipart/form-data" class="col-12">
                    @csrf
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <div class="card shadow--card-2">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <span class="card-header-icon mr-1"><i class="tio-dashboard"></i></span>
                                        <span>{{ translate('only_banner_popup') }}</span>
                                    </h5>
                                </div>
                                
                                <div class="card-body">
                                    <div class="d-flex justify-content-center flex-wrap flex-sm-nowrap __gap-12px">
                                        <label class="__custom-upload-img mr-lg-5">
                                            <label class="form-label">
                                                {{ translate('banner_image') }} <span class="text--primary">( 340 × 340 px )</span>
                                                <span class="form-label-secondary" data-toggle="tooltip"
                                                    data-placement="right"
                                                    data-original-title="{{ translate('This banner is used as a Popup Model when the customer visit on your menu then show on screen first.') }}">
                                                    <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                        alt="">
                                                </span>
                                            </label>
                                            <div class="text-center">
                                                <img class="img--110 min-height-170px min-width-170px onerror-image"
                                                    id="viewer"
                                                    data-onerror-image="{{ asset('public/assets/admin/img/upload.png') }}"
                                                    src="{{ $store->banner_popup_full_url ?? asset('public/assets/admin/img/upload.png') }}"
                                                    alt="{{ translate('image') }}" />
                                            </div>
                                            <input type="file" name="banner_popup" id="customFileEg1"
                                                class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        </label>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <div class="text-center">
                                            <small>Rendered size: 340 × 340 px</small>
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
                                        <span>{{ translate('banner_with_text_popup') }}</span>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-center flex-wrap flex-sm-nowrap __gap-12px">
                                        <label class="__custom-upload-img mr-lg-5">
                                            <label class="form-label">
                                                {{ translate('banner_image') }} <span class="text--primary">( 356 × 200
                                                    px)</span>
                                                <span class="form-label-secondary" data-toggle="tooltip"
                                                    data-placement="right"
                                                    data-original-title="{{ translate('This banner is used as a Popup Model when the customer visit on your menu then show on screen first.') }}">
                                                    <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                        alt="">
                                                </span>
                                            </label>
                                            <div class="text-center">
                                                <img class="img--110 min-height-170px min-width-170px onerror-image"
                                                    id="viewer"
                                                    data-onerror-image="{{ asset('public/assets/admin/img/upload.png') }}"
                                                    src="{{ $store->text_banner_image_full_url ?? asset('public/assets/admin/img/upload.png') }}"
                                                    alt="{{ translate('image') }}" />
                                            </div>
                                            <input type="file" name="image" id="customFileEg1"
                                                class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        </label>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <div class="text-center">
                                            <small>Rendered size: 356 × 200 px</small>
                                        </div>
                                    </div>
                                    <div id="default-form">
                                        <div class=" ">
                                            <label class="input-label"
                                                for="heading">{{ translate('messages.heading') }}</label>
                                            <input type="text" id="heading" name="heading" class="form-control"
                                                value="{{ $store->text_banner_popup['heading'] ?? '' }}"
                                                placeholder="{{ translate('messages.top_heading') }}">
                                        </div>
                                        <div class=" ">
                                            <label class="input-label"
                                                for="title">{{ translate('messages.title') }}</label>
                                            <input type="text" id="title" name="title" class="form-control"
                                                value="{{ $store->text_banner_popup['title'] ?? '' }}"
                                                placeholder="{{ translate('messages.title') }}">
                                        </div>
                                        <div class="">
                                            <label class="input-label"
                                                for="description">{{ translate('messages.description') }}
                                            </label>
                                            <textarea type="text" id="description" name="description" placeholder="{{ translate('messages.description') }}"
                                                class="form-control min-h-50px ckeditor">{{ $store->text_banner_popup['description'] ?? '' }}</textarea>
                                        </div>
                                        <div class="row g-3">
                                        <div class="col-sm-6 col-md-4 col-xl-6">
                                            <label class="input-label"
                                                for="label">{{ translate('messages.label') }}</label>
                                            <input type="text" id="label" name="label" class="form-control"
                                                value="{{ $store->text_banner_popup['label'] ?? '' }}"
                                                placeholder="{{ translate('messages.label') }}">
                                        </div>
                                        <div class="col-sm-6 col-md-4 col-xl-6">
                                            <label class="input-label"
                                                for="button">{{ translate('messages.button') }}</label>
                                            <input type="text" id="button" name="button" class="form-control"
                                                value="{{ $store->text_banner_popup['button'] ?? '' }}"
                                                placeholder="{{ translate('messages.button') }}">
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="justify-content-end btn--container">
                                <button type="submit" class="btn btn--primary" value="2">{{ translate('save_changes') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ translate('messages.Create Schedule For ') }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="javascript:" method="post" id="add-schedule">
                            @csrf
                            <input type="hidden" name="day" id="day_id_input">
                            <div class=" ">
                                <label for="recipient-name"
                                    class="col-form-label">{{ translate('messages.Start time') }}:</label>
                                <input type="time" id="recipient-name" class="form-control" name="start_time"
                                    required>
                            </div>
                            <div class=" ">
                                <label for="message-text"
                                    class="col-form-label">{{ translate('messages.End time') }}:</label>
                                <input type="time" id="message-text" class="form-control" name="end_time" required>
                            </div>
                            <div class="btn--container mt-4 justify-content-end">
                                <button type="reset" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                                <button type="submit"
                                    class="btn btn--primary">{{ translate('messages.Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create schedule modal -->

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
