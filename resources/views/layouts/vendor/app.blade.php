<!DOCTYPE html>
<?php
    if (env('APP_MODE') == 'demo') {
        $site_direction = session()->get('site_direction_vendor');
    }else{
        $site_direction = session()->has('vendor_site_direction')?session()->get('vendor_site_direction'):'ltr';
    }
    $country=\App\Models\BusinessSetting::where('key','country')->first();
    $countryCode= strtolower($country?$country->value:'auto');

    $storeId = \App\CentralLogics\Helpers::get_store_id();
    $store = \App\Models\Store::findOrFail($storeId);
    $moduleType = $store?->module?->module_type;
?>
{{-- {{ dd($countryCode) }} --}}
<html dir="{{ $site_direction }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}"  class="{{ $site_direction === 'rtl'?'active':'' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>@yield('title')</title>
    <!-- Favicon -->
    @php($logo = \App\Models\BusinessSetting::where(['key'=>'icon'])->first())
    <link rel="shortcut icon" href="">
    <link rel="icon" type="image/x-icon" href="{{\App\CentralLogics\Helpers::get_full_url('business', $logo?->value?? '', $logo?->storage[0]?->value ?? 'public','favicon')}}">
    <!-- Font -->
    <link href="{{asset('public/assets/admin/css/fonts.css')}}" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{asset('public/assets/admin/css/emogi-area.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/admin/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/admin/intltelinput/css/intlTelInput.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/admin/css/owl.min.css')}}">
    @stack('css_or_js')

    <script src="{{asset('public/assets/admin')}}/vendor/hs-navbar-vertical-aside/hs-navbar-vertical-aside-mini-cache.js"></script>
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/toastr.css">
</head>

<body class="footer-offset">
    @if (env('APP_MODE')=='demo')
    <div class="direction-toggle">
        <i class="tio-settings"></i>
        <span></span>
    </div>
    @endif
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="loading" class="initial-hidden">
                <div class="loading-inner">
                    <img width="200" src="{{asset('public/assets/admin/img/loader.gif')}}">
                </div>
            </div>
        </div>
    </div>
</div>
{{--loader--}}

<!-- Builder -->
@include('layouts.vendor.partials._front-settings')
<!-- End Builder -->

<!-- JS Preview mode only -->
@include('layouts.vendor.partials._header')

    @if( isset($moduleType) && $moduleType == 'rental')
        @include("rental::provider.partials._sidebar_{$moduleType}")
    @else
        @include('layouts.vendor.partials._sidebar')
    @endif
<!-- END ONLY DEV -->

<main id="content" role="main" class="main pointer-event">
    <!-- Content -->
@yield('content')

<!-- End Content -->

    <!-- Footer -->
