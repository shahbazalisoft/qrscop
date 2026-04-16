<div class="d-flex flex-wrap justify-content-between align-items-center mb-5 mt-4 __gap-12px">
    <div class="js-nav-scroller hs-nav-scroller-horizontal mt-2">
        <!-- Nav -->
        <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/email/vendor-template/forgot-password') ? 'active' : '' }}"
                href="{{ route('admin.settings.email.vendor_template', ['forgot-password']) }}">
                    {{translate('Forgot Password')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/email/vendor-template/store-registration') ? 'active' : '' }}"
                href="{{ route('admin.settings.email.vendor_template', ['store-registration']) }}">
                    {{translate('New Restaurant Registration')}}
                </a>
            </li>
            @if (\App\CentralLogics\Helpers::subscription_check())
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/email/vendor-template/subscription-successful') ? 'active' : '' }}"
                href="{{ route('admin.settings.email.vendor_template', ['subscription-successful']) }}">
                    {{translate('Subscription_Successful')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/email/vendor-template/subscription-renew') ? 'active' : '' }}"
                href="{{ route('admin.settings.email.vendor_template', ['subscription-renew']) }}">
                    {{translate('Subscription_Renew')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/email/vendor-template/subscription-shift') ? 'active' : '' }}"
                href="{{ route('admin.settings.email.vendor_template', ['subscription-shift']) }}">
                    {{translate('Subscription_Shift')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/email/vendor-template/subscription-cancel') ? 'active' : '' }}"
                href="{{ route('admin.settings.email.vendor_template', ['subscription-cancel']) }}">
                    {{translate('Subscription_Cancel')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/email/vendor-template/subscription-deadline') ? 'active' : '' }}"
                href="{{ route('admin.settings.email.vendor_template', ['subscription-deadline']) }}">
                    {{translate('Subscription_Deadline_Warning')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/email/vendor-template/subscription-plan_upadte') ? 'active' : '' }}"
                href="{{ route('admin.settings.email.vendor_template', ['subscription-plan_upadte']) }}">
                    {{translate('Subscription_Plan_Update')}}
                </a>
            </li>
            @endif
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/email/vendor-template/contact-us') ? 'active' : '' }}"
                href="{{ route('admin.settings.email.vendor_template', ['contact-us']) }}">
                    {{translate('Contact Us')}}
                </a>
            </li>
        </ul>
        <!-- End Nav -->
    </div>
</div>
