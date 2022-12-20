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

            <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
                <a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>

            <li class="{{ Request::is('roles*') ? 'active' : '' }}">
                <a href="{{ route('roles.index') }}"><i class="fa fa-snowflake-o"></i> <span>Roles</span></a>
            </li>

            <li class="{{ Request::is('users*') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}"><i class="fa fa-address-card"></i> <span>Users</span></a>
            </li>

            <li class="{{ Request::is('branches*') ? 'active' : '' }}">
                <a href="{{ route('branches.index') }}"><i class="fa fa-table"></i> <span> Branches</span></a>
            </li>

            <li class="{{ Request::is('agents*') ? 'active' : '' }}">
                <a href="{{ route('agents.index') }}"><i class="fa fa-users"></i> <span>Agents</span></a>
            </li>

            <li class="{{ Request::is('dealers*') ? 'active' : '' }}">
                <a href="{{ route('dealers.index') }}"><i class="fa fa-users"></i> <span>Dealers</span></a>
            </li>

            <li class="{{ Request::is('purchases*') ? 'active' : '' }}">
                <a href="{{ route('purchases.index') }}"><i class="fa fa-rub"></i> <span>Purchases</span></a>
            </li>

            <li class="{{ Request::is('quotations*') ? 'active' : '' }}">
                <a href="{{ route('quotations.index') }}"><i class="fa fa-question-circle"></i>
                    <span>Qoutations/Enquiries</span></a>
            </li>

            <li class="{{ Request::is('sales*') ? 'active' : '' }}">
                <a href="{{ route('sales.index') }}"><i class="fa fa-rupee"></i> <span>Sales</span></a>
            </li>

            <li class="{{ Request::is('rto*') ? 'active' : '' }}">
                <a href="{{ route('rto.index') }}"><i class="fa fa-share"></i> <span>RTO</span></a>
            </li>
            <li
                class="treeview {{ Request::is('brands*') || Request::is('models*') || Request::is('colors*') || Request::is('states*') || Request::is('districts*') || Request::is('cities*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-cog"></i>
                    <span>Common Setting</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu"
                    {{ Request::is('brands*') || Request::is('models*') || Request::is('colors*') || Request::is('states*') || Request::is('districts*') || Request::is('cities*') ? 'display:"block"' : '' }}>

                    <li class="{{ Request::is('brands*') ? 'active' : '' }}">
                        <a href="{{ route('brands.index') }}"><i class="fa fa-circle-o"></i>Brands</a>
                    </li>
                    <li class="{{ Request::is('models*') ? 'active' : '' }}">
                        <a href="{{ route('models.index') }}"><i class="fa fa-circle-o"></i>Models</a>
                    </li>
                    <li class="{{ Request::is('colors*') ? 'active' : '' }}">
                        <a href="{{ route('colors.index') }}"><i class="fa fa-circle-o"></i>Colors</a>
                    </li>
                    <li class="{{ Request::is('states*') ? 'active' : '' }}">
                        <a href="{{ route('states.index') }}"><i class="fa fa-circle-o"></i> States</a>
                    </li>
                    <li class="{{ Request::is('districts*') ? 'active' : '' }}">
                        <a href="{{ route('districts.index') }}"><i class="fa fa-circle-o"></i>Districts</a>
                    </li>
                    <li class="{{ Request::is('cities*') ? 'active' : '' }}">
                        <a href="{{ route('cities.index') }}"><i class="fa fa-circle-o"></i>Cities</a>
                    </li>
                </ul>
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
