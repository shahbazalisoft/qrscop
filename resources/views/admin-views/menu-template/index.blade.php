@extends('layouts.admin.app')

@section('title', translate('messages.menu_template'))

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
                    {{ translate('menu_template') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <div class="card mt-3">
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper">
                    <h5 class="card-title">{{ translate('messages.template_list') }}<span
                            class="badge badge-soft-dark ml-2" id="itemCount">{{ $rows->total() }}</span></h5>

                    <form class="search-form w-340-lg">
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input type="search" name="search" value="{{ request()?->search ?? null }}"
                                class="form-control h-40" placeholder="{{ translate('messages.search_templates') }}"
                                aria-label="{{ translate('messages.ex_:_categories') }}">
                            <button type="submit" class="btn btn--primary h-40"><i class="tio-search"></i></button>
                        </div>
                        <!-- End Search -->
                    </form>
                    @if (request()->get('search'))
                        <button type="reset" class="btn btn--primary ml-2 location-reload-to-category"
                            data-url="{{ url()->full() }}">{{ translate('messages.reset') }}</button>
                    @endif
                    <!-- Unfold -->
                    <div>
                        <button type="button" class="btn btn--primary font-regular" data-bs-toggle="modal"
                            data-bs-target="#new_menu_template" onclick="openModal()"><i
                                class="tio-add-circle-outlined"></i>{{ translate('messages.add_menu_template') }}</button>
                    </div>
                    <!-- End Unfold -->
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable"
                        class="table table-borderless table-thead-bordered table-align-middle"
                        data-hs-datatables-options='{
                            "isResponsive": false,
                            "isShowPaging": false,
                            "paging":false,
                        }'>
                        <thead class="bg-table-head">
                            <tr>
                                <th class=" text-title border-0">{{ translate('sl') }}</th>
                                <th class=" text-title border-0 w--1">{{ translate('messages.template') }}</th>
                                <th class=" text-title border-0 w--1">{{ translate('messages.title') }}</th>
                                <th class=" text-title border-0 text-center">{{ translate('messages.status') }}</th>
                                <th class=" text-title border-0 text-center">{{ translate('messages.priority') }}</th>
                                <th class=" text-title border-0 text-center">{{ translate('messages.action') }}</th>
                            </tr>
                        </thead>

                        <tbody id="table-div">
                            @foreach ($rows as $key => $row)
                                <tr>
                                    <td>{{ $key + $rows->firstItem() }}</td>
                                    <td>
                                        <img class="avatar avatar-lg mr-3 onerror-image" src="{{asset('storage/app/public/menu-template')}}/{{ $row->template }}"
                                         data-onerror-image="{{asset('public/assets/admin/img/160x160/img2.jpg')}}" alt="{{$row->template}} image">
                                    </td>
                                    <td>
                                        <span class="d-block fs-14 d-block text-title max-w-250 min-w-160">
                                            {{ Str::limit($row['title'], 20, '...') }}
                                        </span>
                                    </td>
                                    
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm"
                                            for="stocksCheckbox{{ $row->id }}">
                                            <input type="checkbox"
                                                data-url="{{ route('admin.menu-templates.status', [$row['id'], $row->status ? 0 : 1]) }}"
                                                class="toggle-switch-input redirect-url"
                                                id="stocksCheckbox{{ $row->id }}"
                                                {{ $row->status ? 'checked' : '' }}>
                                            <span class="toggle-switch-label mx-auto">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </td>
                                    
                                    <td>
                                        <form action="{{ route('admin.menu-templates.priority', $row->id) }}"
                                            class="priority-form">
                                            <select name="priority" id="priority"
                                                class="form-control form--control-select  priority-select  mx-auto {{ $row->priority == 0 ? 'text-title' : '' }} {{ $row->priority == 1 ? 'text-info' : '' }} {{ $row->priority == 2 ? 'text-success' : '' }}">
                                                <option value="0" class="text--title"
                                                    {{ $row->priority == 0 ? 'selected' : '' }}>
                                                    {{ translate('messages.normal') }}</option>
                                                <option value="1" class="text--title"
                                                    {{ $row->priority == 1 ? 'selected' : '' }}>
                                                    {{ translate('messages.medium') }}</option>
                                                <option value="2" class="text--title"
                                                    {{ $row->priority == 2 ? 'selected' : '' }}>
                                                    {{ translate('messages.high') }}</option>
                                            </select>
                                        </form>

                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn action-btn btn-outline-theme-dark "
                                                href="{{route('admin.menu-templates.edit',[$row['id']])}}" data-id="{{ $row['id'] }}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a class="btn action-btn btn--danger btn-outline-danger form-alert"
                                                href="javascript:" data-id="category-{{ $row['id'] }}"
                                                data-message="{{ translate('Want to delete this template') }}"
                                                title="{{ translate('messages.delete_category') }}"><i
                                                    class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{ route('admin.menu-templates.delete', [$row['id']]) }}"
                                                method="post" id="category-{{ $row['id'] }}">
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
            @if (count($rows) !== 0)
                <hr>
            @endif

            @if (count($rows) === 0)
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
                        {!! $rows->withQueryString()->links() !!}
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Model Popup Start --}}
    <div class="modal fade" id="new_menu_template" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('messages.add_menu_template') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addMenuTemplateForm">
                    <div class="modal-body">
                        <div class="form-group" id="new_category_group">
                            <label class="input-label" for="default_name">{{ translate('messages.title') }} </label>
                            <input type="text" name="title" id="title" class="form-control"
                                placeholder="{{ translate('messages.menu_template') }}" >
                            <span class="text-danger errorMsg" id="name_error"></span>
                        </div>

                        <div class="form-group" id="new_category_group">
                            <label class="input-label" for="default_image">{{ translate('messages.template_image') }} <small
                                    class="text-danger">* ( {{ translate('messages.ratio') }} 1:1)</small></label>
                            <label class="text-center my-auto position-relative d-inline-block">
                                <img class="img--176 border" id="viewer"
                                    src="{{ asset('public/assets/admin/img/upload-img.png') }}" alt="image" />
                                <div class="icon-file-group">
                                    <div class="icon-file">
                                        <input type="file" name="template" id="template"
                                            class="custom-file-input this-url  read-url"
                                            accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <i class="tio-edit"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <span class="text-danger errorMsg" id="template_error"></span>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ translate('messages.close') }}</button>
                        <div class="loaderBtn">
                            <button type="submit" class="btn btn-primary" >{{ translate('messages.save_changes') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Model Popup End --}}

@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin') }}/js/view-pages/category-index.js"></script>
    <script>
        function openModal() {
            $(".errorMsg").html('');
            $('#new_menu_template').modal('show');
        }
        $('#addMenuTemplateForm').on('submit', function(e) {
            e.preventDefault();
            $(".errorMsg").html('');
            let image = $("#template").val();

            if (image === "") {
                $('#template_error').html('{{ translate('messages.menu_template_field_is_required') }}');
                return false;
            }
            let formData = new FormData(this);
            let url = "{{ route('admin.menu-templates.store') }}";
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
                        $("#addMenuTemplateForm")[0].reset();
                        $('#new_menu_template').modal('hide');
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 4000);
                    } else {
                        toastr.error(response.message);
                    }

                },
                error: function (xhr) {
                    handleValidationErrors(xhr);
                },
            });
        });
        function handleValidationErrors(xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;

                $.each(errors, function (key, value) {
                    $('#' + key + '_error').html(value[0]);
                });
            } else {
                toastr.error('Something went wrong');
            }
        }
        "use strict";
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
    </script>
@endpush
