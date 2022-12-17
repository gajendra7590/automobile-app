<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>
                    @if (Auth::user())
                        {{ Auth::user()->name }}
                    @else
                        Guest
                    @endif
                </p>
                <a href="javascript:void(0);"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>

            <li><a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a> </li>

            <li><a href="{{ route('roles.index') }}"><i class="fa fa-snowflake-o"></i> <span>Roles</span></a>
            </li>

            <li><a href="{{ route('users.index') }}"><i class="fa fa-address-card"></i> <span>Users</span></a>
            </li>

            <li><a href="{{ route('brands.index') }}"><i class="fa fa-table"></i> <span> Branches</span></a>
            </li>

            <li><a href="{{ route('brands.index') }}"><i class="fa fa-users"></i> <span>Agents</span></a>
            </li>

            <li><a href="{{ route('brands.index') }}"><i class="fa fa-users"></i> <span>Dealers</span></a>
            </li>

            <li><a href="{{ route('brands.index') }}"><i class="fa fa-list-alt"></i> <span>Brands</span></a>
            </li>

            <li><a href="{{ route('brands.index') }}"><i class="fa fa-rub"></i> <span>Purchases</span></a>
            </li>

            <li><a href="{{ route('brands.index') }}"><i class="fa fa-rupee"></i> <span>Sales</span></a>
            </li>

            <li><a href="{{ route('brands.index') }}"><i class="fa fa-share"></i> <span>RTO</span></a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-pie-chart"></i>
                    <span>Reports</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="pages/charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>
                    <li><a href="pages/charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>
                    <li><a href="pages/charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>
                    <li><a href="pages/charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
