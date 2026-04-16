<div class="js-nav-scroller hs-nav-scroller-horizontal mb-4">
    <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
        <li class="nav-item">
            <a href="{{ route('vendor.business-settings.menu-template-customize') }}"
                class="nav-link {{ Request::is('vendor/business-settings/menu-template-csutomize') ? 'active' : '' }}">{{ translate('menu_template_customization') }}</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('vendor.business-settings.banner-popup') }}" class="nav-link {{ Request::is('vendor/business-settings/banner-popup') ? 'active' : '' }}">{{ translate('banner_popup_setup') }} </a>
        </li>
        
        {{-- <li class="nav-item">
                    <a href="" class="nav-link">{{ translate('Subscription_Refunds') }}</a>
                </li> --}}
    </ul>
</div>
