{{-- PDF Template: Style 1 - Dark Luxury --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { margin: 0; padding: 0; font-family: Georgia, 'Times New Roman', serif; }
        .card {
            width: 100%; min-height: 100%;
            background: #0d0d0d; color: #fff;
            text-align: center; position: relative;
            padding: 0;
        }
        /* Table Number Badge */
        .table-badge {
            position: absolute; top: 16px; left: 16px;
            background: #c8a45a; color: #0d0d0d;
            font-size: 13px; font-weight: 800;
            padding: 6px 16px; border-radius: 6px;
            letter-spacing: 0.5px;
            font-family: Arial, sans-serif;
        }
        /* Hero area */
        .hero {
            width: 100%; height: 120px;
            background: #1a1a1a;
        }
        /* Brand */
        .brand { padding: 0 24px; text-align: center; margin-top: -30px; }
        .logo {
            width: 60px; height: 60px; border-radius: 50%;
            border: 3px solid #c8a45a; display: inline-block;
            overflow: hidden; background: #1a1a1a;
        }
        .logo img { width: 60px; height: 60px; }
        .store-name {
            font-size: 22px; font-weight: 800; color: #c8a45a;
            letter-spacing: 1px; margin: 8px 0 0;
        }
        .subtitle {
            font-size: 10px; letter-spacing: 3px; text-transform: uppercase;
            color: #888; margin: 4px 0 0;
        }
        .gold-line {
            width: 60px; height: 2px; background: #c8a45a;
            margin: 14px auto;
        }
        /* Food images */
        .food-row { text-align: center; margin: 16px 0; }
        .food-circle {
            width: 60px; height: 60px; border-radius: 50%;
            border: 2px solid #c8a45a; display: inline-block;
            overflow: hidden; background: #1a1a1a; margin: 0 6px;
        }
        .food-circle img { width: 60px; height: 60px; }
        /* QR */
        .qr-area { margin: 14px 0; text-align: center; }
        .qr-label {
            font-size: 11px; letter-spacing: 2px; color: #c8a45a;
            text-transform: uppercase; margin-bottom: 10px;
            font-family: Arial, sans-serif;
        }
        .qr-box {
            display: inline-block; padding: 10px; background: #fff;
            border-radius: 12px;
        }
        .qr-box img { width: 140px; height: 140px; display: block; }
        /* Footer */
        .footer-info {
            font-size: 10px; color: #555; margin-top: 14px;
            padding: 0 20px 20px;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div class="card">
        @if($tableNo)
            <div class="table-badge">Table {{ $tableNo }}</div>
        @endif

        <div class="hero"></div>

        <div class="brand">
            <div class="logo">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo">
                @endif
            </div>
            <h2 class="store-name">{{ $store->name }}</h2>
            <p class="subtitle">Enjoy Delicious Food</p>
        </div>

        <div class="gold-line"></div>

        <div class="food-row">
            <div class="food-circle"><img src="{{ $foodImages['burger'] }}" alt="Burger"></div>
            <div class="food-circle"><img src="{{ $foodImages['pizza'] }}" alt="Pizza"></div>
            <div class="food-circle"><img src="{{ $foodImages['cake'] }}" alt="Cake"></div>
        </div>

        <div class="qr-area">
            <p class="qr-label">Scan For Menu</p>
            <div class="qr-box">
                <img src="{{ $qrBase64 }}" alt="QR Code">
            </div>
        </div>

        <div class="footer-info">
            @if($store->phone) {{ $store->phone }} @endif
            @if($store->phone && $store->address) &bull; @endif
            @if($store->address) {{ Str::limit($store->address, 40) }} @endif
        </div>
    </div>
</body>
</html>
