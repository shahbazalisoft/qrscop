@extends('layouts.admin.app')

@section('title', translate('Website Appearance'))

@push('css_or_js')
<style>
    .color-card {
        border: 1px solid #e7eaf3;
        border-radius: 10px;
        padding: 20px;
        transition: box-shadow 0.3s ease;
    }
    .color-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .color-input-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .color-input-group input[type="color"] {
        width: 50px;
        height: 40px;
        border: 2px solid #e7eaf3;
        border-radius: 8px;
        padding: 2px;
        cursor: pointer;
        background: #fff;
    }
    .color-input-group input[type="text"] {
        flex: 1;
    }
    .color-preview-bar {
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }
    .color-preview-bar .preview-btn {
        padding: 10px 24px;
        border-radius: 6px;
        border: none;
        font-weight: 600;
        cursor: default;
    }
    .color-preview-bar .preview-text {
        margin: 0;
    }
    .preset-btn {
        border: 2px solid #e7eaf3;
        border-radius: 8px;
        padding: 8px 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fff;
    }
    .preset-btn:hover {
        border-color: #10847E;
    }
    .preset-btn .preset-colors {
        display: flex;
        gap: 4px;
        margin-bottom: 4px;
    }
    .preset-btn .preset-colors span {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: inline-block;
    }
    .preset-btn small {
        font-size: 11px;
        color: #666;
    }
