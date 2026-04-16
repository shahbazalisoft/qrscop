@php
    $vendorData = \App\CentralLogics\Helpers::get_store_data();
    $title = $vendorData?->module_type == 'rental' && addon_published_status('Rental') ? 'Provider' : 'Store';
@endphp
@extends('layouts.vendor.app')
@section('title',translate('messages.store_view'))
@push('css_or_js')
<style>
    .section-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 16px;
        margin-top: 20px;
    }
    .section-link-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px 20px;
        box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        text-decoration: none;
        color: #333;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        transition: all 0.2s;
        border: 2px solid transparent;
    }
    .section-link-card:hover {
        border-color: #00868F;
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        color: #333;
        text-decoration: none;
    }
    .section-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .section-link-card h6 {
        margin: 0 0 4px;
        font-size: 0.95rem;
        font-weight: 600;
    }
    .section-link-card p {
        margin: 0;
        font-size: 0.8rem;
        color: #888;
        line-height: 1.4;
    }
    .section-link-card .arrow {
        margin-left: auto;
        color: #ccc;
        font-size: 1.1rem;
        align-self: center;
    }
    .section-link-card:hover .arrow {
        color: #00868F;
    }
</style>
@endpush

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between">
            <h2 class="page-header-title text-capitalize my-2">
                <img class="w--26" src="{{asset('/public/assets/admin/img/store.png')}}" alt="public">
                <span>{{translate('messages.my_'.$title.'_info')}}</span>
            </h2>
            <div class="my-2">
                <a class="btn btn--primary" href="{{route('vendor.shop.edit')}}"><i class="tio-edit"></i>{{translate('messages.edit_'.$title.'_information')}}</a>
            </div>
        </div>
    </div>

    <!-- Store Info Card -->
    <div class="card border-0">
        <div class="card-body p-0">
            @if($shop->cover_photo)
            <div>
                <img class="my-restaurant-img onerror-image" src="{{ $shop->cover_photo_full_url }}"
                data-onerror-image="{{asset('public/assets/admin/img/900x400/img1.jpg')}}">
            </div>
            @endif
            <div class="my-resturant--card">
                @if($shop->image=='def.png')
                <div class="my-resturant--avatar">
                    <img class="border onerror-image"
                    src="{{asset('public/assets/back-end')}}/img/shop.png"
                    data-onerror-image="{{asset('public/assets/admin/img/160x160/img1.jpg')}}" alt="User Pic">
                </div>
                @else
                <div class="my-resturant--avatar onerror-image">
                    <img src="{{ $shop->logo_full_url }}"
                    class="border" data-onerror-image="{{asset('public/assets/admin/img/160x160/img1.jpg')}}" alt="">
                </div>
                @endif
                <div class="my-resturant--content">
                    <span class="d-block mb-1 pb-1">
                        <strong>{{translate('messages.name')}} :</strong> {{$shop->name}}
                    </span>
                    <span class="d-block mb-1 pb-1">
                        <strong>{{translate('messages.phone')}} :</strong> <a href="tel:{{$shop->phone}}">{{$shop->phone}}</a>
                    </span>
                    <span class="d-block mb-1 pb-1">
                        <strong>{{translate('messages.address')}} :</strong> {{$shop->address}}
                    </span>
                    <span class="d-block mb-1 pb-1">
                        <strong>{{translate('messages.Business_Plan')}} :</strong> {{translate($shop->store_business_model)}}
                    </span>
                    <span class="d-block mb-1 pb-1">
                        @if ($shop->store_business_model == 'commission')
                        <strong>{{translate('messages.admin_commission')}} :</strong> {{(isset($shop->comission)? $shop->comission:\App\Models\BusinessSetting::where('key','admin_commission')->first()->value)}}%
                        @elseif(in_array($shop->store_business_model ,['subscription','unsubscribed']))
                        <strong>{{translate('Subscription_plan')}} :</strong> {{ $shop?->store_sub_update_application?->package?->package_name}}
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sections Grid -->
    {{-- <div class="section-grid">

        <!-- Restaurant Info -->
        <a href="{{ route('vendor.shop.edit') }}" class="section-link-card">
            <div class="section-icon" style="background: #e6f7ef; color: #16a34a;">
                <i class="tio-edit"></i>
            </div>
            <div>
                <h6>{{ translate('messages.restaurant_info') }}</h6>
                <p>{{ translate('messages.edit_name_address_contact_logo_cover') }}</p>
            </div>
            <span class="arrow"><i class="tio-chevron-right"></i></span>
        </a>

        <!-- Menu Template -->
        <a href="{{ route('vendor.business-settings.menu-template') }}" class="section-link-card">
            <div class="section-icon" style="background: #fff3e0; color: #FB8500;">
                <i class="tio-fastfood"></i>
            </div>
            <div>
                <h6>{{ translate('messages.menu_template') }}</h6>
                <p>{{ translate('messages.change_template_colors_layout') }}</p>
            </div>
            <span class="arrow"><i class="tio-chevron-right"></i></span>
        </a>

        <!-- Menu Customization -->
        <a href="" class="section-link-card">
            <div class="section-icon" style="background: #ede9fe; color: #7c3aed;">
                <i class="tio-brush"></i>
            </div>
            <div>
                <h6>{{ translate('messages.menu_customization') }}</h6>
                <p>{{ translate('messages.logo_header_title_description_cart_icon') }}</p>
            </div>
            <span class="arrow"><i class="tio-chevron-right"></i></span>
        </a>

        <!-- QR Management -->
        <a href="{{ route('vendor.business-settings.qr-setup') }}" class="section-link-card">
            <div class="section-icon" style="background: #e0f2fe; color: #0284c7;">
                <i class="tio-barcode"></i>
            </div>
            <div>
                <h6>{{ translate('messages.QR_Management') }}</h6>
                <p>{{ translate('messages.manage_and_generate_qr_codes') }}</p>
            </div>
            <span class="arrow"><i class="tio-chevron-right"></i></span>
        </a>

        <!-- Store Setup -->
        <a href="{{ route('vendor.business-settings.store-setup') }}" class="section-link-card">
            <div class="section-icon" style="background: #fce4ec; color: #e91e63;">
                <i class="tio-settings"></i>
            </div>
            <div>
                <h6>{{ translate('messages.store_setup') }}</h6>
                <p>{{ translate('messages.meta_data_schedule_store_settings') }}</p>
            </div>
            <span class="arrow"><i class="tio-chevron-right"></i></span>
        </a>

        <!-- Subscription -->
        <a href="#" class="section-link-card">
            <div class="section-icon" style="background: #e0f7f7; color: #00868F;">
                <i class="tio-premium-outlined"></i>
            </div>
            <div>
                <h6>{{ translate('messages.subscription') }}</h6>
                <p>{{ translate('messages.view_plan_details_and_status') }}</p>
            </div>
            <span class="arrow"><i class="tio-chevron-right"></i></span>
        </a>

    </div> --}}

    <!-- Announcement -->
    {{-- <div class="card border-0 mt-3">
        <div class="card-header">
            <h5 class="card-title toggle-switch toggle-switch-sm d-flex justify-content-between">
                <span class="card-header-icon mr-1"><i class="tio-dashboard"></i></span>
                <span>{{translate('Announcement')}}</span>
                <span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('This_feature_is_for_sharing_important_information_or_announcements_related_to_the_'.$title.'.')}}">
                    <img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" alt="">
                </span>
            </h5>
            <label class="toggle-switch toggle-switch-sm" for="announcement_status">
                <input class="toggle-switch-input dynamic-checkbox" type="checkbox" id="announcement_status"
                       data-id="announcement_status"
                       data-type="status"
                       data-image-on='{{asset('/public/assets/admin/img/modal')}}/digital-payment-on.png'
                       data-image-off="{{asset('/public/assets/admin/img/modal')}}/digital-payment-off.png"
                       data-title-on="{{translate('Do_you_want_to_enable_the_announcement')}}"
                       data-title-off="{{translate('Do_you_want_to_disable_the_announcement')}}"
                       data-text-on="<p>{{translate('User_will_able_to_see_the_Announcement_on_the_store_page.')}}</p>"
                       data-text-off="<p>{{translate('User_will_not_be_able_to_see_the_Announcement_on_the_store_page')}}</p>"
                       name="announcement" value="1" {{$shop->announcement?'checked':''}}>
                <span class="toggle-switch-label">
                    <span class="toggle-switch-indicator"></span>
                </span>
            </label>
        </div>
        <form action="{{route('vendor.business-settings.toggle-settings',[$shop->id,$shop->announcement?0:1, 'announcement'])}}"
            method="get" id="announcement_status_form">
        </form>
        <div class="card-body">
            <form action="{{route('vendor.shop.update-message')}}" method="post">
            @csrf
                <textarea name="announcement_message" class="form-control" rows="5" placeholder="{{ translate('messages.ex_:_ABC_Company') }}">{{ $shop->announcement_message??'' }}</textarea>
                <div class="justify-content-end btn--container mt-2">
                    <button type="submit" class="btn btn--primary">{{translate('publish')}}</button>
                </div>
            </form>
        </div>
    </div> --}}
</div>
@endsection
