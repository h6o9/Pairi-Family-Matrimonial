<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Marriage Bureau Login - Piyari Family</title>

    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/components.css') }}">
    @include('partials.toastr-styles')
    <style>
        :root {
            --primary: #6E0016;
            --secondary: #F5A623;
        }
        html, body, #app {
            background: #ffffff !important;
            min-height: 100%;
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
</head>

<body>
    <div id="app">
        <section class="section login-page-wrap">
            <div class="login-card">
                <div class="text-center mb-4">
                    <img src="{{ asset('assets/img/piyari_logo.png') }}" alt="Piyari Family" width="200" class="mb-3" onerror="this.src='https://via.placeholder.com/200x50/7B1113/FFFFFF?text=Piyari+Family'">
                    <h4 class="text-dark font-weight-normal mb-1">Welcome to <span class="font-weight-bold">Piyari Family</span></h4>
                    <p class="text-muted mb-0">Marriage Bureau Login</p>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('marriage-bureau.login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="control-label">Password</label>
                        <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group text-right mb-3">
                        <a href="{{ route('marriage-bureau.password.request') }}" class="text-primary">Forgot Password?</a>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                            Login
                        </button>
                    </div>

                    <div class="mt-4 text-center">
                        Don't have an account? <a href="{{ route('marriage-bureau.register') }}">Register here</a>
                    </div>
                </form>

                <div class="text-center mt-4 text-small text-muted">
                    Copyright &copy; Piyari Family.
                </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('backend/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('backend/js/popper.min.js') }}"></script>
    <script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
    @include('partials.toastr-scripts')
</body>
</html>
