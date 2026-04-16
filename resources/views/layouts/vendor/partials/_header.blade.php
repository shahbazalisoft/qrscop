<div id="headerMain" class="d-none">
    <header id="header"
            class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-flush navbar-container navbar-bordered">
        <div class="navbar-nav-wrap">
            <div class="navbar-nav-wrap-content-left  d-xl-none">
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
            <div class="navbar-nav-wrap-content-right">
                <!-- Navbar -->
                <ul class="navbar-nav align-items-center flex-row">
                    @php
                        $__store = \App\Models\Store::with('store_sub')->find(\App\CentralLogics\Helpers::get_store_id());
                        $__daysLeft = 0;
                        if ($__store && $__store->store_sub && $__store->store_sub->expiry_date) {
                            $__daysLeft = max(0, (int) \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($__store->store_sub->expiry_date), false));
                        }
                    @endphp
                    @if($__daysLeft <= 20)
                    <li class="nav-item ml-auto mr-2 flex-grow-0">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-none d-md-flex align-items-center gap-2 px-3 py-1 rounded" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                                <span class="vendor-blink-dot"></span>
                                @if($__daysLeft > 0)
                                <span style="color: #d1d5db; font-size: 12px; white-space: nowrap;">
                                    <strong style="color: {{ $__daysLeft <= 5 ? '#ef4444' : '#f59e0b' }};">{{ $__daysLeft }}</strong> {{ translate('days remaining') }}
                                </span>
                                @else
                                <span style="color: #d1d5db; font-size: 12px; white-space: nowrap;">
                                    <strong style="color: {{ $__daysLeft <= 5 ? '#ef4444' : '#f59e0b' }};">{{ translate('Expired') }}</strong> 
                                </span>
                                @endif
                            </div>
                            <style>
                                .vendor-blink-dot {
                                    width: 8px;
                                    height: 8px;
                                    min-width: 8px;
                                    border-radius: 50%;
                                    background: {{ $__daysLeft <= 5 ? '#ef4444' : '#f59e0b' }};
                                    display: inline-block;
                                    animation: vendorBlink 1.2s ease-in-out infinite;
                                }
                                @keyframes vendorBlink {
                                    0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(245,158,11,0.6); }
                                    50% { opacity: 0.3; box-shadow: 0 0 8px 2px rgba(245,158,11,0.4); }
                                }
                            </style>
                            <a href="{{ route('vendor.subscriptionackage.subscriberDetail') }}" class="btn btn-sm text-white px-3" style="background: linear-gradient(135deg, #f59e0b, #d97706); border: none; font-size: 12px; font-weight: 600; white-space: nowrap;">
                                {{ translate('Subscribe') }}
                            </a>
                        </div>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link p-0 d-flex align-items-center" href="javascript:;" data-toggle="modal" data-target="#referralCodeModal" title="Referral Code" style="gap: 6px; white-space: nowrap;">
                            <i class="tio-gift" style="font-size: 20px;"></i>
                            <span class="d-none d-md-inline" style="font-size: 12px; font-weight: 600;">Refer <i class="tio-arrow-forward" style="font-size: 10px;"></i> 30 days</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item ml-3 max-sm-m-0">
                        <div class="hs-unfold">
                            <div>
                                @php($local = session()->has('vendor_local')?session('vendor_local'):null)
                                @php($lang = \App\Models\BusinessSetting::where('key', 'system_language')->first())
                                @if ($lang)
                                <div
                                    class="topbar-text dropdown disable-autohide text-capitalize d-flex">
                                    <a class="topbar-link dropdown-toggle d-flex align-items-center title-color"
                                    href="#" data-toggle="dropdown">
                                            @foreach(json_decode($lang['value'],true) as $data)
                                                @if($data['code']==$local)
                                                    <i class="tio-globe"></i> {{$data['code']}}

                                                @elseif(!$local &&  $data['default'] == true)
                                                    <i class="tio-globe"></i> {{$data['code']}}
                                                @endif
                                            @endforeach
                                    </a>
                                    <ul class="dropdown-menu lang-menu">
                                        @foreach(json_decode($lang['value'],true) as $key =>$data)
                                            @if($data['status']==1)
                                                <li>
                                                    <a class="dropdown-item py-1"
                                                        href="{{route('vendor.lang',[$data['code']])}}">
                                                        <span class="text-capitalize">{{$data['code']}}</span>
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                    </li> --}}
                    



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
                                            {{\App\CentralLogics\Helpers::get_loggedin_user()->f_name}}
                                            {{\App\CentralLogics\Helpers::get_loggedin_user()->l_name}}
                                        </span>
                                        <span class="card-text">{{\App\CentralLogics\Helpers::get_loggedin_user()->email}}</span>
                                    </div>
                                    <div class="avatar avatar-sm avatar-circle">
                                        <img class="avatar-img  onerror-image aspect-1-1"  data-onerror-image="{{asset('public/assets/admin/img/160x160/img1.jpg')}}"
                                        src="{{ \App\CentralLogics\Helpers::get_loggedin_user()->toArray()['image_full_url'] }}"
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
                                            src="{{ \App\CentralLogics\Helpers::get_loggedin_user()->toArray()['image_full_url'] }}"
                                                 alt="Owner image">
                                        </div>
                                        <div class="media-body">
                                            <span class="card-title h5">{{\App\CentralLogics\Helpers::get_loggedin_user()->f_name}} {{\App\CentralLogics\Helpers::get_loggedin_user()->l_name}}</span>
                                            <span class="card-text">{{\App\CentralLogics\Helpers::get_loggedin_user()->email}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{route('vendor.profile.view')}}">
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




