<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ \App\CentralLogics\Helpers::get_settings('business_name') }}</title>
    <link href="{{ asset('public/assets/web/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/assets/web/css/bootstrap-icons.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2310847E' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .error-container {
            text-align: center;
            position: relative;
            z-index: 1;
            padding: 40px 20px;
        }

        .error-code {
            font-family: 'Playfair Display', serif;
            font-size: 150px;
            font-weight: 700;
            color: transparent;
            -webkit-text-stroke: 2px #10847E;
            line-height: 1;
            margin-bottom: 20px;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .error-icon {
            font-size: 70px;
            color: #10847E;
            margin-bottom: 25px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        .error-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #fff;
            margin-bottom: 15px;
        }

        .error-message {
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
            max-width: 450px;
            margin: 0 auto 35px;
            line-height: 1.6;
        }

        .error-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 28px;
            font-weight: 500;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .btn-warning {
            background: #10847E;
            color: #1a1a2e;
            border: none;
        }

        .btn-warning:hover {
            background: #ffca2c;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255, 193, 7, 0.3);
        }

        .btn-outline-light {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            color: #fff;
        }

        .btn i {
            margin-right: 8px;
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }

        .floating-element {
            position: absolute;
            opacity: 0.08;
            color: #10847E;
            font-size: 50px;
        }

        .floating-element:nth-child(1) { top: 10%; left: 8%; animation: float 4s ease-in-out infinite; }
        .floating-element:nth-child(2) { top: 20%; right: 12%; animation: float 5s ease-in-out infinite 0.5s; }
        .floating-element:nth-child(3) { bottom: 15%; left: 15%; animation: float 4.5s ease-in-out infinite 1s; }
        .floating-element:nth-child(4) { bottom: 25%; right: 8%; animation: float 3.5s ease-in-out infinite 0.3s; }

        .restaurant-decoration {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .restaurant-decoration p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
            margin-bottom: 15px;
        }

        .decoration-icons {
            display: flex;
            justify-content: center;
            gap: 25px;
        }

        .decoration-icons i {
            font-size: 24px;
            color: rgba(255, 193, 7, 0.4);
        }

        @media (max-width: 768px) {
            .error-code { font-size: 100px; }
            .error-title { font-size: 1.5rem; }
            .error-icon { font-size: 50px; }
            .error-message { font-size: 0.9rem; }
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <div class="floating-element"><i class="bi bi-cup-hot"></i></div>
        <div class="floating-element"><i class="bi bi-egg-fried"></i></div>
        <div class="floating-element"><i class="bi bi-qr-code"></i></div>
        <div class="floating-element"><i class="bi bi-basket2"></i></div>
    </div>

    <div class="error-container">
        <div class="error-code">@yield('code')</div>
        <div class="error-icon">
            @yield('icon', '<i class="bi bi-emoji-frown"></i>')
        </div>
        <h1 class="error-title">@yield('title')</h1>
        <p class="error-message">@yield('description', 'Something went wrong. Please try again or return to the homepage.')</p>

        <div class="error-buttons">
            <a href="{{ url('/') }}" class="btn btn-warning">
                <i class="bi bi-house-door"></i>Back to Home
            </a>
            <a href="javascript:history.back()" class="btn btn-outline-light">
                <i class="bi bi-arrow-left"></i>Go Back
            </a>
        </div>

        <div class="restaurant-decoration">
            <p>{{ \App\CentralLogics\Helpers::get_settings('business_name') }} - Digital Menu Solutions</p>
            <div class="decoration-icons">
                <i class="bi bi-phone"></i>
                <i class="bi bi-qr-code-scan"></i>
                <i class="bi bi-shop"></i>
            </div>
        </div>
    </div>

    <script src="{{ asset('public/assets/web/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
