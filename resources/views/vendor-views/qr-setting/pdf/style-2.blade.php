{{-- PDF Template: Style 2 - Emerald Nature --}}
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
            background: #0b1a0f; color: #fff;
            text-align: center; position: relative;
            padding: 30px 24px;
        }
        /* Table Number Badge */
        .table-badge {
            position: absolute; top: 16px; left: 16px;
            background: #22c55e; color: #fff;
            font-size: 13px; font-weight: 800;
            padding: 6px 16px; border-radius: 6px;
            letter-spacing: 0.5px;
        }
        /* Brand */
        .brand { text-align: center; margin-bottom: 4px; }
        .logo {
            width: 52px; height: 52px; border-radius: 50%;
            border: 3px solid #22c55e; display: inline-block;
            overflow: hidden; background: #0b1a0f;
        }
        .logo img { width: 52px; height: 52px; }
        .store-name {
            font-size: 24px; font-weight: 800; color: #fff;
            margin: 10px 0 0;
        }
        .tagline {
            display: inline-block; background: #22c55e;
            color: #fff; padding: 5px 20px; border-radius: 20px;
            font-size: 10px; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; margin: 10px 0 0;
        }
        .divider {
            width: 40px; height: 3px; background: #22c55e;
            margin: 18px auto; border-radius: 2px;
        }
        /* Food */
        .food-row { text-align: center; margin: 16px 0 20px; }
        .food-card { display: inline-block; width: 80px; text-align: center; margin: 0 6px; vertical-align: top; }
        .food-wrap {
            width: 68px; height: 68px; border-radius: 50%;
            border: 2px solid #22c55e; display: inline-block;
            overflow: hidden; margin-bottom: 6px;
        }
        .food-wrap img { width: 68px; height: 68px; }
        .fname { font-size: 10px; color: #86efac; font-weight: 600; letter-spacing: 1px; display: block; }
        /* QR */
        .qr-wrap {
            background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.2);
            border-radius: 16px; padding: 16px; margin: 0 auto;
            display: inline-block;
        }
        .scan-label {
            font-size: 11px; color: #22c55e; font-weight: 700;
            letter-spacing: 2px; text-transform: uppercase; margin-bottom: 10px;
        }
        .qr-box {
            display: inline-block; padding: 10px; background: #fff;
            border-radius: 12px;
        }
        .qr-box img { width: 140px; height: 140px; display: block; }
        /* Footer */
        .footer {
            font-size: 9px; color: #3a5a40; margin-top: 16px;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="card">
        @if($tableNo)
            <div class="table-badge">Table {{ $tableNo }}</div>
        @endif

        <div class="brand">
            <div class="logo">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo">
                @endif
            </div>
            <h2 class="store-name">{{ $store->name }}</h2>
        </div>
        <div class="tagline">Fresh & Delicious</div>
        <div class="divider"></div>

        <div class="food-row">
            <div class="food-card">
                <div class="food-wrap"><img src="{{ $foodImages['salad'] }}" alt="Salad"></div>
                <span class="fname">Starters</span>
            </div>
            <div class="food-card">
                <div class="food-wrap"><img src="{{ $foodImages['burger'] }}" alt="Burger"></div>
                <span class="fname">Mains</span>
            </div>
            <div class="food-card">
                <div class="food-wrap"><img src="{{ $foodImages['cake'] }}" alt="Cake"></div>
                <span class="fname">Desserts</span>
            </div>
        </div>

        <div class="qr-wrap">
            <p class="scan-label">Scan For Menu</p>
            <div class="qr-box">
                <img src="{{ $qrBase64 }}" alt="QR Code">
            </div>
        </div>

        <p class="footer">
            @if($store->phone) {{ $store->phone }} @endif
            @if($store->phone && $store->address) &bull; @endif
            @if($store->address) {{ Str::limit($store->address, 40) }} @endif
        </p>
    </div>
</body>
</html>
