@extends('admin.auth.app')
@section('title')
    <title>{{ __('Login') }}</title>
@endsection
@section('content')
    <div class="auth-bg-gradient py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card overflow-hidden">
                        <div class="row g-0">
                            <div class="col-md-6 d-none d-md-block">
                                <div class="h-100">
                                    <img class="h-100 w-100 object-fit-fill" src="{{ asset('backend/img/admin-auth-bg.jpg') }}"
                                        alt="Pairi Family">
                                </div>
                            </div>
                            <!-- end col -->

                            <div class="col-md-6">
                                <div class="row d-flex justify-content-center align-items-center h-100">
                                    <div class="p-5">
                                        <div>
                                            <a class="d-block text-center" href="#">
                                                <img src="{{ asset('backend/img/admin-auth-bg.jpg') }}" alt="Pairi Family"
                                                    width="220">
                                            </a>
                                        </div>

                                        <div class="mt-4">
                                            <form id="adminLoginForm" action="{{ route('admin.store-login') }}"
                                                method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <x-admin.form-input id="email" name="email" type="email"
                                                        value="{{ old('email', 'admin@pairifamily.com') }}" label="{{ __('Email') }}"
                                                        required="true" />
                                                </div>

                                                <div class="mb-2">
                                                    <x-admin.form-input id="password" name="password" type="password"
                                                        label="{{ __('Password') }}" required="true" />
                                                </div>

                                                <p class="text-muted small mb-3">
                                                    Admin login: <strong>admin@pairifamily.com</strong> / <strong>password123</strong>
                                                </p>

                                                <div class="mb-2 d-flex justify-content-between">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="remember" name="remember"
                                                            type="checkbox" tabindex="3"
                                                            {{ old('remember') ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="remember">{{ __('Remember Me') }}</label>
                                                    </div>
                                                </div>

                                                <x-admin.button class="btn w-100" type="submit"
                                                    text="{{ __('Login') }}" />

                                            </form>
                                        </div>
                                        <div class="mt-3 text-center">
                                            <p>{{ __('Forgot your password?') }} <a class="fw-semibold text-primary"
                                                    href="{{ route('admin.password.request') }}">{{ __('Reset Password') }}</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
@endsection
