<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset('images/nav_logo.jpg') }}" alt="EXG Logo" class="brand-image elevation-3" style="opacity: 0.8;" />
        <span class="brand-text font-weight-light">&nbsp;</span>
    </a>
    @php
    $urlName = explode('/',Request::path());
    if(!isset($urlName[1])) {
    $urlName[1] = '';
    }
    @endphp
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-item @if($urlName[0] == 'home') active @endif">
                    <a href="{{ url('/') }}" class="nav-link @if($urlName[0] == 'home') active @endif">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item @if($urlName[0] == 'masters') menu-open @endif">
                    <a href="#" class="nav-link @if($urlName[0] == 'masters') active @endif">
                        <i class="nav-icon fas fa-asterisk"></i>
                        <p>
                            Masters
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link @if($urlName[1] == 'users') active @endif">
                                <i class="fa fa-dollar-sign nav-icon"></i>
                                <p>User</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sku-codes.index') }}" class="nav-link @if($urlName[1] == 'sku-codes') active @endif">
                                <i class="fas fa-users nav-icon"></i>
                                <p>Skus</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('variants.index') }}" class="nav-link @if($urlName[1] == 'variants') active @endif">
                                <i class="fa fa-sitemap nav-icon"></i>
                                <p>Variants</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
