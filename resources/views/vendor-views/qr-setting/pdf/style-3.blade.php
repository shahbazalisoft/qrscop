{{-- PDF Template: Style 3 - Sunset Warm --}}
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
            background: #16213e; color: #fff;
            text-align: center; position: relative;
            padding: 0;
        }
        .accent-bar {
            width: 100%; height: 5px;
            background: #ff6b35;
        }
        /* Table Number Badge */
        .table-badge {
            position: absolute; top: 16px; left: 16px;
            background: #ff6b35; color: #fff;
            font-size: 13px; font-weight: 800;
            padding: 6px 16px; border-radius: 6px;
            letter-spacing: 0.5px;
        }
        .body { padding: 28px 24px; text-align: center; }
        /* Brand */
        .brand { text-align: center; margin-bottom: 4px; }
        .logo {
            width: 52px; height: 52px; border-radius: 50%;
            border: 3px solid #ff6b35; display: inline-block;
            overflow: hidden;
        }
        .logo img { width: 52px; height: 52px; }
        .store-name {
            font-size: 24px; font-weight: 800; color: #ffd700;
            margin: 10px 0 0;
        }
        .subtitle {
            font-size: 10px; color: rgba(255,255,255,0.5);
            letter-spacing: 3px; text-transform: uppercase; margin: 6px 0 0;
        }
        /* Ornament */
        .ornament { text-align: center; margin: 18px 0; color: #ffd700; font-size: 12px; }
        .ornament-line {
            display: inline-block; width: 35px; height: 1px;
            background: #ff6b35; vertical-align: middle; margin: 0 8px;
        }
        /* Food */
        .food-strip { text-align: center; margin: 0 0 20px; }
        .food-item { display: inline-block; width: 80px; text-align: center; margin: 0 6px; vertical-align: top; }
        .food-ring {
            width: 64px; height: 64px; border-radius: 50%;
            border: 2px solid rgba(255,107,53,0.6); display: inline-block;
            overflow: hidden; background: rgba(255,255,255,0.05);
            margin-bottom: 6px;
        }
        .food-ring img { width: 64px; height: 64px; }
        .item-label { font-size: 9px; color: #ffd700; font-weight: 600; letter-spacing: 1px; display: block; }
        /* QR */
        .scan-msg {
            font-size: 13px; font-weight: 700; color: #fff;
            letter-spacing: 1px; text-transform: uppercase; margin: 0 0 4px;
        }
        .scan-sub { font-size: 10px; color: rgba(255,255,255,0.4); margin-bottom: 14px; }
        .qr-box {
            display: inline-block; padding: 10px; background: #fff;
            border-radius: 12px;
        }
        .qr-box img { width: 140px; height: 140px; display: block; }
        /* Footer */
        .footer {
            font-size: 9px; color: rgba(255,255,255,0.25);
            margin-top: 16px; letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="accent-bar"></div>

        @if($tableNo)
            <div class="table-badge">Table {{ $tableNo }}</div>
        @endif

        <div class="body">
            <div class="brand">
                <div class="logo">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Logo">
                    @endif
                </div>
                <h2 class="store-name">{{ $store->name }}</h2>
            </div>
            <p class="subtitle">Restaurant & Cafe</p>

            <div class="ornament">
                <span class="ornament-line"></span>
                &#9733;
                <span class="ornament-line"></span>
            </div>

            <div class="food-strip">
                <div class="food-item">
                    <div class="food-ring"><img src="{{ $foodImages['pizza'] }}" alt="Pizza"></div>
                    <span class="item-label">Appetizers</span>
                </div>
                <div class="food-item">
                    <div class="food-ring"><img src="{{ $foodImages['burger'] }}" alt="Burger"></div>
                    <span class="item-label">Entrees</span>
                </div>
                <div class="food-item">
                    <div class="food-ring"><img src="{{ $foodImages['cake'] }}" alt="Cake"></div>
                    <span class="item-label">Desserts</span>
                </div>
            </div>

            <p class="scan-msg">Scan Our Menu</p>
            <p class="scan-sub">Point your camera at the QR code</p>
            <div class="qr-box">
                <img src="{{ $qrBase64 }}" alt="QR Code">
            </div>

            <p class="footer">
                @if($store->phone) {{ $store->phone }} @endif
                @if($store->phone && $store->address) &bull; @endif
                @if($store->address) {{ Str::limit($store->address, 40) }} @endif
            </p>
        </div>

        <div class="accent-bar"></div>
    </div>
</body>
</html>
