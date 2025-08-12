<!-- BEGIN: Side Menu -->
<nav class="side-nav">
    <ul>
        <li>
            <a href="{{ route('dashboard') }}" class="side-menu {{ request()->routeIs('dashboard') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="home"></i>
                </div>
                <div class="side-menu__title">Dashboard</div>
            </a>
        </li>
        <li>
            <a href="{{ route('supplies.index') }}" class="side-menu {{ request()->routeIs('supplies.*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="package"></i>
                </div>
                <div class="side-menu__title">Inventory</div>
            </a>
        </li>
        <li>
            <a href="{{ route('categories.index') }}" class="side-menu {{ request()->routeIs('categories.*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="tag"></i>
                </div>
                <div class="side-menu__title">Categories</div>
            </a>
        </li>
        <li>
            <a href="{{ route('departments.index') }}" class="side-menu {{ request()->routeIs('departments.*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="users"></i>
                </div>
                <div class="side-menu__title">Departments</div>
            </a>
        </li>
        <li>
            <a href="{{ route('deployed-items.index') }}" class="side-menu {{ request()->routeIs('deployed-items.*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="truck"></i>
                </div>
                <div class="side-menu__title">Deployed Items</div>
            </a>
        </li>
        <li>
            <a href="{{ route('reports.index') }}" class="side-menu {{ request()->routeIs('reports.*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="bar-chart-2"></i>
                </div>
                <div class="side-menu__title">Reports</div>
            </a>
        </li>
        <li>
            <a href="{{ route('register') }}" class="side-menu {{ request()->routeIs('register') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="user-plus"></i>
                </div>
                <div class="side-menu__title">Register User</div>
            </a>
        </li>
    </ul>
</nav>
<!-- END: Side Menu --> 