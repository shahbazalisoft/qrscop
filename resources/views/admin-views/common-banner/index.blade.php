@extends('layouts.admin.app')

@section('title', translate('messages.common_banner'))

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
                    {{ translate('messages.common_banner') }}
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
                                <div class="input-group input--group">
                                    <input id="datatableSearch" type="search" name="search" class="form-control"
                                        placeholder="{{ translate('messages.search_by_title') }}"
                                        aria-label="{{ translate('messages.search_here') }}"
                                        value="{{ request()->search }}">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                            </form>
                            <div>
                                <button type="button" class="btn btn--primary font-regular" onclick="openModal()">
                                    <i class="tio-add-circle-outlined"></i>
                                    {{ translate('messages.New_Banner') }}
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
                                                    alt="{{ $banner->title_one }} image">
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
                                                        data-url="{{ route('admin.common-banner.status_update', [$banner['id'], $banner->status ? 0 : 1]) }}"
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
                                                <form action="{{ route('admin.common-banner.delete', [$banner['id']]) }}"
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
        </div>
    </div>

    {{-- Add Banner Modal --}}
    <div class="modal fade" id="new_banner" tabindex="-1" role="dialog" aria-labelledby="addBannerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBannerModalLabel">{{ translate('messages.add_new_banner') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addBannerForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="input-label" for="title_one">{{ translate('messages.title_one') }}</label>
                            <input type="text" name="title_one" id="title_one" class="form-control"
                                placeholder="{{ translate('messages.title_one') }}">
                        </div>
                        <div class="form-group">
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

    {{-- Edit Banner Modal --}}
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

    @include('admin-views.product.partials._gallery_picker_modal')

@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin/js/gallery-picker.js') }}"></script>
    <script>
        GalleryPicker.init({
            apiUrl: "{{ route('admin.gallery.api') }}",
            uploadUrl: "{{ route('admin.gallery.image-upload') }}"
        });
    </script>
    <script>
        "use strict";

        function openModal() {
            $(".errorMsg").html('');
            $('#addBannerForm')[0].reset();
            $('#viewer').attr('src', '{{ asset('/public/assets/admin/img/upload-4.png') }}');
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
            if (hasGalleryThumb) {
                $('#banner_image_input').removeAttr('required');
            }
            let formData = new FormData(this);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admin.common-banner.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status) {
                        $("#addBannerForm")[0].reset();
                        $('#new_banner').modal('hide');
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
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
                url: "{{ url('admin/common-banner/edit') }}/" + id,
                type: "GET",
                success: function(response) {
                    if (response.status) {
                        let banner = response.banner;
                        $('#edit_banner_id').val(banner.id);
                        $('#edit_title_one').val(banner.title_one);
                        $('#edit_title_two').val(banner.title_two);
                        $('#edit_viewer').attr('src', banner.image);
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

        $('#editBannerForm').on('submit', function(e) {
            e.preventDefault();
            $(".errorMsg").html('');
            let formData = new FormData(this);
            let bannerId = $('#edit_banner_id').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('admin/common-banner/update') }}/" + bannerId,
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
                        }, 2000);
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
