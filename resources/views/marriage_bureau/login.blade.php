<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Marriage Bureau Login - Piyari Family</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/fontawesome/css/all.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/components.css') }}">
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
    </style>
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="d-flex flex-wrap align-items-stretch">
                <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">
                    <div class="p-4 m-3">
                        <div class="mb-5 text-center">
                            <img src="{{ asset('assets/img/piyari_logo.png') }}" alt="Piyari Family" width="200" class="mb-4 mt-2" onerror="this.src='https://via.placeholder.com/200x50/7B1113/FFFFFF?text=Piyari+Family'">
                            <h4 class="text-dark font-weight-normal">Welcome to <span class="font-weight-bold">Piyari Family</span></h4>
                            <p class="text-muted">Marriage Bureau Login</p>
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

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right" tabindex="4">
                                    Login
                                </button>
                            </div>
                            
                            <div class="mt-5 text-center">
                                Don't have an account? <a href="{{ route('marriage-bureau.register') }}">Register here</a>
                            </div>
                        </form>

                        <div class="text-center mt-5 text-small">
                            Copyright &copy; Piyari Family.
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom"
                    data-background="{{ asset('backend/img/admin-auth-bg.jpg') }}" style="background-image: linear-gradient(135deg, rgba(123, 17, 19, 0.8) 0%, rgba(245, 166, 35, 0.6) 100%), url('{{ asset('backend/img/admin-auth-bg.jpg') }}'); background-size: cover; background-position: center;">
                    <div class="absolute-bottom-left index-2">
                        <div class="text-light p-5 pb-2">
                            <div class="mb-5 pb-3">
                                <h1 class="mb-2 display-4 font-weight-bold">Marriage Bureau Panel</h1>
                                <h5 class="font-weight-normal text-muted-transparent">Manage your clients effectively</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('backend/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('backend/js/popper.min.js') }}"></script>
    <script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
</body>
</html>
