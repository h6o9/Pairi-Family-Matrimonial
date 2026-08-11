<style>
    .sidebar-brand.panel-logo-brand {
        height: auto !important;
        min-height: 82px;
        padding: 7px 10px;
    }

    .panel-brand-logo {
        width: 145px;
        height: 68px;
        object-fit: contain;
    }

    .sidebar-brand-sm .panel-brand-logo {
        width: 42px;
        height: 42px;
    }

    .mb-sidebar-group {
        border-bottom: 1px solid #eeeeee;
    }

    .mb-sidebar-toggle {
        display: flex !important;
        align-items: center;
        cursor: pointer;
        font-weight: 600;
        margin: 4px 8px;
        border-radius: 8px;
        padding: 11px 12px !important;
    }

    .mb-sidebar-toggle .menu-caret {
        margin-left: auto !important;
        margin-right: 0 !important;
        width: 24px !important;
        height: 24px;
        border-radius: 50%;
        background: #f2e7eb;
        color: #7B1E3A;
        align-items: center;
        justify-content: center;
        transition: transform 0.25s ease, background-color 0.25s ease;
    }

    .mb-sidebar-toggle .minus-icon,
    .mb-sidebar-toggle[aria-expanded="true"] .plus-icon {
        display: none;
    }

    .mb-sidebar-toggle[aria-expanded="true"] .minus-icon {
        display: inline-flex;
    }

    .mb-sidebar-toggle[aria-expanded="false"] .plus-icon {
        display: inline-flex;
    }

    .mb-sidebar-toggle[aria-expanded="true"] {
        background: #f8eef1;
        color: #7B1E3A;
    }

    body.sidebar-mini .main-sidebar .mb-sidebar-toggle .menu-caret {
        display: none !important;
    }

    body.sidebar-mini .main-sidebar .mb-sidebar-submenu,
    body.sidebar-gone .main-sidebar .mb-sidebar-submenu {
        display: none !important;
        height: 0 !important;
    }

    .mb-sidebar-submenu {
        list-style: none;
        margin: 0 10px 6px 22px;
        padding: 4px 0;
        background: #fafafa;
        border-left: 2px solid #ead4dc;
        border-radius: 0 8px 8px 0;
    }

    .mb-sidebar-submenu a {
        display: block;
        padding: 9px 15px 9px 48px;
        font-size: 13px;
    }

    .mb-sidebar-submenu a.active {
        color: #7B1E3A;
        background: #f2e7eb;
        border-left: 3px solid #7B1E3A;
        font-weight: 600;
    }
</style>

<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand panel-logo-brand">
            <a href="{{ route('marriage-bureau.dashboard') }}">
                <img src="{{ asset('assets/img/piyari_logo.png') }}" alt="Piyari Family" class="panel-brand-logo">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('marriage-bureau.dashboard') }}">
                <img src="{{ asset('assets/img/piyari_logo.png') }}" alt="PF" class="panel-brand-logo">
            </a>
        </div>
        @php
            $subscriptionsOpen = request()->routeIs('marriage-bureau.subscription.*');
            $usersOpen = request()->routeIs('marriage-bureau.users.*');
        @endphp
        <ul class="sidebar-menu" id="marriageBureauSidebarMenu">
            <li>
                <a class="nav-link {{ request()->routeIs('marriage-bureau.dashboard') ? 'active' : '' }}" href="{{ route('marriage-bureau.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="mb-sidebar-group">
                <a class="nav-link mb-sidebar-toggle" data-bs-toggle="collapse" href="#mbSubscriptionsMenu" role="button" aria-expanded="{{ $subscriptionsOpen ? 'true' : 'false' }}" aria-controls="mbSubscriptionsMenu">
                    <i class="fas fa-crown"></i> <span>Subscriptions</span>
                    <i class="fas fa-plus menu-caret plus-icon"></i>
                    <i class="fas fa-minus menu-caret minus-icon"></i>
                </a>
                <ul id="mbSubscriptionsMenu" class="mb-sidebar-submenu collapse {{ $subscriptionsOpen ? 'show' : '' }}" data-bs-parent="#marriageBureauSidebarMenu">
                    <li><a href="{{ route('marriage-bureau.subscription.index') }}" class="{{ request()->routeIs('marriage-bureau.subscription.*') ? 'active' : '' }}">Premium Plans</a></li>
                </ul>
            </li>

            <li class="mb-sidebar-group">
                <a class="nav-link mb-sidebar-toggle" data-bs-toggle="collapse" href="#mbUsersMenu" role="button" aria-expanded="{{ $usersOpen ? 'true' : 'false' }}" aria-controls="mbUsersMenu">
                    <i class="fas fa-users"></i> <span>Management</span>
                    <i class="fas fa-plus menu-caret plus-icon"></i>
                    <i class="fas fa-minus menu-caret minus-icon"></i>
                </a>
                <ul id="mbUsersMenu" class="mb-sidebar-submenu collapse {{ $usersOpen ? 'show' : '' }}" data-bs-parent="#marriageBureauSidebarMenu">
                    <li><a href="{{ route('marriage-bureau.users.index') }}" class="{{ request()->routeIs('marriage-bureau.users.*') ? 'active' : '' }}">Manage Users</a></li>
                </ul>
            </li>
        </ul>
    </aside>
</div>
