<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Link Expired - Piyari Family</title>
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
    <style>
        html, body { background: #fff; min-height: 100%; }
        .auth-page-wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
    </style>
</head>
<body>
    <section class="auth-page-wrap text-center">
        <div>
            <img src="{{ asset('assets/img/piyari_logo.png') }}" alt="Piyari Family" width="180" class="mb-3">
            <h4>Reset link expired or invalid</h4>
            <p class="text-muted">Please request a new password reset link.</p>
            <a href="{{ route('marriage-bureau.password.request') }}" class="btn btn-primary mt-3">Request New Link</a>
        </div>
    </section>
</body>
</html>