</style>
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title mr-3">
            <span class="page-header-icon">
                <img src="{{ asset('public/assets/admin/img/business.png') }}" class="w--26" alt="">
            </span>
            <span>{{ translate('Website Appearance') }}</span>
        </h1>
        <p class="page-header-text">{{ translate('Customize your website homepage colors from here') }}</p>
    </div>

    <!-- Live Preview -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title"><i class="tio-eye mr-1"></i> {{ translate('Live Preview') }}</h5>
        </div>
        <div class="card-body">
            <div class="color-preview-bar" id="previewBar" style="background-color: {{ $colors['dark_bg'] }};">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="preview-text" id="previewTitle" style="color: {{ $colors['text_light'] }}; font-family: serif;">{{ \App\CentralLogics\Helpers::get_settings('business_name') }} Preview</h4>
                        <p class="preview-text mt-1" id="previewMuted" style="color: {{ $colors['text_muted'] }};">This is how your website will look</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="preview-btn" id="previewBtn" style="background-color: {{ $colors['primary_color'] }}; color: {{ $colors['text_light'] }};">Get Started</span>
                        <span class="preview-btn" id="previewBtnOutline" style="background-color: transparent; color: {{ $colors['text_light'] }}; border: 2px solid {{ $colors['text_light'] }};">Learn More</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Color Presets -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title"><i class="tio-color-bucket mr-1"></i> {{ translate('Quick Presets') }}</h5>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-3">
                <button type="button" class="preset-btn" onclick="applyPreset('default')">
                    <div class="preset-colors">
                        <span style="background:#10847E"></span>
                        <span style="background:#0d0d0d"></span>
                        <span style="background:#1a1a2e"></span>
                    </div>
                    <small>Default (Teal Dark)</small>
                </button>
                <button type="button" class="preset-btn" onclick="applyPreset('blue')">
                    <div class="preset-colors">
                        <span style="background:#2563EB"></span>
                        <span style="background:#0f172a"></span>
                        <span style="background:#1e293b"></span>
                    </div>
                    <small>Blue Dark</small>
                </button>
                <button type="button" class="preset-btn" onclick="applyPreset('purple')">
                    <div class="preset-colors">
                        <span style="background:#7C3AED"></span>
                        <span style="background:#0f0a1e"></span>
                        <span style="background:#1a1030"></span>
                    </div>
                    <small>Purple Dark</small>
                </button>
                <button type="button" class="preset-btn" onclick="applyPreset('orange')">
                    <div class="preset-colors">
                        <span style="background:#EA580C"></span>
                        <span style="background:#1a0e05"></span>
                        <span style="background:#2a1a0e"></span>
                    </div>
                    <small>Orange Dark</small>
                </button>
                <button type="button" class="preset-btn" onclick="applyPreset('green')">
                    <div class="preset-colors">
                        <span style="background:#16A34A"></span>
                        <span style="background:#0a1a0e"></span>
                        <span style="background:#0f2a18"></span>
                    </div>
                    <small>Green Dark</small>
                </button>
                <button type="button" class="preset-btn" onclick="applyPreset('red')">
                    <div class="preset-colors">
                        <span style="background:#DC2626"></span>
                        <span style="background:#1a0a0a"></span>
                        <span style="background:#2a1010"></span>
                    </div>
                    <small>Red Dark</small>
                </button>
                <button type="button" class="preset-btn" onclick="applyPreset('light')">
                    <div class="preset-colors">
                        <span style="background:#10847E"></span>
                        <span style="background:#ffffff; border:1px solid #ddd"></span>
                        <span style="background:#f8f9fa; border:1px solid #ddd"></span>
                    </div>
                    <small>Light Theme</small>
                </button>
            </div>
        </div>
    </div>

    <!-- Color Settings Form -->
    <form action="{{ route('admin.settings.general.website-appearance-update') }}" method="POST">
        @csrf
        <div class="row g-3">
            <!-- Primary Colors -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title"><i class="tio-brush mr-1"></i> {{ translate('Brand Colors') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label font-weight-bold">{{ translate('Primary Color') }}
                                    <small class="text-muted">(Buttons, links, accents)</small>
                                </label>
                                <div class="color-input-group">
                                    <input type="color" id="primary_color" name="primary_color" value="{{ $colors['primary_color'] }}" onchange="syncColor(this, 'primary_color_text'); updatePreview();">
                                    <input type="text" id="primary_color_text" class="form-control" value="{{ $colors['primary_color'] }}" onchange="syncText(this, 'primary_color'); updatePreview();">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label font-weight-bold">{{ translate('Button Hover Color') }}
                                    <small class="text-muted">(Button hover state)</small>
                                </label>
                                <div class="color-input-group">
                                    <input type="color" id="btn_hover_color" name="btn_hover_color" value="{{ $colors['btn_hover_color'] }}" onchange="syncColor(this, 'btn_hover_color_text');">
                                    <input type="text" id="btn_hover_color_text" class="form-control" value="{{ $colors['btn_hover_color'] }}" onchange="syncText(this, 'btn_hover_color');">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label font-weight-bold">{{ translate('Secondary Color') }}
                                    <small class="text-muted">(Navbar, cards)</small>
                                </label>
                                <div class="color-input-group">
                                    <input type="color" id="secondary_color" name="secondary_color" value="{{ $colors['secondary_color'] }}" onchange="syncColor(this, 'secondary_color_text'); updatePreview();">
                                    <input type="text" id="secondary_color_text" class="form-control" value="{{ $colors['secondary_color'] }}" onchange="syncText(this, 'secondary_color'); updatePreview();">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Background Colors -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title"><i class="tio-image mr-1"></i> {{ translate('Background Colors') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label font-weight-bold">{{ translate('Dark Background') }}
                                    <small class="text-muted">(Main page background)</small>
                                </label>
                                <div class="color-input-group">
                                    <input type="color" id="dark_bg" name="dark_bg" value="{{ $colors['dark_bg'] }}" onchange="syncColor(this, 'dark_bg_text'); updatePreview();">
                                    <input type="text" id="dark_bg_text" class="form-control" value="{{ $colors['dark_bg'] }}" onchange="syncText(this, 'dark_bg'); updatePreview();">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label font-weight-bold">{{ translate('Light Background') }}
                                    <small class="text-muted">(Alternate sections)</small>
                                </label>
                                <div class="color-input-group">
                                    <input type="color" id="light_bg" name="light_bg" value="{{ $colors['light_bg'] }}" onchange="syncColor(this, 'light_bg_text');">
                                    <input type="text" id="light_bg_text" class="form-control" value="{{ $colors['light_bg'] }}" onchange="syncText(this, 'light_bg');">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label font-weight-bold">{{ translate('Border Color') }}
                                    <small class="text-muted">(Borders, dividers)</small>
                                </label>
                                <div class="color-input-group">
                                    <input type="color" id="border_color" name="border_color" value="{{ $colors['border_color'] }}" onchange="syncColor(this, 'border_color_text');">
                                    <input type="text" id="border_color_text" class="form-control" value="{{ $colors['border_color'] }}" onchange="syncText(this, 'border_color');">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Text Colors -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="tio-text mr-1"></i> {{ translate('Text Colors') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label font-weight-bold">{{ translate('Light Text') }}
                                    <small class="text-muted">(Headings on dark bg)</small>
                                </label>
                                <div class="color-input-group">
                                    <input type="color" id="text_light" name="text_light" value="{{ $colors['text_light'] }}" onchange="syncColor(this, 'text_light_text'); updatePreview();">
                                    <input type="text" id="text_light_text" class="form-control" value="{{ $colors['text_light'] }}" onchange="syncText(this, 'text_light'); updatePreview();">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label font-weight-bold">{{ translate('Dark Text') }}
                                    <small class="text-muted">(Text on light bg)</small>
                                </label>
                                <div class="color-input-group">
                                    <input type="color" id="text_dark" name="text_dark" value="{{ $colors['text_dark'] }}" onchange="syncColor(this, 'text_dark_text');">
                                    <input type="text" id="text_dark_text" class="form-control" value="{{ $colors['text_dark'] }}" onchange="syncText(this, 'text_dark');">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label font-weight-bold">{{ translate('Muted Text') }}
                                    <small class="text-muted">(Descriptions, subtitles)</small>
                                </label>
                                <div class="color-input-group">
                                    <input type="color" id="text_muted" name="text_muted" value="{{ $colors['text_muted'] }}" onchange="syncColor(this, 'text_muted_text'); updatePreview();">
                                    <input type="text" id="text_muted_text" class="form-control" value="{{ $colors['text_muted'] }}" onchange="syncText(this, 'text_muted'); updatePreview();">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reset & Submit -->
            <div class="col-lg-6 d-flex align-items-end">
                <div class="card w-100">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 200px;">
                        <p class="text-center text-muted mb-4">{{ translate('Save your color changes to apply them on the website homepage') }}</p>
                        <div class="d-flex gap-3">
                            <button type="button" class="btn btn-outline-danger" onclick="applyPreset('default')">
                                <i class="tio-restore mr-1"></i> {{ translate('Reset to Default') }}
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="tio-save mr-1"></i> {{ translate('Save Changes') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('script_2')
<script>
    function syncColor(colorInput, textId) {
        document.getElementById(textId).value = colorInput.value;
    }
    function syncText(textInput, colorId) {
        var val = textInput.value;
        if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
            document.getElementById(colorId).value = val;
        }
    }
    function updatePreview() {
        var bar = document.getElementById('previewBar');
        var title = document.getElementById('previewTitle');
        var muted = document.getElementById('previewMuted');
        var btn = document.getElementById('previewBtn');
        var btnOutline = document.getElementById('previewBtnOutline');

        bar.style.backgroundColor = document.getElementById('dark_bg').value;
        title.style.color = document.getElementById('text_light').value;
        muted.style.color = document.getElementById('text_muted').value;
        btn.style.backgroundColor = document.getElementById('primary_color').value;
        btn.style.color = document.getElementById('text_light').value;
        btnOutline.style.color = document.getElementById('text_light').value;
        btnOutline.style.borderColor = document.getElementById('text_light').value;
    }

    var presets = {
        'default': {
            primary_color: '#10847E', btn_hover_color: '#0c6b66', secondary_color: '#1a1a2e',
            dark_bg: '#0d0d0d', light_bg: '#f8f9fa', border_color: '#2a2a2a',
            text_light: '#ffffff', text_dark: '#333333', text_muted: '#888888'
        },
        'blue': {
            primary_color: '#2563EB', btn_hover_color: '#1d4ed8', secondary_color: '#1e293b',
            dark_bg: '#0f172a', light_bg: '#f1f5f9', border_color: '#334155',
            text_light: '#ffffff', text_dark: '#1e293b', text_muted: '#94a3b8'
        },
        'purple': {
            primary_color: '#7C3AED', btn_hover_color: '#6d28d9', secondary_color: '#1a1030',
            dark_bg: '#0f0a1e', light_bg: '#f5f3ff', border_color: '#2e1f5e',
            text_light: '#ffffff', text_dark: '#1e1b4b', text_muted: '#a78bfa'
        },
        'orange': {
            primary_color: '#EA580C', btn_hover_color: '#c2410c', secondary_color: '#2a1a0e',
            dark_bg: '#1a0e05', light_bg: '#fff7ed', border_color: '#3d2010',
            text_light: '#ffffff', text_dark: '#431407', text_muted: '#fb923c'
        },
        'green': {
            primary_color: '#16A34A', btn_hover_color: '#15803d', secondary_color: '#0f2a18',
            dark_bg: '#0a1a0e', light_bg: '#f0fdf4', border_color: '#1a3d24',
            text_light: '#ffffff', text_dark: '#14532d', text_muted: '#86efac'
        },
        'red': {
            primary_color: '#DC2626', btn_hover_color: '#b91c1c', secondary_color: '#2a1010',
            dark_bg: '#1a0a0a', light_bg: '#fef2f2', border_color: '#3d1515',
            text_light: '#ffffff', text_dark: '#450a0a', text_muted: '#fca5a5'
        },
        'light': {
            primary_color: '#10847E', btn_hover_color: '#0c6b66', secondary_color: '#e2e8f0',
            dark_bg: '#ffffff', light_bg: '#f8f9fa', border_color: '#e2e8f0',
            text_light: '#1a202c', text_dark: '#1a202c', text_muted: '#718096'
        }
    };

    function applyPreset(name) {
        var p = presets[name];
        if (!p) return;
        Object.keys(p).forEach(function(key) {
            var colorEl = document.getElementById(key);
            var textEl = document.getElementById(key + '_text');
            if (colorEl) colorEl.value = p[key];
            if (textEl) textEl.value = p[key];
        });
        updatePreview();
    }
</script>
@endpush
