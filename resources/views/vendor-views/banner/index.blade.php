@extends('layouts.vendor.app')

@section('title', translate('messages.banner'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/fi_9752284.png') }}" class="w--26" alt="">
                </span>
                <span>
                    {{ translate('messages.Banner_Setup') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">


            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header py-2 border-0">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">
                                {{ translate('messages.banner_list') }}<span class="badge badge-soft-dark ml-2"
                                    id="itemCount">{{ $banners->count() }}</span>
                            </h5>
                            <form id="search-form" class="search-form">
                                <!-- Search -->
                                <div class="input-group input--group">
                                    <input id="datatableSearch" type="search" name="search" class="form-control"
                                        placeholder="{{ translate('messages.search_by_title') }}"
                                        aria-label="{{ translate('messages.search_here') }}"
                                        value="{{ request()->search }}">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form>
                            <div>
                                <button type="button" class="btn btn--primary font-regular" data-bs-toggle="modal"
                                    data-bs-target="#new_menu" onclick="openModal()"><i class="tio-add-circle-outlined"></i>
                                    {{ translate('messages.New_Banner') }}</button>
                            </div>
                            <div>
                                <button type="button" class="btn btn--primary font-regular" onclick="openCommonBannerModal()">
                                    <i class="tio-image"></i>
                                    {{ translate('messages.common_banner') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                                "order": [],
                                "orderCellsTop": true,
                                "search": "#datatableSearch",
                                "entries": "#datatableEntries",
                                "isResponsive": false,
                                "isShowPaging": false,
                                "paging": false
                               }'>
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0">{{ translate('messages.SL') }}</th>
                                    <th class="border-0">{{ translate('messages.banner_Image') }}</th>
                                    <th class="border-0">{{ translate('messages.title_one') }}</th>
                                    <th class="border-0">{{ translate('messages.title_two') }}</th>
                                    <th class="border-0 text-center">{{ translate('messages.status') }}</th>
                                    <th class="border-0 text-center">{{ translate('messages.action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($banners as $key => $banner)
                                    <tr>
                                        <td>{{ $key + $banners->firstItem() }}</td>
                                        <td>
                                            <span class="media align-items-center">
                                                <img class="img--ratio-3 w-auto h--50px rounded mr-2 onerror-image"
                                                    src="{{ $banner['image_full_url'] }}"
                                                    data-onerror-image="{{ asset('/public/assets/admin/img/900x400/img1.jpg') }}"
                                                    alt="{{ $banner->name }} image">
                                            </span>
                                        </td>
                                        <td>
                                            <h5 class="text-hover-primary mb-0">
                                                {{ Str::limit($banner['title_one'], 25, '...') }}</h5>
                                        </td>
                                        
                                        <td>
                                            <h5 class="text-hover-primary mb-0">
                                                {{ Str::limit($banner['title_two'], 25, '...') }}</h5>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <label class="toggle-switch toggle-switch-sm"
                                                    for="statusCheckbox{{ $banner->id }}">
                                                    <input type="checkbox"
                                                        data-url="{{ route('vendor.banner.status_update', [$banner['id'], $banner->status ? 0 : 1]) }}"
                                                        class="toggle-switch-input redirect-url"
                                                        id="statusCheckbox{{ $banner->id }}"
                                                        {{ $banner->status ? 'checked' : '' }}>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn--container justify-content-center">
                                                <a class="btn action-btn btn--primary btn-outline-primary"
                                                    href="javascript:" onclick="openEditModal({{ $banner['id'] }})"
                                                    title="{{ translate('messages.edit_banner') }}"><i
                                                        class="tio-edit"></i>
                                                </a>
                                                <a class="btn action-btn btn--danger btn-outline-danger form-alert"
                                                    href="javascript:" data-id="banner-{{ $banner['id'] }}"
                                                    data-message="{{ translate('Want to delete this banner ?') }}"
                                                    title="{{ translate('messages.delete_banner') }}"><i
                                                        class="tio-delete-outlined"></i>
                                                </a>
                                                <form action="{{ route('vendor.banner.delete', [$banner['id']]) }}"
                                                    method="post" id="banner-{{ $banner['id'] }}">
                                                    @csrf @method('delete')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if (count($banners) !== 0)
                            <hr>
                        @endif
                        <div class="page-area">
                            {!! $banners->links() !!}
                        </div>
                        @if (count($banners) === 0)
                            <div class="empty--data">
                                <img src="{{ asset('/public/assets/admin/svg/illustrations/no-data.svg') }}"
                                    alt="public">
                                <h5>
                                    {{ translate('no_data_found') }}
                                </h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

    {{-- Model Popup Start --}}
    <div class="modal fade" id="new_banner" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('messages.add_new_menu') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addBannerForm">
                    <div class="modal-body">
                        <input name="position" value="0" class="initial-hidden">
                        <div class="form-group" id="new_category_group">
                            <label class="input-label" for="title_one">{{ translate('messages.title_one') }} </label>
                            <input type="text" name="title_one" id="title_one" class="form-control"
                                placeholder="{{ translate('messages.title_one') }}">
                        </div>
                        <div class="form-group" id="new_category_group">
                            <label class="input-label" for="title_two">{{ translate('messages.title_two') }}</label>
                            <input type="text" name="title_two" id="title_two" class="form-control"
                                placeholder="{{ translate('messages.title_two') }}">
                        </div>

                        <div class="col-sm-12">
                            <h3 class="form-label d-block mb-2">
                                {{ translate('Upload_Banner') }}
                            </h3>
                            <label class="upload-img-3 m-0 d-block error-wrapper">
                                <div class="img">
                                    <img src="{{ asset('/public/assets/admin/img/upload-4.png') }}" id="viewer"
                                        class="vertical-img mw-100 vertical" alt="">
                                </div>
                                <input type="file" name="image" id="banner_image_input" hidden required>
                            </label>
                            <h3 class="form-label d-block mt-2">
                                {{ translate('Banner_Image_Ratio_3:1') }}
                            </h3>
                            <p>{{ translate('image_format_:_jpg_,_png_,_jpeg_|_maximum_size:_2_MB') }}</p>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-gallery-picker mt-2"
                                data-picker-target="thumbnail" data-max-select="1"
                                data-viewer-id="viewer" data-input-id="banner_image_input" data-form-id="addBannerForm">
                                <i class="tio-album"></i> {{ translate('messages.Choose from Gallery') }}
                            </button>
                        </div>
                        <div class="col-sm-12">
                        </div>
                        <span class="text-danger errorMsg" id="image_error"></span>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ translate('messages.close') }}</button>
                        <div class="loaderBtn">
                            <button type="submit"
                                class="btn btn-primary">{{ translate('messages.save_changes') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Model Popup End --}}

    {{-- Edit Banner Modal Start --}}
    <div class="modal fade" id="edit_banner" tabindex="-1" role="dialog" aria-labelledby="editBannerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBannerModalLabel">{{ translate('messages.update_banner') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editBannerForm">
                    <input type="hidden" name="banner_id" id="edit_banner_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="input-label" for="edit_title_one">{{ translate('messages.title_one') }}</label>
                            <input type="text" name="title_one" id="edit_title_one" class="form-control"
                                placeholder="{{ translate('messages.title_one') }}">
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="edit_title_two">{{ translate('messages.title_two') }}</label>
                            <input type="text" name="title_two" id="edit_title_two" class="form-control"
                                placeholder="{{ translate('messages.title_two') }}">
                        </div>

                        <div class="col-sm-12">
                            <h3 class="form-label d-block mb-2">
                                {{ translate('Upload_Banner') }}
                            </h3>
                            <label class="upload-img-3 m-0 d-block error-wrapper">
                                <div class="img">
                                    <img src="{{ asset('/public/assets/admin/img/upload-4.png') }}" id="edit_viewer"
                                        class="vertical-img mw-100 vertical onerror-image" alt="">
                                </div>
                                <input type="file" name="image" id="edit_banner_image" hidden>
                            </label>
                            <h3 class="form-label d-block mt-2">
                                {{ translate('Banner_Image_Ratio_3:1') }}
                            </h3>
                            <p>{{ translate('image_format_:_jpg_,_png_,_jpeg_|_maximum_size:_2_MB') }}</p>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-gallery-picker mt-2"
                                data-picker-target="thumbnail" data-max-select="1"
                                data-viewer-id="edit_viewer" data-input-id="edit_banner_image" data-form-id="editBannerForm">
                                <i class="tio-album"></i> {{ translate('messages.Choose from Gallery') }}
                            </button>
                        </div>
                        <span class="text-danger errorMsg" id="edit_image_error"></span>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ translate('messages.close') }}</button>
                        <div class="loaderBtn">
                            <button type="submit"
                                class="btn btn-primary">{{ translate('messages.update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Edit Banner Modal End --}}

    {{-- Common Banner Modal --}}
    <div class="modal fade" id="common_banner_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('messages.select_common_banner') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="common_banner_loading" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div class="row g-3" id="common_banner_list"></div>
                    <div id="common_banner_empty" class="text-center py-4 d-none">
                        <p class="text-muted">{{ translate('no_common_banners_available') }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('messages.close') }}</button>
                    <button type="button" class="btn btn-primary" id="common_banner_submit" disabled>{{ translate('messages.add_banner') }}</button>
                </div>
            </div>
        </div>
    </div>

    @include('admin-views.product.partials._gallery_picker_modal')

@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin/js/gallery-picker.js') }}"></script>
    <script>
        GalleryPicker.init({
            apiUrl: "{{ route('vendor.gallery.api') }}",
            uploadUrl: "{{ route('vendor.gallery.image-upload') }}"
        });
    </script>
    <script>
        "use strict";
        $('#reset_btn').click(function() {
            $('#viewer').attr('src', '{{ asset('/public/assets/admin/img/upload-4.png') }}');
        })

        function openModal() {
            $(".errorMsg").html('');
            $('#new_banner').modal('show');
        }

        $('#addBannerForm').on('submit', function(e) {
            e.preventDefault();
            $(".errorMsg").html('');
            let image = $("#banner_image_input").val();
            let hasGalleryThumb = $('#addBannerForm input[name="gallery_thumbnail"]').length > 0;

            if (image === "" && !hasGalleryThumb) {
                $('#image_error').html('{{ translate('messages.banner_field_is_required') }}');
                return false;
            }
            // Remove required if gallery thumbnail was picked
            if (hasGalleryThumb) {
                $('#banner_image_input').removeAttr('required');
            }
            let formData = new FormData(this);
            let url = "{{ route('vendor.banner.store') }}";
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "POST",
                data: formData,
                contentType: false, // IMPORTANT
                processData: false, // IMPORTANT
                beforeSend: function() {
                    // $(".loaderBtn").html('<button type="button" class="btn btn-primary"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button> </div>');
                },
                success: function(response) {
                    // $(".loaderBtn").html('<button type="button" class="btn btn-primary" onClick="update_request_category()">Save changes</button>');

                    if (response.status) {
                        $("#addBannerForm")[0].reset();
                        $('#new_banner').modal('hide');
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 4000);
                    } else {
                        toastr.error(response.message);
                    }

                },
                error: function(xhr) {
                    handleValidationErrors(xhr);
                },
            });
        });

        function handleValidationErrors(xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;

                $.each(errors, function(key, value) {
                    $('#' + key + '_error').html(value[0]);
                });
            } else {
                toastr.error('Something went wrong');
            }
        }

        function openEditModal(id) {
            $(".errorMsg").html('');
            $.ajax({
                url: "{{ url('vendor/banner/edit') }}/" + id,
                type: "GET",
                success: function(response) {
                    if (response.status) {
                        let banner = response.banner;
                        $('#edit_banner_id').val(banner.id);
                        $('#edit_title_one').val(banner.title_one);
                        $('#edit_title_two').val(banner.title_two);
                        $('#edit_viewer').attr('src', banner.image);
                        // Clear any previous file/gallery selection
                        $('#edit_banner_image').val('');
                        $('#editBannerForm input[name="gallery_thumbnail"]').remove();
                        $('#edit_banner').modal('show');
                    }
                },
                error: function() {
                    toastr.error('Something went wrong');
                }
            });
        }

        // Common Banner
        var selectedCommonBannerId = null;

        function openCommonBannerModal() {
            selectedCommonBannerId = null;
            $('#common_banner_submit').prop('disabled', true);
            $('#common_banner_list').empty();
            $('#common_banner_loading').removeClass('d-none');
            $('#common_banner_empty').addClass('d-none');
            $('#common_banner_modal').modal('show');

            $.ajax({
                url: "{{ route('vendor.banner.common-banners') }}",
                type: "GET",
                success: function(response) {
                    $('#common_banner_loading').addClass('d-none');
                    if (response.status && response.banners.length > 0) {
                        response.banners.forEach(function(banner) {
                            var card = '<div class="col-sm-6 col-md-4">' +
                                '<div class="card common-banner-card border" data-id="' + banner.id + '" style="cursor:pointer;">' +
                                '<img src="' + banner.image_url + '" class="card-img-top" style="height:120px;object-fit:cover;" alt="">' +
                                '<div class="card-body p-2">' +
                                '<p class="mb-0 small font-weight-bold text-truncate">' + (banner.title_one || '') + '</p>' +
                                '<p class="mb-0 small text-muted text-truncate">' + (banner.title_two || '') + '</p>' +
                                '</div></div></div>';
                            $('#common_banner_list').append(card);
                        });
                    } else {
                        $('#common_banner_empty').removeClass('d-none');
                    }
                },
                error: function() {
                    $('#common_banner_loading').addClass('d-none');
                    toastr.error('Something went wrong');
                }
            });
        }

        $(document).on('click', '.common-banner-card', function() {
            $('.common-banner-card').removeClass('border-primary shadow');
            $(this).addClass('border-primary shadow');
            selectedCommonBannerId = $(this).data('id');
            $('#common_banner_submit').prop('disabled', false);
        });

        $('#common_banner_submit').on('click', function() {
            if (!selectedCommonBannerId) return;
            var btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: "{{ route('vendor.banner.store-from-common') }}",
                type: "POST",
                data: { common_banner_id: selectedCommonBannerId },
                success: function(response) {
                    if (response.status) {
                        $('#common_banner_modal').modal('hide');
                        toastr.success(response.message);
                        setTimeout(function() { window.location.reload(); }, 2000);
                    } else {
                        toastr.error(response.message);
                        btn.prop('disabled', false).html('{{ translate('messages.add_banner') }}');
                    }
                },
                error: function() {
                    toastr.error('Something went wrong');
                    btn.prop('disabled', false).html('{{ translate('messages.add_banner') }}');
                }
            });
        });

        $('#editBannerForm').on('submit', function(e) {
            e.preventDefault();
            $(".errorMsg").html('');
            let formData = new FormData(this);
            let bannerId = $('#edit_banner_id').val();
            let url = "{{ url('vendor/banner/update') }}/" + bannerId;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status) {
                        $("#editBannerForm")[0].reset();
                        $('#edit_banner').modal('hide');
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 4000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    handleValidationErrors(xhr);
                },
            });
        });
    </script>
@endpush
