<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Reset Password - Piyari Family</title>
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    @include('partials.toastr-styles')
    <style>
        :root { --primary: #6E0016; }
        html, body { background: #fff !important; min-height: 100%; }
        .auth-page-wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 48px 24px; }
        .auth-card { width: 100%; max-width: 420px; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: #5a0c0e; border-color: #5a0c0e; }
    </style>
</head>
<body>
    <section class="auth-page-wrap">
        <div class="auth-card">
            <div class="text-center mb-4">
                <img src="{{ asset('assets/img/piyari_logo.png') }}" alt="Piyari Family" width="200" class="mb-3">
                <h4 class="mb-1">Reset Password</h4>
                <p class="text-muted mb-0">Marriage Bureau Panel</p>
            </div>

            <form action="{{ route('marriage-bureau.password.reset-store', $token) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $bureau->email) }}" required>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Reset Password</button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('marriage-bureau.login') }}">Back to Login</a>
            </div>
        </div>
    </section>
    <script src="{{ asset('global/js/jquery-3.7.1.min.js') }}"></script>
    @include('partials.toastr-scripts')
</body>
</html>
