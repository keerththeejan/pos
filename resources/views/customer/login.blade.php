@extends('layouts.guest')

@section('title', __('Customer Login'))

@section('content')
<style>
    .auth-wrap {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 32px 16px;
        background: linear-gradient(135deg, #f8fafc 0%, #eef2f7 100%);
    }
    .auth-card {
        width: 100%;
        max-width: 440px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(16,24,40,0.08);
        overflow: hidden;
        border: 1px solid #edf2f7;
    }
    .auth-header {
        padding: 22px 26px;
        border-bottom: 1px solid #edf2f7;
        background: #ffffff;
    }
    .auth-title { margin: 0; font-weight: 700; color: #111827; font-size: 20px; }
    .auth-subtitle { margin: 4px 0 0; color: #6b7280; font-size: 13px; }
    .auth-body { padding: 24px 26px 26px; }
    .form-label { font-weight: 600; color: #374151; font-size: 13px; margin-bottom: 6px; }
    .input {
        width: 100%;
        height: 44px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 0 12px;
        outline: none;
        transition: box-shadow .2s, border-color .2s;
        background: #f9fafb;
    }
    .input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.15); background: #fff; }
    .form-row { margin-bottom: 14px; }
    .remember { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #4b5563; margin: 8px 0 16px; }
    .btn-primary-custom {
        display: inline-block;
        background: linear-gradient(90deg, #6366f1, #3b82f6);
        color: #fff;
        border: none;
        height: 44px;
        line-height: 44px;
        padding: 0 18px;
        border-radius: 10px;
        font-weight: 600;
        box-shadow: 0 6px 16px rgba(59,130,246,.25);
        transition: transform .08s ease-in-out, box-shadow .2s;
        width: 100%;
    }
    .btn-primary-custom:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(59,130,246,.3); }
    .actions { display:flex; justify-content: space-between; align-items:center; gap: 10px; margin-top: 8px; }
    .link-muted { color: #6b7280; text-decoration: none; }
    .link-muted:hover { color: #374151; text-decoration: underline; }
    .errors { border:1px solid #fecaca; background:#fff1f2; color:#991b1b; padding:10px 12px; border-radius:10px; font-size:13px; margin-bottom:12px; }
    .errors ul { margin:0; padding-left:18px; }
    .brand-top { text-align:center; padding-top: 6px; color:#6b7280; font-size:12px; }
</style>

<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-subtitle">Login to your POS account</p>
        </div>
        <div class="auth-body">
            @if ($errors->any())
                <div class="errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('customer.login') }}" novalidate>
                @csrf

                <div class="form-row">
                    <label class="form-label" for="login">Email or Username</label>
                    <input id="login" type="text" class="input" name="login" value="{{ old('login') }}" required autofocus placeholder="Enter email or username">
                </div>

                <div class="form-row">
                    <label class="form-label" for="password">Password</label>
                    <input id="password" type="password" class="input" name="password" required placeholder="Enter password">
                </div>

                <label class="remember">
                    <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}> Remember me
                </label>

                <button type="button" class="btn-primary-custom" onclick="window.location.href='http://pos/login'">
    Login
</button>



                <div class="actions">
                    <a href="{{ url('/') }}" class="link-muted">Back to Home</a>
                    <a href="{{ route('customer.login.show') }}" class="link-muted">Need help?</a>
                </div>
            </form>
            <div class="brand-top">© {{ date('Y') }} POS</div>
        </div>
    </div>
  </div>
@endsection
