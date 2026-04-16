@extends('payment-views.layouts.master')

@section('content')
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; margin: 0; padding: 40px 20px; }
        .container { max-width: 500px; margin: 0 auto; text-align: center; }
        .card { background: #fff; border-radius: 12px; padding: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; font-size: 24px; margin-bottom: 10px; }
        p { color: #666; margin-bottom: 20px; }
        .spinner { width: 40px; height: 40px; border: 4px solid #e0e0e0; border-top-color: #5f259f; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .error-box { background: #fee; border: 1px solid #fcc; color: #c00; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .btn { display: inline-block; padding: 12px 24px; background: #5f259f; color: #fff; text-decoration: none; border-radius: 6px; margin-top: 15px; }
        .btn:hover { background: #4a1d7a; }
    </style>

    <div class="container">
        <div class="card">
            @if(session('error'))
                <div class="error-box">
                    <strong>Payment Error:</strong> {{ session('error') }}
                </div>
                <p>Please try again or choose a different payment method.</p>
                <a href="{{ url()->previous() }}" class="btn">Go Back</a>
            @else
                <h1>Redirecting to PhonePe...</h1>
                <p>Please wait while we redirect you to PhonePe payment gateway.</p>
                <div class="spinner"></div>

                <form id="phonepe-form" action="{{ route('phonepe.initiate') }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="payment_id" value="{{ $data->id }}">
                </form>

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        document.getElementById('phonepe-form').submit();
                    });
                </script>
            @endif
        </div>
    </div>
@endsection
