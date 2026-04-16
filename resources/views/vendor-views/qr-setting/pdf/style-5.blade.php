{{-- PDF Template: Style 5 - Fast Food Poster --}}
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
            background: #0a0a0a; color: #fff;
            text-align: center; position: relative;
            padding: 0;
        }
        /* Table Number Badge */
        .table-badge {
            position: absolute; top: 16px; left: 16px;
            background: #d32f2f; color: #fff;
            font-size: 13px; font-weight: 800;
            padding: 6px 16px; border-radius: 6px;
            letter-spacing: 0.5px;
            font-family: Arial, sans-serif;
        }
        /* Hero */
        .hero {
            width: 100%; height: 140px;
            background: #1a1a1a; overflow: hidden;
        }
        .hero img { width: 100%; height: 140px; }
        .red-stripe {
            width: 100%; height: 4px; background: #d32f2f;
        }
        .body {
            padding: 16px 20px 24px; text-align: center;
        }
        /* Brand */
        .brand { text-align: center; }
        .logo {
            width: 50px; height: 50px; border-radius: 50%;
            border: 3px solid #d32f2f; display: inline-block;
            overflow: hidden; background: #1a1a1a;
        }
        .logo img { width: 50px; height: 50px; }
        .store-name {
            font-size: 30px; font-weight: 900; color: #fff;
            font-style: italic; margin: 6px 0 0; line-height: 1.1;
        }
        .tag {
            display: inline-block; background: #d32f2f; color: #fff;
            padding: 4px 18px; font-size: 10px; font-weight: 700;
            letter-spacing: 3px; text-transform: uppercase;
            margin: 8px 0 0; border-radius: 2px;
            font-family: Arial, sans-serif;
        }
        /* Section */
        .section-title {
            font-size: 14px; font-weight: 800; color: #fff;
            letter-spacing: 1px; text-transform: uppercase;
            margin: 18px 0 2px; font-family: Arial, sans-serif;
        }
        .section-line {
            width: 50px; height: 2px; background: #d32f2f;
            margin: 4px auto 14px;
        }
        /* QR */
        .scan-label {
            font-size: 11px; letter-spacing: 2px; color: #d32f2f;
            text-transform: uppercase; font-weight: 700;
            margin-bottom: 8px; font-family: Arial, sans-serif;
        }
        .qr-box {
            display: inline-block; padding: 8px; background: #fff;
            border-radius: 10px; border: 2px solid #d32f2f;
        }
        .qr-box img { width: 130px; height: 130px; display: block; }
        /* Food Row */
        .food-row { text-align: center; margin: 18px 0 0; }
        .food-item { display: inline-block; width: 80px; text-align: center; margin: 0 6px; vertical-align: top; }
        .food-circle {
            width: 70px; height: 70px; border-radius: 50%;
            border: 3px solid #d32f2f; display: inline-block;
            overflow: hidden; background: #1a1a1a; margin-bottom: 6px;
        }
        .food-circle img { width: 70px; height: 70px; }
        .food-name {
            font-size: 10px; font-weight: 700; color: #fff;
            font-style: italic; line-height: 1.2; display: block;
            font-family: Georgia, serif;
        }
        /* Footer */
        .footer {
            font-size: 9px; color: #555; margin-top: 12px;
            letter-spacing: 1px; font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div class="card">
        @if($tableNo)
            <div class="table-badge">Table {{ $tableNo }}</div>
        @endif

        <div class="hero">
            <img src="{{ $foodImages['burger'] }}" alt="Food">
        </div>
        <div class="red-stripe"></div>

        <div class="body">
            <div class="brand">
                <div class="logo">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Logo">
                    @endif
                </div>
                <h2 class="store-name">{{ $store->name }}</h2>
            </div>
            <div class="tag">Enjoy Delicious Food</div>

            <p class="section-title">Scan Our Menu</p>
            <div class="section-line"></div>

            <p class="scan-label">Scan For Menu</p>
            <div class="qr-box">
                <img src="{{ $qrBase64 }}" alt="QR Code">
            </div>

            <div class="food-row">
                <div class="food-item">
                    <div class="food-circle"><img src="{{ $foodImages['pizza'] }}" alt="Pizza"></div>
                    <span class="food-name">Pizza</span>
                </div>
                
                <div class="food-item">
                    <div class="food-circle"><img src="{{ $foodImages['burger'] }}" alt="Burger"></div>
                    <span class="food-name">Burgers</span>
                </div>
                <div class="food-item">
                    <div class="food-circle"><img src="{{ $foodImages['salad'] }}" alt="Salad"></div>
                    <span class="food-name">Salads</span>
                </div>
            </div>

            <p class="footer">
                @if($store->phone) {{ $store->phone }} @endif
                @if($store->phone && $store->address) &bull; @endif
                @if($store->address) {{ Str::limit($store->address, 40) }} @endif
            </p>
        </div>
    </div>
</body>
</html>