@include('layouts.vendor.partials._footer')
<!-- End Footer -->

    <div class="d-none" id="text-validate-translate"
        data-required="{{ translate('this_field_is_required') }}"
        data-something-went-wrong="{{ translate('something_went_wrong!') }}"
        data-max-limit-crossed="{{ translate('max_limit_crossed') }}"
        data-file-size-larger="{{ translate('file_size_is_larger') }}"
        data-passwords-do-not-match="{{ translate('passwords_do_not_match') }}"
        data-valid-email="{{ translate('please_enter_a_valid_email') }}"
        data-password-validation="{{ translate('password_must_be_8+_chars_with_upper,_lower,_number_&_symbol') }}"
    ></div>


    <div class="modal fade" id="toggle-modal">
        <div class="modal-dialog status-warning-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
                <div class="modal-body pb-5 pt-0">
                    <div class="max-349 mx-auto mb-20">
                        <div>
                            <div class="text-center">
                                <img id="toggle-image" alt="" class="mb-20">
                                <h5 class="modal-title" id="toggle-title"></h5>
                            </div>
                            <div class="text-center" id="toggle-message">
                            </div>
                        </div>
                        <div class="btn--container justify-content-center">
                            <button type="button" id="toggle-ok-button" class="btn btn--primary min-w-120 confirm-Toggle" data-dismiss="modal">{{translate('Ok')}}</button>
                            <button id="reset_btn" type="reset" class="btn btn--cancel min-w-120" data-dismiss="modal">
                                {{translate("Cancel")}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="toggle-status-modal">
        <div class="modal-dialog status-warning-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
                <div class="modal-body pb-5 pt-0">
                    <div class="max-349 mx-auto mb-20">
                        <div>
                            <div class="text-center">
                                <img id="toggle-status-image" alt="" class="mb-20">
                                <h5 class="modal-title" id="toggle-status-title"></h5>
                            </div>
                            <div class="text-center" id="toggle-status-message">
                            </div>
                        </div>
                        <div class="btn--container justify-content-center">
                            <button type="button" id="toggle-status-ok-button" class="btn btn--primary min-w-120 confirm-Status-Toggle" data-dismiss="modal">{{translate('Ok')}}</button>
                            <button id="reset_btn" type="reset" class="btn btn--cancel min-w-120" data-dismiss="modal">
                                {{translate("Cancel")}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- New Menu Order Popup --}}
    <div class="modal fade" id="popup-modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center">
                                <h2 class="update_notification_text">
                                    <i class="tio-shopping-cart-outlined"></i> {{translate('messages.You have new order, Check Please.')}}
                                </h2>
                                <hr>
                                <button class="btn btn-primary check-order">{{translate('messages.Ok, let me check')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Menu Order Detail Popup --}}
    <div class="modal fade" id="menu-order-popup" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header" style="background:#10847E;">
                    <h5 class="modal-title text-white">
                        <i class="tio-shopping-cart-outlined mr-1"></i>
                        <span id="mop-title">{{ translate('New Order Received!') }}</span>
                    </h5>
                    <span id="mop-badge" class="badge badge-light ml-2" style="font-size:14px;"></span>
                </div>
                <div class="modal-body p-0" id="mop-body">
                    {{-- Filled by JS --}}
                </div>
                <div class="modal-footer border-top justify-content-between">
                    <small class="text-muted"><i class="tio-info-outlined"></i> {{ translate('Sound will repeat until you act on all orders') }}</small>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="mop-close-btn">{{ translate('Close') }}</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="new-dynamic-submit-model">
        <div class="modal-dialog modal-dialog-centered status-warning-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
                <div class="modal-body pb-5 pt-0">
                    <div class="max-349 mx-auto mb-20">
                        <div>
                            <div class="text-center">
                                <img id="image-src" class="mb-20">
                                <h5 class="modal-title" id="toggle-title"></h5>
                            </div>
                            <div class="text-center" id="toggle-message">
                                <h3 id="modal-title"></h3>
                                <div id="modal-text"></div>
                            </div>

                            </div>
                            <div class="mb-4 d-none" id="note-data">
                                <textarea class="form-control" placeholder="{{ translate('your_note_here') }}" id="get-text-note" cols="5" ></textarea>
                            </div>
                        <div class="btn--container justify-content-center">
                            <div id="hide-buttons">
                                <div class="d-flex justify-content-center flex-wrap gap-3">
                                    <button data-dismiss="modal" id="cancel_btn_text" class="btn btn--cancel min-w-120" >{{translate("Not_Now")}}</button>
                                    <button type="button" id="new-dynamic-ok-button" class="btn btn-primary confirm-model min-w-120">{{translate('Yes')}}</button>
                                </div>
                            </div>

                            <button data-dismiss="modal"  type="button" id="new-dynamic-ok-button-show" class="btn btn--primary  d-none min-w-120">{{translate('Okay')}}</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
<!-- ========== END MAIN CONTENT ========== -->

<!-- ========== END SECONDARY CONTENTS ========== -->
<script src="{{asset('public/assets/admin')}}/js/custom.js"></script>
{{-- <script src="{{asset('public/assets/admin')}}/js/firebase.min.js"></script> --}}
<!-- JS Implementing Plugins -->

@stack('script')

