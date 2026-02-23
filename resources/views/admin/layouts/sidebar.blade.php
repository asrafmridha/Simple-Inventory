<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">

        <span class="brand-text font-weight-light">Inventory System </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="pb-3 mt-3 mb-3 user-panel d-flex">
            <div class="image">
                <img src="{{ asset('backend/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">



                <li class="nav-item">
                    <a href="{{ route('products.index') }}"
                        class="nav-link {{ request()->is('products*') ? 'active' : '' }} ">
                        <i class="fa-solid fa-calendar-plus"></i>
                        <p>
                            Product
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview
    {{ request()->is('purchases*') ? 'menu-open' : '' }}">

                    <a href="#" class="nav-link
        {{ request()->is('purchases*') ? 'active' : '' }}">

                        <i class="fa-solid fa-box"></i>

                        <p>
                            Purchase
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        {{-- Purchase History --}}
                        <li class="nav-item">
                            <a href="{{ route('purchases.index') }}"
                                class="nav-link {{ request()->routeIs('purchases.index') ? 'active' : '' }}">

                                <i class="far fa-circle nav-icon"></i>
                                <p>Purchase History</p>

                            </a>
                        </li>

                        {{-- Purchase Create --}}
                        <li class="nav-item">
                            <a href="{{ route('purchases.create') }}"
                                class="nav-link {{ request()->routeIs('purchases.create') ? 'active' : '' }}">

                                <i class="far fa-circle nav-icon"></i>
                                <p>Create Purchase</p>

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
