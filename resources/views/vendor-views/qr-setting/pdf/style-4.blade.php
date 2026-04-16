{{-- PDF Template: Style 4 - Modern Gradient --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; }
        .card {
            width: 100%; min-height: 100%;
            background: #24243e; color: #fff;
            text-align: center; position: relative;
            padding: 28px 24px;
        }
        /* Table Number Badge */
        .table-badge {
            position: absolute; top: 16px; left: 16px;
            background: #6c63ff; color: #fff;
            font-size: 13px; font-weight: 800;
            padding: 6px 16px; border-radius: 6px;
            letter-spacing: 0.5px;
        }
        /* Brand */
        .logo {
            width: 56px; height: 56px; border-radius: 50%;
            border: 2px solid rgba(108,99,255,0.5); display: inline-block;
            overflow: hidden; margin-bottom: 10px;
        }
        .logo img { width: 56px; height: 56px; }
        .store-name {
            font-size: 20px; font-weight: 800; color: #a78bfa;
            margin: 0;
        }
        .subtitle {
            font-size: 11px; color: rgba(255,255,255,0.5);
            letter-spacing: 2px; text-transform: uppercase; margin: 4px 0 0;
        }
        /* Pills */
        .pills { text-align: center; margin: 18px 0; }
        .pill {
            display: inline-block; background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px; padding: 6px 14px;
            font-size: 11px; color: rgba(255,255,255,0.7);
            margin: 3px 2px;
        }
        .pill img {
            width: 22px; height: 22px; border-radius: 50%;
            vertical-align: middle; margin-right: 4px;
        }
        /* QR */
        .scan-area {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px; padding: 18px;
            margin: 16px auto; display: inline-block;
        }
        .scan-label {
            font-size: 12px; color: #a78bfa;
            letter-spacing: 1px; text-transform: uppercase; margin-bottom: 10px;
        }
        .qr-box {
            display: inline-block; padding: 10px; background: #fff;
            border-radius: 12px;
        }
        .qr-box img { width: 140px; height: 140px; display: block; }
        .url-text {
            font-size: 10px; color: rgba(255,255,255,0.3);
            margin-top: 8px; word-break: break-all;
        }
        /* Footer */
        .footer {
            font-size: 10px; color: rgba(255,255,255,0.2);
            margin-top: 14px;
        }
    </style>
</head>
<body>
    <div class="card">
        @if($tableNo)
            <div class="table-badge">Table {{ $tableNo }}</div>
        @endif

        <div class="logo">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="Logo">
            @endif
        </div>
        <h2 class="store-name">{{ $store->name }}</h2>
        <p class="subtitle">Digital Menu Experience</p>

        <div class="pills">
            <span class="pill"><img src="{{ $foodImages['salad'] }}" alt="Salad"> Starters</span>
            <span class="pill"><img src="{{ $foodImages['burger'] }}" alt="Burger"> Mains</span>
            <span class="pill"><img src="{{ $foodImages['cake'] }}" alt="Cake"> Desserts</span>
            <span class="pill"><img src="{{ $foodImages['pizza'] }}" alt="Pizza"> Drinks</span>
        </div>

        <div class="scan-area">
            <p class="scan-label">Scan to Explore Menu</p>
            <div class="qr-box">
                <img src="{{ $qrBase64 }}" alt="QR Code">
            </div>
            <p class="url-text">{{ $menuUrl }}</p>
        </div>

        <p class="footer">Powered by {{ \App\CentralLogics\Helpers::get_settings('business_name') }}</p>
    </div>
</body>
</html>
