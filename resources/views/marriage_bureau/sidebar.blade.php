<style>
    .mb-sidebar-group {
        border-bottom: 1px solid #eeeeee;
    }

    .mb-sidebar-toggle {
        display: flex !important;
        align-items: center;
        cursor: pointer;
        font-weight: 600;
    }

    .mb-sidebar-toggle .menu-caret {
        margin-left: auto !important;
        margin-right: 0 !important;
        width: 16px !important;
    }

    .mb-sidebar-toggle .minus-icon,
    .mb-sidebar-toggle[aria-expanded="true"] .plus-icon {
        display: none;
    }

    .mb-sidebar-toggle[aria-expanded="true"] .minus-icon {
        display: inline-block;
    }

    .mb-sidebar-submenu {
        list-style: none;
        margin: 0;
        padding: 0 0 6px;
        background: #fafafa;
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
        <div class="sidebar-brand">
            <a href="{{ route('marriage-bureau.dashboard') }}">Piyari Family</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('marriage-bureau.dashboard') }}">PF</a>
        </div>
        @php
            $subscriptionsOpen = request()->routeIs('marriage-bureau.subscription.*');
            $usersOpen = request()->routeIs('marriage-bureau.users.*');
        @endphp
        <ul class="sidebar-menu">
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
                <ul id="mbSubscriptionsMenu" class="mb-sidebar-submenu collapse {{ $subscriptionsOpen ? 'show' : '' }}">
                    <li><a href="{{ route('marriage-bureau.subscription.index') }}" class="{{ request()->routeIs('marriage-bureau.subscription.*') ? 'active' : '' }}">Premium Plans</a></li>
                </ul>
            </li>

            <li class="mb-sidebar-group">
                <a class="nav-link mb-sidebar-toggle" data-bs-toggle="collapse" href="#mbUsersMenu" role="button" aria-expanded="{{ $usersOpen ? 'true' : 'false' }}" aria-controls="mbUsersMenu">
                    <i class="fas fa-users"></i> <span>Management</span>
                    <i class="fas fa-plus menu-caret plus-icon"></i>
                    <i class="fas fa-minus menu-caret minus-icon"></i>
                </a>
                <ul id="mbUsersMenu" class="mb-sidebar-submenu collapse {{ $usersOpen ? 'show' : '' }}">
                    <li><a href="{{ route('marriage-bureau.users.index') }}" class="{{ request()->routeIs('marriage-bureau.users.*') ? 'active' : '' }}">Manage Users</a></li>
                </ul>
            </li>
        </ul>
    </aside>
</div>
