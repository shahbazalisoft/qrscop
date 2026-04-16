<div id="headerMain" class="d-none">
    <header id="header"
            class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-flush navbar-container navbar-bordered">
        <div class="navbar-nav-wrap">

            <div class="navbar-nav-wrap-content-left d-xl-none">
                <!-- Navbar Vertical Toggle -->
                <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mr-3">
                    <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                       data-placement="right" title="Collapse"></i>
                    <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                       data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                       data-toggle="tooltip" data-placement="right" title="Expand"></i>
                </button>
                <!-- End Navbar Vertical Toggle -->
            </div>

            <!-- Secondary Content -->
            <div class="navbar-nav-wrap-content-right flex-grow-1 w-0">
                <!-- Navbar -->
                <ul class="navbar-nav align-items-center flex-row flex-grow-1 __navbar-nav">


                    {{-- Search button hidden
                    <li class="nav-item max-sm-m-0 w-xxl-200px ml-auto flex-grow-0">
                        <button type="button" id="modalOpener" class="title-color bg--secondary border-0 rounded justify-content-between w-100 align-items-center py-2 px-2 px-md-3 d-flex gap-1" data-toggle="modal" data-target="#staticBackdrop">
                            <div class="align-items-center d-flex flex-grow-1 gap-1 justify-content-between">
                                <span class="align-items-center d-none d-xxl-flex gap-2 text-muted">{{translate('Search_or')}}

                                    <span class="bg-E7E6E8 border ctrlplusk d-md-block d-none font-bold fs-12 fw-bold lh-1 ms-1 px-1 rounded text-muted">Ctrl+K</span>

                                </span>
                                <img width="14" class="h-auto" src="{{asset('/public/assets/admin/img/new-img/search.svg')}}" class="svg" alt="">
                            </div>
                        </button>
                    </li>
                    --}}
                    
                    <li class="nav-item ml-auto">
                        <!-- Notifications -->
                        <div class="hs-unfold mr-3">
                            <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle position-relative"
                               href="javascript:;"
                               id="notificationDropdownInvoker"
                               data-hs-unfold-options='{
                                     "target": "#notificationDropdown",
                                     "type": "css-animation"
                                   }'>
                                <i class="tio-notifications-on-outlined"></i>
                                <span class="notification-badge" style="display:none; position:absolute; top:-2px; right:-2px; background:#ed4c78; color:#fff; font-size:10px; font-weight:700; min-width:18px; height:18px; line-height:18px; text-align:center; border-radius:50%; padding:0 4px;"></span>
                            </a>

                            <div id="notificationDropdown"
                                 class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu py-0"
                                 style="width: 24rem; max-height: 28rem;">
                                <div class="card-header d-flex align-items-center justify-content-between py-2 px-3">
                                    <h6 class="m-0">{{translate('Notifications')}}</h6>
                                    <a href="javascript:;" class="text-primary fs-12 mark-all-read-btn" style="display:none;">{{translate('Mark all as read')}}</a>
                                </div>
                                <div class="card-body p-0 notification-list-container" style="max-height: 20rem; overflow-y: auto;">
                                    <div class="text-center text-muted py-4 no-notification-msg">
                                        {{translate('No new notifications')}}
                                    </div>
                                </div>
                                <div class="card-footer text-center py-2">
                                    <a href="{{route('admin.notifications.index')}}" class="text-primary fs-12">{{translate('View all notifications')}}</a>
                                </div>
                            </div>
                        </div>
                        <!-- End Notifications -->
                    </li>
                    <li class="nav-item">
                        <!-- Account -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker navbar-dropdown-account-wrapper" href="javascript:;"
                               data-hs-unfold-options='{
                                     "target": "#accountNavbarDropdown",
                                     "type": "css-animation"
                                   }'>
                                <div class="cmn--media right-dropdown-icon d-flex align-items-center">
                                    <div class="media-body pl-0 pr-2">
                                        <span class="card-title h5 text-right">
                                            {{auth('admin')->user()->full_name}}
                                        </span>
                                        <span class="card-text">{{Str::limit(auth('admin')->user()->email, 15, '...'); }}</span>
                                    </div>
                                    <div class="avatar avatar-sm avatar-circle">
                                        <img class="avatar-img  onerror-image aspect-1-1"  data-onerror-image="{{asset('public/assets/admin/img/160x160/img1.jpg')}}"
                                        src="{{auth('admin')->user()?->toArray()['image_full_url']}}"
                                            alt="Image Description">
                                        <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                    </div>
                                </div>
                            </a>

                            <div id="accountNavbarDropdown"
                                 class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account min--240">
                                <div class="dropdown-item-text">
                                    <div class="media align-items-center">
                                        <div class="avatar avatar-sm avatar-circle mr-2">
                                            <img class="avatar-img  onerror-image aspect-1-1 "  data-onerror-image="{{asset('public/assets/admin/img/160x160/img1.jpg')}}"
                                            src="{{auth('admin')->user()?->toArray()['image_full_url']}}"
                                                 alt="Owner image">
                                        </div>
                                        <div class="media-body">
                                            <span class="card-title h5">{{auth('admin')->user()->full_name}}</span>
                                            <span class="card-text">{{Str::limit(auth('admin')->user()->email, 15, '...'); }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{route('admin.profile')}}">
                                    <span class="text-truncate pr-2" title="Settings">{{translate('messages.settings')}}</span>
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item log-out" >
                                    <span class="text-truncate pr-2 log-out" title="Sign out">{{translate('messages.sign_out')}}</span>
                                </a>
                            </div>
                        </div>
                        <!-- End Account -->
                    </li>
                </ul>
                <!-- End Navbar -->
            </div>
            <!-- End Secondary Content -->
        </div>
    </header>
