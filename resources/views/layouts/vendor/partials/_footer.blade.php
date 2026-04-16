@php
    $vendorData = \App\CentralLogics\Helpers::get_store_data();
    $title = $vendorData?->module_type == 'rental' && addon_published_status('Rental') ? 'Provider' : 'Store';
@endphp
<div class="footer">
    <div class="row justify-content-between align-items-center">
        <div class="col">
            <p class="font-size-sm mb-0">
                &copy; {{Str::limit(\App\CentralLogics\Helpers::get_store_data()->name, 50, '...')}}. <span
                    class="d-none d-sm-inline-block"></span>
            </p>
        </div>
    </div>
</div>
