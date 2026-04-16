<div class="d-flex flex-wrap justify-content-between align-items-center mb-5 mt-4 __gap-12px">
    <div class="js-nav-scroller hs-nav-scroller-horizontal mt-2">
        <!-- Nav -->
        <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
            <li class="nav-item">
                <a class="nav-link   {{ Request::is('admin/settings/third-party/payment-method') ? 'active' : '' }}" href="{{route('admin.settings.third-party.payment-method')}}"   aria-disabled="true">{{translate('Payment Methods')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/third-party/sms-module') ? 'active' : '' }}" href="{{ route('admin.settings.third-party.sms_module') }}"  aria-disabled="true">{{translate('SMS Module')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{Request::is('admin/settings/third-party/social-login')?'active':''}}" href="{{route('admin.settings.third-party.social_login_index')}}"  aria-disabled="true">{{translate('Social Logins')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/third-party/recaptcha*') ? 'active' : '' }}" href="{{route('admin.settings.third-party.recaptcha_index')}}"  aria-disabled="true">{{translate('Recaptcha')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/third-party/analytics*') ? 'active' : '' }}" href="{{route('admin.settings.third-party.analytics_index')}}"  aria-disabled="true">{{translate('Google Analytics')}}</a>
            </li>
        </ul>
        <!-- End Nav -->
    </div>
</div>
