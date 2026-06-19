@php
    $header_admin = Auth::guard('admin')->user();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <link type="image/x-icon" href="{{ asset($setting->favicon) }}" rel="shortcut icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('title')
    @include('admin.partials.styles')
    @stack('css')
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar px-3 py-2">
                <div class="me-2 form-inline">
                    <ul class="navbar-nav d-flex align-items-center">
                        <li><a class="nav-link nav-link-lg" data-toggle="sidebar" href="#"><i
                                    class="fas fa-bars"></i></a></li>
									<p style="margin: 0; padding: 0; color: #ffffff; font-size: 20px;">Pairi Family Admin</p>
                    </ul>
                </div>
                <div class="mr-auto me-md-auto search-box position-relative">
                    <div class="position-absolute d-none rounded-2" id="admin_menu_list">
                        <a class="not-found-message d-none" href="javascript:;">{{ __('Not Found!') }}</a>
                    </div>
                </div>

                <ul class="navbar-nav">
                    <!-- @include('admin.partials.notifications', [
                        'adminNotifications' => Cache::get('admin-notifications', collect([])),
                    ])-->

                    

                    <li class="dropdown"><a class="nav-link dropdown-toggle nav-link-lg nav-link-user dropdown_user"
                            data-bs-toggle="dropdown" href="javascript:;">
                            @if ($header_admin->image)
                                <img class="me-1 rounded-circle" src="{{ asset($header_admin->image) }}"
                                    alt="image">
                            @else
                                <img class="me-1 rounded-circle" src="{{ asset($setting->default_avatar) }}"
                                    alt="image">
                            @endif

                            <div class="d-sm-none d-lg-inline-block">{{ $header_admin->name }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item has-icon d-flex align-items-center {{ isRoute(['admin.edit-profile'], 'text-primary') }}"
                                href="{{ route('admin.edit-profile') }}">
                                <i class="far fa-user"></i> {{ __('Profile') }}
                            </a>
                            <a class="dropdown-item has-icon d-flex align-items-center" href="javascript:;"
                                onclick="event.preventDefault(); $('#admin-logout-form').trigger('submit');">
                                <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                            </a>
                        </div>
                    </li>

                </ul>
            </nav>

            @include('admin.sidebar')
            @yield('admin-content')

            <footer class="main-footer">
                <div class="footer-left">
                   Pairi Family &copy; {{ date('Y') }}
                </div>
                <!-- <div class="footer-right">
                    <span>{{ __('version') }}: {{ $setting->version ?? '' }} ({{ __('Loaded in') }}
                        %%LOAD_TIME%%)</span>
                </div> -->
            </footer>

        </div>
    </div>

    {{-- start admin logout form --}}
    <form class="d-none" id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST">
        @csrf
    </form>
    {{-- end admin logout form --}}

    {{-- delete modal --}}
    <x-admin.delete-modal />

    @stack('modals')

    @include('admin.partials.javascripts')
    @include('admin.js-variables')
    @stack('js')

</body>

</html>
