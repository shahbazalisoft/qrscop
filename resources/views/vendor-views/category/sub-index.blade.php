@extends('layouts.vendor.app')

@section('title', translate('messages.sub_menu'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/category.png')}}" class="w--20" alt="">
                </span>
                <span>
                    {{translate('messages.sub_menu')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="card mt-2">
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper">
                    <h5 class="card-title">{{translate('messages.sub_menu_list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$categories->total()}}</span></h5>

                    <form   class="search-form">
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input id="datatableSearch" data-reload_url="{{url()->full()}}" name="search" value="{{ request()?->search ?? null }}"  type="search" class="form-control" placeholder="{{translate('messages.ex_:_search_sub_categories')}}" aria-label="{{translate('messages.ex_:_sub_categories')}}">
                            <input type="hidden" name="position" value="1">
                            <input type="hidden" name="sub_category" value="1">
                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                        </div>
                        <!-- End Search -->
                    </form>
                    @if(request()->get('search'))
                    <button type="reset" id="reset_btn" class="btn btn--primary ml-2 location-reload-to-category" data-url="{{url()->full()}}">{{translate('messages.reset')}}</button>
                    @endif
                    <div>
                        <button type="button" class="btn btn--primary font-regular" data-bs-toggle="modal"
                            data-bs-target="#new_sub_menu" onclick="openModal()"><i
                                class="tio-add-circle-outlined"></i>{{ translate('messages.New_Sub_Menu') }}</button>
                    </div>
                    <!-- Unfold -->
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable"
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        data-hs-datatables-options='{
                            "search": "#datatableSearch",
                            "entries": "#datatableEntries",
                            "isResponsive": false,
                            "isShowPaging": false,
                            "paging":false,
                        }'>
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">{{translate('sl')}}</th>
                                <th class="border-0 w--1">{{translate('messages.main_category')}}</th>
                                <th class="border-0 text-center">{{translate('messages.sub_category')}}</th>
                                <th class="border-0 text-center">{{translate('messages.status')}}</th>
                                <th class="border-0 text-center">{{translate('messages.priority')}}</th>
                                <th class="border-0 text-center">{{translate('messages.action')}}</th>
                            </tr>
                        </thead>

                        <tbody id="table-div">
                        @foreach($categories as $key=>$category)
                            <tr>
                                <td>{{$key+$categories->firstItem()}}</td>
                                <td>
                                    <span class="d-block font-size-sm text-body">
                                        {{ $category?->parent?->name ? Str::limit($category->parent['name'],20,'...') : translate('Invalid_Category') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="d-block font-size-sm text-body">
                                        {{Str::limit($category?->name,20,'...')}}
                                    </span>
                                </td>
                                <td>
                                    <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$category->id}}">
                                    <input type="checkbox" data-url="{{route('vendor.category.status',[$category['id'],$category->status?0:1])}}" class="toggle-switch-input redirect-url" id="stocksCheckbox{{$category->id}}" {{$category->status?'checked':''}}>
                                        <span class="toggle-switch-label mx-auto">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>
                                
                                <td>
                                    <form action="" class="priority-form">
                                        <select name="priority" id="priority" class="form-control priority-select form--control-select mx-auto {{$category->priority == 0 ? 'text-title':''}} {{$category->priority == 1 ? 'text-info':''}} {{$category->priority == 2 ? 'text-success':''}}">
                                            <option value="0" {{$category->priority == 0?'selected':''}}>{{translate('messages.normal')}}</option>
                                            <option value="1" {{$category->priority == 1?'selected':''}}>{{translate('messages.medium')}}</option>
                                            <option value="2" {{$category->priority == 2?'selected':''}}>{{translate('messages.high')}}</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <div class="btn--container justify-content-center">
                                             <a class="btn action-btn btn-outline-theme-dark offcanvas-trigger editBtn" href="javascript:void(0)"
                                                data-id="{{ $category['id'] }}"
                                                data-url="{{ route('vendor.category.sub-edit') }}"

                                            data-target="#offcanvas__categoryBtn">
                                                <i class="tio-edit"></i>
                                            </a>
                                        <a class="btn action-btn btn--danger btn-outline-danger form-alert" href="javascript:"
                                           data-id="category-{{$category['id']}}" data-message="{{ translate('Want to delete this category') }}" title="{{translate('messages.delete_category')}}"><i class="tio-delete-outlined"></i>
                                        </a>
                                        <form action="{{route('vendor.category.delete',[$category['id']])}}" method="post" id="category-{{$category['id']}}">
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
            @if(count($categories) !== 0)
            <hr>
            @endif
            <div class="page-area">
                {!! $categories->appends(request()->query())->links() !!}
            </div>
            @if(count($categories) === 0)
            <div class="empty--data">
                <img src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                <h5>
                    {{translate('no_data_found')}}
                </h5>
            </div>
            @endif
        </div>
    </div>
        <div id="offcanvas__categoryBtn" class="custom-offcanvas d-flex flex-column justify-content-between">
         <div id="data-view" class="h-100">
        </div>
    </div>
    <div id="offcanvasOverlay" class="offcanvas-overlay"></div>

    {{-- Model Popup Start --}}
    <div class="modal fade" id="new_sub_menu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('messages.add_new_sub_menu') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addSubMenuForm">
                    <div class="modal-body">
                        <input name="position" value="0" class="initial-hidden">
                        <div class="form-group" id="new_category_group">
                            <label class="input-label" for="default_name">{{ translate('messages.sub_menu') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="sub_menu" id="sub_menu" class="form-control"
                                placeholder="{{ translate('messages.new_sub_menu') }}" >
                            <span class="text-danger errorMsg" id="name_error"></span>
                        </div>

                        <div class="form-group" id="new_category_group">
                            <label class="input-label" for="default_image">{{ translate('messages.main_menu') }} <small
                                    class="text-danger">*</small></label>
                            <select name="parent_id" id="parent_id" class="form-control js-select2-custom" >
                                <option value="" selected disabled>{{translate('Select Main Menu')}}</option>
                                @foreach($categoryList as $cat)
                                    <option value="{{$cat['id']}}" >{{$cat['name']}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger errorMsg" id="parent_id_error"></span>
                        </div>

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
    <div id="modalContainer"></div>

    {{-- Model Popup End --}}



@endsection

@push('script_2')
    {{-- <script src="{{asset('public/assets/admin')}}/js/view-pages/sub-category-index.js"></script> --}}
    <script>
        function openModal() {
            $(".errorMsg").html('');
            $('#new_sub_menu').modal('show');
        }

        $('#addSubMenuForm').on('submit', function(e) {
            e.preventDefault();
            $(".errorMsg").html('');
            let sub_menu = $("#sub_menu").val().trim();
            let parent_id = $("#parent_id").val();

            if (sub_menu === "") {
                $('#name_error').html('{{ translate('messages.sub_menu_field_is_required') }}');
                return false;
            }
            if (!parent_id) {
                $('#parent_id_error').html('{{ translate('messages.main_menu_field_is_required') }}');
                return false;
            }
            let url = "{{ route('vendor.category.store') }}";
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "POST",
                data: { name: sub_menu, parent_id: parent_id, position: 1},
                beforeSend: function() {
                    // $(".loaderBtn").html('<button type="button" class="btn btn-primary"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button> </div>');
                },
                success: function(response) {
                    // $(".loaderBtn").html('<button type="button" class="btn btn-primary" onClick="update_request_category()">Save changes</button>');

                    if (response.status) {
                        $("#addSubMenuForm")[0].reset();
                        $('#new_sub_menu').modal('hide');
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

        $(document).on('click', '.editBtn', function () {
            let id = $(this).data('id');
            let url = $(this).data('url');
            $.ajax({
                url: url,
                type: "GET",
                data: { id },
                success: function (res) {
                    $('#modalContainer').html(res.html);
                    $('#edit_sub_menu').modal('show');
                }
            });
        });
        $(document).on('submit', '#editSubMenuForm', function (e) {
            e.preventDefault();
            $(".errorMsg").html('');
            let sub_menu = $("#edit_name").val().trim();
            let parent_id = $("#edit_parent_id").val();
            let id = $("#id").val();

            if (sub_menu === "") {
                $('#edit_name_error').html('{{ translate('messages.sub_menu_field_is_required') }}');
                return false;
            }
            if (!parent_id) {
                $('#edit_parent_id_error').html('{{ translate('messages.main_menu_field_is_required') }}');
                return false;
            }
            let url = "{{ route('vendor.category.sub-update') }}";
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "POST",
                data: {id: id, name: sub_menu, parent_id: parent_id, position: 1},
                beforeSend: function() {
                    // $(".loaderBtn").html('<button type="button" class="btn btn-primary"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button> </div>');
                },
                success: function(response) {
                    // $(".loaderBtn").html('<button type="button" class="btn btn-primary" onClick="update_request_category()">Save changes</button>');

                    if (response.status) {
                        $("#editSubMenuForm")[0].reset();
                        $('#edit_sub_menu').modal('hide');
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
        $('#reset_btn').click(function() {
            $('#exampleFormControlSelect1').val(null).trigger('change');
            $('#viewer').attr('src', "{{ asset('public/assets/admin/img/upload-img.png') }}");
        })
    </script>
@endpush
