@php
    $profileRoute = $profileRoute ?? '#';
    $logoutRoute = $logoutRoute ?? '#';
    $userName = $userName ?? 'User';
    $userImage = $userImage ?? null;
@endphp

<ul class="navbar-nav navbar-right ml-auto">
    <li class="dropdown">
        <a href="#" class="nav-link dropdown-toggle nav-link-lg nav-link-user" data-bs-toggle="dropdown" data-turbolinks="false" role="button" aria-expanded="false">
            @if ($userImage)
                <img alt="image" src="{{ asset($userImage) }}" class="rounded-circle mr-1" width="30" height="30" style="object-fit:cover;">
            @else
                <img alt="image" src="{{ asset('backend/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1" width="30" height="30">
            @endif
            <span class="d-sm-none d-lg-inline-block text-white">{{ $userName }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right panel-user-dropdown">
            <a href="{{ $profileRoute }}" class="dropdown-item has-icon">
                <i class="fas fa-user"></i> {{ __('Profile') }}
            </a>
            <div class="dropdown-divider"></div>
            <form id="panel-logout-form" action="{{ $logoutRoute }}" method="POST" class="d-none">
                @csrf
            </form>
            <a href="#" class="dropdown-item has-icon" onclick="event.preventDefault(); document.getElementById('panel-logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
            </a>
        </div>
    </li>
</ul>
