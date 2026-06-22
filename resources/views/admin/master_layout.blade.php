@php
    $header_user = Auth::guard('admin')->user();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <link type="image/x-icon" href="{{ asset($setting->favicon ?? '') }}" rel="shortcut icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('title')
    @include('admin.partials.styles')
    @stack('css')
    
    <style>
        /* Piyari Family Premium Theme */
        :root {
            --primary: #7B1113; /* Burgundy */
            --primary-hover: #5a0c0e;
            --secondary: #F5A623; /* Gold */
            --bg-color: #FFF5F5; /* Soft Pink/Cream */
        }
        body {
            background-color: var(--bg-color);
        }
        .navbar-bg {
            background-color: var(--primary) !important;
            height: 70px;
        }
        .main-sidebar {
            background-color: #fff;
            border-right: 2px solid var(--secondary);
            overflow-y: auto;
            height: 100vh;
        }
        .sidebar-menu li.active a {
            color: var(--primary) !important;
            font-weight: bold;
        }
        .sidebar-menu li a:hover {
            color: var(--secondary) !important;
        }
        .btn-primary {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #fff !important;
        }
        .btn-primary:hover {
            background-color: var(--primary-hover) !important;
            border-color: var(--primary-hover) !important;
        }
        .btn-warning, .btn-secondary {
            background-color: var(--secondary) !important;
            border-color: var(--secondary) !important;
            color: #fff !important;
        }
        .card {
            border-top: 3px solid var(--primary);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .brand-text {
            color: var(--primary);
            font-weight: bold;
        }
        .logo-img {
            max-height: 40px;
        }
    </style>
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar px-3 py-2">
                <div class="me-2 form-inline">
                    <ul class="navbar-nav d-flex align-items-center">
                        <li><a class="nav-link nav-link-lg" data-toggle="sidebar" href="javascript:void(0)"><i class="fas fa-bars"></i></a></li>
                        <img src="{{ asset('assets/img/piyari_logo.png') }}" alt="Piyari Family Logo" class="logo-img me-2" onerror="this.src='https://via.placeholder.com/150x40/7B1113/FFFFFF?text=Piyari+Family'">
                        <p style="margin: 0; padding: 0; color: #ffffff; font-size: 20px; font-weight: bold;">Admin Panel</p>
                    </ul>
                </div>
                <div class="mr-auto me-md-auto search-box position-relative">
                </div>
                <ul class="navbar-nav navbar-right ml-auto">
                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            @if ($header_user?->image)
                                <img alt="image" src="{{ asset($header_user?->image) }}" class="rounded-circle mr-1">
                            @else
                                <img alt="image" src="{{ asset('backend/img/avatar/avatar-1.png') }}"
                                    class="rounded-circle mr-1">
                            @endif
                            <div class="d-sm-none d-lg-inline-block">{{ $header_user?->name ?? 'Admin' }} - Super Admin</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{ route('admin.edit-profile') }}" class="dropdown-item has-icon">
                                <i class="far fa-user"></i> {{ __('Profile') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item has-icon text-danger" style="display: flex; align-items: center; border:none; background:none; width: 100%; cursor: pointer;">
                                    <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>

            @include('admin.sidebar')

            <!-- Main Content -->
            @yield('admin-content')

            <footer class="main-footer">
                <div class="footer-left">
                    {{-- {{ $setting?->copyright }} --}}
                    Piyari Family &copy; {{ date('Y') }}
                </div>
                <div class="footer-right">
                    {{-- {{ $setting?->version }} --}}
                </div>
            </footer>
        </div>
    </div>

    @include('admin.partials.javascripts')

    @stack('js')

</body>

</html>
