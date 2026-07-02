@extends('admin.auth.app')
@section('title')
    <title>{{ __('Admin Login - Piyari Family') }}</title>
@endsection
@section('content')
<style>
    :root {
        --primary: #6E0016;
        --secondary: #F5A623;
    }
    body, #app, .section {
        background: #ffffff !important;
    }
    .btn-primary {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
    }
    .btn-primary:hover {
        background-color: #5a0c0e !important;
        border-color: #5a0c0e !important;
    }
    .text-primary {
        color: var(--primary) !important;
    }
    .login-page-wrap {
        min-height: 100vh;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }
    .login-card {
        width: 100%;
        max-width: 420px;
    }
</style>
<section class="section login-page-wrap">
    <div class="login-card">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/img/piyari_logo.png') }}" alt="Piyari Family" width="200" class="mb-3" onerror="this.src='https://via.placeholder.com/200x50/7B1113/FFFFFF?text=Piyari+Family'">
            <h4 class="text-dark font-weight-normal mb-1">Welcome to <span class="font-weight-bold">Piyari Family</span></h4>
            <p class="text-muted mb-0">System Admin Panel</p>
        </div>

        <form id="adminLoginForm" action="{{ route('admin.store-login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email', 'admin@pairifamily.com') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control" name="password" required>
            </div>

            <div class="form-group d-flex justify-content-between align-items-center">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="remember">{{ __('Remember Me') }}</label>
                </div>
                <a href="{{ route('admin.password.request') }}" class="text-small text-primary">
                    Forgot Password?
                </a>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                    {{ __('Login') }}
                </button>
            </div>
        </form>

        <div class="text-center mt-4 text-small text-muted">
            Copyright &copy; Piyari Family.
        </div>
    </div>
</section>
@endsection
