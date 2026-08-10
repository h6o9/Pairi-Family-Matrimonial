<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Marriage Bureau Registration - Piyari Family</title>

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
        .auth-page-wrap {
            min-height: 100vh;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 24px;
            box-sizing: border-box;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }
        .register-avatar {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div id="app">
        <section class="section auth-page-wrap">
            <div class="auth-card">
                <div class="text-center mb-4">
                    <img src="{{ asset('assets/img/piyari_logo.png') }}" alt="Piyari Family" width="200" class="mb-3" onerror="this.src='https://via.placeholder.com/200x50/7B1113/FFFFFF?text=Piyari+Family'">
                    <h4 class="text-dark font-weight-normal mb-1">Welcome to <span class="font-weight-bold">Piyari Family</span></h4>
                    <p class="text-muted mb-0">Marriage Bureau Registration</p>
                </div>

                <form method="POST" action="{{ route('marriage-bureau.register') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group text-center mb-4">
                        <img id="register-avatar-preview" src="{{ asset('backend/img/avatar/avatar-1.png') }}" alt="Profile" class="rounded-circle register-avatar mb-2">
                        <div>
                            <label class="btn btn-sm btn-primary mt-1 mb-0">
                                Upload Image
                                <input type="file" id="image" name="image" class="d-none" accept="image/jpeg,image/png,image/jpg">
                            </label>
                        </div>
                        @error('image')
                            <span class="text-danger d-block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name">Bureau Name</label>
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" class="form-control" name="password" required>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Register
                        </button>
                    </div>

                    <div class="mt-4 text-center">
                        Already have an account? <a href="{{ route('marriage-bureau.login') }}">Login here</a>
                    </div>
                </form>

                <div class="text-center mt-4 text-small text-muted">
                    Copyright &copy; Piyari Family.
                </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('backend/js/popper.min.js') }}"></script>
    <script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
    <script>
        document.getElementById('image').addEventListener('change', function (e) {
            var file = e.target.files[0];
            if (file) {
                document.getElementById('register-avatar-preview').src = URL.createObjectURL(file);
            }
        });
    </script>
    @include('partials.toastr-scripts')
</body>
</html>
