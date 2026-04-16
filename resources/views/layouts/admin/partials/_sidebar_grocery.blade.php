<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar-brand-wrapper justify-content-between">
                <!-- Logo -->
                @php($store_logo = \App\Models\BusinessSetting::where(['key' => 'logo'])->first())
                <a class="navbar-brand" href="{{ route('admin.dashboard') }}" aria-label="Front">
                    <img class="navbar-brand-logo initial--36 onerror-image onerror-image"
                        data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                        src="{{ \App\CentralLogics\Helpers::get_full_url('business', $store_logo?->value ?? '', $store_logo?->storage[0]?->value ?? 'public', 'favicon') }}"
                        alt="Logo">
                    <img class="navbar-brand-logo-mini initial--36 onerror-image onerror-image"
                        data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                        src="{{ \App\CentralLogics\Helpers::get_full_url('business', $store_logo?->value ?? '', $store_logo?->storage[0]?->value ?? 'public', 'favicon') }}"
                        alt="Logo">
                </a>
                <!-- End Logo -->

                <!-- Navbar Vertical Toggle -->
                <button type="button"
                    class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                    <i class="tio-clear tio-lg"></i>
                </button>
                <!-- End Navbar Vertical Toggle -->

                <div class="navbar-nav-wrap-content-left">
                    <!-- Navbar Vertical Toggle -->
                    <button type="button" class="js-navbar-vertical-aside-toggle-invoker close">
                        <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                            data-placement="right" title="Collapse"></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                            data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->
                </div>

            </div>

            <!-- Content -->
            <div class="navbar-vertical-content bg--005555" id="navbar-vertical-content">
                <form autocomplete="off" class="sidebar--search-form">
                    <div class="search--form-group">
                        <button type="button" class="btn"><i class="tio-search"></i></button>
                        <input autocomplete="false" name="qq" type="text" class="form-control form--control"
                            placeholder="{{ translate('Search Menu...') }}" id="search">

                        <div id="search-suggestions" class="flex-wrap mt-1"></div>
                    </div>
                </form>
                <ul class="navbar-nav navbar-nav-lg nav-tabs">
                    <!-- Dashboards -->
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('admin') ? 'show active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('admin.dashboard') }}"
                            title="{{ translate('messages.dashboard') }}">
                            <i class="tio-home-vs-1-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.dashboard') }}
                            </span>
                        </a>
                    </li>
                    <!-- End Dashboards -->
                    <!-- Menu Orders -->
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/menu-order*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:" title="{{ translate('messages.menu_orders') }}">
                            <i class="tio-fastfood nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.menu_orders') }}
                            </span>
                        </a>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub" style="display:{{ Request::is('admin/menu-order*') ? 'block' : 'none' }}">
                            <li class="nav-item {{ Request::is('admin/menu-order/list/all') || Request::is('admin/menu-order/list') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.menu-order.list', 'all') }}" title="{{ translate('messages.all') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{ translate('messages.all') }}
                                        <span class="badge badge-soft-info badge-pill ml-1">
                                            {{ \App\Models\MenuOrder::count() }}
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{ Request::is('admin/menu-order/list/pending') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.menu-order.list', 'pending') }}" title="{{ translate('messages.pending') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{ translate('messages.pending') }}
                                        <span class="badge badge-soft-warning badge-pill ml-1">
                                            {{ \App\Models\MenuOrder::where('status', 'pending')->count() }}
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{ Request::is('admin/menu-order/list/confirmed') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.menu-order.list', 'confirmed') }}" title="{{ translate('messages.confirmed') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{ translate('messages.confirmed') }}
                                        <span class="badge badge-soft-info badge-pill ml-1">
                                            {{ \App\Models\MenuOrder::where('status', 'confirmed')->count() }}
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{ Request::is('admin/menu-order/list/preparing') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.menu-order.list', 'preparing') }}" title="{{ translate('messages.preparing') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{ translate('messages.preparing') }}
                                        <span class="badge badge-soft-primary badge-pill ml-1">
                                            {{ \App\Models\MenuOrder::where('status', 'preparing')->count() }}
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{ Request::is('admin/menu-order/list/completed') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.menu-order.list', 'completed') }}" title="{{ translate('messages.completed') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{ translate('messages.completed') }}
                                        <span class="badge badge-soft-success badge-pill ml-1">
                                            {{ \App\Models\MenuOrder::where('status', 'completed')->count() }}
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{ Request::is('admin/menu-order/list/cancelled') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.menu-order.list', 'cancelled') }}" title="{{ translate('messages.cancelled') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{ translate('messages.cancelled') }}
                                        <span class="badge badge-soft-danger badge-pill ml-1">
                                            {{ \App\Models\MenuOrder::where('status', 'cancelled')->count() }}
                                        </span>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- End Menu Orders -->
                    <!-- Marketing section -->
                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('messages.item_section') }}">{{ translate('messages.product_management') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                    @if (\App\CentralLogics\Helpers::module_permission_check('item'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/item*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('Product Setup') }}">
                                <i class="tio-premium-outlined nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate text-capitalize">{{ translate('Product Setup') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display:{{ Request::is('admin/item*') ? 'block' : 'none' }}">
                                <li
                                    class="nav-item {{ Request::is('admin/item/add-new') || (Request::is('admin/item/edit/*') && strpos(request()->fullUrl(), 'product_gellary=1') !== false) ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.item.add-new') }}"
                                        title="{{ translate('messages.add_new') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.add_new') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ Request::is('admin/item/list') || (Request::is('admin/item/edit/*') && (strpos(request()->fullUrl(), 'temp_product=1') == false && strpos(request()->fullUrl(), 'product_gellary=1') == false)) ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.item.list') }}"
                                        title="{{ translate('messages.food_list') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.list') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/setting/gallery') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.gallery.index') }}"
                                        title="{{ translate('messages.gallery') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.gallery') }}</span>
                                    </a>
                                </li>
                                {{-- @endif --}}
                                <li class="nav-item {{ Request::is('admin/item/bulk-import') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.item.bulk-import') }}"
                                        title="{{ translate('messages.bulk_import') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_import') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/item/bulk-export') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.item.bulk-export-index') }}"
                                        title="{{ translate('messages.bulk_export') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_export') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if (\App\CentralLogics\Helpers::module_permission_check('category'))
                        <!-- Category -->
                        @if (\App\CentralLogics\Helpers::module_permission_check('category'))
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/menu*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                    href="javascript:" title="{{ translate('messages.menu') }}">
                                    <i class="tio-category nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.menu') }}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display:{{ Request::is('admin/menu*') ? 'block' : 'none' }}">
                                    <li
                                        class="nav-item @yield('main_category')  {{ Request::is('admin/menu/list') || Request::is('admin/menu/edit*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.category.list') }}"
                                            title="{{ translate('messages.category') }}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{ translate('messages.menu') }}</span>
                                        </a>
                                    </li>
                                    {{-- <li
                                        class="nav-item  @yield('sub_category') {{ request()->input('position') == 1 && Request::is('admin/category/add') ? 'active' : '' }}">
                                        <a class="nav-link " href=""
                                            title="{{ translate('messages.sub_menu') }}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{ translate('messages.sub_menu') }}</span>
                                        </a>
                                    </li> --}}
                                </ul>
                            </li>
                        @endif
                    @endif
                    <!-- Orders -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('restaurant'))
                        <li class="nav-item">
                            <small class="nav-subtitle">{{ translate('messages.restaurant_management') }}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                    @endif
                    <!-- End Orders -->
                    <!-- Restaurant Management -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('restaurant'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/store*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.store.list') }}"
                                title="{{ translate('messages.manage_restaurant') }}">
                                <i class="tio-image nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.restaurant_list') }}</span>
                            </a>
                        </li>
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/menu-templates*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.menu-templates.list') }}"
                                title="{{ translate('messages.menu_template') }}">
                                <i class="tio-image nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.menu_template') }}</span>
                            </a>
                        </li>
                    @endif
                    <!-- End Banner -->
                    <!-- Common Banner -->
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/common-banner*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('admin.common-banner.list') }}" title="{{ translate('messages.common_banner') }}">
                            <i class="tio-image nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.common_banner') }}</span>
                        </a>
                    </li>
                    <!-- End Common Banner -->

                    

                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('messages.item_section') }}">{{ translate('messages.busines_setup') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/settings*') ? 'show active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('admin.settings.index') }}"
                            title="{{ translate('messages.settings') }}">
                            <i class="tio-home-vs-1-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.settings') }}
                            </span>
                        </a>
                    </li>
                    @if (\App\CentralLogics\Helpers::module_permission_check('subscription'))
                        <li class="navbar-vertical-aside-has-menu @yield('subscription')">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" id="tourb-3"
                                href="javascript:" title="{{ translate('messages.subscription_management') }}">
                                <i class="tio-crown nav-icon"></i>
                                <span class="text-truncate">{{ translate('messages.subscription_management') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display:{{ Request::is('admin/business-settings/subscription*') ? 'block' : 'none' }}">
                                <li class="navbar-vertical-aside-has-menu @yield('subscription_index')">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.settings.subscription.subscriptionackage.index') }}"
                                        title="{{ translate('messages.subscription_Package') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{ translate('messages.subscription_Package') }}
                                        </span>
                                    </a>
                                </li>
                                <li class="navbar-vertical-aside-has-menu  @yield('subscriberList')">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.settings.subscription.subscriptionackage.subscriberList') }}"
                                        title="{{ translate('messages.Subscriber_List') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{ translate('messages.Subscriber_List') }}
                                        </span>
                                    </a>
                                </li>
                                <li class="navbar-vertical-aside-has-menu  @yield('subscription_settings')">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.settings.subscription.subscriptionackage.settings') }}"
                                        title="{{ translate('messages.settings') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{ translate('messages.settings') }}
                                        </span>
                                    </a>
                                </li>
                                <li class="navbar-vertical-aside-has-menu @yield('qr_payment_requests')">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.settings.subscription.subscriptionackage.qrPaymentRequests') }}"
                                        title="{{ translate('QR Payment Requests') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{ translate('QR Payments') }}
                                            @php($pending_qr_count = \App\Models\QrPaymentRequest::where('status', 'pending')->count())
                                            @if($pending_qr_count > 0)
                                                <span class="badge badge-danger ml-1">{{ $pending_qr_count }}</span>
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif


                    <!-- Contact Messages -->
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/users/contact*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('admin.users.contact.list') }}"
                            title="{{ translate('messages.Contact Messages') }}">
                            <i class="tio-email nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.Contact Messages') }}
                            </span>
                        </a>
                    </li>
                    <!-- End Contact Messages -->

                    <!-- Career Jobs -->
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/career-jobs*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('admin.career-jobs.list') }}"
                            title="{{ translate('messages.Career Jobs') }}">
                            <i class="tio-briefcase nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.Career Jobs') }}
                            </span>
                        </a>
                    </li>
                    <!-- End Career Jobs -->

                    {{-- @includeIf('layouts.admin.partials._logout_modal') --}}
                </ul>
            </div>
            <!-- End Content -->
        </div>
    </aside>
