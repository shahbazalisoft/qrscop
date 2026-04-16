@extends('layouts.vendor.app')

@section('title', translate('messages.menu_template'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .template-card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
    }
    .template-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .template-card.active {
        border: 3px solid #28a745;
    }
    .template-card.active::before {
        content: '\2713';
        position: absolute;
        top: 10px;
        right: 10px;
        background: #28a745;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        z-index: 10;
    }
    .template-preview {
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    .template-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .template-preview .preview-placeholder {
        color: white;
        font-size: 3rem;
    }
    .template-info {
        padding: 20px;
    }
    .template-name {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }
    .template-actions {
        display: flex;
        gap: 10px;
    }
    .btn-activate {
        flex: 1;
        padding: 10px 15px;
        border: none;
        background: linear-gradient(135deg, #FFB703 0%, #FB8500 100%);
        color: white;
        border-radius: 8px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-activate:hover {
        opacity: 0.9;
        transform: scale(1.02);
    }
    .btn-activated {
        background: #28a745;
        cursor: default;
    }
    .current-template-badge {
        background: #28a745;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        display: inline-block;
        margin-bottom: 10px;
    }
    .menu-link-box {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        border-radius: 15px;
        padding: 25px;
        color: white;
        margin-bottom: 30px;
    }
    .menu-link-box h5 {
        color: #FFB703;
        margin-bottom: 10px;
    }
    .menu-link-box .link-display {
        background: rgba(255,255,255,0.1);
        padding: 12px 15px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 15px;
    }
    .menu-link-box .link-display > a {
        color: #FFB703;
        word-break: break-all;
    }
    .menu-link-box .link-display .copy-btn {
        color: #1a1a2e;
    }
    .menu-link-box .copy-btn {
        background: #FFB703;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        color: #1a1a2e;
        cursor: pointer;
        margin-left: 10px;
        white-space: nowrap;
    }
    .template-preview {
        position: relative;
    }
    .template-actions {
        display: flex;
        gap: 8px;
    }
    .btn-view {
        padding: 10px 15px;
        border: 1px solid #ddd;
        background: white;
        color: #333;
        border-radius: 8px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-view:hover {
        background: #f5f5f5;
        color: #333;
    }
    .btn-setting {
        padding: 10px 12px;
        border: 1px solid #ddd;
        background: white;
        color: #666;
        border-radius: 8px;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .btn-setting:hover {
        background: #f0f0f0;
        color: #333;
    }

    /* Color Settings Modal */
    .color-modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .color-modal-overlay.active {
        display: flex;
    }
    .color-modal {
        background: #fff;
        border-radius: 16px;
        width: 420px;
        max-width: 95vw;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        overflow: hidden;
    }
    .color-modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .color-modal-header h5 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
    }
    .color-modal-close {
        background: none;
        border: none;
        font-size: 1.3rem;
        cursor: pointer;
        color: #999;
        padding: 0;
        line-height: 1;
    }
    .color-modal-close:hover {
        color: #333;
    }
    .color-modal-body {
        padding: 24px;
    }
    .color-field {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        padding: 12px 14px;
        background: #f8f9fa;
        border-radius: 10px;
    }
    .color-field:last-child {
        margin-bottom: 0;
    }
    .color-field-label {
        font-weight: 500;
        color: #333;
        font-size: 0.95rem;
    }
    .color-field-label small {
        display: block;
        color: #888;
        font-weight: 400;
        font-size: 0.8rem;
        margin-top: 2px;
    }
    .color-field-input {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .color-field-input input[type="color"] {
        width: 40px;
        height: 40px;
        border: 2px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        padding: 2px;
        background: #fff;
    }
    .color-field-input input[type="text"] {
        width: 80px;
        padding: 6px 8px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.85rem;
        font-family: monospace;
        text-transform: uppercase;
    }
    .color-modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #eee;
        display: flex;
        gap: 10px;
        justify-content: space-between;
    }
    .btn-reset-colors {
        padding: 10px 18px;
        border: 1px solid #dc3545;
        background: #fff;
        color: #dc3545;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .btn-reset-colors:hover {
        background: #dc3545;
        color: #fff;
    }
    .btn-save-colors {
        padding: 10px 24px;
        border: none;
        background: linear-gradient(135deg, #FFB703 0%, #FB8500 100%);
        color: white;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-save-colors:hover {
        opacity: 0.9;
    }
    .btn-save-colors:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center py-2">
            <div class="col-sm mb-2 mb-sm-0">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('/public/assets/admin/img/grocery.svg') }}" width="38" alt="img">
                    <div class="w-0 flex-grow pl-2">
                        <h1 class="page-header-title mb-0">{{ translate('messages.menu_template') }}</h1>
                        <p class="page-header-text m-0">
                            {{ translate('messages.choose_a_beautiful_template_for_your_digital_menu') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Link Box -->
    <div class="menu-link-box">
        <h5><i class="tio-link mr-2"></i>{{ translate('messages.your_menu_link') }}</h5>
        <p class="mb-0">{{ translate('messages.share_this_link_or_generate_qr_code_for_customers') }}</p>
        <div class="link-display" style="flex-wrap: wrap;">
            <a href="{{ route('store.menu', $store->slug) }}" target="_blank" style="flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis;">
                {{ route('store.menu', $store->slug) }}
            </a>
            <div style="display: flex; gap: 8px; flex-shrink: 0;">
                <button class="copy-btn" onclick="copyMenuLink()" style="margin-left: 0;">
                    <i class="tio-copy mr-1"></i>{{ translate('messages.copy') }}
                </button>
                <a href="{{ route('store.menu', $store->slug) }}" target="_blank" class="copy-btn" style="margin-left: 0; text-decoration: none; display: inline-flex; align-items: center;">
                    <i class="tio-open-in-new mr-1"></i>{{ translate('messages.open') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="row g-4">
        @forelse($templates as $template)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="template-card {{ $currentTemplate == $template->template_id ? 'active' : '' }}">
                <div class="template-preview">
                    @if($template->template)
                    
                        <img src="{{ \App\CentralLogics\Helpers::get_full_url('menu-template', $template->template, 'public') }}"
                             alt="{{ $template->title }}"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <span class="preview-placeholder" style="display: none;"><i class="tio-image"></i></span>
                    @else
                        <span class="preview-placeholder"><i class="tio-image"></i></span>
                    @endif
                </div>
                <div class="template-info">
                    <h5 class="template-name">{{ $template->title }}</h5>
                    <div class="template-actions">
                        <a href="{{ route('store.menu.style', [$store->slug, $template->template_no]) }}" target="_blank" class="btn-view">
                            <i class="tio-open-in-new mr-1"></i>{{ translate('messages.preview') }}
                        </a>
                        @if($template->template_no == 13)
                        <button type="button" class="btn-setting" onclick="openColorSettings()">
                            <i class="tio-settings"></i>
                        </button>
                        @endif
                        @if($currentTemplate == $template->template_no)
                            <button class="btn-activate btn-activated" disabled style="flex: 1;">
                                <i class="tio-done mr-1"></i>{{ translate('messages.active') }}
                            </button>
                        @else
                            <form action="{{ route('vendor.business-settings.menu_change_status', $template->id) }}" method="post" style="flex: 1;" id="template-activate-{{ $template->id }}">
                                @csrf @method('patch')
                                <button type="button" class="btn-activate w-100 form-alert"
                                        data-id="template-activate-{{ $template->id }}"
                                        data-message="{{ translate('messages.want_to_activate_this_template') }}">
                                    {{ translate('messages.activate') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <img src="{{ asset('public/assets/admin/svg/illustrations/sorry.svg') }}" alt="" width="100">
                <h5 class="mt-3">{{ translate('messages.no_templates_available') }}</h5>
                <p class="text-muted">{{ translate('messages.contact_admin_to_add_templates') }}</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Color Settings Modal -->
@php
    $menuColors = $store->menu_colors;
    $defaultColors = ['accent' => '#10847e', 'bg' => '#f5f5f7', 'surface' => '#ffffff', 'text' => '#1a1a2e'];
@endphp
<div class="color-modal-overlay" id="colorModal">
    <div class="color-modal">
        <div class="color-modal-header">
            <h5><i class="tio-color-bucket mr-2"></i>{{ translate('messages.menu_colors') }}</h5>
            <button class="color-modal-close" onclick="closeColorSettings()">&times;</button>
        </div>
        <div class="color-modal-body">
            <div class="color-field">
                <div class="color-field-label">
                    {{ translate('messages.accent_color') }}
                    <small>{{ translate('messages.buttons_links_active_states') }}</small>
                </div>
                <div class="color-field-input">
                    <input type="color" id="color-accent" value="{{ $menuColors['accent'] ?? $defaultColors['accent'] }}" onchange="syncColorText(this, 'text-accent')">
                    <input type="text" id="text-accent" value="{{ $menuColors['accent'] ?? $defaultColors['accent'] }}" onchange="syncColorPicker(this, 'color-accent')">
                </div>
            </div>
            <div class="color-field">
                <div class="color-field-label">
                    {{ translate('messages.background_color') }}
                    <small>{{ translate('messages.page_background') }}</small>
                </div>
                <div class="color-field-input">
                    <input type="color" id="color-bg" value="{{ $menuColors['bg'] ?? $defaultColors['bg'] }}" onchange="syncColorText(this, 'text-bg')">
                    <input type="text" id="text-bg" value="{{ $menuColors['bg'] ?? $defaultColors['bg'] }}" onchange="syncColorPicker(this, 'color-bg')">
                </div>
            </div>
            <div class="color-field">
                <div class="color-field-label">
                    {{ translate('messages.card_color') }}
                    <small>{{ translate('messages.cards_nav_surfaces') }}</small>
                </div>
                <div class="color-field-input">
                    <input type="color" id="color-surface" value="{{ $menuColors['surface'] ?? $defaultColors['surface'] }}" onchange="syncColorText(this, 'text-surface')">
                    <input type="text" id="text-surface" value="{{ $menuColors['surface'] ?? $defaultColors['surface'] }}" onchange="syncColorPicker(this, 'color-surface')">
                </div>
            </div>
            <div class="color-field">
                <div class="color-field-label">
                    {{ translate('messages.text_color') }}
                    <small>{{ translate('messages.primary_text') }}</small>
                </div>
                <div class="color-field-input">
                    <input type="color" id="color-text" value="{{ $menuColors['text'] ?? $defaultColors['text'] }}" onchange="syncColorText(this, 'text-text')">
                    <input type="text" id="text-text" value="{{ $menuColors['text'] ?? $defaultColors['text'] }}" onchange="syncColorPicker(this, 'color-text')">
                </div>
            </div>
        </div>
        <div class="color-modal-footer">
            <button type="button" class="btn-reset-colors" onclick="resetColors()">
                <i class="tio-restore mr-1"></i>{{ translate('messages.reset_default') }}
            </button>
            <button type="button" class="btn-save-colors" id="btnSaveColors" onclick="saveColors()">
                <i class="tio-save mr-1"></i>{{ translate('messages.save') }}
            </button>
        </div>
    </div>
</div>

@endsection

@push('script_2')
<script>
    function copyMenuLink() {
        const link = "{{ route('store.menu', $store->slug) }}";

        // Try modern clipboard API first
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(link).then(function() {
                toastr.success('{{ translate("messages.link_copied_to_clipboard") }}');
            }).catch(function() {
                fallbackCopyText(link);
            });
        } else {
            // Fallback for HTTP or older browsers
            fallbackCopyText(link);
        }
    }

    // Color Settings
    function openColorSettings() {
        document.getElementById('colorModal').classList.add('active');
    }

    function closeColorSettings() {
        document.getElementById('colorModal').classList.remove('active');
    }

    // Close modal on overlay click
    document.getElementById('colorModal').addEventListener('click', function(e) {
        if (e.target === this) closeColorSettings();
    });

    function syncColorText(colorInput, textId) {
        document.getElementById(textId).value = colorInput.value;
    }

    function syncColorPicker(textInput, colorId) {
        var val = textInput.value.trim();
        if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
            document.getElementById(colorId).value = val;
        }
    }

    function saveColors() {
        var btn = document.getElementById('btnSaveColors');
        btn.disabled = true;
        btn.innerHTML = '<i class="tio-loading tio-spin mr-1"></i>{{ translate("messages.saving") }}';

        $.ajax({
            url: "{{ route('vendor.business-settings.menu_save_colors') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                accent: document.getElementById('color-accent').value,
                bg: document.getElementById('color-bg').value,
                surface: document.getElementById('color-surface').value,
                text: document.getElementById('color-text').value,
            },
            success: function(res) {
                toastr.success(res.message || '{{ translate("messages.colors_saved_successfully") }}');
                closeColorSettings();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || '{{ translate("messages.something_went_wrong") }}');
            },
            complete: function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="tio-save mr-1"></i>{{ translate("messages.save") }}';
            }
        });
    }

    function resetColors() {
        if (!confirm('{{ translate("messages.reset_colors_confirmation") }}')) return;

        var defaults = {accent: '#10847e', bg: '#f5f5f7', surface: '#ffffff', text: '#1a1a2e'};

        $.ajax({
            url: "{{ route('vendor.business-settings.menu_reset_colors') }}",
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                // Reset UI pickers to defaults
                ['accent', 'bg', 'surface', 'text'].forEach(function(key) {
                    document.getElementById('color-' + key).value = defaults[key];
                    document.getElementById('text-' + key).value = defaults[key];
                });
                toastr.success(res.message || '{{ translate("messages.colors_reset_successfully") }}');
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || '{{ translate("messages.something_went_wrong") }}');
            }
        });
    }

    function fallbackCopyText(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        textArea.style.top = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            document.execCommand('copy');
            toastr.success('{{ translate("messages.link_copied_to_clipboard") }}');
        } catch (err) {
            toastr.error('{{ translate("messages.failed_to_copy") }}');
        }

        document.body.removeChild(textArea);
    }

</script>
@endpush
