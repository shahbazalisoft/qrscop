@extends('layouts.vendor.app')
@section('title', translate('messages.Edit Kitchen Staff'))

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{ asset('public/assets/admin/img/role.png') }}" class="w--26" alt="">
            </span>
            <span>{{ translate('messages.edit_kitchen_staff') }}</span>
        </h1>
    </div>

    <form action="{{ route('vendor.kitchen-staff.update', [$staff->id]) }}" method="post" class="js-validate">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <span class="card-header-icon"><i class="tio-user"></i></span>
                    <span>{{ translate('messages.general_information') }}</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="input-label" for="f_name">{{ translate('messages.first_name') }}</label>
                            <input type="text" name="f_name" class="form-control" id="f_name"
                                value="{{ $staff->f_name }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="input-label" for="l_name">{{ translate('messages.last_name') }}</label>
                            <input type="text" name="l_name" class="form-control" id="l_name"
                                value="{{ $staff->l_name }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="input-label" for="email">{{ translate('messages.email') }}</label>
                            <input type="email" name="email" class="form-control" id="email"
                                value="{{ $staff->email }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="input-label" for="phone">{{ translate('messages.phone') }}</label>
                            <input type="tel" name="phone" class="form-control" id="phone"
                                value="{{ $staff->phone }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="input-label" for="password">{{ translate('messages.password') }}
                                <small class="text-muted">({{ translate('messages.leave_empty_to_keep_current') }})</small>
                            </label>
                            <input type="password" name="password" class="form-control" id="password"
                                placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="btn--container justify-content-end">
                            <a href="{{ route('vendor.kitchen-staff.list') }}" class="btn btn--reset">{{ translate('messages.back') }}</a>
                            <button type="submit" class="btn btn--primary">{{ translate('messages.update') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
