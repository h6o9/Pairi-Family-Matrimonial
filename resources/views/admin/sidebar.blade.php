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

.sidebar-menu > li {
    border-bottom: 1px solid #eeeeee;
}

.sidebar-group-toggle {
    font-weight: 600;
    cursor: pointer;
}

.sidebar-group-toggle .menu-caret {
    width: 16px;
    margin-right: 0;
    margin-left: auto;
    transition: transform 0.2s;
}

.sidebar-group-toggle .minus-icon,
.sidebar-group-toggle[aria-expanded="true"] .plus-icon {
    display: none;
}

.sidebar-group-toggle[aria-expanded="true"] .minus-icon {
    display: inline-block;
}

.sidebar-submenu {
    list-style: none;
    margin: 0;
    padding: 0 0 6px;
    background: #fafafa;
}

.sidebar-submenu li a {
    padding: 9px 15px 9px 45px;
    font-size: 13px;
    border-left: 3px solid transparent;
}

.sidebar-submenu li a::before {
    content: "\203A";
    margin-right: 10px;
    color: #9a9a9a;
}

.sidebar-submenu li a.active {
    color: #7B1E3A;
    background: #f2e7eb;
    border-left-color: #7B1E3A;
    font-weight: 600;
}
</style>

<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                <span class="brand-text">Piyari Family</span>
            </a>
        </div>

        @php
            $usersOpen = request()->routeIs('admin.users.*');
            $featuresOpen = request()->routeIs('admin.subscriptions.*', 'admin.notifications.*');
            $marriageBureauOpen = request()->routeIs('admin.marriage-bureaus.*', 'admin.marriage-bureau-subscriptions.*');
            $profileSettingsOpen = request()->routeIs('admin.lookups.*');
            $configurationOpen = request()->routeIs('admin.settings.*');
        @endphp

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>

            <li>
                <a class="sidebar-group-toggle" data-bs-toggle="collapse" href="#usersMenu" role="button" aria-expanded="{{ $usersOpen ? 'true' : 'false' }}" aria-controls="usersMenu">
                    <i class="fas fa-user-friends"></i>
                    <span>{{ __('Users') }}</span>
                    <i class="fas fa-plus menu-caret plus-icon"></i>
                    <i class="fas fa-minus menu-caret minus-icon"></i>
                </a>
                <ul id="usersMenu" class="sidebar-submenu collapse {{ $usersOpen ? 'show' : '' }}">
                    <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">{{ __('All Users') }}</a></li>
                </ul>
            </li>

            <li>
                <a class="sidebar-group-toggle" data-bs-toggle="collapse" href="#featuresMenu" role="button" aria-expanded="{{ $featuresOpen ? 'true' : 'false' }}" aria-controls="featuresMenu">
                    <i class="fas fa-layer-group"></i>
                    <span>{{ __('Features') }}</span>
                    <i class="fas fa-plus menu-caret plus-icon"></i>
                    <i class="fas fa-minus menu-caret minus-icon"></i>
                </a>
                <ul id="featuresMenu" class="sidebar-submenu collapse {{ $featuresOpen ? 'show' : '' }}">
                    <li><a href="{{ route('admin.subscriptions.index') }}" class="{{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">{{ __('Subscriptions') }}</a></li>
                    <li><a href="{{ route('admin.notifications.index') }}" class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">{{ __('Notifications') }}</a></li>
                </ul>
            </li>

            <li>
                <a class="sidebar-group-toggle" data-bs-toggle="collapse" href="#marriageBureauMenu" role="button" aria-expanded="{{ $marriageBureauOpen ? 'true' : 'false' }}" aria-controls="marriageBureauMenu">
                    <i class="fas fa-building"></i>
                    <span>{{ __('Marriage Bureau') }}</span>
                    <i class="fas fa-plus menu-caret plus-icon"></i>
                    <i class="fas fa-minus menu-caret minus-icon"></i>
                </a>
                <ul id="marriageBureauMenu" class="sidebar-submenu collapse {{ $marriageBureauOpen ? 'show' : '' }}">
                    <li><a href="{{ route('admin.marriage-bureaus.index') }}" class="{{ request()->routeIs('admin.marriage-bureaus.*') ? 'active' : '' }}">{{ __('Marriage Bureaus') }}</a></li>
                    <li><a href="{{ route('admin.marriage-bureau-subscriptions.index') }}" class="{{ request()->routeIs('admin.marriage-bureau-subscriptions.*') ? 'active' : '' }}">{{ __('MB Subscriptions') }}</a></li>
                </ul>
            </li>

            <li>
                <a class="sidebar-group-toggle" data-bs-toggle="collapse" href="#profileSettingsMenu" role="button" aria-expanded="{{ $profileSettingsOpen ? 'true' : 'false' }}" aria-controls="profileSettingsMenu">
                    <i class="fas fa-user-cog"></i>
                    <span>{{ __('User Profile Settings') }}</span>
                    <i class="fas fa-plus menu-caret plus-icon"></i>
                    <i class="fas fa-minus menu-caret minus-icon"></i>
                </a>
                <ul id="profileSettingsMenu" class="sidebar-submenu collapse {{ $profileSettingsOpen ? 'show' : '' }}">
                    @foreach(config('profile_lookups', []) as $lookupType => $lookup)
                        <li>
                            <a href="{{ route('admin.lookups.index', ['type' => $lookupType]) }}" class="{{ request()->routeIs('admin.lookups.*') && request()->route('type') === $lookupType ? 'active' : '' }}">
                                {{ __($lookup['label']) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>

            <li>
                <a class="sidebar-group-toggle" data-bs-toggle="collapse" href="#configurationMenu" role="button" aria-expanded="{{ $configurationOpen ? 'true' : 'false' }}" aria-controls="configurationMenu">
                    <i class="fas fa-cogs"></i>
                    <span>{{ __('Configuration') }}</span>
                    <i class="fas fa-plus menu-caret plus-icon"></i>
                    <i class="fas fa-minus menu-caret minus-icon"></i>
                </a>
                <ul id="configurationMenu" class="sidebar-submenu collapse {{ $configurationOpen ? 'show' : '' }}">
                    <li><a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">{{ __('System Settings') }}</a></li>
                </ul>
            </li>
        </ul>
    </aside>
</div>
