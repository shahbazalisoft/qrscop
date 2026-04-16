<div class="d-flex flex-wrap justify-content-between align-items-center mb-5 mt-4 __gap-12px">
    <div class="js-nav-scroller hs-nav-scroller-horizontal mt-2">
        <!-- Nav -->
        <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
            <li class="nav-item">
                <a class="nav-link  {{ Request::is('admin/business-settings/business-setup') ?'active':'' }}" href="{{ route('admin.settings.general.update') }}"   aria-disabled="true">{{translate('messages.business_information')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/language') ?'active':'' }}" href="{{route('admin.business-settings.language.index')}}"  aria-disabled="true">{{translate('messages.Languages')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/general/website-appearance') ?'active':'' }}" href="{{route('admin.settings.general.website-appearance')}}"  aria-disabled="true">{{ translate('Website Appearance') }}</a>
            </li>
        </ul>
        <!-- End Nav -->
    </div>
</div>