<!-- Referral Code Modal -->
<div class="modal fade" id="referralCodeModal" tabindex="-1" aria-labelledby="referralCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #00868D, #006d6d); border: none; padding: 20px;">
                <div class="text-center w-100">
                    <i class="tio-gift" style="font-size: 40px; color: #fff;"></i>
                    <h5 class="modal-title mt-2 text-white" id="referralCodeModalLabel">Referral Program</h5>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="position: absolute; top: 10px; right: 15px; opacity: 0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center" style="padding: 24px;">
                <p class="mb-3" style="color: #555; font-size: 14px;">Invite Restaurant Owners and Get 30 Days More Access!</p>
                <div style="background: #f8f9fa; border: 2px dashed #00868D; border-radius: 8px; padding: 12px; margin-bottom: 16px;">
                    <span style="font-size: 22px; font-weight: 700; letter-spacing: 3px; color: #00868D;" id="referralCodeText">{{\App\CentralLogics\Helpers::get_store_data()->referral_code}}</span>
                </div>
                <button class="btn btn-sm btn-block text-white mb-3" id="copyReferralBtn" style="background: #00868D; border: none; border-radius: 8px; padding: 10px; font-weight: 600;">
                    <i class="tio-copy"></i> Copy Code
                </button>
                <button class="btn btn-sm btn-block text-white mb-3" 
                        id="shareReferralLinkBtn"
                        style="background: #28a745; border: none; border-radius: 8px; padding: 10px; font-weight: 600;">
                    <i class="tio-share"></i> Share Registration Link
                </button>
                <hr>
                <div class="text-left" style="font-size: 13px; color: #666;">
                    <p class="font-weight-bold mb-2" style="color: #333;">How it works:</p>
                    <p class="mb-1"><i class="tio-checkmark-circle text-success mr-1"></i> Share your referral code with restaurant owners</p>
                    <p class="mb-1"><i class="tio-checkmark-circle text-success mr-1"></i> They sign up using your referral code</p>
                    <p class="mb-1"><i class="tio-checkmark-circle text-success mr-1"></i> When they subscribe to any package, you get <strong>30 extra days</strong></p>
                    <p class="mb-0"><i class="tio-checkmark-circle text-success mr-1"></i> No limit on referrals!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Copy referral code
        $(document).on('click', '#copyReferralBtn', function () {
            var code = document.getElementById('referralCodeText').innerText.trim();
            var modal = document.getElementById('referralCodeModal');
            var temp = document.createElement('input');
            temp.setAttribute('type', 'text');
            temp.setAttribute('value', code);
            temp.style.position = 'absolute';
            temp.style.left = '-9999px';
            modal.appendChild(temp);
            temp.focus();
            temp.select();
            temp.setSelectionRange(0, code.length);
            document.execCommand('copy');
            modal.removeChild(temp);
            var btn = document.getElementById('copyReferralBtn');
            btn.innerHTML = '<i class="tio-checkmark-circle"></i> Copied!';
            setTimeout(function () {
                btn.innerHTML = '<i class="tio-copy"></i> Copy Code';
            }, 2000);
        });
        $(document).on('click', '#shareReferralLinkBtn', function () {
            var code = document.getElementById('referralCodeText').innerText.trim();
            var modal = document.getElementById('referralCodeModal');

            var registrationUrl = "{{ url('/restaurant/apply') }}?ref=" + code;

            var temp = document.createElement('input');
            temp.setAttribute('type', 'text');
            temp.setAttribute('value', registrationUrl);
            temp.style.position = 'absolute';
            temp.style.left = '-9999px';

            modal.appendChild(temp);
            temp.focus();
            temp.select();
            temp.setSelectionRange(0, registrationUrl.length);

            document.execCommand('copy');
            modal.removeChild(temp);

            var btn = document.getElementById('shareReferralLinkBtn');
            btn.innerHTML = '<i class="tio-checkmark-circle"></i> Link Copied!';

            setTimeout(function () {
                btn.innerHTML = '<i class="tio-link"></i> Copy Registration Link';
            }, 2000);
        });
                $(document).on('click', '.log-out', function () {
                Swal.fire({
                title: '{{ translate('Do you want to sign out?') }}',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonColor: '#FC6A57',
                cancelButtonColor: '#363636',
                confirmButtonText: `{{ translate('yes')}}`,
                cancelButtonText: `{{ translate('Cancel')}}`,
                }).then((result) => {
                if (result.value) {
                location.href='{{route('logout')}}';
                }
            })
        });
                $(document).on('click', '.add-to-session', function () {
                    var session_data = $(this).data("id");
                    $.ajax({
                        url: '',
                        method: 'POST',
                        data: {
                            value: session_data,
                            _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {

                            }
                        });
                });
                $(document).on('click', '#hide-warning', function () {
                $('.hide-warning').hide();
                });


    });


</script>
