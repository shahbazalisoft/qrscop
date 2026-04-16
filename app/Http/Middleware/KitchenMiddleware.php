<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KitchenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('kitchen')->check()) {
            $user = Auth::guard('kitchen')->user();

            if (!$user->status) {
                Auth::guard('kitchen')->logout();
                return redirect()->route('kitchen.login');
            }

            if (!$user->is_logged_in) {
                Auth::guard('kitchen')->logout();
                return redirect()->route('kitchen.login');
            }

            if (session('kitchen_login_token') !== $user->login_remember_token) {
                Auth::guard('kitchen')->logout();
                session()->invalidate();
                session()->regenerateToken();
                return redirect()->route('kitchen.login')
                    ->withErrors(['Your session has expired. Please log in again.']);
            }

            return $next($request);
        }

        return redirect()->route('kitchen.login');
    }
}
