<div class="d-flex flex-wrap justify-content-between align-items-center mb-5 mt-4 __gap-12px">
    <div class="js-nav-scroller hs-nav-scroller-horizontal mt-2">
        <!-- Nav -->
        <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/email/admin-template/store-registration') ? 'active' : '' }}"
                href="{{ route('admin.settings.email.admin_template', ['store-registration']) }}">
                    {{translate('New Store Registration')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/email/admin-template/contact-us') ? 'active' : '' }}"
                href="{{ route('admin.settings.email.admin_template', ['contact-us']) }}">
                    {{translate('Contact Us')}}
                </a>
            </li>
        </ul>
        <!-- End Nav -->
    </div>
</div>
