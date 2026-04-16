@extends('layouts.vendor.app')

@section('title', translate('messages.customers'))

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
                    {{ translate('messages.Customers') }}
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
                                {{ translate('messages.customer_list') }}<span class="badge badge-soft-dark ml-2"
                                    id="itemCount">{{ $customers->count() }}</span>
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
                                    <th class="border-0">{{ translate('messages.name') }}</th>
                                    <th class="border-0">{{ translate('messages.mobile_no') }}</th>
                                    <th class="border-0">{{ translate('messages.total_order') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($customers as $key => $customer)
                                    <tr>
                                        <td>{{ $key + $customers->firstItem() }}</td>
                                        <td>
                                            <h5 class="text-hover-primary mb-0">
                                                {{$customer['name']?? 'N/A'}}</h5>
                                        </td>
                                        
                                        <td>
                                            <h5 class="text-hover-primary mb-0">
                                                {{ $customer['phone'] }}</h5>
                                        </td>
                                        <td>
                                            <h5 class="text-hover-primary mb-0">
                                                {{ $customer['total_order'] }}</h5>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if (count($customers) !== 0)
                            <hr>
                        @endif
                        <div class="page-area">
                            {!! $customers->links() !!}
                        </div>
                        @if (count($customers) === 0)
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

@endsection