<!-- JS Front -->
<script src="{{asset('public/assets/admin')}}/js/vendor.min.js"></script>
<script src="{{asset('public/assets/admin')}}/js/theme.min.js"></script>
<script src="{{asset('public/assets/admin')}}/js/sweet_alert.js"></script>
<script src="{{asset('public/assets/admin')}}/js/toastr.js"></script>
<script src="{{asset('public/assets/admin')}}/js/emogi-area.js"></script>
<script src="{{asset('public/assets/admin/js/owl.min.js')}}"></script>
<script src="{{asset('public/assets/admin/js/app-blade/vendor.js')}}"></script>
{!! Toastr::message() !!}
<script src="{{asset('public/assets/admin/intltelinput/js/intlTelInput.min.js')}}"></script>
<script src="{{asset('public/assets/admin/js/form-validate.js')}}"></script>

@if ($errors->any())

<script>
    "use strict";
    @foreach ($errors->all() as $error)
    toastr.error('{{ translate($error) }}', Error, {
        CloseButton: true,
        ProgressBar: true
    });
    @endforeach
</script>
@endif

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@stack('script_2')
<audio id="myAudio">
    <source src="{{asset('public/assets/admin/sound/notification.mp3')}}" type="audio/mpeg">
</audio>
    <script src="{{asset('public/assets/admin/js/view-pages/common.js')}}"></script>
    <script src="{{asset('public/assets/admin/js/keyword-highlighted.js')}}"></script>

<script>
    var audio = document.getElementById("myAudio");

    function playAudio() {
        audio.play();
    }

    function pauseAudio() {
        audio.pause();
    }
