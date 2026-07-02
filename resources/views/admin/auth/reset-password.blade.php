@extends('admin.auth.app')
@section('title')
    <title>{{ __('Reset Password') }} - Piyari Family</title>
@endsection
@section('content')
<style>
    :root { --primary: #6E0016; }
    body, #app, .section { background: #fff !important; }
    .auth-page-wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 48px 24px; }
    .auth-card { width: 100%; max-width: 420px; }
    .btn-primary { background: var(--primary); border-color: var(--primary); }
    .btn-primary:hover { background: #5a0c0e; border-color: #5a0c0e; }
</style>
<section class="section auth-page-wrap">
    <div class="auth-card">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/img/piyari_logo.png') }}" alt="Piyari Family" width="200" class="mb-3">
            <h4 class="mb-1">{{ __('Reset Password') }}</h4>
            <p class="text-muted mb-0">Admin Panel</p>
        </div>

        <form action="{{ route('admin.password.reset-store', $token) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email', $admin->email) }}" required>
            </div>
            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block">{{ __('Reset Password') }}</button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('admin.login') }}">{{ __('Go to login page') }}</a>
        </div>
    </div>
</section>
@endsection
