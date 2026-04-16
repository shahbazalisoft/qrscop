@extends('layouts.vendor.app')
@section('title', translate('messages.Kitchen Staff'))

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <h1 class="page-header-title mb-2">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/role.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{ translate('messages.kitchen_staff') }}
                    <span class="badge badge-soft-dark ml-2">{{ $staff->total() }}</span>
                </span>
            </h1>
            <a href="{{ route('vendor.kitchen-staff.create') }}" class="btn btn--primary mb-2">
                <i class="tio-add-circle"></i>
                <span class="text">{{ translate('messages.add_new') }}</span>
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header py-2 justify-content-end border-0">
            <div class="search--button-wrapper justify-content-end">
                <form class="search-form">
                    <div class="input-group input--group">
                        <input value="{{ $search ?? '' }}" type="search" name="search" class="form-control"
                            placeholder="{{ translate('messages.Ex:') }} {{ translate('Search by name or email..') }}"
                            aria-label="Search">
                        <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0">{{ translate('messages.#') }}</th>
                            <th class="border-0">{{ translate('messages.name') }}</th>
                            <th class="border-0">{{ translate('messages.email') }}</th>
                            <th class="border-0">{{ translate('messages.phone') }}</th>
                            <th class="border-0 text-center">{{ translate('messages.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staff as $k => $s)
                            <tr>
                                <th scope="row">{{ $k + $staff->firstItem() }}</th>
                                <td class="text-capitalize text-break">{{ $s->f_name }} {{ $s->l_name }}</td>
                                <td>{{ $s->email }}</td>
                                <td>{{ $s->phone }}</td>
                                <td>
                                    <div class="btn--container justify-content-center">
                                        <a class="btn action-btn btn--primary btn-outline-primary"
                                            href="{{ route('vendor.kitchen-staff.edit', [$s->id]) }}"
                                            title="{{ translate('messages.edit') }}">
                                            <i class="tio-edit"></i>
                                        </a>
                                        <a class="btn action-btn btn--danger btn-outline-danger form-alert"
                                            href="javascript:"
                                            data-id="kitchen-staff-{{ $s->id }}"
                                            data-message="{{ translate('messages.Want_to_delete_this_kitchen_staff') }}"
                                            title="{{ translate('messages.delete') }}">
                                            <i class="tio-delete-outlined"></i>
                                        </a>
                                    </div>
                                    <form action="{{ route('vendor.kitchen-staff.destroy', [$s->id]) }}"
                                        method="post" id="kitchen-staff-{{ $s->id }}">
                                        @csrf @method('delete')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if(count($staff) !== 0)
        <div class="card-footer">
            <div class="page-area">
                <table>
                    <tfoot>
                        {!! $staff->links() !!}
                    </tfoot>
                </table>
            </div>
        </div>
        @endif
        @if(count($staff) === 0)
        <div class="empty--data">
            <img src="{{ asset('/public/assets/admin/svg/illustrations/sorry.svg') }}" alt="public">
            <h5>{{ translate('no_data_found') }}</h5>
        </div>
        @endif
    </div>
</div>
@endsection
