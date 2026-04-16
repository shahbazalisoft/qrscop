@extends('layouts.admin.app')

@section('title', translate('messages.settings'))

@push('css_or_js')
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center py-2">
                <div class="col-sm mb-2 mb-sm-0">
                    <div class="d-flex align-items-center">
                        <img class="onerror-image"
                            data-onerror-image="{{ asset('/public/assets/admin/new-img\setting-icon.svg') }}"
                            src="{{ asset('/public/assets/admin/img/new-img\setting-icon.svg') }}" width="38"
                            alt="img">
                        <div class="w-0 flex-grow pl-2">
                            <h1 class="page-header-title mb-0">{{ translate('messages.settings') }}.</h1>
                            <p class="page-header-text m-0">
                                {{ translate('Hello, Here You Can Manage Your QR Scanner, After activate QR we will provide you activated QR Scanner') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="col-lg-12">
            <h4 class="card-title mb-3">
                <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>
                <span>General Settings</span>
            </h4>
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4 col-sm-8">
                            <a href="{{ route('admin.settings.general.edit') }}">
                                <div class="__customer-statistics-card h-100" style="--clr:#006AB4">
                                    <div class="title">
                                        <img src="{{ asset('/public/assets/admin/img/new-img/deliveryman/newly.svg') }}"
                                            alt="new-img">
                                        <h4 style="font-size: 18px;">{{ translate('messages.general_setting') }}</h4>
                                    </div>
                                    <p class="page-header-text m-0">Here You Can Manage 3rd party Setting</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-8">
                            <a href="{{route('admin.settings.third-party.payment-method')}}">
                                <div class="__customer-statistics-card h-100" style="--clr:#006AB4">
                                    <div class="title">
                                        <img src="{{ asset('/public/assets/admin/img/new-img/deliveryman/newly.svg') }}"
                                            alt="new-img">
                                        <h4 style="font-size: 18px;">{{ translate('messages.3rd_party') }}</h4>
                                    </div>
                                    <p class="page-header-text m-0">Here You Can Manage 3rd party Setting</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-8">
                            <a href="{{ route('admin.settings.general.edit') }}">
                                <div class="__customer-statistics-card h-100" style="--clr:#006AB4">
                                    <div class="title">
                                        <img src="{{ asset('/public/assets/admin/img/new-img/deliveryman/newly.svg') }}"
                                            alt="new-img">
                                        <h4 style="font-size: 18px;">{{ translate('messages.general_setting') }}</h4>
                                    </div>
                                    <p class="page-header-text m-0">Here You Can Manage 3rd party Setting</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mt-3">
            <h4 class="card-title mb-3">
                <span class="card-header-icon mr-2"><i class="tio-color-bucket"></i></span>
                <span>Website Appearance</span>
            </h4>
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4 col-sm-8">
                            <a href="{{ route('admin.settings.general.website-appearance') }}">
                                <div class="__customer-statistics-card h-100" style="--clr:#10847E">
                                    <div class="title">
                                        <img src="{{ asset('/public/assets/admin/img/new-img/deliveryman/newly.svg') }}"
                                            alt="new-img">
                                        <h4 style="font-size: 18px;">{{ translate('Website Colors') }}</h4>
                                    </div>
                                    <p class="page-header-text m-0">Customize your website homepage colors, backgrounds, and text colors</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-8">
                            <a href="{{ route('admin.settings.general.clear_cache') }}">
                                <div class="__customer-statistics-card h-100" style="--clr:#10847E">
                                    <div class="title">
                                        <img src="{{ asset('/public/assets/admin/img/new-img/deliveryman/newly.svg') }}"
                                            alt="new-img">
                                        <h4 style="font-size: 18px;">{{ translate('cache_clear') }}</h4>
                                    </div>
                                    <p class="page-header-text m-0">Clear all cache</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <h4 class="card-title mb-3">
                <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>
                <span>Email Settings</span>
            </h4>
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4 col-sm-8">
                            <a href="{{ route('admin.settings.email.index') }}">
                                <div class="__customer-statistics-card h-100" style="--clr:#006AB4">
                                    <div class="title">
                                        <img src="{{ asset('/public/assets/admin/img/new-img/deliveryman/newly.svg') }}"
                                            alt="new-img">
                                        <h4 style="font-size: 18px;">{{ translate('messages.SMTP_Setting') }}</h4>
                                    </div>
                                    <p class="page-header-text m-0">Here You Can Manage Email SMTP config Setting</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-8">
                            <a href="{{ route('admin.settings.email.admin_template', ['store-registration']) }}">
                                <div class="__customer-statistics-card h-100" style="--clr:#006AB4">
                                    <div class="title">
                                        <img src="{{ asset('/public/assets/admin/img/new-img/deliveryman/newly.svg') }}"
                                            alt="new-img">
                                        <h4 style="font-size: 18px;">{{ translate('messages.admin_template') }}</h4>
                                    </div>
                                    <p class="page-header-text m-0">Here You Can Manage Email SMTP config Setting</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-8">
                            <a href="{{ route('admin.settings.email.vendor_template', ['forgot-password']) }}">
                                <div class="__customer-statistics-card h-100" style="--clr:#006AB4">
                                    <div class="title">
                                        <img src="{{ asset('/public/assets/admin/img/new-img/deliveryman/newly.svg') }}"
                                            alt="new-img">
                                        <h4 style="font-size: 18px;">{{ translate('messages.vendor_template') }}</h4>
                                    </div>
                                    <p class="page-header-text m-0">Here You Can Manage Email SMTP config Setting</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('script_2')
@endpush
