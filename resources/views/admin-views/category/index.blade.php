@extends('layouts.admin.app')

@section('title', translate('messages.Menu List'))

@push('css_or_js')
@endpush

@section('content')
    <div id="content-disable" class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/category.png') }}" class="w--20" alt="">
                </span>
                <span>
                    {{ translate('menu_list') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="card mt-3">
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper">
                    <h5 class="card-title">{{ translate('messages.menu_list') }}<span class="badge badge-soft-dark ml-2"
                            id="itemCount">{{ $categories->total() }}</span></h5>

                    <form class="search-form w-340-lg">
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input type="search" name="search" value="{{ request()?->search ?? null }}"
                                class="form-control h-40" placeholder="{{ translate('messages.search_menu') }}"
                                aria-label="{{ translate('messages.ex_:_menu') }}">
                            <button type="submit" class="btn btn--primary h-40"><i class="tio-search"></i></button>
                        </div>
                        <!-- End Search -->
                    </form>
                    @if (request()->get('search'))
                        <button type="reset" class="btn btn--primary ml-2 location-reload-to-category"
                            data-url="{{ url()->full() }}">{{ translate('messages.reset') }}</button>
                    @endif
                    <!-- Unfold -->
                    {{-- <div class="hs-unfold mr-2">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white text-title dropdown-toggle font-medium min-height-40"
                            href="javascript:;"
                            data-hs-unfold-options='{
                                    "target": "#usersExportDropdown",
                                    "type": "css-animation"
                                }'>
                            <i class="tio-download-to mr-1 text-title"></i> {{ translate('messages.export') }}
                        </a>

                        <div id="usersExportDropdown"
                            class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">

                            <span class="dropdown-header">{{ translate('messages.download_options') }}</span>
                            <a id="export-excel" class="dropdown-item"
                                href="{{ route('admin.category.export-categories', ['type' => 'excel', request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ asset('public/assets/admin') }}/svg/components/excel.svg"
                                    alt="Image Description">
                                {{ translate('messages.excel') }}
                            </a>
                            <a id="export-csv" class="dropdown-item"
                                href="{{ route('admin.category.export-categories', ['type' => 'csv', request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ asset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                    alt="Image Description">
                                .{{ translate('messages.csv') }}
                            </a>

                        </div>
                    </div> --}}
                    <!-- End Unfold -->
                    <div>
                        <button type="button" class="btn btn--primary font-regular" data-bs-toggle="modal"
                            data-bs-target="#new_menu" onclick="openModal()"><i
                                class="tio-add-circle-outlined"></i>{{ translate('messages.New_Menu') }}</button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable" class="table table-borderless table-thead-bordered table-align-middle"
                        data-hs-datatables-options='{
                            "isResponsive": false,
                            "isShowPaging": false,
                            "paging":false,
                        }'>
                        <thead class="bg-table-head">
                            <tr>
                                <th class=" text-title border-0">{{ translate('sl') }}</th>
                                <th class=" text-title border-0">{{ translate('messages.store') }}</th>
                                <th class=" text-title border-0 w--1">{{ translate('messages.menu') }}</th>
                                <th class=" text-title border-0 text-center">{{ translate('messages.status') }}</th>
                                <th class=" text-title border-0 text-center">{{ translate('messages.priority') }}</th>
                                <th class=" text-title border-0 text-center">{{ translate('messages.action') }}</th>
                            </tr>
                        </thead>

                        <tbody id="table-div">
                            @foreach ($categories as $key => $category)
                                <tr>
                                    <td>{{ $key + $categories->firstItem() }}</td>
                                    <td>{{ $category->store->name }}</td>
                                    <td>
                                        <span class="d-block fs-14 d-block text-title max-w-250 min-w-160">
                                            {{ Str::limit($category['name'], 20, '...') }}
                                        </span>
                                    </td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm"
                                            for="stocksCheckbox{{ $category->id }}">
                                            <input type="checkbox"
                                                data-url="{{ route('admin.category.status', [$category['id'], $category->status ? 0 : 1]) }}"
                                                class="toggle-switch-input redirect-url"
                                                id="stocksCheckbox{{ $category->id }}"
                                                {{ $category->status ? 'checked' : '' }}>
                                            <span class="toggle-switch-label mx-auto">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </td>

                                    <td>
                                        <form action="{{ route('admin.category.priority', $category->id) }}"
                                            class="priority-form">
                                            <select name="priority" id="priority"
                                                class="form-control form--control-select  priority-select  mx-auto {{ $category->priority == 0 ? 'text-title' : '' }} {{ $category->priority == 1 ? 'text-info' : '' }} {{ $category->priority == 2 ? 'text-success' : '' }}">
                                                <option value="0" class="text--title"
                                                    {{ $category->priority == 0 ? 'selected' : '' }}>
                                                    {{ translate('messages.normal') }}</option>
                                                <option value="1" class="text--title"
                                                    {{ $category->priority == 1 ? 'selected' : '' }}>
                                                    {{ translate('messages.medium') }}</option>
                                                <option value="2" class="text--title"
                                                    {{ $category->priority == 2 ? 'selected' : '' }}>
                                                    {{ translate('messages.high') }}</option>
                                            </select>
                                        </form>

                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn action-btn btn-outline-theme-dark"
                                                href="javascript:" onclick="openEditModal({{ $category['id'] }})"
                                                data-id="{{ $category['id'] }}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a class="btn action-btn btn--danger btn-outline-danger form-alert"
                                                href="javascript:" data-id="category-{{ $category['id'] }}"
                                                data-message="{{ translate('Want to delete this category') }}"
                                                title="{{ translate('messages.delete_category') }}"><i
                                                    class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{ route('admin.category.delete', [$category['id']]) }}"
                                                method="post" id="category-{{ $category['id'] }}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if (count($categories) !== 0)
                <hr>
            @endif

            @if (count($categories) === 0)
                <div class="empty--data">
                    <img src="{{ asset('/public/assets/admin/svg/illustrations/sorry.svg') }}" alt="public">
                    <h5>
                        {{ translate('no_data_found') }}
                    </h5>
                </div>
            @endif
            <div class="page-area px-4 pb-3">
                <div class="d-flex align-items-center justify-content-end">
                    <div>
                        {!! $categories->withQueryString()->links() !!}
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="offcanvas__categoryBtn" class="custom-offcanvas d-flex flex-column justify-content-between">
        <div id="data-view" class="h-100">
        </div>
    </div>
    <div id="offcanvasOverlay" class="offcanvas-overlay"></div>

    {{-- Model Popup Start --}}
    <div class="modal fade" id="new_menu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('messages.add_new_menu') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addMenuForm">
                    <div class="modal-body">
                        <input name="position" value="0" class="initial-hidden">
                        <div class="form-group mb-0 error-wrapper">
                            <label class="input-label" for="store_id">{{ translate('messages.store') }} <span
                                    class="form-label-secondary text-danger" data-toggle="tooltip" data-placement="right"
                                    data-original-title="{{ translate('messages.Required.') }}"> *
                                </span><span class="input-label-secondary"></span></label>
                            <select name="store_id" id="store_id" title="{{ translate('messages.select_store') }}"
                                data-placeholder="{{ translate('messages.select_store') }}"
                                class="js-data-example-ajax form-control">
                            </select>
                            <span class="text-danger errorMsg" id="store_id_error"></span>

                        </div>
                        <div class="form-group" id="new_category_group">
                            <label class="input-label" for="default_name">{{ translate('messages.menu') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="{{ translate('messages.new_menu') }}">
                            <span class="text-danger errorMsg" id="name_error"></span>
                        </div>

                        <div class="form-group" id="new_category_group">
                            <label class="input-label" for="default_image">{{ translate('messages.menu_image') }} <small
                                    class="text-danger">* ( {{ translate('messages.ratio') }} 1:1)</small></label>
                            <label class="text-center my-auto position-relative d-inline-block">
                                <img class="img--176 border" id="viewer"
                                    src="{{ asset('public/assets/admin/img/upload-img.png') }}" alt="image" />
                                <div class="icon-file-group">
                                    <div class="icon-file">
                                        <input type="file" name="image" id="image"
                                            class="custom-file-input this-url  read-url"
                                            accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <i class="tio-edit"></i>
                                    </div>
                                </div>
                            </label>
                            <br>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-gallery-picker mt-2"
                                data-picker-target="thumbnail" data-max-select="1"
                                data-viewer-id="viewer" data-input-id="image" data-form-id="addMenuForm">
                                <i class="tio-album"></i> {{ translate('messages.Choose from Gallery') }}
                            </button>
                        </div>

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

    {{-- Edit Menu Modal Start --}}
    <div class="modal fade" id="edit_menu" tabindex="-1" role="dialog" aria-labelledby="editMenuModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMenuModalLabel">{{ translate('messages.update_menu') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editMenuForm">
                    <input type="hidden" name="category_id" id="edit_category_id">
                    <div class="modal-body">
                        <div class="form-group mb-0 error-wrapper">
                            <label class="input-label" for="edit_store_id">{{ translate('messages.store') }} <span
                                    class="form-label-secondary text-danger" data-toggle="tooltip" data-placement="right"
                                    data-original-title="{{ translate('messages.Required.') }}"> *
                                </span></label>
                            <select name="store_id" id="edit_store_id" title="{{ translate('messages.select_store') }}"
                                data-placeholder="{{ translate('messages.select_store') }}"
                                class="js-data-example-ajax-edit form-control">
                            </select>
                            <span class="text-danger errorMsg" id="edit_store_id_error"></span>
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="edit_name">{{ translate('messages.menu') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control"
                                placeholder="{{ translate('messages.new_menu') }}">
                            <span class="text-danger errorMsg" id="edit_name_error"></span>
                        </div>
                        <div class="form-group" id="edit_image_group">
                            <label class="input-label">{{ translate('messages.menu_image') }} <small
                                    class="text-danger">* ( {{ translate('messages.ratio') }} 1:1 )</small></label>
                            <label class="text-center my-auto position-relative d-inline-block">
                                <img class="img--176 border" id="edit_viewer"
                                    src="{{ asset('public/assets/admin/img/upload-img.png') }}" alt="image" />
                                <div class="icon-file-group">
                                    <div class="icon-file">
                                        <input type="file" name="image" id="edit_image"
                                            class="custom-file-input"
                                            accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <i class="tio-edit"></i>
                                    </div>
                                </div>
                            </label>
                            <br>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-gallery-picker mt-2"
                                data-picker-target="thumbnail" data-max-select="1"
                                data-viewer-id="edit_viewer" data-input-id="edit_image" data-form-id="editMenuForm">
                                <i class="tio-album"></i> {{ translate('messages.Choose from Gallery') }}
                            </button>
                        </div>
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
    {{-- Edit Menu Modal End --}}

    @include('admin-views.product.partials._gallery_picker_modal')

@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin') }}/js/view-pages/category-index.js"></script>
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
            $('#new_menu').modal('show');
        }
        $('#addMenuForm').on('submit', function(e) {
            e.preventDefault();
            $(".errorMsg").html('');
            let name = $("#name").val().trim();
            let image = $("#image").val();
            let store = $("#store_id").val();

            if (!store) {
                $('#store_id_error').html('{{ translate('messages.store_field_is_required') }}');
                return false;
            }
            if (name === "") {
                $('#name_error').html('{{ translate('messages.menu_field_is_required') }}');
                return false;
            }
            let formData = new FormData(this);
            let url = "{{ route('admin.category.store') }}";
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
                    $('#loading').show()
                    // $(".loaderBtn").html('<button type="button" class="btn btn-primary"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button> </div>');
                },
                success: function(response) {
                    // $(".loaderBtn").html('<button type="button" class="btn btn-primary" onClick="update_request_category()">Save changes</button>');
                    $('#loading').hide()
                    if (response.status) {
                        $("#addMenuForm")[0].reset();
                        $('#new_menu').modal('hide');
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

        function handleValidationErrors(xhr, prefix) {
            prefix = prefix || '';
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;

                $.each(errors, function(key, value) {
                    $('#' + prefix + key + '_error').html(value[0]);
                });
            } else {
                toastr.error('Something went wrong');
            }
        }

        // Edit modal
        function openEditModal(id) {
            $(".errorMsg").html('');
            $.ajax({
                url: "{{ url('admin/menu/edit') }}/" + id,
                type: "GET",
                success: function(response) {
                    if (response.status) {
                        let cat = response.category;
                        $('#edit_category_id').val(cat.id);
                        $('#edit_name').val(cat.name);
                        $('#edit_viewer').attr('src', cat.image);
                        // Set store in select2
                        $('#edit_store_id').empty();
                        if (cat.store_id && cat.store_name) {
                            let option = new Option(cat.store_name, cat.store_id, true, true);
                            $('#edit_store_id').append(option).trigger('change');
                        }
                        $('#edit_image').val('');
                        $('#editMenuForm input[name="gallery_thumbnail"]').remove();
                        $('#edit_menu').modal('show');
                    }
                },
                error: function() {
                    toastr.error('Something went wrong');
                }
            });
        }

        $('#edit_store_id').select2({
            ajax: {
                url: '{{ url('/') }}/admin/store/get-stores',
                data: function(params) {
                    return {
                        q: params.term,
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

        $('#editMenuForm').on('submit', function(e) {
            e.preventDefault();
            $(".errorMsg").html('');
            let name = $("#edit_name").val().trim();
            let store = $("#edit_store_id").val();

            if (!store) {
                $('#edit_store_id_error').html('{{ translate('messages.store_field_is_required') }}');
                return false;
            }
            if (name === "") {
                $('#edit_name_error').html('{{ translate('messages.menu_field_is_required') }}');
                return false;
            }
            let formData = new FormData(this);
            let categoryId = $('#edit_category_id').val();
            let url = "{{ url('admin/menu/update') }}/" + categoryId;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(response) {
                    $('#loading').hide();
                    if (response.status) {
                        $("#editMenuForm")[0].reset();
                        $('#edit_menu').modal('hide');
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 4000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    $('#loading').hide();
                    handleValidationErrors(xhr, 'edit_');
                },
            });
        });

        // Preview edit image
        $("#edit_image").change(function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#edit_viewer').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        $('.location-reload-to-category').on('click', function() {
            const url = $(this).data('url');
            let nurl = new URL(url);
            nurl.searchParams.delete('search');
            location.href = nurl;
        });

        $("#customFileEg1").change(function() {
            readURL(this);
            $('#viewer').show(1000)
        });

        $('#reset_btn').click(function() {
            $('#exampleFormControlSelect1').val(null).trigger('change');
            $('#viewer').attr('src', "{{ asset('public/assets/admin/img/upload-img.png') }}");
        })


        $(document).on('click', '.data-info-show', function() {
            let id = $(this).data('id');
            let url = $(this).data('url');
            $('#content-disable').addClass('disabled');
            fetch_data(id, url)
        })

        function fetch_data(id, url) {
            $.ajax({
                url: url,
                type: "get",
                beforeSend: function() {
                    $('#data-view').empty();
                    $('#loading').show()
                },
                success: function(data) {
                    $("#data-view").append(data.view);
                    initLangTabs();
                    initSelect2Dropdowns();
                },
                complete: function() {
                    $('#loading').hide()
                }
            })
        }

        function initSelect2Dropdowns() {
            $('.js-select2-custom1').select2({
                placeholder: 'Select tax rate',
                allowClear: true
            });

            $('.offcanvas-close, #offcanvasOverlay').on('click', function() {
                $('.custom-offcanvas').removeClass('open');
                $('#offcanvasOverlay').removeClass('show');
                $('#content-disable').removeClass('disabled');
            });
        }
    </script>
@endpush
