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
                        <i class="nav-icon fas fa-database"></i>
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
                        <li class="nav-item">
                            <a href="{{ route('master-pallets.index') }}" class="nav-link @if($urlName[1] == 'master-pallets') active @endif">
                                <i class="fa fa-pallet nav-icon"></i>
                                <p>Master Pallet</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('warehouses.index') }}" class="nav-link @if($urlName[1] == 'warehouses') active @endif">
                                <i class="fa fa-building nav-icon"></i>
                                <p>Warehouse</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item @if($urlName[0] == 'process' || $urlName[0] == 'orders-management' ) menu-open @endif">
                    <a href="#" class="nav-link @if($urlName[0] == 'process' || $urlName[0] == 'orders-management') active @endif">
                        <i class="nav-icon fas fa-paperclip"></i>
                        <p>
                            Process
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('pallets.index') }}" class="nav-link @if($urlName[1] == 'pallets') active @endif">
                                <i class="fa fa-pallet nav-icon"></i>
                                <p>Manage Pallet</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('reach-trucks.index') }}" class="nav-link @if($urlName[1] == 'reach-trucks') active @endif">
                                <i class="fa fa-truck nav-icon"></i>
                                <p>Reach Truck</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('orders.index') }}" class="nav-link @if($urlName[1] == 'orders') active @endif">
                                <i class="fa fa-receipt nav-icon"></i>
                                <p>Orders</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item @if($urlName[0] == 'reports') menu-open @endif">
                    <a href="#" class="nav-link @if($urlName[0] == 'reports') active @endif">
                        <i class="nav-icon fas fa-asterisk"></i>
                        <p>
                            Reports
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item @if($urlName[1] == 'pallet-report') menu-is-opening menu-open @endif">
                            <a href="#" class="nav-link @if($urlName[1] == 'pallet-report') active @endif">
                                <i class="fas fa-pallet nav-icon"></i>
                                <p>
                                    Pallet
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" @if($urlName[1] == 'pallet-report') style="display: block; @else style="display: none;@endif ">
                                <li class="nav-item">
                                    <a href="{{ route('pallet-report.index') }}" class="nav-link @if(!empty($urlName[2]) && $urlName[2] == 'sku-details') active @endif">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Sku Wise</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('box-pallet-report.index') }}" class="nav-link @if(!empty($urlName[2]) && $urlName[2] == 'box-details') active @endif">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Box Wise</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('order-report.index') }}" class="nav-link @if($urlName[1] == 'order-report') active @endif">
                                <i class="fa fa-receipt nav-icon"></i>
                                <p>Order</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sku-report.index') }}" class="nav-link @if($urlName[1] == 'sku-report') active @endif">
                                <i class="fa fa-receipt nav-icon"></i>
                                <p>Sku</p>
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
