@extends('layouts.admin.app')

@section('title', translate('messages.Google Analytics Setup'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/captcha.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('messages.google_analytics_setup')}}
                </span>
            </h1>
            @include('admin-views.business-settings.partials.third-party-links')
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-header">
                <h4 class="m-0">
                    {{translate('Google Analytics Information')}}
                </h4>
            </div>
            <div class="card-body">
                @php($config=\App\CentralLogics\Helpers::get_business_settings('analytics'))
                <form action="{{route('admin.settings.third-party.analytics_update',['analytics'])}}" method="post">
                    @csrf
                    <label class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control mb-4">
                        <span class="pr-1 d-flex align-items-center switch--label">
                            <span class="line--limit-1">
                                @if (isset($config) && $config['status'] == 1)
                                {{translate('Google Analytics Status Turn OFF')}}
                                @else
                                {{translate('Google Analytics Status Turn ON')}}
                                @endif
                            </span>
                        </span>
                        <input type="checkbox"
                                data-id="recaptcha_status"
                                data-type="toggle"
                                data-image-on="{{ asset('/public/assets/admin/img/modal/important-recapcha.png') }}"
                                data-image-off="{{ asset('/public/assets/admin/img/modal/warning-recapcha.png') }}"
                                data-title-on="{{ translate('Important!') }}"
                                data-title-off="{{ translate('Warning!') }}"
                                data-text-on="<p>{{ translate('Want to enabled Google Analytics.') }}</p>"
                                data-text-off="<p>{{ translate('Want to Disabling Google Analytics.') }}</p>"
                                class="status toggle-switch-input dynamic-checkbox-toggle"
                                name="status" id="recaptcha_status" value="1" {{isset($config) && $config['status'] == 1 ? 'checked':''}}>
                        <span class="toggle-switch-label text p-0">
                            <span class="toggle-switch-indicator"></span>
                        </span>
                    </label>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="measurement_key" class="form-label">{{translate('messages.Measurement Key')}}</label><br>
                                <input id="measurement_key" type="text" class="form-control" name="measurement_key"
                                        value="{{$config['measurement_key']??""}}">
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end">
                        <button type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary call-demo">{{translate('messages.save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
