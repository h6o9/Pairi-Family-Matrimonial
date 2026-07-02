<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Forgot Password - Piyari Family</title>
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
    <style>
        :root { --primary: #6E0016; }
        html, body { background: #fff !important; min-height: 100%; }
        .auth-page-wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 48px 24px; }
        .auth-card { width: 100%; max-width: 420px; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: #5a0c0e; border-color: #5a0c0e; }
        .text-primary { color: var(--primary) !important; }
    </style>
</head>
<body>
    <section class="auth-page-wrap">
        <div class="auth-card">
            <div class="text-center mb-4">
                <img src="{{ asset('assets/img/piyari_logo.png') }}" alt="Piyari Family" width="200" class="mb-3">
                <h4 class="mb-1">Forgot Password</h4>
                <p class="text-muted mb-0">Marriage Bureau Panel</p>
            </div>

            @if(session('message'))
                <div class="alert alert-{{ session('alert-type') === 'error' ? 'danger' : 'success' }}">{{ session('message') }}</div>
            @endif

            <form action="{{ route('marriage-bureau.password.email') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Send Reset Link</button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('marriage-bureau.login') }}" class="text-primary">Back to Login</a>
            </div>
        </div>
    </section>
</body>
</html>
