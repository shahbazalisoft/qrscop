@extends('layouts.vendor.app')

@section('title', translate('messages.qr_setup'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <?php
        $store = \App\CentralLogics\Helpers::get_store_data();
        $menuUrl = url($store->slug . '/menu');
        $storeLogo = $store->logo_full_url ?? asset('/public/assets/admin/img/100x100/food-default-image.png');
        $activeQr = $get_qr->where('status', 1)->first();
        $hasGeneratedQr = ($activeQr && $activeQr->qr_scanner) ? true : false;
        $qrImg = $hasGeneratedQr
            ? \App\CentralLogics\Helpers::get_full_url('qrcodes', $activeQr->qr_scanner, 'public')
            : asset('public/assets/admin/img/default-qr.svg');
        $activeTemplate = $store->qr_template ?? 1;
        $foodImg1 = $store->qr_food_image_1_full_url ?? asset('public/assets/admin/img/qr-dummy/burger.svg');
        $foodImg2 = $store->qr_food_image_2_full_url ?? asset('public/assets/admin/img/qr-dummy/pizza.svg');
        $foodImg3 = $store->qr_food_image_3_full_url ?? asset('public/assets/admin/img/qr-dummy/cake.svg');
        $foodImg4 = $store->qr_food_image_4_full_url ?? asset('public/assets/admin/img/qr-dummy/salad.svg');
    ?>
    <style>
        /* ====== Menu URL Section ====== */
        .url-input-group {
            display: flex; align-items: center; background: #f8f9fa;
            border: 2px solid #e8e8e8; border-radius: 8px; padding: 4px; gap: 4px;
        }
        .url-input-group input {
            flex: 1; border: none; background: transparent; padding: 10px 14px;
            font-size: 14px; color: #334257; font-weight: 500; outline: none;
        }
        .url-input-group .btn-copy {
            background: #334257; color: #fff; border: none; border-radius: 6px;
            padding: 8px 16px; font-size: 13px; font-weight: 600; cursor: pointer;
            white-space: nowrap; transition: all 0.3s;
        }
        .url-input-group .btn-copy:hover { background: #1a2635; }
        .url-warning {
            background: #fff8e6; border: 1px solid #ffe0a0; border-radius: 8px;
            padding: 12px 16px; display: flex; align-items: flex-start; gap: 10px; margin-top: 16px;
        }
        .url-warning i { color: #ffa800; font-size: 20px; flex-shrink: 0; margin-top: 2px; }
        .url-warning p { margin: 0; font-size: 13px; color: #6b6b6b; line-height: 1.5; }

        /* ====== Slug Input Row ====== */
        .slug-input-row {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            border: 2px solid #e8e8e8;
            border-radius: 8px;
            padding: 4px;
            gap: 0;
            overflow: hidden;
        }
        .slug-input-row .slug-prefix {
            padding: 10px 2px 10px 14px;
            font-size: 13px;
            color: #999;
            white-space: nowrap;
            font-weight: 500;
            user-select: none;
        }
        .slug-input-row input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 10px 4px;
            font-size: 14px;
            color: #334257;
            font-weight: 600;
            outline: none;
            min-width: 80px;
        }
        .slug-input-row .slug-suffix {
            padding: 10px 8px 10px 2px;
            font-size: 13px;
            color: #999;
            white-space: nowrap;
            font-weight: 500;
            user-select: none;
        }
        .btn-generate {
            background: #00AA96;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }
        .btn-generate:hover { background: #009985; }
        @media (max-width: 576px) {
            .slug-input-row { flex-wrap: wrap; }
            .slug-input-row .slug-prefix { padding: 8px 2px 0 10px; font-size: 11px; }
            .slug-input-row .slug-suffix { padding: 0 6px 8px 0; font-size: 11px; }
            .slug-input-row input { width: 100%; padding: 4px 4px 8px; }
            .btn-generate { width: 100%; justify-content: center; margin: 4px; }
        }

        /* ====== QR Tabs ====== */
        .qr-tabs {
            display: flex; gap: 0; border-bottom: 2px solid #e8e8e8;
            margin: 16px 0 24px; overflow-x: auto; -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .qr-tabs::-webkit-scrollbar { display: none; }
        .qr-tab-btn {
            padding: 12px 24px; font-size: 14px; font-weight: 600;
            color: #999; position: relative; transition: all 0.3s;
            text-decoration: none; display: inline-flex; align-items: center;
            white-space: nowrap; flex-shrink: 0;
        }
        .qr-tab-btn:hover { color: #334257; text-decoration: none; }
        .qr-tab-btn.active { color: #00AA96; }
        .qr-tab-btn.active::after {
            content: ''; position: absolute; bottom: -2px; left: 0; right: 0;
            height: 2px; background: #00AA96;
        }
        @media (max-width: 576px) {
            .qr-tab-btn { padding: 10px 14px; font-size: 13px; }
        }

        /* ====== Active Template Tab ====== */
        .active-tpl-wrapper {
            display: flex; gap: 30px; align-items: flex-start; flex-wrap: wrap;
        }
        .active-tpl-card {
            max-width: 340px; flex-shrink: 0; margin: 0 auto;
        }
        .active-tpl-info {
            flex: 1; min-width: 250px;
        }
        .active-qr-preview {
            display: inline-block; padding: 14px;
            background: #fff; border: 2px solid #e8e8e8; border-radius: 14px;
        }
        .active-qr-preview img {
            width: 160px; height: 160px; object-fit: contain; display: block;
        }
        .active-qr-info .gap-2 { gap: 8px; }
        .active-qr-info .gap-3 { gap: 12px; }
        @media (max-width: 991px) {
            .active-tab-row { flex-direction: column-reverse; }
            .active-tpl-card { max-width: 100%; width: 100%; }
            .active-tpl-card .qr-tpl { max-width: 340px; margin: 0 auto; }
        }

        /* ====== QR Templates Grid ====== */
        .qr-templates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
            padding: 10px 0;
        }
        .qr-tpl {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            position: relative;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            min-height: 620px;
        }
        .qr-tpl:hover { transform: translateY(-4px); box-shadow: 0 8px 36px rgba(0,0,0,0.16); }
        .qr-tpl-actions {
            position: absolute; top: 12px; right: 12px; z-index: 5;
            display: flex; gap: 6px;
        }
        .qr-tpl-actions .btn { width: 34px; height: 34px; border-radius: 50%; padding: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
        .qr-tpl.active-template { outline: 3px solid #00AA96; outline-offset: -3px; }
        .qr-tpl .tpl-active-badge {
            display: none; position: absolute; top: 12px; left: 12px; z-index: 6;
            background: #00AA96; color: #fff; font-size: 10px; font-weight: 700;
            padding: 4px 10px; border-radius: 6px; letter-spacing: 0.5px;
        }
        .qr-tpl.active-template .tpl-active-badge { display: none; }
        .qr-tpl .tpl-use-btn {
            display: block; width: 100%; padding: 10px; border: none;
            font-size: 13px; font-weight: 700; cursor: pointer;
            text-align: center; transition: all 0.3s;
            background: #334257; color: #fff; letter-spacing: 0.5px;
        }
        .qr-tpl .tpl-use-btn:hover { background: #1a2635; }
        .qr-tpl.active-template .tpl-use-btn {
            background: #00AA96; cursor: default;
        }

        /* -- STYLE 1: Dark Luxury -- */
        .qr-tpl-1 { background: #0d0d0d; color: #fff; }
        .qr-tpl-1 .tpl-hero {
            height: 160px; background: linear-gradient(180deg, rgba(0,0,0,0) 0%, #0d0d0d 100%),
            url('{{ $foodImg1 }}') center/cover; position: relative;
        }
        .qr-tpl-1 .tpl-hero-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, #0d0d0d 90%);
        }
        .qr-tpl-1 .tpl-body { padding: 0 24px 24px; text-align: center; flex: 1; display: flex; flex-direction: column; justify-content: center; }
        .qr-tpl-1 .tpl-brand {
            display: flex; flex-direction: column; align-items: center;
            margin-top: -30px; position: relative; z-index: 2;
        }
        .qr-tpl-1 .tpl-logo {
            width: 56px; height: 56px; border-radius: 50%; border: 3px solid #c8a45a;
            object-fit: cover; background: #1a1a1a; margin-bottom: 8px;
        }
        .qr-tpl-1 .tpl-name {
            font-size: 22px; font-weight: 800; letter-spacing: 1px;
            font-family: 'Georgia', serif; color: #c8a45a; margin: 0;
        }
        .qr-tpl-1 .tpl-sub { font-size: 10px; letter-spacing: 3px; text-transform: uppercase; color: #888; margin: 4px 0 0; }
        .qr-tpl-1 .tpl-line { width: 60px; height: 2px; background: #c8a45a; margin: 14px auto; }
        .qr-tpl-1 .tpl-foods {
            display: flex; justify-content: center; gap: 12px; margin: 16px 0;
        }
        .qr-tpl-1 .tpl-food-circle {
            width: 60px; height: 60px; border-radius: 50%; border: 2px solid #c8a45a;
            overflow: hidden; background: #1a1a1a;
        }
        .qr-tpl-1 .tpl-food-circle img { width: 100%; height: 100%; object-fit: cover; }
        .qr-tpl-1 .tpl-qr-area { margin: 14px 0; }
        .qr-tpl-1 .tpl-qr-label { font-size: 11px; letter-spacing: 2px; color: #c8a45a; text-transform: uppercase; margin-bottom: 10px; }
        .qr-tpl-1 .tpl-qr-box {
            display: inline-block; padding: 10px; background: #fff; border-radius: 12px;
        }
        .qr-tpl-1 .tpl-qr-box img { width: 130px; height: 130px; display: block; }
        .qr-tpl-1 .tpl-footer-info { font-size: 10px; color: #555; margin-top: 12px; }

        /* -- STYLE 2: Emerald Nature -- */
        .qr-tpl-2 { background: #0b1a0f; color: #fff; overflow: hidden; }
        .qr-tpl-2 .tpl-leaf-bg {
            position: absolute; inset: 0; z-index: 0; opacity: 0.06;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M30 5 Q40 20 30 35 Q20 20 30 5Z' fill='%2300ff88'/%3E%3Cpath d='M10 30 Q20 45 10 55 Q5 45 10 30Z' fill='%2300ff88'/%3E%3Cpath d='M50 25 Q55 40 50 50 Q42 38 50 25Z' fill='%2300ff88'/%3E%3C/svg%3E") repeat;
        }
        .qr-tpl-2 .tpl-body { padding: 30px 24px; text-align: center; position: relative; z-index: 1; flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; }
        .qr-tpl-2 .tpl-brand {
            display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 4px;
        }
        .qr-tpl-2 .tpl-logo {
            width: 52px; height: 52px; border-radius: 50%; object-fit: cover;
            border: 3px solid #22c55e; background: #0b1a0f; flex-shrink: 0;
        }
        .qr-tpl-2 .tpl-name {
            font-size: 24px; font-weight: 800; color: #fff; margin: 0;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        .qr-tpl-2 .tpl-tagline {
            display: inline-block; background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff; padding: 5px 20px; border-radius: 20px;
            font-size: 10px; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; margin: 10px 0 0;
        }
        .qr-tpl-2 .tpl-divider {
            width: 40px; height: 3px; background: #22c55e; margin: 18px auto; border-radius: 2px;
        }
        .qr-tpl-2 .tpl-food-row {
            display: flex; justify-content: center; gap: 14px; margin: 16px 0 20px;
        }
        .qr-tpl-2 .tpl-food-card { width: 80px; text-align: center; }
        .qr-tpl-2 .tpl-food-card .food-wrap {
            width: 68px; height: 68px; border-radius: 50%; overflow: hidden;
            border: 2px solid #22c55e; margin: 0 auto 6px;
            box-shadow: 0 0 15px rgba(34,197,94,0.25);
        }
        .qr-tpl-2 .tpl-food-card .food-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .qr-tpl-2 .tpl-food-card .fname {
            font-size: 10px; color: #86efac; font-weight: 600; letter-spacing: 1px;
        }
        .qr-tpl-2 .tpl-qr-wrap {
            background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.2);
            border-radius: 16px; padding: 16px; margin: 0 auto; display: inline-block;
        }
        .qr-tpl-2 .tpl-scan-label {
            font-size: 11px; color: #22c55e; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; margin-bottom: 10px;
        }
        .qr-tpl-2 .tpl-qr-box {
            display: inline-block; padding: 10px; background: #fff; border-radius: 12px;
        }
        .qr-tpl-2 .tpl-qr-box img { width: 130px; height: 130px; display: block; }
        .qr-tpl-2 .tpl-footer { font-size: 9px; color: #3a5a40; margin-top: 16px; letter-spacing: 1px; }

        /* -- STYLE 3: Sunset Warm -- */
        .qr-tpl-3 { background: linear-gradient(170deg, #1a1a2e 0%, #16213e 40%, #0f3460 100%); color: #fff; }
        .qr-tpl-3 .tpl-accent-bar {
            height: 5px; background: linear-gradient(90deg, #ff6b35, #ffd700, #ff6b35);
        }
        .qr-tpl-3 .tpl-body { padding: 28px 24px; text-align: center; flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; }
        .qr-tpl-3 .tpl-brand {
            display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 4px;
        }
        .qr-tpl-3 .tpl-logo {
            width: 52px; height: 52px; border-radius: 50%; object-fit: cover;
            border: 3px solid #ff6b35; flex-shrink: 0;
        }
        .qr-tpl-3 .tpl-name {
            font-size: 24px; font-weight: 800; margin: 0;
            background: linear-gradient(90deg, #ffd700, #ff6b35);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .qr-tpl-3 .tpl-sub {
            font-size: 10px; color: rgba(255,255,255,0.5); letter-spacing: 3px;
            text-transform: uppercase; margin: 6px 0 0;
        }
        .qr-tpl-3 .tpl-ornament {
            display: flex; align-items: center; justify-content: center; gap: 10px; margin: 18px 0;
        }
        .qr-tpl-3 .tpl-ornament span { width: 35px; height: 1px; background: linear-gradient(90deg, transparent, #ff6b35); }
        .qr-tpl-3 .tpl-ornament span:last-child { background: linear-gradient(90deg, #ff6b35, transparent); }
        .qr-tpl-3 .tpl-ornament i { color: #ffd700; font-size: 12px; }
        .qr-tpl-3 .tpl-food-strip {
            display: flex; justify-content: center; gap: 12px; margin: 0 0 20px;
        }
        .qr-tpl-3 .tpl-food-item {
            width: 80px; text-align: center;
        }
        .qr-tpl-3 .tpl-food-item .food-ring {
            width: 64px; height: 64px; border-radius: 50%; overflow: hidden;
            border: 2px solid rgba(255,107,53,0.6); margin: 0 auto 6px;
            background: rgba(255,255,255,0.05);
            box-shadow: 0 0 12px rgba(255,107,53,0.15);
        }
        .qr-tpl-3 .tpl-food-item .food-ring img { width: 100%; height: 100%; object-fit: cover; }
        .qr-tpl-3 .tpl-food-item .item-label {
            font-size: 9px; color: #ffd700; font-weight: 600; letter-spacing: 1px;
        }
        .qr-tpl-3 .tpl-scan-msg {
            font-size: 13px; font-weight: 700; color: #fff; margin: 0 0 4px;
            letter-spacing: 1px; text-transform: uppercase;
        }
        .qr-tpl-3 .tpl-scan-sub { font-size: 10px; color: rgba(255,255,255,0.4); margin-bottom: 14px; }
        .qr-tpl-3 .tpl-qr-box {
            display: inline-block; padding: 10px; background: #fff; border-radius: 12px;
            box-shadow: 0 0 20px rgba(255,107,53,0.15);
        }
        .qr-tpl-3 .tpl-qr-box img { width: 130px; height: 130px; display: block; }
        .qr-tpl-3 .tpl-footer {
            font-size: 9px; color: rgba(255,255,255,0.25); margin-top: 16px; letter-spacing: 1px;
        }

        /* -- STYLE 4: Modern Gradient -- */
        .qr-tpl-4 { background: linear-gradient(160deg, #0f0c29 0%, #302b63 50%, #24243e 100%); color: #fff; }
        .qr-tpl-4 .tpl-glow {
            position: absolute; width: 200px; height: 200px; border-radius: 50%;
            background: radial-gradient(circle, rgba(108,99,255,0.3) 0%, transparent 70%);
            top: -60px; right: -40px; z-index: 0;
        }
        .qr-tpl-4 .tpl-body { padding: 28px 24px; text-align: center; position: relative; z-index: 1; flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; }
        .qr-tpl-4 .tpl-logo {
            width: 56px; height: 56px; border-radius: 50%; object-fit: cover;
            border: 2px solid rgba(108,99,255,0.5); margin-bottom: 10px;
        }
        .qr-tpl-4 .tpl-name {
            font-size: 20px; font-weight: 800; margin: 0;
            background: linear-gradient(90deg, #a78bfa, #6c63ff, #38bdf8);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .qr-tpl-4 .tpl-sub { font-size: 11px; color: rgba(255,255,255,0.5); letter-spacing: 2px; text-transform: uppercase; margin: 4px 0 0; }
        .qr-tpl-4 .tpl-pills {
            display: flex; justify-content: center; gap: 8px; margin: 18px 0; flex-wrap: wrap;
        }
        .qr-tpl-4 .tpl-pill {
            background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px; padding: 6px 14px; font-size: 11px; color: rgba(255,255,255,0.7);
            display: flex; align-items: center; gap: 5px;
        }
        .qr-tpl-4 .tpl-pill img {
            width: 22px; height: 22px; border-radius: 50%; object-fit: cover;
        }
        .qr-tpl-4 .tpl-scan-area {
            background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px; padding: 18px; margin: 16px 0;
        }
        .qr-tpl-4 .tpl-scan-label { font-size: 12px; color: #a78bfa; letter-spacing: 1px; margin-bottom: 10px; text-transform: uppercase; }
        .qr-tpl-4 .tpl-qr-box {
            display: inline-block; padding: 10px; background: #fff; border-radius: 12px;
        }
        .qr-tpl-4 .tpl-qr-box img { width: 130px; height: 130px; display: block; }
        .qr-tpl-4 .tpl-url { font-size: 10px; color: rgba(255,255,255,0.3); margin-top: 8px; word-break: break-all; }
        .qr-tpl-4 .tpl-footer { font-size: 10px; color: rgba(255,255,255,0.2); margin-top: 14px; }

        /* -- STYLE 5: Fast Food Poster -- */
        .qr-tpl-5 { background: #0a0a0a; color: #fff; }
        .qr-tpl-5 .tpl-hero {
            height: 200px; position: relative; overflow: hidden;
            background: linear-gradient(180deg, transparent 40%, #0a0a0a 100%);
        }
        .qr-tpl-5 .tpl-hero img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .qr-tpl-5 .tpl-hero-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0) 30%, #0a0a0a 95%);
        }
        .qr-tpl-5 .tpl-red-stripe {
            height: 4px; background: linear-gradient(90deg, transparent, #d32f2f, #d32f2f, transparent);
            margin: 0;
        }
        .qr-tpl-5 .tpl-body { padding: 0 20px 24px; text-align: center; margin-top: -40px; position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; }
        .qr-tpl-5 .tpl-brand {
            display: flex; align-items: center; justify-content: center; gap: 12px;
        }
        .qr-tpl-5 .tpl-logo {
            width: 50px; height: 50px; border-radius: 50%; object-fit: cover;
            border: 3px solid #d32f2f; background: #1a1a1a; flex-shrink: 0;
        }
        .qr-tpl-5 .tpl-name {
            font-size: 32px; font-weight: 900; margin: 0;
            font-family: 'Georgia', 'Times New Roman', serif;
            font-style: italic;
            color: #fff;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
            line-height: 1.1;
        }
        .qr-tpl-5 .tpl-tagline {
            display: inline-block; background: #d32f2f; color: #fff;
            padding: 4px 18px; font-size: 10px; font-weight: 700;
            letter-spacing: 3px; text-transform: uppercase;
            margin: 8px 0 0; border-radius: 2px;
        }
        .qr-tpl-5 .tpl-section-title {
            font-size: 14px; font-weight: 800; color: #fff;
            letter-spacing: 1px; margin: 20px 0 2px;
            text-transform: uppercase;
        }
        .qr-tpl-5 .tpl-section-sub {
            font-size: 10px; color: #888; text-transform: uppercase;
            letter-spacing: 2px; margin: 0 0 14px;
        }
        .qr-tpl-5 .tpl-section-line {
            width: 50px; height: 2px; background: #d32f2f; margin: 4px auto 14px;
        }
        .qr-tpl-5 .tpl-food-row {
            display: flex; justify-content: center; gap: 16px; margin: 0 0 18px;
        }
        .qr-tpl-5 .tpl-food-item {
            width: 80px; text-align: center;
        }
        .qr-tpl-5 .tpl-food-item .food-circle {
            width: 70px; height: 70px; border-radius: 50%;
            border: 3px solid #d32f2f; overflow: hidden;
            margin: 0 auto 6px; background: #1a1a1a;
        }
        .qr-tpl-5 .tpl-food-item .food-circle img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .qr-tpl-5 .tpl-food-item .food-name {
            font-size: 10px; font-weight: 700; color: #fff;
            font-style: italic; line-height: 1.2;
        }
        .qr-tpl-5 .tpl-qr-section { margin: 6px 0 0; }
        .qr-tpl-5 .tpl-scan-label {
            font-size: 11px; letter-spacing: 2px; color: #d32f2f;
            text-transform: uppercase; font-weight: 700; margin-bottom: 8px;
        }
        .qr-tpl-5 .tpl-qr-box {
            display: inline-block; padding: 8px; background: #fff; border-radius: 10px;
            border: 2px solid #d32f2f;
        }
        .qr-tpl-5 .tpl-qr-box img { width: 120px; height: 120px; display: block; }
        .qr-tpl-5 .tpl-footer {
            font-size: 9px; color: #555; margin-top: 10px; letter-spacing: 1px;
        }

        /* ====== Shared QR placeholder ====== */
        .qr-placeholder {
            width: 130px; height: 130px; display: flex; align-items: center;
            justify-content: center; background: #eee; border-radius: 8px;
            font-size: 11px; color: #999; text-align: center;
        }

        /* ====== Table Number Input ====== */
        .table-no-input {
            display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
        }
        .table-no-input input {
            width: 120px; border: 2px solid #e8e8e8; border-radius: 8px;
            padding: 8px 14px; font-size: 16px; font-weight: 700;
            color: #334257; text-align: center; outline: none;
            transition: border-color 0.3s;
        }
        .table-no-input input:focus { border-color: #00AA96; }
        .table-no-input .hint { font-size: 12px; color: #999; }

        /* ====== Table No Corner Ribbon ====== */
        .tpl-table-badge {
            display: none; position: absolute; top: 0; left: 0; z-index: 6;
            width: 90px; height: 90px; overflow: hidden;
        }
        .tpl-table-badge.visible { display: block; }
        .tpl-table-badge .badge-inner {
            position: absolute; top: 18px; left: -22px;
            width: 130px; text-align: center;
            transform: rotate(-45deg);
            padding: 5px 0; font-size: 11px; font-weight: 800;
            letter-spacing: 0.5px; box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        }
        /* Per-template ribbon styles */
        .qr-tpl-1 .tpl-table-badge .badge-inner {
            background: #c8a45a; color: #0d0d0d;
        }
        .qr-tpl-2 .tpl-table-badge .badge-inner {
            background: #22c55e; color: #fff;
        }
        .qr-tpl-3 .tpl-table-badge .badge-inner {
            background: linear-gradient(90deg, #ffd700, #ff6b35); color: #1a1a2e;
        }
        .qr-tpl-4 .tpl-table-badge .badge-inner {
            background: linear-gradient(90deg, #a78bfa, #6c63ff); color: #fff;
        }
        .qr-tpl-5 .tpl-table-badge .badge-inner {
            background: #d32f2f; color: #fff;
        }

        /* ====== Print ====== */
        @media print {
            body * { visibility: hidden; }
            .qr-tpl.printing, .qr-tpl.printing * { visibility: visible; }
            .qr-tpl.printing { position: absolute; left: 50%; top: 0; transform: translateX(-50%);
                box-shadow: none; width: 380px; }
            .no-print { display: none !important; }
            .tpl-active-badge { display: none !important; }
        }

        @media (max-width: 767px) {
            .qr-templates-grid { grid-template-columns: 1fr; max-width: 360px; margin: 0 auto; }
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
                            <h1 class="page-header-title mb-0">{{ translate('messages.QR_Manage_And_Generate') }}</h1>
                            <p class="page-header-text m-0">{{ translate('Manage your menu URL and QR code templates') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 1: Menu URL & Generate QR -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-1"><i class="tio-link mr-1"></i> {{ translate('Menu URL & Generate QR') }}</h5>
                <p class="text-muted mb-3" style="font-size: 13px;">{{ translate('Set your restaurant URL slug and generate QR code for your menu.') }}</p>

                <!-- Slug Input + Generate -->
                <form action="{{ route('vendor.business-settings.generate-qr') }}" method="POST" id="generateQrForm">
                    @csrf
                    <label class="mb-1" style="font-size: 13px; font-weight: 600; color: #334257;">{{ translate('Restaurant URL Slug') }}</label>
                    <div class="slug-input-row">
                        <span class="slug-prefix">{{ url('/') }}/</span>
                        <input type="text" name="slug" id="slugField" value="{{ $store->slug }}" placeholder="your-restaurant-name" required>
                        <span class="slug-suffix">/menu</span>
                        <button type="submit" class="btn-generate" onclick="return confirmGenerate()">
                            <i class="tio-qr-code mr-1"></i> {{ translate('Generate QR') }}
                        </button>
                    </div>
                </form>

                <!-- Full URL (readonly) -->
                <label class="mt-3 mb-1" style="font-size: 13px; font-weight: 600; color: #334257;">{{ translate('Your Menu URL') }}</label>
                <div class="url-input-group">
                    <input type="text" id="menuUrlField" value="{{ $menuUrl }}" readonly>
                    <button class="btn-copy" onclick="copyMenuUrl()" id="copyUrlBtn">
                        <i class="tio-copy mr-1"></i> {{ translate('Copy') }}
                    </button>
                </div>

                <div class="url-warning">
                    <i class="tio-info"></i>
                    <p><strong>{{ translate('Important:') }}</strong> {{ translate('Please set your URL slug carefully. After generating, the QR code will point to this URL. Changing it later will make old printed QR codes invalid. Contact admin if you need to change it.') }}</p>
                </div>
            </div>
        </div>

        <!-- Section 2: QR Templates (Tabbed) -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-0"><i class="tio-qr-code mr-1"></i> {{ translate('QR Menu Card Templates') }}</h5>
                <p class="text-muted mb-0" style="font-size: 13px;">{{ translate('Manage your active QR template or choose a new one.') }}</p>

                <!-- Tabs -->
                <div class="qr-tabs">
                    <a href="{{ route('vendor.business-settings.qr-setup', ['tab' => 'active']) }}" class="qr-tab-btn {{ $tab == 'active' ? 'active' : '' }}">
                        <i class="tio-checkmark-circle mr-1"></i>{{ translate('Active Template') }}
                    </a>
                    <a href="{{ route('vendor.business-settings.qr-setup', ['tab' => 'all']) }}" class="qr-tab-btn {{ $tab == 'all' ? 'active' : '' }}">
                        <i class="tio-layout mr-1"></i>{{ translate('All Templates') }}
                    </a>
                    <a href="{{ route('vendor.business-settings.qr-setup', ['tab' => 'customize']) }}" class="qr-tab-btn {{ $tab == 'customize' ? 'active' : '' }}">
                        <i class="tio-image mr-1"></i>{{ translate('Customize') }}
                    </a>
                    <a href="{{ route('vendor.business-settings.qr-setup', ['tab' => 'tables']) }}" class="qr-tab-btn {{ $tab == 'tables' ? 'active' : '' }}">
                        <i class="tio-table mr-1"></i>{{ translate('Table QR') }}
                    </a>
                </div>

                @if($tab == 'active')
                {{-- ========== TAB: Active Template ========== --}}
                @php
                    $activeTpl = $qr_templates->firstWhere('id', $activeTemplate);
                    $tableQrsList = $get_qr->whereNotNull('table_no')->sortBy('table_no');
                @endphp

                <div class="row active-tab-row">
                    {{-- ===== LEFT: Settings & QR Selection ===== --}}
                    <div class="col-lg-6 order-lg-1 order-2">
                        <div class="p-3 mb-3" style="background: #f8f9fa; border-radius: 10px; border: 1px solid #e8e8e8;">
                            <h6 class="mb-3" style="font-weight: 700; color: #334257;">
                                <i class="tio-qr-code mr-1"></i>{{ translate('Select QR Code') }}
                            </h6>

                            {{-- Radio: Default QR --}}
                            <div class="mb-2">
                                <label class="d-flex align-items-center p-2 mb-0 rounded" style="cursor: pointer; border: 2px solid #e8e8e8; background: #fff; transition: all 0.2s;">
                                    <input type="radio" name="qr_selection" value="default"
                                        data-qr-img="{{ $qrImg }}"
                                        data-table-no=""
                                        data-menu-url="{{ $menuUrl }}"
                                        checked
                                        class="mr-2 qr-radio-select">
                                    <img src="{{ $qrImg }}" alt="QR" style="width: 40px; height: 40px; border-radius: 6px; border: 1px solid #e8e8e8; padding: 2px; margin-right: 10px;">
                                    <div>
                                        <span style="font-size: 13px; font-weight: 600; color: #334257;">{{ translate('Default QR') }}</span>
                                        <span class="badge badge-success ml-1" style="font-size: 10px;">{{ translate('Main') }}</span>
                                        <br><code style="font-size: 11px;">{{ $menuUrl }}</code>
                                    </div>
                                </label>
                            </div>

                            {{-- Radio: Table QRs --}}
                            @if($tableQrsList->count() > 0)
                            <label class="mb-1 mt-3" style="font-size: 12px; font-weight: 600; color: #999; text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="tio-table mr-1"></i>{{ translate('Table QR Codes') }}
                            </label>
                            <div style="max-height: 300px; overflow-y: auto;">
                                @foreach($tableQrsList as $tqr)
                                @php
                                    $tqrImgUrl = \App\CentralLogics\Helpers::get_full_url('qrcodes', $tqr->qr_scanner, 'public');
                                    $tqrMenuUrl = url($store->slug . '/menu') . '?table=' . urlencode($tqr->table_no);
                                @endphp
                                <div class="mb-2">
                                    <label class="d-flex align-items-center p-2 mb-0 rounded" style="cursor: pointer; border: 2px solid #e8e8e8; background: #fff; transition: all 0.2s;">
                                        <input type="radio" name="qr_selection" value="table_{{ $tqr->id }}"
                                            data-qr-img="{{ $tqrImgUrl }}"
                                            data-table-no="{{ $tqr->table_no }}"
                                            data-menu-url="{{ $tqrMenuUrl }}"
                                            class="mr-2 qr-radio-select">
                                        <img src="{{ $tqrImgUrl }}" alt="QR" style="width: 40px; height: 40px; border-radius: 6px; border: 1px solid #e8e8e8; padding: 2px; margin-right: 10px;">
                                        <div>
                                            <span style="font-size: 13px; font-weight: 600; color: #334257;">{{ translate('Table') }} {{ $tqr->table_no }}</span>
                                            <br><code style="font-size: 11px;">{{ $tqrMenuUrl }}</code>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-3 mt-2" style="border: 2px dashed #e8e8e8; border-radius: 8px;">
                                <p class="mb-1 text-muted" style="font-size: 13px;"><i class="tio-table mr-1"></i>{{ translate('No table QR codes yet') }}</p>
                                <a href="{{ route('vendor.business-settings.qr-setup', ['tab' => 'tables']) }}" class="btn btn-sm btn-outline-primary" style="font-size: 12px;">
                                    <i class="tio-add mr-1"></i>{{ translate('Generate Table QR') }}
                                </a>
                            </div>
                            @endif
                        </div>

                        {{-- Info & Actions --}}
                        <div class="p-3" style="background: #fff; border-radius: 10px; border: 1px solid #e8e8e8;">
                            <h6 class="mb-2" style="font-weight: 700; color: #334257;">
                                {{ $activeTpl ? $activeTpl->name : translate('No Template Selected') }}
                                <span class="badge badge-success ml-2" style="font-size: 11px;">{{ translate('Active') }}</span>
                            </h6>
                            <p class="text-muted mb-2" style="font-size: 13px;">{{ translate('Points to:') }} <strong class="text-dark" id="activePointsToUrl">{{ $menuUrl }}</strong></p>

                            <div class="mb-3">
                                <label class="mb-1" style="font-size: 12px; font-weight: 600; color: #334257;">{{ translate('QR Code') }}</label>
                                <div class="active-qr-preview">
                                    <img src="{{ $qrImg }}" alt="QR Code" id="activeQrPreviewImg">
                                </div>
                            </div>

                            <div class="d-flex gap-2 flex-wrap">
                                @if($hasGeneratedQr)
                                <a href="{{ $qrImg }}" download class="btn btn-sm btn-outline-primary" id="downloadQrBtn"><i class="tio-download-to mr-1"></i>{{ translate('Download QR') }}</a>
                                @endif
                                <button class="btn btn-sm btn-outline-secondary" onclick="copyActiveUrl()"><i class="tio-copy mr-1"></i>{{ translate('Copy URL') }}</button>
                                <a href="{{ route('vendor.business-settings.qr-setup', ['tab' => 'all']) }}" class="btn btn-sm btn-outline-dark"><i class="tio-layout mr-1"></i>{{ translate('Change Template') }}</a>
                            </div>
                            <div class="d-flex gap-2 flex-wrap mt-3">
                                <button class="btn btn-sm btn-info" onclick="printTemplate('qrTplActive')"><i class="tio-print mr-1"></i>{{ translate('Print Template') }}</button>
                                <a href="{{ route('vendor.business-settings.download-qr-pdf') }}" class="btn btn-sm btn-success" id="downloadPdfBtn"><i class="tio-download-to mr-1"></i>{{ translate('Download PDF') }}</a>
                            </div>

                            {{-- Note --}}
                            <div class="mt-3 p-2" style="background: #fff8e6; border: 1px solid #ffe0a0; border-radius: 8px;">
                                <p class="mb-0" style="font-size: 12px; color: #6b6b6b; line-height: 1.5;">
                                    <i class="tio-info mr-1" style="color: #ffa800;"></i>
                                    {{ translate('Select a QR code above to preview it on the template. The template preview, print, and PDF download will all use the selected QR. Use "Default QR" for the main menu or select a table QR to print table-specific cards.') }}
                                </p>
                            </div>
                            @if(!$hasGeneratedQr)
                            <p class="mt-2 mb-0" style="font-size: 12px; color: #ffa800;"><i class="tio-info mr-1"></i>{{ translate('This is a default preview. Generate your QR code above to get a permanent one.') }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- ===== RIGHT: Active Template Preview ===== --}}
                    <div class="col-lg-6 order-lg-2 order-1 mb-3 mb-lg-0">
                        <div class="active-tpl-card">
                            @foreach($qr_templates as $tpl)
                                @if($tpl->id == $activeTemplate)
                                    @php $isActive = true; @endphp

                                    @if($tpl->style == 1)
                                    <div class="qr-tpl qr-tpl-1 active-template" id="qrTplActive">
                                        <div class="tpl-active-badge"><i class="tio-checkmark-circle mr-1"></i>Active</div>
                                        <div class="tpl-table-badge"><div class="badge-inner">Table <span class="table-no-val"></span></div></div>
                                        <div class="qr-tpl-actions no-print">
                                            <button class="btn btn-sm btn-light" onclick="printTemplate('qrTplActive')" title="Print"><i class="tio-print"></i></button>
                                        </div>
                                        <div class="tpl-hero"><div class="tpl-hero-overlay"></div></div>
                                        <div class="tpl-body">
                                            <div class="tpl-brand">
                                                <img src="{{ $storeLogo }}" class="tpl-logo" alt="">
                                                <h3 class="tpl-name">{{ $store->name }}</h3>
                                                <p class="tpl-sub">Enjoy Delicious Food</p>
                                            </div>
                                            <div class="tpl-line"></div>
                                            <div class="tpl-foods">
                                                <div class="tpl-food-circle"><img src="{{ $foodImg1 }}" alt="Burger"></div>
                                                <div class="tpl-food-circle"><img src="{{ $foodImg2 }}" alt="Pizza"></div>
                                                <div class="tpl-food-circle"><img src="{{ $foodImg3 }}" alt="Cake"></div>
                                            </div>
                                            <div class="tpl-qr-area">
                                                <p class="tpl-qr-label">Scan For Menu</p>
                                                <div class="tpl-qr-box"><img src="{{ $qrImg }}" alt="QR" class="tpl-qr-img"></div>
                                            </div>
                                            <div class="tpl-footer-info">
                                                @if($store->phone) {{ $store->phone }} @endif
                                                @if($store->phone && $store->address) &bull; @endif
                                                @if($store->address) {{ Str::limit($store->address, 35) }} @endif
                                            </div>
                                        </div>
                                    </div>

                                    @elseif($tpl->style == 2)
                                    <div class="qr-tpl qr-tpl-2 active-template" id="qrTplActive">
                                        <div class="tpl-active-badge"><i class="tio-checkmark-circle mr-1"></i>Active</div>
                                        <div class="tpl-table-badge"><div class="badge-inner">Table <span class="table-no-val"></span></div></div>
                                        <div class="qr-tpl-actions no-print">
                                            <button class="btn btn-sm btn-light" onclick="printTemplate('qrTplActive')" title="Print"><i class="tio-print"></i></button>
                                        </div>
                                        <div class="tpl-leaf-bg"></div>
                                        <div class="tpl-body">
                                            <div class="tpl-brand">
                                                <img src="{{ $storeLogo }}" class="tpl-logo" alt="">
                                                <h3 class="tpl-name">{{ $store->name }}</h3>
                                            </div>
                                            <div class="tpl-tagline">Fresh & Delicious</div>
                                            <div class="tpl-divider"></div>
                                            <div class="tpl-food-row">
                                                <div class="tpl-food-card"><div class="food-wrap"><img src="{{ $foodImg4 }}" alt="Salad"></div><span class="fname">Starters</span></div>
                                                <div class="tpl-food-card"><div class="food-wrap"><img src="{{ $foodImg1 }}" alt="Burger"></div><span class="fname">Mains</span></div>
                                                <div class="tpl-food-card"><div class="food-wrap"><img src="{{ $foodImg3 }}" alt="Cake"></div><span class="fname">Desserts</span></div>
                                            </div>
                                            <div class="tpl-qr-wrap">
                                                <p class="tpl-scan-label">Scan For Menu</p>
                                                <div class="tpl-qr-box"><img src="{{ $qrImg }}" alt="QR" class="tpl-qr-img"></div>
                                            </div>
                                            <p class="tpl-footer">
                                                @if($store->phone) {{ $store->phone }} @endif
                                                @if($store->phone && $store->address) &bull; @endif
                                                @if($store->address) {{ Str::limit($store->address, 35) }} @endif
                                            </p>
                                        </div>
                                    </div>

                                    @elseif($tpl->style == 3)
                                    <div class="qr-tpl qr-tpl-3 active-template" id="qrTplActive">
                                        <div class="tpl-active-badge"><i class="tio-checkmark-circle mr-1"></i>Active</div>
                                        <div class="tpl-table-badge"><div class="badge-inner">Table <span class="table-no-val"></span></div></div>
                                        <div class="qr-tpl-actions no-print">
                                            <button class="btn btn-sm btn-light" onclick="printTemplate('qrTplActive')" title="Print"><i class="tio-print"></i></button>
                                        </div>
                                        <div class="tpl-accent-bar"></div>
                                        <div class="tpl-body">
                                            <div class="tpl-brand">
                                                <img src="{{ $storeLogo }}" class="tpl-logo" alt="">
                                                <h3 class="tpl-name">{{ $store->name }}</h3>
                                            </div>
                                            <p class="tpl-sub">Restaurant & Cafe</p>
                                            <div class="tpl-ornament"><span></span><i class="tio-star"></i><span></span></div>
                                            <div class="tpl-food-strip">
                                                <div class="tpl-food-item"><div class="food-ring"><img src="{{ $foodImg2 }}" alt="Pizza"></div><span class="item-label">Appetizers</span></div>
                                                <div class="tpl-food-item"><div class="food-ring"><img src="{{ $foodImg1 }}" alt="Burger"></div><span class="item-label">Entrees</span></div>
                                                <div class="tpl-food-item"><div class="food-ring"><img src="{{ $foodImg3 }}" alt="Cake"></div><span class="item-label">Desserts</span></div>
                                            </div>
                                            <p class="tpl-scan-msg">Scan Our Menu</p>
                                            <p class="tpl-scan-sub">Point your camera at the QR code</p>
                                            <div class="tpl-qr-box"><img src="{{ $qrImg }}" alt="QR" class="tpl-qr-img"></div>
                                            <p class="tpl-footer">
                                                @if($store->phone) {{ $store->phone }} @endif
                                                @if($store->phone && $store->address) &bull; @endif
                                                @if($store->address) {{ Str::limit($store->address, 35) }} @endif
                                            </p>
                                        </div>
                                        <div class="tpl-accent-bar"></div>
                                    </div>

                                    @elseif($tpl->style == 4)
                                    <div class="qr-tpl qr-tpl-4 active-template" id="qrTplActive">
                                        <div class="tpl-active-badge"><i class="tio-checkmark-circle mr-1"></i>Active</div>
                                        <div class="tpl-table-badge"><div class="badge-inner">Table <span class="table-no-val"></span></div></div>
                                        <div class="qr-tpl-actions no-print">
                                            <button class="btn btn-sm btn-light" onclick="printTemplate('qrTplActive')" title="Print"><i class="tio-print"></i></button>
                                        </div>
                                        <div class="tpl-glow"></div>
                                        <div class="tpl-body">
                                            <img src="{{ $storeLogo }}" class="tpl-logo" alt="">
                                            <h3 class="tpl-name">{{ $store->name }}</h3>
                                            <p class="tpl-sub">Digital Menu Experience</p>
                                            <div class="tpl-pills">
                                                <div class="tpl-pill"><img src="{{ $foodImg4 }}" alt="Salad"> Starters</div>
                                                <div class="tpl-pill"><img src="{{ $foodImg1 }}" alt="Burger"> Mains</div>
                                                <div class="tpl-pill"><img src="{{ $foodImg3 }}" alt="Cake"> Desserts</div>
                                                <div class="tpl-pill"><img src="{{ $foodImg2 }}" alt="Pizza"> Drinks</div>
                                            </div>
                                            <div class="tpl-scan-area">
                                                <p class="tpl-scan-label">Scan to Explore Menu</p>
                                                <div class="tpl-qr-box"><img src="{{ $qrImg }}" alt="QR" class="tpl-qr-img"></div>
                                                <p class="tpl-url" id="tplUrlText">{{ $menuUrl }}</p>
                                            </div>
                                            <p class="tpl-footer">Powered by {{ \App\CentralLogics\Helpers::get_settings('business_name') }}</p>
                                        </div>
                                    </div>

                                    @elseif($tpl->style == 5)
                                    <div class="qr-tpl qr-tpl-5 active-template" id="qrTplActive">
                                        <div class="tpl-active-badge"><i class="tio-checkmark-circle mr-1"></i>Active</div>
                                        <div class="tpl-table-badge"><div class="badge-inner">Table <span class="table-no-val"></span></div></div>
                                        <div class="qr-tpl-actions no-print">
                                            <button class="btn btn-sm btn-light" onclick="printTemplate('qrTplActive')" title="Print"><i class="tio-print"></i></button>
                                            <button onclick="downloadCardImage()">Download Image</button>
                                        </div>
                                        <div class="tpl-hero"><img src="{{ $foodImg1 }}" alt="Food"><div class="tpl-hero-overlay"></div></div>
                                        <div class="tpl-body">
                                            <div class="tpl-brand">
                                                <img src="{{ $storeLogo }}" class="tpl-logo" alt="Logo">
                                                <h3 class="tpl-name">{{ $store->name }}</h3>
                                            </div>
                                            <div class="tpl-tagline">Enjoy Delicious Food</div>
                                            <p class="tpl-section-title">Scan Our Menu</p>
                                            <div class="tpl-section-line"></div>
                                            <div class="tpl-qr-section">
                                                <p class="tpl-scan-label">Scan For Menu</p>
                                                <div class="tpl-qr-box"><img src="{{ $qrImg }}" alt="QR" class="tpl-qr-img"></div>
                                            </div>
                                            <div class="tpl-food-row" style="margin-top: 18px;">
                                                <div class="tpl-food-item"><div class="food-circle"><img src="{{ $foodImg2 }}" alt="Pizza"></div><span class="food-name">Pizza</span></div>
                                                <div class="tpl-food-item"><div class="food-circle"><img src="{{ $foodImg1 }}" alt="Burger"></div><span class="food-name">Burgers</span></div>
                                                <div class="tpl-food-item"><div class="food-circle"><img src="{{ $foodImg4 }}" alt="Salad"></div><span class="food-name">Salads</span></div>
                                            </div>
                                            <p class="tpl-footer">
                                                @if($store->phone) {{ $store->phone }} @endif
                                                @if($store->phone && $store->address) &bull; @endif
                                                @if($store->address) {{ Str::limit($store->address, 35) }} @endif
                                            </p>
                                        </div>
                                    </div>
                                    @endif

                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                @elseif($tab == 'all')
                {{-- ========== TAB: All Templates ========== --}}
                <div class="qr-templates-grid">

                    @foreach($qr_templates as $tpl)
                        @php $isActive = ($activeTemplate == $tpl->id); @endphp

                        @if($tpl->style == 1)
                        <!-- ========== TEMPLATE: {{ $tpl->name }} (Style 1) ========== -->
                        <div class="qr-tpl qr-tpl-1 {{ $isActive ? 'active-template' : '' }}" id="qrTpl{{ $tpl->id }}">
                            <div class="tpl-active-badge"><i class="tio-checkmark-circle mr-1"></i>Active</div>
                            <div class="tpl-table-badge"><div class="badge-inner">Table <span class="table-no-val"></span></div></div>
                            <div class="qr-tpl-actions no-print">
                                <button class="btn btn-sm btn-light" onclick="printTemplate('qrTpl{{ $tpl->id }}')" title="Print"><i class="tio-print"></i></button>
                            </div>
                            <div class="tpl-hero">
                                <div class="tpl-hero-overlay"></div>
                            </div>
                            <div class="tpl-body">
                                <div class="tpl-brand">
                                    <img src="{{ $storeLogo }}" class="tpl-logo" alt="">
                                    <h3 class="tpl-name">{{ $store->name }}</h3>
                                    <p class="tpl-sub">Enjoy Delicious Foodsssss</p>
                                </div>
                                <div class="tpl-line"></div>
                                <div class="tpl-foods">
                                    <div class="tpl-food-circle"><img src="{{ $foodImg1 }}" alt="Burger"></div>
                                    <div class="tpl-food-circle"><img src="{{ $foodImg2 }}" alt="Pizza"></div>
                                    <div class="tpl-food-circle"><img src="{{ $foodImg3 }}" alt="Cake"></div>
                                </div>
                                <div class="tpl-qr-area">
                                    <p class="tpl-qr-label">Scan For Menu</p>
                                    <div class="tpl-qr-box">
                                        <img src="{{ $qrImg }}" alt="QR">
                                    </div>
                                </div>
                                <div class="tpl-footer-info">
                                    @if($store->phone) {{ $store->phone }} @endif
                                    @if($store->phone && $store->address) &bull; @endif
                                    @if($store->address) {{ Str::limit($store->address, 35) }} @endif
                                </div>
                            </div>
                            @if($isActive)
                                <button class="tpl-use-btn no-print" disabled>{{ translate('Active Template') }}</button>
                            @else
                                <form action="{{ route('vendor.business-settings.change-template') }}" method="POST" id="qr-tpl-change-{{ $tpl->id }}" style="display:block; margin:0; padding:0;">
                                    @csrf
                                    <input type="hidden" name="template" value="{{ $tpl->id }}">
                                    <button type="button" class="tpl-use-btn no-print form-alert"
                                            data-id="qr-tpl-change-{{ $tpl->id }}"
                                            data-message="{{ translate('Want to activate this QR template?') }}">
                                        {{ translate('Use This Template') }}
                                    </button>
                                </form>
                            @endif
                        </div>

                        @elseif($tpl->style == 2)
                        <!-- ========== TEMPLATE: {{ $tpl->name }} (Style 2) ========== -->
                        <div class="qr-tpl qr-tpl-2 {{ $isActive ? 'active-template' : '' }}" id="qrTpl{{ $tpl->id }}">
                            <div class="tpl-active-badge"><i class="tio-checkmark-circle mr-1"></i>Active</div>
                            <div class="tpl-table-badge"><div class="badge-inner">Table <span class="table-no-val"></span></div></div>
                            <div class="qr-tpl-actions no-print">
                                <button class="btn btn-sm btn-light" onclick="printTemplate('qrTpl{{ $tpl->id }}')" title="Print"><i class="tio-print"></i></button>
                            </div>
                            <div class="tpl-leaf-bg"></div>
                            <div class="tpl-body">
                                <div class="tpl-brand">
                                    <img src="{{ $storeLogo }}" class="tpl-logo" alt="">
                                    <h3 class="tpl-name">{{ $store->name }}</h3>
                                </div>
                                <div class="tpl-tagline">Fresh & Delicious</div>
                                <div class="tpl-divider"></div>
                                <div class="tpl-food-row">
                                    <div class="tpl-food-card">
                                        <div class="food-wrap"><img src="{{ $foodImg4 }}" alt="Salad"></div>
                                        <span class="fname">Starters</span>
                                    </div>
                                    <div class="tpl-food-card">
                                        <div class="food-wrap"><img src="{{ $foodImg1 }}" alt="Burger"></div>
                                        <span class="fname">Mains</span>
                                    </div>
                                    <div class="tpl-food-card">
                                        <div class="food-wrap"><img src="{{ $foodImg3 }}" alt="Cake"></div>
                                        <span class="fname">Desserts</span>
                                    </div>
                                </div>
                                <div class="tpl-qr-wrap">
                                    <p class="tpl-scan-label">Scan For Menu</p>
                                    <div class="tpl-qr-box">
                                        <img src="{{ $qrImg }}" alt="QR">
                                    </div>
                                </div>
                                <p class="tpl-footer">
                                    @if($store->phone) {{ $store->phone }} @endif
                                    @if($store->phone && $store->address) &bull; @endif
                                    @if($store->address) {{ Str::limit($store->address, 35) }} @endif
                                </p>
                            </div>
                            @if($isActive)
                                <button class="tpl-use-btn no-print" disabled>{{ translate('Active Template') }}</button>
                            @else
                                <form action="{{ route('vendor.business-settings.change-template') }}" method="POST" id="qr-tpl-change-{{ $tpl->id }}" style="display:block; margin:0; padding:0;">
                                    @csrf
                                    <input type="hidden" name="template" value="{{ $tpl->id }}">
                                    <button type="button" class="tpl-use-btn no-print form-alert"
                                            data-id="qr-tpl-change-{{ $tpl->id }}"
                                            data-message="{{ translate('Want to activate this QR template?') }}">
                                        {{ translate('Use This Template') }}
                                    </button>
                                </form>
                            @endif
                        </div>

                        @elseif($tpl->style == 3)
                        <!-- ========== TEMPLATE: {{ $tpl->name }} (Style 3) ========== -->
                        <div class="qr-tpl qr-tpl-3 {{ $isActive ? 'active-template' : '' }}" id="qrTpl{{ $tpl->id }}">
                            <div class="tpl-active-badge"><i class="tio-checkmark-circle mr-1"></i>Active</div>
                            <div class="tpl-table-badge"><div class="badge-inner">Table <span class="table-no-val"></span></div></div>
                            <div class="qr-tpl-actions no-print">
                                <button class="btn btn-sm btn-light" onclick="printTemplate('qrTpl{{ $tpl->id }}')" title="Print"><i class="tio-print"></i></button>
                            </div>
                            <div class="tpl-accent-bar"></div>
                            <div class="tpl-body">
                                <div class="tpl-brand">
                                    <img src="{{ $storeLogo }}" class="tpl-logo" alt="">
                                    <h3 class="tpl-name">{{ $store->name }}</h3>
                                </div>
                                <p class="tpl-sub">Restaurant & Cafe</p>
                                <div class="tpl-ornament">
                                    <span></span><i class="tio-star"></i><span></span>
                                </div>
                                <div class="tpl-food-strip">
                                    <div class="tpl-food-item">
                                        <div class="food-ring"><img src="{{ $foodImg2 }}" alt="Pizza"></div>
                                        <span class="item-label">Appetizers</span>
                                    </div>
                                    <div class="tpl-food-item">
                                        <div class="food-ring"><img src="{{ $foodImg1 }}" alt="Burger"></div>
                                        <span class="item-label">Entrees</span>
                                    </div>
                                    <div class="tpl-food-item">
                                        <div class="food-ring"><img src="{{ $foodImg3 }}" alt="Cake"></div>
                                        <span class="item-label">Desserts</span>
                                    </div>
                                </div>
                                <p class="tpl-scan-msg">Scan Our Menu</p>
                                <p class="tpl-scan-sub">Point your camera at the QR code</p>
                                <div class="tpl-qr-box">
                                    <img src="{{ $qrImg }}" alt="QR">
                                </div>
                                <p class="tpl-footer">
                                    @if($store->phone) {{ $store->phone }} @endif
                                    @if($store->phone && $store->address) &bull; @endif
                                    @if($store->address) {{ Str::limit($store->address, 35) }} @endif
                                </p>
                            </div>
                            <div class="tpl-accent-bar"></div>
                            @if($isActive)
                                <button class="tpl-use-btn no-print" disabled>{{ translate('Active Template') }}</button>
                            @else
                                <form action="{{ route('vendor.business-settings.change-template') }}" method="POST" id="qr-tpl-change-{{ $tpl->id }}" style="display:block; margin:0; padding:0;">
                                    @csrf
                                    <input type="hidden" name="template" value="{{ $tpl->id }}">
                                    <button type="button" class="tpl-use-btn no-print form-alert"
                                            data-id="qr-tpl-change-{{ $tpl->id }}"
                                            data-message="{{ translate('Want to activate this QR template?') }}">
                                        {{ translate('Use This Template') }}
                                    </button>
                                </form>
                            @endif
                        </div>

                        @elseif($tpl->style == 4)
                        <!-- ========== TEMPLATE: {{ $tpl->name }} (Style 4) ========== -->
                        <div class="qr-tpl qr-tpl-4 {{ $isActive ? 'active-template' : '' }}" id="qrTpl{{ $tpl->id }}">
                            <div class="tpl-active-badge"><i class="tio-checkmark-circle mr-1"></i>Active</div>
                            <div class="tpl-table-badge"><div class="badge-inner">Table <span class="table-no-val"></span></div></div>
                            <div class="qr-tpl-actions no-print">
                                <button class="btn btn-sm btn-light" onclick="printTemplate('qrTpl{{ $tpl->id }}')" title="Print"><i class="tio-print"></i></button>
                            </div>
                            <div class="tpl-glow"></div>
                            <div class="tpl-body">
                                <img src="{{ $storeLogo }}" class="tpl-logo" alt="">
                                <h3 class="tpl-name">{{ $store->name }}</h3>
                                <p class="tpl-sub">Digital Menu Experience</p>
                                <div class="tpl-pills">
                                    <div class="tpl-pill"><img src="{{ $foodImg4 }}" alt="Salad"> Starters</div>
                                    <div class="tpl-pill"><img src="{{ $foodImg1 }}" alt="Burger"> Mains</div>
                                    <div class="tpl-pill"><img src="{{ $foodImg3 }}" alt="Cake"> Desserts</div>
                                    <div class="tpl-pill"><img src="{{ $foodImg2 }}" alt="Pizza"> Drinks</div>
                                </div>
                                <div class="tpl-scan-area">
                                    <p class="tpl-scan-label">Scan to Explore Menu</p>
                                    <div class="tpl-qr-box">
                                        <img src="{{ $qrImg }}" alt="QR">
                                    </div>
                                    <p class="tpl-url">{{ $menuUrl }}</p>
                                </div>
                                <p class="tpl-footer">Powered by {{ \App\CentralLogics\Helpers::get_settings('business_name') }}</p>
                            </div>
                            @if($isActive)
                                <button class="tpl-use-btn no-print" disabled>{{ translate('Active Template') }}</button>
                            @else
                                <form action="{{ route('vendor.business-settings.change-template') }}" method="POST" id="qr-tpl-change-{{ $tpl->id }}" style="display:block; margin:0; padding:0;">
                                    @csrf
                                    <input type="hidden" name="template" value="{{ $tpl->id }}">
                                    <button type="button" class="tpl-use-btn no-print form-alert"
                                            data-id="qr-tpl-change-{{ $tpl->id }}"
                                            data-message="{{ translate('Want to activate this QR template?') }}">
                                        {{ translate('Use This Template') }}
                                    </button>
                                </form>
                            @endif
                        </div>

                        @elseif($tpl->style == 5)
                        <!-- ========== TEMPLATE: {{ $tpl->name }} (Style 5) ========== -->
                        <div class="qr-tpl qr-tpl-5 {{ $isActive ? 'active-template' : '' }}" id="qrTpl{{ $tpl->id }}">
                            <div class="tpl-active-badge"><i class="tio-checkmark-circle mr-1"></i>Active</div>
                            <div class="tpl-table-badge"><div class="badge-inner">Table <span class="table-no-val"></span></div></div>
                            <div class="qr-tpl-actions no-print">
                                <button class="btn btn-sm btn-light" onclick="printTemplate('qrTpl{{ $tpl->id }}')" title="Print"><i class="tio-print"></i></button>
                            </div>
                            <div class="tpl-hero">
                                <img src="{{ $foodImg1 }}" alt="Food">
                                <div class="tpl-hero-overlay"></div>
                            </div>
                            <div class="tpl-body">
                                <div class="tpl-brand">
                                    <img src="{{ $storeLogo }}" class="tpl-logo" alt="Logo">
                                    <h3 class="tpl-name">{{ $store->name }}</h3>
                                </div>
                                <div class="tpl-tagline">Enjoy Delicious Food</div>
                                <p class="tpl-section-title">Scan Our Menu</p>
                                <div class="tpl-section-line"></div>
                                <div class="tpl-qr-section">
                                    <p class="tpl-scan-label">Scan For Menu</p>
                                    <div class="tpl-qr-box">
                                        <img src="{{ $qrImg }}"  alt="QR">
                                    </div>
                                </div>
                                <div class="tpl-food-row" style="margin-top: 18px;">
                                    <div class="tpl-food-item">
                                        <div class="food-circle"><img src="{{ $foodImg2 }}" alt="Pizza"></div>
                                        <span class="food-name">Pizza</span>
                                    </div>
                                    <div class="tpl-food-item">
                                        <div class="food-circle"><img src="{{ $foodImg1 }}" alt="Burger"></div>
                                        <span class="food-name">Burgers</span>
                                    </div>
                                    <div class="tpl-food-item">
                                        <div class="food-circle"><img src="{{ $foodImg4 }}" alt="Salad"></div>
                                        <span class="food-name">Salads</span>
                                    </div>
                                </div>
                                <p class="tpl-footer">
                                    @if($store->phone) {{ $store->phone }} @endif
                                    @if($store->phone && $store->address) &bull; @endif
                                    @if($store->address) {{ Str::limit($store->address, 35) }} @endif
                                </p>
                            </div>
                            @if($isActive)
                                <button class="tpl-use-btn no-print" disabled>{{ translate('Active Template') }}</button>
                            @else
                                <form action="{{ route('vendor.business-settings.change-template') }}" method="POST" id="qr-tpl-change-{{ $tpl->id }}" style="display:block; margin:0; padding:0;">
                                    @csrf
                                    <input type="hidden" name="template" value="{{ $tpl->id }}">
                                    <button type="button" class="tpl-use-btn no-print form-alert"
                                            data-id="qr-tpl-change-{{ $tpl->id }}"
                                            data-message="{{ translate('Want to activate this QR template?') }}">
                                        {{ translate('Use This Template') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                        @endif

                    @endforeach

                </div>

                @elseif($tab == 'customize')
                {{-- ========== TAB: Customize Food Images ========== --}}
                <div class="py-3">
                    <div class="mb-3">
                        <h6 style="font-weight: 700; color: #334257;"><i class="tio-image mr-1"></i>{{ translate('Customize QR Template Images') }}</h6>
                        <p class="text-muted" style="font-size: 13px;">{{ translate('Upload your own food images to display on QR templates and PDFs. Leave empty to use defaults.') }}</p>
                    </div>
                    <form action="{{ route('vendor.business-settings.update-food-images') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            @foreach([
                                ['field' => 'qr_food_image_1', 'label' => 'Image 1', 'current' => $store->qr_food_image_1_full_url, 'default' => asset('public/assets/admin/img/qr-dummy/burger.svg')],
                                ['field' => 'qr_food_image_2', 'label' => 'Image 2', 'current' => $store->qr_food_image_2_full_url, 'default' => asset('public/assets/admin/img/qr-dummy/pizza.svg')],
                                ['field' => 'qr_food_image_3', 'label' => 'Image 3', 'current' => $store->qr_food_image_3_full_url, 'default' => asset('public/assets/admin/img/qr-dummy/cake.svg')],
                                ['field' => 'qr_food_image_4', 'label' => 'Image 4', 'current' => $store->qr_food_image_4_full_url, 'default' => asset('public/assets/admin/img/qr-dummy/salad.svg')],
                            ] as $img)
                            <div class="col-sm-6 col-lg-3 mb-4">
                                <div class="card h-100" style="border: 2px solid #e8e8e8; border-radius: 12px;">
                                    <div class="card-body text-center p-3">
                                        <label class="d-block mb-2" style="font-size: 13px; font-weight: 600; color: #334257;">{{ translate($img['label']) }}</label>
                                        <div style="width: 100px; height: 100px; border-radius: 50%; border: 2px solid #e8e8e8; overflow: hidden; margin: 0 auto 12px; background: #f8f9fa;">
                                            <img src="{{ $img['current'] ?? $img['default'] }}" alt="{{ $img['label'] }}" id="preview_{{ $img['field'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        @if($img['current'])
                                            <span class="badge badge-success mb-2" style="font-size: 10px;">{{ translate('Custom') }}</span>
                                        @else
                                            <span class="badge badge-secondary mb-2" style="font-size: 10px;">{{ translate('Default') }}</span>
                                        @endif
                                        <input type="file" name="{{ $img['field'] }}" id="{{ $img['field'] }}" accept="image/*" onchange="previewFoodImage(this, 'preview_{{ $img['field'] }}')" style="display: none;">
                                        <label for="{{ $img['field'] }}" class="btn btn-sm btn-outline-primary mb-0" style="font-size: 12px; font-weight: 600; border-radius: 6px; cursor: pointer;">
                                            <i class="tio-upload mr-1"></i>{{ translate('Choose file') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4" style="font-weight: 600; border-radius: 8px;">
                                <i class="tio-save mr-1"></i>{{ translate('Save Images') }}
                            </button>
                        </div>
                    </form>
                </div>

                @elseif($tab == 'tables')
                {{-- ========== TAB: Table QR Codes ========== --}}
                @php
                    $tableQrs = $get_qr->whereNotNull('table_no')->sortBy('table_no');
                @endphp
                <div class="py-3">
                    <div class="mb-4">
                        <h6 style="font-weight: 700; color: #334257;"><i class="tio-table mr-1"></i>{{ translate('Table-wise QR Codes') }}</h6>
                        <p class="text-muted" style="font-size: 13px;">{{ translate('Generate separate QR codes for each table. Each QR code will include the table number in the menu URL.') }}</p>
                    </div>

                    {{-- Generate New Table QR --}}
                    <div class="p-3 mb-4" style="background: #f8f9fa; border-radius: 10px; border: 1px solid #e8e8e8;">
                        <form action="{{ route('vendor.business-settings.generate-table-qr') }}" method="POST" class="d-flex align-items-end gap-3 flex-wrap">
                            @csrf
                            <div>
                                <label class="mb-1" style="font-size: 13px; font-weight: 600; color: #334257;">{{ translate('Table Number') }}</label>
                                <input type="text" name="table_no" class="form-control" placeholder="e.g. 1, 2, A1, VIP" maxlength="20" required style="min-width: 200px;">
                            </div>
                            <button type="submit" class="btn btn-primary" style="font-weight: 600; border-radius: 8px; height: 40px;">
                                <i class="tio-qr-code mr-1"></i>{{ translate('Generate Table QR') }}
                            </button>
                        </form>
                        @if(!$store->slug)
                        <p class="mt-2 mb-0" style="font-size: 12px; color: #ffa800;"><i class="tio-info mr-1"></i>{{ translate('You need to generate your main QR code first to set up the menu URL slug.') }}</p>
                        @else
                        <p class="mt-2 mb-0" style="font-size: 12px; color: #999;">{{ translate('URL format:') }} <code>{{ url($store->slug . '/menu') }}?table=<strong>{number}</strong></code></p>
                        @endif
                    </div>

                    {{-- Table QR List --}}
                    @if($tableQrs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-borderless table-thead-bordered table-align-middle" style="border-radius: 10px; overflow: hidden;">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 80px;">{{ translate('Table') }}</th>
                                    <th style="width: 100px;">{{ translate('QR Code') }}</th>
                                    <th>{{ translate('Menu URL') }}</th>
                                    <th style="width: 200px;" class="text-center">{{ translate('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tableQrs as $tqr)
                                @php
                                    $tqrImg = \App\CentralLogics\Helpers::get_full_url('qrcodes', $tqr->qr_scanner, 'public');
                                    $tqrUrl = url($store->slug . '/menu') . '?table=' . urlencode($tqr->table_no);
                                @endphp
                                <tr>
                                    <td>
                                        <span class="badge badge-primary px-3 py-2" style="font-size: 14px; font-weight: 700;">{{ $tqr->table_no }}</span>
                                    </td>
                                    <td>
                                        <img src="{{ $tqrImg }}" alt="QR Table {{ $tqr->table_no }}" style="width: 70px; height: 70px; border: 1px solid #e8e8e8; border-radius: 8px; padding: 4px;">
                                    </td>
                                    <td>
                                        <code style="font-size: 12px;">{{ $tqrUrl }}</code>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ $tqrImg }}" download="table-{{ $tqr->table_no }}-qr.svg" class="btn btn-sm btn-outline-primary" title="{{ translate('Download') }}">
                                                <i class="tio-download-to"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('{{ $tqrUrl }}')" title="{{ translate('Copy URL') }}">
                                                <i class="tio-copy"></i>
                                            </button>
                                            <form action="{{ route('vendor.business-settings.delete-table-qr', $tqr->id) }}" method="POST" class="d-inline" id="delete-table-qr-{{ $tqr->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger form-alert" data-id="delete-table-qr-{{ $tqr->id }}" data-message="{{ translate('Want to delete QR for table') }} {{ $tqr->table_no }}?" title="{{ translate('Delete') }}">
                                                    <i class="tio-delete"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <img src="{{ asset('public/assets/admin/svg/illustrations/sorry.svg') }}" alt="" style="width: 100px; opacity: 0.5;" class="mb-3">
                        <p class="text-muted">{{ translate('No table QR codes generated yet. Create one above!') }}</p>
                    </div>
                    @endif
                </div>

                @endif

            </div>
        </div>

    </div>
@endsection

@push('script_2')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    // Auto-update URL preview when slug changes
    document.getElementById('slugField').addEventListener('input', function() {
        var slug = this.value.toLowerCase().replace(/[^a-z0-9-]/g, '-').replace(/-+/g, '-').replace(/^-/, '');
        this.value = slug;
        document.getElementById('menuUrlField').value = '{{ url("/") }}/' + slug + '/menu';
    });
    document.getElementById('slugField').addEventListener('blur', function() {
        this.value = this.value.replace(/-$/g, '');
        document.getElementById('menuUrlField').value = '{{ url("/") }}/' + this.value + '/menu';
    });

    function confirmGenerate() {
        return confirm('{{ translate("Are you sure? After generating QR code with this URL slug, you cannot change it without admin permission. Please double-check your restaurant slug before proceeding.") }}');
    }

    function copyMenuUrl() {
        var input = document.getElementById('menuUrlField');
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value).then(function() {
            var btn = document.getElementById('copyUrlBtn');
            btn.innerHTML = '<i class="tio-checkmark-circle mr-1"></i> {{ translate("Copied!") }}';
            btn.style.background = '#00AA96';
            setTimeout(function() {
                btn.innerHTML = '<i class="tio-copy mr-1"></i> {{ translate("Copy") }}';
                btn.style.background = '#334257';
            }, 2000);
        });
    }

    // Table number - live update across all templates (All Templates tab)
    function updateTableNo() {
        var input = document.getElementById('tableNoInput');
        if (!input) return;
        var val = input.value.trim();
        document.querySelectorAll('.tpl-table-badge').forEach(function(badge) {
            badge.classList.toggle('visible', val !== '');
        });
        document.querySelectorAll('.table-no-val').forEach(function(span) {
            span.textContent = val;
        });
    }
    var tableInput = document.getElementById('tableNoInput');
    if (tableInput) {
        tableInput.addEventListener('input', updateTableNo);
        updateTableNo();
    }

    // Active Template tab - QR radio selection handler
    document.querySelectorAll('.qr-radio-select').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var qrImg = this.getAttribute('data-qr-img');
            var tableNo = this.getAttribute('data-table-no');
            var menuUrlVal = this.getAttribute('data-menu-url');
            var tpl = document.getElementById('qrTplActive');
            if (!tpl) return;

            // Update QR image in template
            tpl.querySelectorAll('.tpl-qr-img').forEach(function(img) {
                img.src = qrImg;
            });
            // Also update any .tpl-qr-box > img without .tpl-qr-img class
            tpl.querySelectorAll('.tpl-qr-box img').forEach(function(img) {
                img.src = qrImg;
            });

            // Update table badge
            var badge = tpl.querySelector('.tpl-table-badge');
            var span = tpl.querySelector('.table-no-val');
            if (badge) badge.classList.toggle('visible', tableNo !== '');
            if (span) span.textContent = tableNo;

            // Update URL text in template (style 4)
            var urlText = document.getElementById('tplUrlText');
            if (urlText) urlText.textContent = menuUrlVal;

            // Update info section
            var pointsTo = document.getElementById('activePointsToUrl');
            if (pointsTo) pointsTo.textContent = menuUrlVal;

            var previewImg = document.getElementById('activeQrPreviewImg');
            if (previewImg) previewImg.src = qrImg;

            // Update download QR button
            var downloadQrBtn = document.getElementById('downloadQrBtn');
            if (downloadQrBtn) downloadQrBtn.href = qrImg;

            // Update download PDF link with table_no param
            var pdfBtn = document.getElementById('downloadPdfBtn');
            if (pdfBtn) {
                var baseUrl = '{{ route("vendor.business-settings.download-qr-pdf") }}';
                pdfBtn.href = baseUrl + (tableNo ? '?table_no=' + encodeURIComponent(tableNo) : '');
            }

            // Highlight selected radio label
            document.querySelectorAll('.qr-radio-select').forEach(function(r) {
                r.closest('label').style.borderColor = '#e8e8e8';
                r.closest('label').style.background = '#fff';
            });
            this.closest('label').style.borderColor = '#00AA96';
            this.closest('label').style.background = '#f0faf9';
        });

        // Trigger on page load for checked radio
        if (radio.checked) {
            radio.closest('label').style.borderColor = '#00AA96';
            radio.closest('label').style.background = '#f0faf9';
        }
    });

    // Copy the currently active URL
    function copyActiveUrl() {
        var url = document.getElementById('activePointsToUrl');
        if (url) {
            navigator.clipboard.writeText(url.textContent).then(function() {
                toastr.success('{{ translate("URL copied to clipboard!") }}');
            });
        }
    }

    function previewFoodImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
            // Update label text
            var label = input.nextElementSibling;
            if (label) label.textContent = input.files[0].name;
        }
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            toastr.success('{{ translate("URL copied to clipboard!") }}');
        });
    }

    function printTemplate(id) {
        document.querySelectorAll('.qr-tpl').forEach(function(el) { el.classList.remove('printing'); });
        document.getElementById(id).classList.add('printing');
        window.print();
        setTimeout(function() {
            document.getElementById(id).classList.remove('printing');
        }, 500);
    }

    async function downloadCardImage() {
        const element = document.getElementById("qrTplActive");

        try {
            // ✅ STEP 1: Fix QR (SVG → PNG)
            const qrImg = element.querySelector('.tpl-qr-img');

            if (qrImg && qrImg.src.includes('.svg')) {
                const pngData = await convertSvgToPng(qrImg);
                qrImg.src = pngData;

                // wait for image to load
                await new Promise(resolve => {
                    qrImg.onload = resolve;
                });
            }

            // ✅ STEP 2: Hide buttons safely
            const controls = element.querySelectorAll('.qr-tpl-actions');
            controls.forEach(el => el.style.display = 'none');

            // small delay
            await new Promise(r => setTimeout(r, 300));

            // ✅ STEP 3: Capture (IMPROVED SETTINGS)
            const canvas = await html2canvas(element, {
                scale: 4,
                useCORS: true,
                allowTaint: false,
                backgroundColor: null,
                logging: false
            });

            // ✅ STEP 4: Download
            const link = document.createElement("a");
            link.download = "qr-card.png";
            link.href = canvas.toDataURL("image/png");
            link.click();

            // ✅ STEP 5: Restore UI
            controls.forEach(el => el.style.display = '');

        } catch (error) {
            console.error("Download failed:", error);
            alert("Something went wrong while downloading image.");
        }
    }

    async function convertSvgToPng(imgElement) {
        try {
            const response = await fetch(imgElement.src);
            const svgText = await response.text();

            const svgBlob = new Blob([svgText], {
                type: "image/svg+xml;charset=utf-8"
            });

            const url = URL.createObjectURL(svgBlob);

            return new Promise(resolve => {
                const img = new Image();

                img.onload = function () {
                    const canvas = document.createElement("canvas");

                    canvas.width = 300;
                    canvas.height = 300;

                    const ctx = canvas.getContext("2d");
                    ctx.fillStyle = "#ffffff"; // white bg for QR
                    ctx.fillRect(0, 0, canvas.width, canvas.height);

                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                    URL.revokeObjectURL(url);
                    resolve(canvas.toDataURL("image/png"));
                };

                img.onerror = function () {
                    console.error("SVG load failed");
                    resolve(imgElement.src); // fallback
                };

                img.src = url;
            });

        } catch (e) {
            console.error("SVG conversion failed:", e);
            return imgElement.src; // fallback
        }
    }
</script>
@endpush
