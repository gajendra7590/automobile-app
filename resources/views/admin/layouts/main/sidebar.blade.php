<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ auth()->check() ? auth()->user()->profile_image : asset('assets/dist/img/default-avatar.png') }}"
                    style="height: 45px;width: 45px;" class="img-circle" alt="User Image">
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
                <a href="{{ route('dashboardIndex') }}"><i class="fa fa-dashboard"></i> <span>DASHBOARD</span></a>
            </li>
            @if (auth()->user() && auth()->user()->is_admin == '1')
                <li
                    class="treeview {{ Request::is('branches*') || Request::is('brands*') || Request::is('models*') || Request::is('colors*') || Request::is('states*') || Request::is('districts*') || Request::is('cities*') || Request::is('gst-rates*') || Request::is('gst-rto-rates*') ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-cog"></i>
                        <span>ALL BASIC SETTINGS</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu"
                        {{ Request::is('branches*') || Request::is('brands*') || Request::is('models*') || Request::is('colors*') || Request::is('states*') || Request::is('districts*') || Request::is('cities*') || Request::is('gst-rates*') || Request::is('gst-rto-rates*') ? 'display:"block"' : '' }}>
                        <li class="{{ Request::is('branches*') ? 'active' : '' }}">
                            <a href="{{ route('branches.index') }}"><i class="fa fa-building-o"></i>OUR BRANCHES</a>
                        </li>
                        <li class="{{ Request::is('brands*') ? 'active' : '' }}">
                            <a href="{{ route('brands.index') }}"><i class="fa fa-empire"></i>BRANDS LIST</a>
                        </li>
                        <li class="{{ Request::is('models*') ? 'active' : '' }}">
                            <a href="{{ route('models.index') }}"><i class="fa fa-gg"></i>MODELS LIST</a>
                        </li>
                        <li class="{{ Request::is('colors*') ? 'active' : '' }}">
                            <a href="{{ route('colors.index') }}"><i class="fa fa-paint-brush"></i>MODEL COLORS</a>
                        </li>
                        <li class="{{ Request::is('states*') ? 'active' : '' }}">
                            <a href="{{ route('states.index') }}"><i class="fa fa-building"></i>STATES LIST</a>
                        </li>
                        <li class="{{ Request::is('districts*') ? 'active' : '' }}">
                            <a href="{{ route('districts.index') }}"><i class="fa fa-building"></i>DISTRICTS LIST</a>
                        </li>
                        <li class="{{ Request::is('cities*') ? 'active' : '' }}">
                            <a href="{{ route('cities.index') }}"><i class="fa fa-building"></i>CITIES/TOWNS LISTS</a>
                        </li>
                        {{-- GST --}}
                        <li class="{{ Request::is('gst-rates*') ? 'active' : '' }}">
                            <a href="{{ route('gst-rates.index') }}"><i class="fa fa-money"></i>GST RATES</a>
                        </li>
                        <li class="{{ Request::is('gst-rto-rates*') ? 'active' : '' }}">
                            <a href="{{ route('gst-rto-rates.index') }}"><i class="fa fa-money"></i>GST RTO RATES</a>
                        </li>
                    </ul>
                </li>
                <li
                    class="treeview {{ Request::is('roles*') || Request::is('users*') || Request::is('brokers*') || Request::is('dealers*') || Request::is('bankFinancers*') || Request::is('rto-agents*') ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-database"></i>
                        <span>ALL USERS</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu"
                        {{ Request::is('roles*') || Request::is('users*') || Request::is('brokers*') || Request::is('dealers*') || Request::is('bankFinancers*') || Request::is('rto-agents*') ? 'display:"block"' : '' }}>

                        {{-- <li class="{{ Request::is('roles*') ? 'active' : '' }}">
                        <a href="{{ route('roles.index') }}"><i class="fa fa-tasks"></i>USER ROLES</a>
                    </li> --}}
                        <li class="{{ Request::is('users*') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}"><i class="fa fa-users"></i>USERS</a>
                        </li>
                        <li class="{{ Request::is('brokers*') ? 'active' : '' }}">
                            <a href="{{ route('brokers.index') }}"><i class="fa fa-user-secret"></i>BROKERS</a>
                        </li>
                        <li class="{{ Request::is('dealers*') ? 'active' : '' }}">
                            <a href="{{ route('dealers.index') }}"><i class="fa fa-vcard"></i> DEALERS</a>
                        </li>
                        <li class="{{ Request::is('bankFinancers*') ? 'active' : '' }}">
                            <a href="{{ route('bankFinancers.index') }}"><i class="fa fa-bank"></i>BANK FINANCERS</a>
                        </li>

                        <li class="{{ Request::is('rto-agents*') ? 'active' : '' }}">
                            <a href="{{ route('rto-agents.index') }}"><i class="fa fa-user-secret"></i>RTO AGENTS</a>
                        </li>
                    </ul>
                </li>
            @endif
            <li class="{{ Request::is('purchases*') ? 'active' : '' }}">
                <a href="{{ route('purchases.index') }}"><i class="fa fa-rub"></i> <span>PURCHASES</span></a>
            </li>

            <li class="{{ Request::is('quotations*') ? 'active' : '' }}">
                <a href="{{ route('quotations.index') }}"><i class="fa fa-question-circle"></i>
                    <span>QUOTATIONS</span></a>
            </li>

            <li class="{{ Request::is('sales*') ? 'active' : '' }}">
                <a href="{{ route('sales.index') }}"><i class="fa fa-rupee"></i> <span>SALES</span></a>
            </li>

            <li class="{{ Request::is('saleAccounts*') ? 'active' : '' }}">
                <a href="{{ route('saleAccounts.index') }}"><i class="fa fa-university"></i>
                    <span>ACCOUNTS</span>
                </a>
            </li>

            <li class="{{ Request::is('rto*') ? 'active' : '' }}">
                <a href="{{ route('rtoRegistration.index') }}"><i class="fa fa-share"></i>
                    <span>RTO REGISTRATIONS</span></a>
            </li>

            <li class="{{ Request::is('reports*') ? 'active' : '' }}">
                <a href="{{ route('reports.index') }}"><i class="fa fa-bar-chart"></i>
                    <span>REPORTS</span></a>
            </li>

            <li class="{{ Request::is('documentUploads*') ? 'active' : '' }}">
                <a href="{{ route('documentUploads.index') }}"><i class="fa fa-upload"></i>
                    <span>DOCUMENT UPLOADS</span></a>
            </li>

            <li class="treeview {{ Request::is('profile*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-key"></i>
                    <span>MANAGE PROFILE</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu {{ Request::is('profile*') ? 'display:"block"' : '' }}">
                    <li><a href="{{ route('profile') }}"><i class="fa fa-user"></i>MY PROFILE</a></li>
                    <li><a href="{{ route('profile') }}"><i class="fa fa-key"></i>CHANGE PASSWORD</a></li>
                    <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i>LOGOUT</a></li>
            </li>
        </ul>
        </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
