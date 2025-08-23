<?php

namespace App\Http\Controllers;

use App\CustomerLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    /**
     * Show customer login form.
     */
    public function showLoginForm()
    {
        return view('customer.login');
    }

    /**
     * Handle customer login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string', // username or email
            'password' => 'required|string',
            'remember' => 'sometimes|boolean',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');
        $remember = (bool) $request->input('remember', false);

        // Allow login by email or username
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $field => $login,
            'password' => $password,
            'is_active' => 1,
        ];

        if (Auth::guard('customer')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            // Redirect to intended URL or fallback to homepage
            return redirect()->intended(url('/'));
        }

        return back()
            ->withErrors(['login' => __('auth.failed')])
            ->withInput($request->only('login', 'remember'));
    }

    /**
     * Logout customer.
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
