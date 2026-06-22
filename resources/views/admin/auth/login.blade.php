@extends('admin.auth.app')
@section('title')
    <title>{{ __('Admin Login - Piyari Family') }}</title>
@endsection
@section('content')
<style>
    :root {
        --primary: #7B1113;
        --secondary: #F5A623;
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
    .auth-gradient {
        background-image: linear-gradient(135deg, rgba(123, 17, 19, 0.85) 0%, rgba(245, 166, 35, 0.6) 100%), url('{{ asset("backend/img/admin-auth-bg.jpg") }}');
        background-size: cover;
        background-position: center;
    }
</style>
<section class="section">
    <div class="d-flex flex-wrap align-items-stretch">
        <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">
            <div class="p-4 m-3">
                <div class="mb-5 text-center">
                    <img src="{{ asset('assets/img/piyari_logo.png') }}" alt="Piyari Family" width="200" class="mb-4 mt-2" onerror="this.src='https://via.placeholder.com/200x50/7B1113/FFFFFF?text=Piyari+Family'">
                    <h4 class="text-dark font-weight-normal">Welcome to <span class="font-weight-bold">Piyari Family</span></h4>
                    <p class="text-muted">System Admin Panel</p>
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

                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                            {{ __('Login') }}
                        </button>
                    </div>
                </form>

                <div class="text-center mt-5 text-small">
                    Copyright &copy; Piyari Family.
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom auth-gradient">
            <div class="absolute-bottom-left index-2">
                <div class="text-light p-5 pb-2">
                    <div class="mb-5 pb-3">
                        <h1 class="mb-2 display-4 font-weight-bold">Admin Panel</h1>
                        <h5 class="font-weight-normal text-muted-transparent">Manage the entire ecosystem</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
