<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kitchen Login</title>
    <link rel="stylesheet" href="{{ asset('public/assets/kitchen/css/kitchen.css') }}">
</head>
<body>
    <div class="k-login-page">
        <div class="k-login-card">
            @if(isset($store) && $store->logo_full_url)
                <img src="{{ $store->logo_full_url }}" alt="Logo" class="k-login-logo">
            @endif
            <h1 class="k-login-title">Kitchen Portal</h1>
            <p class="k-login-subtitle">Sign in to manage orders</p>

            @if($errors->any())
                <ul class="k-error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form action="{{ route('kitchen.login.submit') }}" method="POST">
                @csrf
                <div class="k-form-group">
                    <label class="k-form-label" for="email">Email</label>
                    <input type="email" name="email" id="email" class="k-form-input"
                           placeholder="kitchen@example.com" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="k-form-group">
                    <label class="k-form-label" for="password">Password</label>
                    <input type="password" name="password" id="password" class="k-form-input"
                           placeholder="Enter your password" required>
                </div>
                <button type="submit" class="k-btn k-btn-primary k-btn-full">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>