</div>

<div id="sidebarCompact" class="d-none">

</div>


@push('script_2')
    <script>
        $(window).on('load', function() {
            if ($(".navbar-vertical-content li.active").length) {
                $('.navbar-vertical-content').animate({
                    scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
                }, 10);
            }
        });

        var $rows = $('#navbar-vertical-content li');
        $('#search-sidebar-menu').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });

        $(document).ready(function() {
            const $searchInput = $('#search');
            const $suggestionsList = $('#search-suggestions');
            const $rows = $('#navbar-vertical-content li');
            const $subrows = $('#navbar-vertical-content li ul li');
            {{-- const suggestions = ['{{strtolower(translate('messages.order'))  }}', '{{ strtolower(translate('messages.campaign'))  }}', '{{ strtolower(translate('messages.category')) }}', '{{ strtolower(translate('messages.product')) }}','{{ strtolower(translate('messages.store')) }}' ]; --}}
            const focusInput = () => updateSuggestions($searchInput.val());
            const hideSuggestions = () => $suggestionsList.slideUp(700);
            const showSuggestions = () => $suggestionsList.slideDown(700);
            let clickSuggestion = function() {
                let suggestionText = $(this).text();
                $searchInput.val(suggestionText);
                hideSuggestions();
                filterItems(suggestionText.toLowerCase());
                updateSuggestions(suggestionText);
            };
            let filterItems = (val) => {
                let unmatchedItems = $rows.show().filter((index, element) => !~$(element).text().replace(
                    /\s+/g, ' ').toLowerCase().indexOf(val));
                let matchedItems = $rows.show().filter((index, element) => ~$(element).text().replace(/\s+/g,
                    ' ').toLowerCase().indexOf(val));
                unmatchedItems.hide();
                matchedItems.each(function() {
                    let $submenu = $(this).find($subrows);
                    let keywordCountInRows = 0;
                    $rows.each(function() {
                        let rowText = $(this).text().toLowerCase();
                        let valLower = val.toLowerCase();
                        let keywordCountRow = rowText.split(valLower).length - 1;
                        keywordCountInRows += keywordCountRow;
                    });
                    if ($submenu.length > 0) {
                        $subrows.show();
                        $submenu.each(function() {
                            let $submenu2 = !~$(this).text().replace(/\s+/g, ' ')
                                .toLowerCase().indexOf(val);
                            if ($submenu2 && keywordCountInRows <= 2) {
                                $(this).hide();
                            }
                        });
                    }
                });
            };
            let updateSuggestions = (val) => {
                $suggestionsList.empty();
                suggestions.forEach(suggestion => {
                    if (suggestion.toLowerCase().includes(val.toLowerCase())) {
                        $suggestionsList.append(
                            `<span class="search-suggestion badge badge-soft-light m-1 fs-14">${suggestion}</span>`
                        );
                    }
                });
                // showSuggestions();
            };
            $searchInput.focus(focusInput);
            $searchInput.on('input', function() {
                updateSuggestions($(this).val());
            });
            $suggestionsList.on('click', '.search-suggestion', clickSuggestion);
            $searchInput.keyup(function() {
                filterItems($(this).val().toLowerCase());
            });
            $searchInput.on('focusout', hideSuggestions);
            $searchInput.on('focus', showSuggestions);
        });
    </script>
@endpush
