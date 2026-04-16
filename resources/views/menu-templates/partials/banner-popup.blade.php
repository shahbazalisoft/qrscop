<div class="banner-popup-overlay">
    <div class="banner-popup">
        <button class="banner-popup-close"><i class="bi bi-x-lg"></i></button>

        <!-- Style 1: Image Only Banner -->
        @if ($store->banner_popup_type == 1 && !empty($store->banner_popup))
            <div class="banner-popup-image">
                <img src="{{$store->banner_popup_full_url}}"
                    alt="Special Offer">
            </div>
        @endif
        <!-- Style 2: Image + Offer Text Banner -->
        @if ($store->banner_popup_type == 2 && isset($store->text_banner_popup))
            @if(isset($store->text_banner_popup['image']) && !empty($store->text_banner_popup['image']))
            <div class="banner-popup-image">
                <img src="{{ $store->text_banner_image_full_url}}"alt="Special Offer">
            </div>
            @endif
            <div class="banner-popup-offer">
                @if(isset($store->text_banner_popup['heading']) && !empty($store->text_banner_popup['heading']))<span class="banner-popup-badge">{{ $store->text_banner_popup['heading'] ?? '' }}</span>@endif
                @if(isset($store->text_banner_popup['title']) && !empty($store->text_banner_popup['title']))<h2 class="banner-popup-title">{{ $store->text_banner_popup['title'] ?? '' }}</h2>@endif
                @if(isset($store->text_banner_popup['description']) && !empty($store->text_banner_popup['description']))<p class="banner-popup-desc">{{ $store->text_banner_popup['description'] ?? '' }}</p>@endif
                @if(isset($store->text_banner_popup['label']) && !empty($store->text_banner_popup['label']))<span class="banner-popup-code">{{ $store->text_banner_popup['label'] ?? '' }}</span>@endif
            </div>
        @endif
    </div>
</div>
