<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered">
        <div class="navbar-vertical-container">
            <div class="navbar-brand-wrapper justify-content-between">
                <!-- Logo -->

                @php($store_data = \App\CentralLogics\Helpers::get_store_data())
                <a class="navbar-brand" href="{{ route('vendor.dashboard') }}" aria-label="Front">
                    <img class="navbar-brand-logo initial--36  onerror-image"
                        data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                        src="{{ $store_data->logo_full_url }}" alt="Logo">
                    <img class="navbar-brand-logo-mini initial--36 onerror-image"
                        data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                        src="{{ $store_data->logo_full_url }}" alt="Logo">
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
            <div class="navbar-vertical-content text-capitalize bg--005555" id="navbar-vertical-content">
                <form class="sidebar--search-form">
                    <div class="search--form-group">
                        {{-- <button type="button" class="btn"><i class="tio-search"></i></button>
                        <input type="text" class="form-control form--control"
                            placeholder="{{ translate('messages.Search Menu...') }}" id="search-sidebar-menu"> --}}
                    </div>
                </form>
                <ul class="navbar-nav navbar-nav-lg nav-tabs">
                    <!-- Dashboards -->
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('vendor/dashboard') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('vendor.dashboard') }}"
                            title="{{ translate('messages.dashboard') }}">
                            <i class="tio-home-vs-1-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.dashboard') }}
                            </span>
                        </a>
                    </li>
                    <!-- Menu Orders -->
                    {{-- <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('messages.order_management') }}">{{ translate('messages.order_management') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li> --}}
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('vendor/menu-order*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{ route('vendor.menu-order.list', 'all') }}"
                            title="{{ translate('messages.menu_orders') }}">
                            <i class="tio-checkmark-circle-outlined nav-icon"></i>
                            {{-- <i class="tio-fastfood nav-icon"></i> --}}
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.orders') }}
                                @php($pendingMenuOrders = \App\Models\MenuOrder::where('store_id', \App\CentralLogics\Helpers::get_store_id())->where('status', 'pending')->count())
                                @if($pendingMenuOrders > 0)
                                    <span class="badge badge-danger ml-1">{{ $pendingMenuOrders }}</span>
                                @endif
                            </span>
                        </a>
                    </li>
                    <!-- End Menu Orders -->
                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('messages.item_section') }}">{{ translate('messages.product_management') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    <!-- Menu -->
                    @if (\App\CentralLogics\Helpers::employee_module_permission_check('item'))
                        <li
                            class="navbar-vertical-aside-has-menu  {{ Request::is('vendor/item/list') || Request::is('vendor/item/add-new') || Request::is('vendor/item/edit*') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('vendor.item.list') }}"
                                title="{{ translate('messages.items') }}">
                                <i class="tio-fastfood nav-icon"></i>
                                <span class="text-truncate">{{ translate('messages.items') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (\App\CentralLogics\Helpers::employee_module_permission_check('category'))
                        
                        <li
                            class="navbar-vertical-aside-has-menu  {{ request()->input('position') == 0 && Request::is('vendor/menu') || Request::is('vendor/menu/edit*') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('vendor.category.index') }}"
                                title="{{ translate('messages.menu') }}">
                                <i class="tio-category nav-icon"></i>
                                <span class="text-truncate">{{ translate('messages.menu') }}</span>
                            </a>
                        </li>
                        {{-- <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('vendor/menu/sub-menu-list') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('vendor.category.sub-index') }}"
                                title="{{ translate('messages.sub_menu') }}">
                                <i class="tio-label nav-icon"></i>
                                <span class="text-truncate">{{ translate('messages.sub_menu') }}</span>
                            </a>
                        </li> --}}
                    @endif
                    <!-- End Menu -->

                    @if (\App\CentralLogics\Helpers::employee_module_permission_check('banner'))
                        <li
                            class="navbar-vertical-aside-has-menu  {{ request()->input('position') == 0 && Request::is('vendor/banner/list') || Request::is('vendor/banner/edit*') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('vendor.banner.list') }}"
                                title="{{ translate('messages.banner') }}">
                                <i class="tio-image nav-icon"></i>
                                <span class="text-truncate">{{ translate('messages.banner') }}</span>
                            </a>
                        </li>
                    @endif

                    <li class="navbar-vertical-aside-has-menu {{ Request::is('vendor/today-special*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{ route('vendor.today-special.index') }}"
                            title="{{ translate('messages.today_special') }}">
                            <i class="tio-star nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.today_special') }}
                            </span>
                        </a>
                    </li>

                    @if (\App\CentralLogics\Helpers::employee_module_permission_check('item'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('vendor/item/bulk-import*') || Request::is('vendor/item/bulk-export*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.Import/Export') }}">
                                <i class="tio-upload nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.Import/Export') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('vendor/item/bulk-import*') || Request::is('vendor/item/bulk-export*') ? 'block' : 'none' }}">
                                <li
                                    class="nav-item {{ Request::is('vendor/item/bulk-import') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('vendor.item.bulk-import') }}"
                                        title="{{ translate('messages.item_import') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.item_import') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ Request::is('vendor/item/bulk-export') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('vendor.item.bulk-export-index') }}"
                                        title="{{ translate('messages.item_export') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.item_export') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    <!-- Business Settings (collapsible) -->
                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('messages.business_section') }}">{{ translate('messages.business_section') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('vendor/business-settings/*') || Request::is('vendor/store/*') || Request::is('vendor/subscription/*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                            title="{{ translate('messages.business_settings') }}">
                            <i class="tio-settings nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.business_settings') }}
                            </span>
                        </a>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                            style="display: {{ Request::is('vendor/business-settings/*') || Request::is('vendor/store/*') || Request::is('vendor/subscription/*') ? 'block' : 'none' }}">
                            @if (\App\CentralLogics\Helpers::employee_module_permission_check('qr-manage'))
                                <li class="nav-item {{ Request::is('vendor/business-settings/menu-template') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('vendor.business-settings.menu-template') }}"
                                        title="{{ translate('messages.menu_template') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.menu_template') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (\App\CentralLogics\Helpers::employee_module_permission_check('qr-manage'))
                                <li class="nav-item {{ Request::is('vendor/business-settings/qr-setup') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('vendor.business-settings.qr-setup') }}"
                                        title="{{ translate('messages.QR_Management') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.QR_Management') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (\App\CentralLogics\Helpers::employee_module_permission_check('store_setup'))
                                <li class="nav-item {{ Request::is('vendor/business-settings/menu-template-csutomize') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('vendor.business-settings.menu-template-customize') }}"
                                        title="{{ translate('messages.storeConfig') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.storeConfig') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (\App\CentralLogics\Helpers::employee_module_permission_check('my_shop'))
                                <li class="nav-item {{ Request::is('vendor/store/*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('vendor.shop.view') }}"
                                        title="{{ translate('messages.my_shop') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.my_shop') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (\App\CentralLogics\Helpers::employee_module_permission_check('business_plan'))
                                <li class="nav-item {{ Request::is('vendor/subscription/*') ? 'active' : '' }} @yield('subscriberList')">
                                    <a class="nav-link" href="{{ route('vendor.subscriptionackage.subscriberDetail') }}"
                                        title="{{ translate('messages.My_Business_Plan') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.My_Business_Plan') }}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('vendor/customers*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{ route('vendor.customers.index') }}"
                            title="{{ translate('messages.csutomers') }}">
                            <i class="tio-user nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.customers') }}
                            </span>
                        </a>
                    </li>
                    <!-- Employee-->
                    @if (
                        \App\CentralLogics\Helpers::employee_module_permission_check('role') ||
                            \App\CentralLogics\Helpers::employee_module_permission_check('employee'))
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                title="{{ translate('messages.employee_section') }}">{{ translate('messages.employee_section') }}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                    @endif

                    @if (\App\CentralLogics\Helpers::employee_module_permission_check('role'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('vendor/custom-role*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link" href=""
                                title="{{ translate('messages.employee_Role') }}">
                                <i class="tio-incognito nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.employee_Role') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (\App\CentralLogics\Helpers::employee_module_permission_check('employee'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('vendor/employee*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.employees') }}">
                                <i class="tio-user nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.employees') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('vendor/employee*') ? 'block' : 'none' }}">
                                <li
                                    class="nav-item {{ Request::is('vendor/employee/add-new') ? 'active' : '' }}">
                                    <a class="nav-link " href=""
                                        title="{{ translate('messages.add_new_Employee') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.add_new') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('vendor/employee/list') ? 'active' : '' }}">
                                    <a class="nav-link " href=""
                                        title="{{ translate('messages.Employee_list') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.list') }}</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    @endif
                    <!-- End Employee -->
                    <!-- Kitchen Staff -->
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('vendor/kitchen-staff*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{ route('vendor.kitchen-staff.list') }}"
                            title="{{ translate('messages.kitchen_staff') }}">
                            <i class="tio-restaurant nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.kitchen_staff') }}
                            </span>
                        </a>
                    </li>
                    <!-- End Kitchen Staff -->
                    <!-- Career Jobs -->
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('vendor/career-jobs*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{ route('vendor.career-jobs.list') }}"
                            title="{{ translate('messages.career_jobs') }}">
                            <i class="tio-briefcase nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.career_jobs') }}
                            </span>
                        </a>
                    </li>
                    <!-- End Career Jobs -->
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
    </script>
@endpush