"use strict";


    $(window).on('load', function(){
        $('main > .container-fluid.content').prepend($('#renew-badge'));
    })



    $(document).on('ready', function(){
        // $('body').css('overflow','')
        $(".direction-toggle").on("click", function () {
            if($('html').hasClass('active')){
                $('html').removeClass('active')
                setDirection(1);
            }else {
                setDirection(0);
                $('html').addClass('active')
            }
        });
        if ($('html').attr('dir') === "rtl") {
            $(".direction-toggle").find('span').text('Toggle LTR')
        } else {
            $(".direction-toggle").find('span').text('Toggle RTL')
        }

        function setDirection(status) {
            if (status === 1) {
                $("html").attr('dir', 'ltr');
                $(".direction-toggle").find('span').text('Toggle RTL')
            } else {
                $("html").attr('dir', 'rtl');
                $(".direction-toggle").find('span').text('Toggle LTR')
            }
            $.get({
                    url: '{{ route('vendor.site_direction') }}',
                    dataType: 'json',
                    data: {
                        status: status,
                    },
                    success: function() {
                    },

                });
            }
        });


    function route_alert(route, message) {
        Swal.fire({
            title: '{{ translate('messages.Are you sure?') }}',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: '{{ translate('messages.no') }}',
            confirmButtonText: '{{ translate('messages.Yes') }}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = route;
            }
        })
    }

    $('.form-alert').on('click',function (){
        let id = $(this).data('id')
        let message = $(this).data('message')
        Swal.fire({
            title: '{{ translate('messages.Are you sure?') }}',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: '{{ translate('messages.no') }}',
            confirmButtonText: '{{ translate('messages.Yes') }}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $('#'+id).submit()
            }
        })
    })


    function set_filter(url, id, filter_by) {
        let nurl = new URL(url);
        nurl.searchParams.set(filter_by, id);
        location.href = nurl;
    }

    @php($fcm_credentials = \App\CentralLogics\Helpers::get_business_settings('fcm_credentials'))
    let firebaseConfig = {
        apiKey: "{{isset($fcm_credentials['apiKey']) ? $fcm_credentials['apiKey'] : ''}}",
        authDomain: "{{isset($fcm_credentials['authDomain']) ? $fcm_credentials['authDomain'] : ''}}",
        projectId: "{{isset($fcm_credentials['projectId']) ? $fcm_credentials['projectId'] : ''}}",
        storageBucket: "{{isset($fcm_credentials['storageBucket']) ? $fcm_credentials['storageBucket'] : ''}}",
        messagingSenderId: "{{isset($fcm_credentials['messagingSenderId']) ? $fcm_credentials['messagingSenderId'] : ''}}",
        appId: "{{isset($fcm_credentials['appId']) ? $fcm_credentials['appId'] : ''}}",
        measurementId: "{{isset($fcm_credentials['measurementId']) ? $fcm_credentials['measurementId'] : ''}}"
    };
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    function startFCM() {
        messaging
            .requestPermission()
            .then(function() {
                return messaging.getToken();
            })
            .then(function(token) {
                @php($store_id=\App\CentralLogics\Helpers::get_store_id())
                // Send the token to your backend to subscribe to topic
                subscribeTokenToBackend(token, 'store_panel_{{$store_id}}_message');
            }).catch(function(error) {
            console.error('Error getting permission or token:', error);
        });
    }

    function subscribeTokenToBackend(token, topic) {
        fetch('{{url('/')}}/subscribeToTopic', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ token: token, topic: topic })
        }).then(response => {
            if (response.status < 200 || response.status >= 400) {
                return response.text().then(text => {
                    throw new Error(`Error subscribing to topic: ${response.status} - ${text}`);
                });
            }
            console.log(`Subscribed to "${topic}"`);
        }).catch(error => {
            console.error('Subscription error:', error);
        });
    }
    function getUrlParameter(sParam) {
            let sPageURL = window.location.search.substring(1);
            let sURLletiables = sPageURL.split('&');
            for (let i = 0; i < sURLletiables.length; i++) {
                let sParameterName = sURLletiables[i].split('=');
                if (sParameterName[0] === sParam) {
                    return sParameterName[1];
                }
            }
        }

        function conversationList() {
            $.ajax({
                url: "",
                success: function(data) {
                    $('#conversation-list').empty();
                    $("#conversation-list").append(data.html);
                    let user_id = getUrlParameter('user');
                    $('.customer-list').removeClass('conv-active');
                    $('#customer-' + user_id).addClass('conv-active');
                }
            })
        }

        function conversationView() {
            let conversation_id = getUrlParameter('conversation');
            let user_id = getUrlParameter('user');
            let url= '{{url('/')}}/vendor-panel/message/view/'+conversation_id+'/' + user_id;
            $.ajax({
                url: url,
                success: function(data) {
                    $('#view-conversation').html(data.view);
                }
            })
        }
        @php($order_notification_type = \App\Models\BusinessSetting::where('key', 'order_notification_type')->first())
        @php($order_notification_type = $order_notification_type ? $order_notification_type->value : 'firebase')
        let order_type = 'all';
        let is_trip =false;
        messaging.onMessage(function (payload) {
            if(payload.data.order_id && payload.data.type === 'new_order'){
                @if(\App\CentralLogics\Helpers::employee_module_permission_check('order') && $order_notification_type == 'firebase')
                    order_type = payload.data.order_type
                    if(order_type === 'trip'){
                        document.querySelector('.update_notification_text').textContent = "{{translate('messages.You have new trip, Check Please.')}}";
                        is_trip= true;
                    }
                    playAudio();
                    $('#popup-modal').appendTo("body").modal('show');
                @endif
            }else if(payload.data.type === 'message'){
                if (window.location.href.includes('message/list?conversation')) {
                    let conversation_id = getUrlParameter('conversation');
                    let user_id = getUrlParameter('user');
                    let url = '{{url('/')}}/vendor-panel/message/view/' + conversation_id + '/' + user_id;
                    $.ajax({
                        url: url,
                        success: function (data) {
                            $('#view-conversation').html(data.view);
                        }
                    })
                }
                toastr.success('{{ translate('messages.New message arrived') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
                if($('#conversation-list').scrollTop() === 0){
                    conversationList();
                }
            }
        });

        @if(\App\CentralLogics\Helpers::employee_module_permission_check('order') && $order_notification_type == 'manual')
        setInterval(function () {
            $.get({
                url: '',
                dataType: 'json',
                success: function (response) {
                    let data = response.data;

                    if(data.order_type === 'trip'){
                        document.querySelector('.update_notification_text').textContent = "{{translate('messages.You have new trip, Check Please.')}}";
                        is_trip= true;
                    }

                    if (data.new_pending_order > 0) {
                        order_type = 'pending';
                        playAudio();
                        $('#popup-modal').appendTo("body").modal('show');
                    }
                    else if(data.new_confirmed_order > 0)
                    {
                        order_type = 'confirmed';
                        playAudio();
                        $('#popup-modal').appendTo("body").modal('show');
                    }
                },
            });
        }, 10000);
        @endif

        $('.check-order').on('click',function (){
            if(order_type){
                if(is_trip === true){
                    location.href = '{{url('/')}}/vendor-panel/trip?status=all';
                } else{
                    location.href = '{{url('/')}}/vendor-panel/order/list/'+order_type;
                }
            }
        });
        startFCM();
        conversationList();
        if(getUrlParameter('conversation')){
            conversationView();
        }


    const inputs = document.querySelectorAll('input[type="tel"]');
            inputs.forEach(input => {
                window.intlTelInput(input, {
                    initialCountry: "{{$countryCode}}",
                    utilsScript: "{{ asset('public/assets/admin/intltelinput/js/utils.js') }}",
                    autoInsertDialCode: true,
                    nationalMode: false,
                    formatOnDisplay: false,
                });
            });


            function keepNumbersAndPlus(inputString) {
                let regex = /[0-9+]/g;
                let filteredString = inputString.match(regex);
            return filteredString ? filteredString.join('') : '';
            }

            $(document).on('keyup', 'input[type="tel"]', function () {
                $(this).val(keepNumbersAndPlus($(this).val()));
                });

    //search option
    $(document).ready(function () {
        $('#searchForm input[name="search"]').keyup(function () {
            var searchKeyword = $(this).val().trim();

            if (searchKeyword.length >= 1) {
                $.ajax({
                    type: 'POST',
                    url: $('#searchForm').attr('action'),
                    data: {search: searchKeyword, _token: $('input[name="_token"]').val()},
                    success: function (response) {
                        if (response.length === 0) {
                            $('#searchResults').html('<div class="fs-16 fw-500 mb-2">' + @json(translate('Search Result')) + '</div>' +
                                '<div class="search-list h-300 d-flex flex-column gap-2 justify-content-center align-items-center fs-16">' +
                                '<img width="30" src="' + @json(asset('/public/assets/admin/img/no-search-found.png')) + '" alt="">' + ' ' +
                                @json(translate('No result found')) +
                                    '</div>');

                        } else {
                            var resultHtml = '';
                            response.forEach(function (route) {
                                var separator = route.fullRoute.includes('?') ? '&' : '?';
                                    var fullRouteWithKeyword = route.fullRoute + separator + 'keyword=' + encodeURIComponent(searchKeyword);

                                    var keywordRegex = searchKeyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                                         keywordRegex = new RegExp('(' + keywordRegex + ')', 'gi');
                                    var highlightedRouteName = route.routeName.replace(keywordRegex, '<mark>$1</mark>');
                                    var highlightedURI = route.URI.replace(keywordRegex, '<mark>$1</mark>');
                                    resultHtml += '<a href="' + fullRouteWithKeyword + '" class="search-list-item d-flex flex-column" data-route-name="' + route.routeName + '" data-route-uri="' + route.URI + '" data-route-full-url="' + route.fullRoute + '" aria-current="true">';
                                    resultHtml += '<h5>' + highlightedRouteName + '</h5>';
                                    resultHtml += '<p class="text-muted fs-12 mb-0">' + highlightedURI + '</p>';
                                    resultHtml += '</a>';
                            });
                            $('#searchResults').html('<div class="fs-16 fw-500 mb-2">' + @json(translate('Search Result')) + '</div>' + '<div class="search-list d-flex flex-column">' + resultHtml + '</div>');

                            $('.search-list-item').click(function () {
                                var routeName = $(this).data('route-name');
                                var routeUri = $(this).data('route-uri');
                                var routeFullUrl = $(this).data('route-full-url');

                                $.ajax({
                                    type: 'POST',
                                    url: '',
                                    data: {
                                        routeName: routeName,
                                        routeUri: routeUri,
                                        routeFullUrl: routeFullUrl,
                                        searchKeyword: searchKeyword,
                                        _token: $('input[name="_token"]').val()
                                    },
                                    success: function (response) {
                                        console.log(response.message);
                                    },
                                    error: function (xhr, status, error) {
                                        console.error(xhr.responseText);
                                    }
                                });
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            } else {
                $('#searchResults').html('<div class="text-center text-muted py-5">{{translate('Write something to search.')}}.</div>');
            }
        });
    });

    document.addEventListener('keydown', function(event) {
        if (event.ctrlKey && event.key === 'k') {
            event.preventDefault();
            document.getElementById('modalOpener').click();
        }
    });

    $(document).ready(function () {
        $("#staticBackdrop").on("shown.bs.modal", function () {
            $(this).find("#searchForm input[type=search]").val('');
            $('#searchResults').html('<div class="text-center text-muted py-5">{{translate('Loading recent searches')}}...</div>');
            $(this).find("#searchForm input[type=search]").focus();

            $.ajax({
                type: 'GET',
                url: '',
                success: function (response) {
                    if (response.length === 0) {
                        $('#searchResults').html('<div class="text-center text-muted py-5">{{translate('It appears that you have not yet searched.')}}.</div>');
                    } else {
                        var resultHtml = '';
                        response.forEach(function (route) {
                            resultHtml += '<a href="' + route.route_full_url + '" class="search-list-item d-flex flex-column" data-route-name="' + route.route_name + '" data-route-uri="' + route.route_uri + '" data-route-full-url="' + route.route_full_url + '" aria-current="true">';
                            resultHtml += '<h5>' + route.route_name + '</h5>';
                            resultHtml += '<p class="text-muted fs-12  mb-0">' + route.route_uri + '</p>';
                            resultHtml += '</a>';
                        });
                        $('#searchResults').html('<div class="recent-search fs-16 fw-500 animate">' +
                            @json(translate('Recent Search')) + '<div class="search-list d-flex flex-column mt-2">' + resultHtml + '</div></div>');

                        $('.search-list-item').click(function () {
                            var routeName = $(this).data('route-name');
                            var routeUri = $(this).data('route-uri');
                            var routeFullUrl = $(this).data('route-full-url');
                            var searchKeyword = $('input[type=search]').val().trim();

                            $.ajax({
                                type: 'POST',
                                url: '',
                                data: {
                                    routeName: routeName,
                                    routeUri: routeUri,
                                    routeFullUrl: routeFullUrl,
                                    searchKeyword: searchKeyword,
                                    _token: $('input[name="_token"]').val()
                                },
                                success: function (response) {
                                    console.log(response.message);
                                },
                                error: function (xhr, status, error) {
                                    console.error(xhr.responseText);
                                }
                            });
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    $('#searchResults').html('<div class="text-center text-muted py-5">{{translate('Error loading recent searches')}}.</div>');
                }
            });
        });
    });

    $("#staticBackdrop").on("hidden.bs.modal", function () {
        $('#searchResults').empty();
    });

    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('search', function() {
        if (!this.value.trim()) {
            $('#searchResults').html('<div class="text-center text-muted py-5"></div>');
        }
    });

    $('#searchForm').submit(function (event) {
        event.preventDefault();
    });


</script>

<!-- IE Support -->
<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
</script>

<!-- Fix: Clean up any lingering modal backdrops or overlays on page load -->
<script>
    $(document).ready(function() {
        // Remove any stuck modal backdrops
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css({'overflow': '', 'padding-right': ''});

        // Ensure loading element is hidden
        $('#loading').hide();

        // Close any open modals
        $('.modal').modal('hide');
    });
</script>

<!-- Menu Order Notification Polling -->
<script>
"use strict";
window._menuOrderAlert = false;
window._menuOrderRingInterval = null;
window._menuOrderData = [];

$(document).ready(function() {
    var checkUrl = '{{ route("vendor.menu-order.check-new") }}';
    var statusUpdateUrl = '{{ url("/vendor/menu-order/status-update") }}';
    var markCheckedUrl = '{{ url("/vendor/menu-order/mark-checked") }}';
    var csrfToken = '{{ csrf_token() }}';
    var currencySymbol = '{!! \App\CentralLogics\Helpers::currency_symbol() !!}';

    var statusLabels = {
        'pending': '{{ translate("Pending") }}',
        'confirmed': '{{ translate("Confirmed") }}',
        'preparing': '{{ translate("Preparing") }}',
        'completed': '{{ translate("Completed") }}',
        'cancelled': '{{ translate("Cancelled") }}'
    };

    var statusColors = {
        'pending': '#ffa800',
        'confirmed': '#3f8ce8',
        'preparing': '#6c63ff',
        'completed': '#00AA96',
        'cancelled': '#ff6d6d'
    };

    // Start ringing every 10 seconds
    function startRinging() {
        if (window._menuOrderRingInterval) return;
        playAudio();
        window._menuOrderRingInterval = setInterval(function() {
            playAudio();
        }, 10000);
    }

    // Stop ringing
    function stopRinging() {
        if (window._menuOrderRingInterval) {
            clearInterval(window._menuOrderRingInterval);
            window._menuOrderRingInterval = null;
        }
        pauseAudio();
    }

    // Build order card HTML
    function buildOrderCard(order) {
        var itemsHtml = '';
        if (order.items && order.items.length) {
            order.items.forEach(function(item) {
                var sizeText = item.size && item.size !== 'default' ? ' <small class="text-muted">(' + item.size + ')</small>' : '';
                itemsHtml += '<div class="d-flex justify-content-between py-1 border-bottom" style="font-size:13px;">' +
                    '<div><span class="font-weight-semibold">' + item.qty + 'x</span> ' + item.name + sizeText + '</div>' +
                    '<div class="text-nowrap">' + currencySymbol + parseFloat(item.price * item.qty).toFixed(2) + '</div>' +
                    '</div>';
            });
        }

        var tableHtml = '';
        if (order.table_no && String(order.table_no).trim()) {
            tableHtml = '<div>' +
                '<h6 class="mb-0" style="color:#cc0000;">' +
                '<i class="tio-chair mr-1"></i>Table-No</h6>' +
                '<div style="text-align: center;">T- <b>' + order.table_no + '</b></div>' +
            '</div>';
        }

        var currentStatus = order.status;
        var statusBadge = '<span class="badge" style="background:' + (statusColors[currentStatus] || '#999') + ';color:#fff;">' + (statusLabels[currentStatus] || currentStatus) + '</span>';

        // Status buttons
        var statusBtns = '';
        var allStatuses = ['confirmed', 'preparing', 'completed', 'cancelled'];
        allStatuses.forEach(function(s) {
            if (s === currentStatus) return;
            var btnClass = s === 'cancelled' ? 'btn-outline-danger' : 'btn-outline-primary';
            statusBtns += '<button class="btn btn-sm ' + btnClass + ' mop-status-btn" data-order-id="' + order.id + '" data-status="' + s + '" style="font-size:11px;padding:3px 10px;">' + statusLabels[s] + '</button> ';
        });

        var instructionsHtml = order.instructions ? '<div class="mt-1"><small class="text-muted"><i class="tio-document-text mr-1"></i>' + order.instructions + '</small></div>' : '';

        return '<div class="mop-order-card border rounded p-3 mb-3" data-order-db-id="' + order.id + '" id="mop-order-' + order.id + '">' +
            '<div class="d-flex justify-content-between align-items-start mb-2">' +
                '<div>' +
                    '<h6 class="mb-0" style="color:#10847E;"><i class="tio-receipt mr-1"></i>#' + order.order_id + '</h6>' +
                    '<small class="text-muted">' + order.created_at + '</small>' +
                '</div>' +
                tableHtml
                +'<div class="text-right">' +
                    statusBadge +
                    '<br><span class="badge badge-light mt-1" style="font-size:11px;text-transform:capitalize;"><i class="tio-restaurant mr-1"></i>' + order.order_type + '</span>' +
                '</div>' +
            '</div>' +
            '<div class="d-flex gap-3 mb-2" style="font-size:13px;">' +
                '<div><i class="tio-user mr-1"></i><strong>' + order.customer_name + '</strong></div>' +
                '<div><i class="tio-call mr-1"></i>' + order.customer_phone + '</div>' +
            '</div>' +
            instructionsHtml +
            '<div class="bg-light rounded p-2 mt-2 mb-2">' + itemsHtml +
                '<div class="d-flex justify-content-between pt-2 font-weight-bold" style="font-size:14px;">' +
                    '<span>{{ translate("Total") }}</span>' +
                    '<span style="color:#10847E;">' + currencySymbol + parseFloat(order.total).toFixed(2) + '</span>' +
                '</div>' +
            '</div>' +
            '<div class="d-flex flex-wrap gap-2 align-items-center">' +
                '<span style="font-size:12px;font-weight:600;color:#334257;" class="mr-2">{{ translate("Change Status") }}:</span>' +
                statusBtns +
            '</div>' +
        '</div>';
    }

    // Render all orders in popup
    function renderPopup(orders) {
        if (!orders.length) {
            stopRinging();
            $('#menu-order-popup').modal('hide');
            return;
        }
        var html = '';
        orders.forEach(function(order) {
            html += buildOrderCard(order);
        });
        $('#mop-body').html('<div class="p-3">' + html + '</div>');
        $('#mop-badge').text(orders.length + ' {{ translate("new") }}');
    }

    // Poll for new orders
    setInterval(function() {
        $.get({
            url: checkUrl,
            dataType: 'json',
            success: function(response) {
                if (response.data && response.data.new_order_count > 0) {
                    window._menuOrderData = response.data.orders;
                    renderPopup(window._menuOrderData);
                    if (!$('#menu-order-popup').hasClass('show')) {
                        $('#menu-order-popup').appendTo("body").modal('show');
                    }
                    if (!window._menuOrderRingInterval) {
                        startRinging();
                    }
                } else {
                    if (window._menuOrderData.length > 0) {
                        window._menuOrderData = [];
                        stopRinging();
                        $('#menu-order-popup').modal('hide');
                    }
                }
            },
            error: function() {}
        });
    }, 10000);

    // Status change - SweetAlert confirmation over modal
    $(document).on('click', '.mop-status-btn', function() {
        var orderId = $(this).data('order-id');
        var newStatus = $(this).data('status');
        var isCancelled = newStatus === 'cancelled';

        Swal.fire({
            title: isCancelled ? '{{ translate("Cancel this order?") }}' : '{{ translate("Change Status") }}',
            text: isCancelled ? '{{ translate("Are you sure you want to cancel this order?") }}' : '{{ translate("Change order status to") }} ' + statusLabels[newStatus] + '?',
            type: isCancelled ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: isCancelled ? '#ff6d6d' : '#10847E',
            cancelButtonColor: '#999',
            confirmButtonText: isCancelled ? '{{ translate("Yes, Cancel") }}' : '{{ translate("Yes, Change") }}',
            cancelButtonText: '{{ translate("No") }}'
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: statusUpdateUrl + '/' + orderId,
                    type: 'POST',
                    data: { status: newStatus, _token: csrfToken, _method: 'PUT' },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $.post(markCheckedUrl + '/' + orderId, { _token: csrfToken });

                            var card = $('#mop-order-' + orderId);
                            if (card.length) {
                                for (var i = 0; i < window._menuOrderData.length; i++) {
                                    if (window._menuOrderData[i].id === orderId) {
                                        window._menuOrderData.splice(i, 1);
                                        break;
                                    }
                                }
                                card.fadeOut(300, function() {
                                    $(this).remove();
                                    $('#mop-badge').text(window._menuOrderData.length + ' {{ translate("new") }}');
                                    if (window._menuOrderData.length === 0) {
                                        stopRinging();
                                        $('#menu-order-popup').modal('hide');
                                    }
                                });
                            }
                            toastr.success('{{ translate("Order status updated successfully") }}');
                        } else {
                            toastr.error('{{ translate("Failed to update status") }}');
                        }
                    },
                    error: function() {
                        toastr.error('{{ translate("Something went wrong") }}');
                    }
                });
            }
        });
    });

    // Close button - mark all as checked and stop ringing
    $('#mop-close-btn').on('click', function() {
        window._menuOrderData.forEach(function(order) {
            $.post(markCheckedUrl + '/' + order.id, { _token: csrfToken });
        });
        window._menuOrderData = [];
        stopRinging();
        $('#menu-order-popup').modal('hide');
    });

    // Keep old popup handler for non-menu orders
    $(document).on('click', '.check-order', function() {
        $('#popup-modal').modal('hide');
    });
});
</script>
</body>
</html>
