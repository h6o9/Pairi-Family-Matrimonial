<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('marriage-bureau.dashboard') }}">Piyari Family</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('marriage-bureau.dashboard') }}">PF</a>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ isRoute('marriage-bureau.dashboard', 'active') }}">
                <a class="nav-link" href="{{ route('marriage-bureau.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="menu-header">Subscriptions</li>
            <li class="{{ isRoute('marriage-bureau.subscription.index', 'active') }}">
                <a class="nav-link" href="{{ route('marriage-bureau.subscription.index') }}">
                    <i class="fas fa-crown"></i> <span>Premium Plans</span>
                </a>
            </li>

            <li class="menu-header">Management</li>
            <li class="{{ isRoute('marriage-bureau.users.*', 'active') }}">
                <a class="nav-link" href="{{ route('marriage-bureau.users.index') }}">
                    <i class="fas fa-users"></i> <span>Manage Users</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