</div>
<div id="headerFluid" class="d-none"></div>
<div id="headerDouble" class="d-none"></div>

<div class="modal fade removeSlideDown" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered max-w-520">
        <div class="modal-content modal-content__search border-0">
            <div class="d-flex flex-column gap-3 rounded-20 bg-card py-2 px-3">
                <div class="d-flex gap-2 align-items-center position-relative">
                    <form class="flex-grow-1" id="searchForm" action="">
                        @csrf
                        <div class="d-flex align-items-center global-search-container">
                            <input  autocomplete="off" class="form-control flex-grow-1 rounded-10 search-input" id="searchInput" maxlength="255" name="search" type="search" placeholder="{{ translate('Search_by_keyword') }}" aria-label="Search" autofocus>
                        </div>
                    </form>
                    <div class="position-absolute right-0 pr-2">
                        <button class="border-0 rounded px-2 py-1" type="button" data-dismiss="modal">{{ translate('Esc') }}</button>
                    </div>
                </div>
                <div class="min-h-350">
                    <div class="search-result" id="searchResults">
                        <div class="text-center text-muted py-5">{{translate('It appears that you have not yet searched.')}}.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



{{-- <div class="toggle-tour">
    <button type="button" class="tour-guide_btn w-40px h-40px border-0 bg-white d-flex align-items-center justify-content-center ">
        <span class="w-32 h-32px  min-w-32 d-flex align-items-center justify-content-center  bg-primary rounded-8"><img src="{{ asset('public/assets/admin/img/solar_multiple-forward-right-line-duotone.svg') }}" alt=""></span>
    </button>
    <div class="d-flex flex-column">

    @if (Request::is('taxvat*'))
        <div class="tour-guide-items offcanvas-trigger text-capitalize fs-14 text-title cursor-pointer" data-target="#global_guideline_offcanvas">{{ translate('Guideline') }}</div>
    @endif

        <div class="tour-guide-items">
            <a href="https://youtube.com/playlist?list=PLLFMbDpKMZBxgtX3n3rKJvO5tlU8-ae2Y" target="_blank"
               class="d-flex align-items-center gap-10px">
                <span class="text-capitalize fs-14 text-title">{{ translate('Turotial') }}</span>
            </a>
        </div>
        <div class="tour-guide-items d-flex cursor-pointer align-items-center gap-10px restart-Tour">
            <span class="text-capitalize fs-14 text-title">{{ translate('Tour') }}</span>
        </div>

    </div>
</div> --}}

@push('script_2')
<script>
    function timeAgoStr(dateStr) {
        var now = new Date();
        var date = new Date(dateStr);
        var seconds = Math.floor((now - date) / 1000);
        if (seconds < 60) return 'Just now';
        var minutes = Math.floor(seconds / 60);
        if (minutes < 60) return minutes + 'm ago';
        var hours = Math.floor(minutes / 60);
        if (hours < 24) return hours + 'h ago';
        var days = Math.floor(hours / 24);
        if (days < 30) return days + 'd ago';
        return Math.floor(days / 30) + 'mo ago';
    }

    function loadNotifications() {
        $.get("{{ route('admin.notifications.get') }}", function(data) {
            var container = $('.notification-list-container');
            var badge = $('.notification-badge');
            var markAllBtn = $('.mark-all-read-btn');
            var noMsg = $('.no-notification-msg');

            if (data.unread_count > 0) {
                badge.text(data.unread_count > 99 ? '99+' : data.unread_count).show();
                markAllBtn.show();
                noMsg.hide();
                container.html('');
                $.each(data.notifications, function(i, n) {
                    var timeAgo = timeAgoStr(n.created_at);
                    var icon = 'tio-store';
                    if (n.type === 'subscription_created' || n.type === 'subscription_updated') icon = 'tio-diamond';
                    if (n.type === 'contact_message') icon = 'tio-email';
                    var item = '<a href="javascript:;" class="dropdown-item notification-item" data-id="' + n.id + '" data-link="' + (n.link || '') + '">' +
                        '<div class="d-flex align-items-start">' +
                            '<div class="mr-2 mt-1"><i class="' + icon + '" style="font-size:1.25rem;"></i></div>' +
                            '<div class="flex-grow-1">' +
                                '<h6 class="mb-0 fs-13">' + n.title + '</h6>' +
                                (n.description ? '<p class="mb-0 fs-12 text-muted text-wrap">' + n.description + '</p>' : '') +
                                '<small class="text-muted">' + timeAgo + '</small>' +
                            '</div>' +
                        '</div>' +
                    '</a>';
                    container.append(item);
                });
            } else {
                badge.hide();
                markAllBtn.hide();
                container.html('<div class="text-center text-muted py-4 no-notification-msg">{{ translate("No new notifications") }}</div>');
            }
        });
    }

    $(document).ready(function() {
        loadNotifications();
        setInterval(loadNotifications, 30000);

        $(document).on('click', '.notification-item', function() {
            var id = $(this).data('id');
            var link = $(this).data('link');
            $.post("{{ url('admin/notifications/mark-read') }}/" + id, {_token: '{{ csrf_token() }}'}, function() {
                loadNotifications();
                if (link) window.location.href = link;
            });
        });

        $(document).on('click', '.mark-all-read-btn', function() {
            $.post("{{ route('admin.notifications.mark-all-read') }}", {_token: '{{ csrf_token() }}'}, function() {
                loadNotifications();
            });
        });
    });
</script>
@endpush



