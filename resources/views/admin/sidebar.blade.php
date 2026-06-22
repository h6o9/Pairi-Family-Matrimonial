<style>
.main-sidebar {
    height: 100vh;
    overflow-y: auto;
    background: #ffffff;
}

#sidebar-wrapper {
    height: 100%;
    overflow-y: auto;
}

.sidebar-brand {
    padding: 20px 15px;
    text-align: center;
    border-bottom: 1px solid #dee2e6;
}

.sidebar-brand a {
    display: inline-block;
    text-decoration: none;
}

.sidebar-brand .brand-text {
    font-size: 22px;
    font-weight: 700;
    color: #7B1E3A;
}

.sidebar-menu {
    list-style: none;
    padding: 0 0 20px 0;
    margin: 0;
}

.sidebar-menu .menu-header {
    margin: 15px 0 5px;
    padding: 5px 20px;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #6c757d;
    font-weight: 600;
}

.sidebar-menu li a {
    display: flex;
    align-items: center;
    padding: 10px 20px;
    color: #333333;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.2s;
}

.sidebar-menu li a:hover {
    background: #e9ecef;
    color: #000;
}

.sidebar-menu li a.active {
    background: #7B1E3A;
    color: white;
}

.sidebar-menu li a i {
    width: 24px;
    margin-right: 10px;
    text-align: center;
    color: inherit;
}
</style>

<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                <span class="brand-text">Piyari Family</span>
            </a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">{{ __('Dashboard') }}</li>
            <li class="{{ isRoute('admin.dashboard', 'active') }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>

            <li class="menu-header">{{ __('Users') }}</li>
            <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}">
                    <i class="fas fa-user-friends"></i>
                    <span>{{ __('All Users') }}</span>
                </a>
            </li>

            <li class="menu-header">{{ __('Features') }}</li>
            <li class="{{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                <a href="{{ route('admin.subscriptions.index') }}">
                    <i class="fas fa-gem"></i>
                    <span>{{ __('Subscriptions') }}</span>
                </a>
            </li>

            <li class="menu-header">{{ __('Marriage Bureau Panel') }}</li>
            <li class="{{ request()->routeIs('admin.marriage-bureaus.*') ? 'active' : '' }}">
                <a href="{{ route('admin.marriage-bureaus.index') }}">
                    <i class="fas fa-building"></i>
                    <span>{{ __('Marriage Bureaus') }}</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.marriage-bureau-subscriptions.*') ? 'active' : '' }}">
                <a href="{{ route('admin.marriage-bureau-subscriptions.index') }}">
                    <i class="fas fa-certificate"></i>
                    <span>{{ __('MB Subscriptions') }}</span>
                </a>
            </li>
            
            <li class="menu-header">{{ __('Configuration') }}</li>
            <li class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.index') }}">
                    <i class="fas fa-cogs"></i>
                    <span>{{ __('System Settings') }}</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
