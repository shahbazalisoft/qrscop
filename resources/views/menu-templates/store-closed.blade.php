<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $store->name }} - {{ translate('Store Closed') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f1419 0%, #1a2332 50%, #0f1419 100%);
            color: #fff;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .closed-container {
            text-align: center;
            padding: 40px 24px;
            max-width: 420px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        /* Animated background circles */
        .bg-circle {
            position: fixed;
            border-radius: 50%;
            opacity: 0.05;
            pointer-events: none;
        }
        .bg-circle-1 {
            width: 400px; height: 400px;
            background: #10847E;
            top: -100px; right: -100px;
            animation: float1 8s ease-in-out infinite;
        }
        .bg-circle-2 {
            width: 300px; height: 300px;
            background: #10847E;
            bottom: -80px; left: -80px;
            animation: float2 10s ease-in-out infinite;
        }
        .bg-circle-3 {
            width: 200px; height: 200px;
            background: #10847E;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation: float3 6s ease-in-out infinite;
        }

        @keyframes float1 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-30px, 30px); }
        }
        @keyframes float2 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(20px, -20px); }
        }
        @keyframes float3 {
            0%, 100% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -50%) scale(1.2); }
        }

        /* Store logo */
        .store-logo {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(16, 132, 126, 0.4);
            margin-bottom: 20px;
            box-shadow: 0 0 30px rgba(16, 132, 126, 0.2);
        }

        .store-name {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #e2e8f0;
        }

        /* Clock icon */
        .clock-wrapper {
            width: 100px;
            height: 100px;
            margin: 0 auto 24px;
            position: relative;
        }
        .clock-bg {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(16, 132, 126, 0.1);
            border: 2px solid rgba(16, 132, 126, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse-ring 2s ease-in-out infinite;
        }
        .clock-icon {
            font-size: 42px;
            color: #10847E;
        }

        @keyframes pulse-ring {
            0%, 100% { box-shadow: 0 0 0 0 rgba(16, 132, 126, 0.3); }
            50% { box-shadow: 0 0 0 15px rgba(16, 132, 126, 0); }
        }

        .closed-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #fff;
        }

        .closed-subtitle {
            font-size: 15px;
            color: #94a3b8;
            margin-bottom: 32px;
            line-height: 1.6;
        }

        /* Schedule card */
        .schedule-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
            backdrop-filter: blur(10px);
        }
        .schedule-card h4 {
            font-size: 13px;
            font-weight: 600;
            color: #10847E;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 14px;
        }
        .schedule-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            font-size: 13px;
        }
        .schedule-row:last-child { border-bottom: none; }
        .schedule-day {
            color: #94a3b8;
            font-weight: 500;
        }
        .schedule-day.today {
            color: #10847E;
            font-weight: 600;
        }
        .schedule-time {
            color: #e2e8f0;
            font-weight: 500;
        }
        .schedule-time.closed-day {
            color: #ff6b6b;
            font-size: 12px;
        }
        .schedule-time.today {
            color: #10847E;
            font-weight: 600;
        }

        /* Contact */
        .contact-section {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .contact-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .contact-btn-primary {
            background: #10847E;
            color: #fff;
        }
        .contact-btn-primary:hover {
            background: #0c6b66;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(16, 132, 126, 0.3);
        }
        .contact-btn-outline {
            background: transparent;
            color: #94a3b8;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .contact-btn-outline:hover {
            color: #fff;
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* Moon/Stars decoration */
        .decoration {
            position: fixed;
            opacity: 0.03;
            font-size: 120px;
            pointer-events: none;
        }
        .deco-1 { top: 10%; left: 5%; transform: rotate(-15deg); }
        .deco-2 { bottom: 10%; right: 5%; transform: rotate(15deg); }
    </style>
</head>
<body>
    <div class="bg-circle bg-circle-1"></div>
    <div class="bg-circle bg-circle-2"></div>
    <div class="bg-circle bg-circle-3"></div>

    <div class="closed-container">
        @if($store->logo_full_url)
            <img src="{{ $store->logo_full_url }}" alt="{{ $store->name }}" class="store-logo" onerror="this.style.display='none'">
        @endif

        <div class="store-name">{{ $store->name }}</div>

        <div class="clock-wrapper">
            <div class="clock-bg">
                <svg class="clock-icon" xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
        </div>

        <h1 class="closed-title">We're Closed</h1>
        <p class="closed-subtitle">
            Sorry, we are not accepting orders right now.<br>
            Please check back during our business hours.
        </p>

        {{-- @if(isset($schedules) && $schedules->count() > 0)
        <div class="schedule-card">
            <h4>Business Hours</h4>
            @php
                $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                $todayIndex = now()->dayOfWeek;
                $offDays = explode(',', $store->off_day ?? '');
                $groupedSchedules = $schedules->groupBy('day');
            @endphp
            @for($d = 0; $d < 7; $d++)
                @php
                    $isToday = ($d == $todayIndex);
                    $isOff = in_array($d, $offDays);
                    $daySchedules = $groupedSchedules->get($d, collect());
                @endphp
                <div class="schedule-row">
                    <span class="schedule-day {{ $isToday ? 'today' : '' }}">
                        {{ $dayNames[$d] }} @if($isToday) (Today) @endif
                    </span>
                    @if($isOff)
                        <span class="schedule-time closed-day">Closed</span>
                    @elseif($daySchedules->count() > 0)
                        <span class="schedule-time {{ $isToday ? 'today' : '' }}">
                            @foreach($daySchedules as $sch)
                                {{ \Carbon\Carbon::parse($sch->opening_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($sch->closing_time)->format('h:i A') }}@if(!$loop->last), @endif
                            @endforeach
                        </span>
                    @else
                        <span class="schedule-time closed-day">Closed</span>
                    @endif
                </div>
            @endfor
        </div>
        @endif --}}

        <div class="contact-section">
            @if($store->phone)
                <a href="tel:{{ $store->phone }}" class="contact-btn contact-btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    Call Us
                </a>
            @endif
            @if($store->address)
                <a href="https://maps.google.com/?q={{ urlencode($store->address) }}" target="_blank" class="contact-btn contact-btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    Directions
                </a>
            @endif
        </div>
    </div>
</body>
</html>
