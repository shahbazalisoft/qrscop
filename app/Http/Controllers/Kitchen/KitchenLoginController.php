<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\KitchenStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KitchenLoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('kitchen')->check()) {
            return redirect()->route('kitchen.dashboard');
        }

        return view('kitchen.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $staff = KitchenStaff::where('email', $request->email)->first();

        if (!$staff) {
            return back()->withInput($request->only('email'))
                ->withErrors(['Invalid kitchen staff credentials.']);
        }

        if (!$staff->status) {
            return back()->withInput($request->only('email'))
                ->withErrors(['Your account is disabled.']);
        }

        if ($staff->store && in_array($staff->store->store_business_model, ['none', 'unsubscribed'])) {
            return back()->withInput($request->only('email'))
                ->withErrors(['Store is inactive.']);
        }

        if (Auth::guard('kitchen')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::guard('kitchen')->user();

            $newToken = Str::random(60);
            $user->login_remember_token = $newToken;
            $user->is_logged_in = 1;
            $user->save();
            session(['kitchen_login_token' => $newToken]);

            return redirect()->route('kitchen.dashboard');
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['Password does not match.']);
    }
}
