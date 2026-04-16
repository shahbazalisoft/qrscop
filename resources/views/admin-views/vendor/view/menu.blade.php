@extends('layouts.admin.app')

@section('title', $store->name . "'s " . translate('messages.menu'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{ asset('public/assets/admin/css/croppie.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        @include('admin-views.vendor.view.partials._header', ['store' => $store])
        <!-- Page Heading -->

        <div class="tab-content">
            <div class="tab-pane fade show active" id="product">
                <div class="card">
                    <div class="card-header border-0 py-2">
                        <div class="search--button-wrapper">
                            <h3 class="card-title"> {{ translate('messages.menu') }} <span
                                    class="badge badge-soft-dark ml-2"><span
                                        class="total_items">{{ $foods->total() }}</span></span>
                            </h3>

                            <form class="search-form">
                                <input type="hidden" name="store_id" value="{{ $store->id }}">
                                <!-- Search -->
                                <div class="input-group input--group">
                                    <input id="datatableSearch" name="search" value="{{ request()?->search ?? null }}"
                                        type="search" class="form-control h--40px"
                                        placeholder="{{ translate('Search by name...') }}"
                                        aria-label="{{ translate('messages.search_here') }}">
                                    <button type="submit" class="btn btn--secondary h--40px"><i
                                            class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form>

                            <!-- Unfold -->
                            <!-- End Unfold -->
                            <a href="" class="btn btn--primary pull-right"><i
                                    class="tio-add-circle"></i> {{ translate('messages.add_new_menu') }}</a>
                        </div>
                    </div>
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                                "order": [],
                                "orderCellsTop": true,
                                "paging": false
                            }'>
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0">{{ translate('sl') }}</th>
                                    <th class="border-0">{{ translate('messages.name') }}</th>
                                    <th class="border-0">{{ translate('messages.status') }}</th>
                                    <th class="border-0 text-center">{{ translate('messages.action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="setrows">

                                @foreach ($foods as $key => $food)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <a class="media align-items-center"
                                                    href="">
                                                    <img class="avatar avatar-lg mr-3 onerror-image"
                                                        src="{{ $food['image_full_url'] ?? asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                                        data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                                        alt="{{ $food->name }} image">

                                                    <div class="media-body">
                                                        <h5 class="text-hover-primary mb-0">
                                                            {{ Str::limit($food['name'], 50, '...') }}</h5>
                                                    </div>
                                                </a>
                                            </td>
                                            
                                            <td>
                                                <label class="toggle-switch toggle-switch-sm"
                                                    for="stocksCheckbox{{ $food->id }}">
                                                    <input type="checkbox" class="toggle-switch-input redirect-url"
                                                        data-url=""
                                                        id="stocksCheckbox{{ $food->id }}"
                                                        {{ $food->status ? 'checked' : '' }}>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </td>
                                            <td>
                                                <div class="btn--container justify-content-center">
                                                    <a class="btn action-btn btn--primary btn-outline-primary"
                                                        href=""
                                                        title="{{ translate('messages.edit_item') }}"><i
                                                            class="tio-edit"></i>
                                                    </a>
                                                    <a class="btn action-btn btn--danger btn-outline-danger form-alert"
                                                        href="javascript:" data-id="food-{{ $food['id'] }}"
                                                        data-message="{{ translate('messages.Want to delete this item ?') }}"
                                                        title="{{ translate('messages.delete_item') }}"><i
                                                            class="tio-delete-outlined"></i>
                                                    </a>
                                                </div>
                                                <form action=""
                                                    method="post" id="food-{{ $food['id'] }}">
                                                    @csrf @method('delete')
                                                </form>
                                            </td>
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if (count($foods) !== 0)
                        <hr>
                    @endif
                    <div class="page-area">
                        {!! $foods->links() !!}
                    </div>
                    @if (count($foods) === 0)
                        <div class="empty--data">
                            <img src="{{ asset('/public/assets/admin/svg/illustrations/sorry.svg') }}" alt="public">
                            <h5>
                                {{ translate('no_data_found') }}
                            </h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <!-- Page level plugins -->
    <script>
        "use script";
        // Call the dataTables jQuery plugin
        $(document).ready(function() {
            $('#dataTable').DataTable();

            // INITIALIZATION OF DATATABLES
            // =======================================================
            let datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function() {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function() {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('change', function() {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function() {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });

        });
    </script>
@endpush
