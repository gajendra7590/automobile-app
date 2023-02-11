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
                    class="treeview {{ Request::is('branches*') || Request::is('brands*') || Request::is('models*') || Request::is('modelVariants*') || Request::is('colors*') || Request::is('states*') || Request::is('districts*') || Request::is('cities*') || Request::is('gst-rates*') || Request::is('gst-rto-rates*') || Request::is('tyreBrands*') || Request::is('batteryBrands*') || Request::is('skuSalesPrice*') ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-calculator"></i>
                        <span>ALL BASIC SETTINGS</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu"
                        {{ Request::is('branches*') || Request::is('brands*') || Request::is('models*') || Request::is('modelVariants*') || Request::is('colors*') || Request::is('states*') || Request::is('districts*') || Request::is('cities*') || Request::is('gst-rates*') || Request::is('gst-rto-rates*') || Request::is('tyreBrands*') || Request::is('batteryBrands*') || Request::is('skuSalesPrice*') ? 'display:"block"' : '' }}>
                        <li class="{{ Request::is('branches*') ? 'active' : '' }}">
                            <a href="{{ route('branches.index') }}">
                                <i class="fa fa-building-o"></i>BRANCHES
                            </a>
                        </li>
                        <li class="{{ Request::is('brands*') ? 'active' : '' }}">
                            <a href="{{ route('brands.index') }}">
                                <i class="fa fa-empire"></i>BRANDS
                            </a>
                        </li>
                        <li class="{{ Request::is('models*') ? 'active' : '' }}">
                            <a href="{{ route('models.index') }}">
                                <i class="fa fa-gg"></i>BRAND MODELS
                            </a>
                        </li>
                        <li class="{{ Request::is('modelVariants*') ? 'active' : '' }}">
                            <a href="{{ route('modelVariants.index') }}">
                                <i class="fa fa-asterisk"></i> MODEL VARIANTS
                            </a>
                        </li>
                        <li class="{{ Request::is('colors*') ? 'active' : '' }}">
                            <a href="{{ route('colors.index') }}">
                                <i class="fa fa-paint-brush"></i>MODEL VARIANT COLORS
                            </a>
                        </li>
                        <li class="{{ Request::is('skuSalesPrice*') ? 'active' : '' }}">
                            <a href="{{ route('skuSalesPrice.index') }}">
                                <i class="fa fa-inr"></i>SKU SALES PRICES
                            </a>
                        </li>
                        <li class="{{ Request::is('states*') ? 'active' : '' }}">
                            <a href="{{ route('states.index') }}">
                                <i class="fa fa-building"></i>STATES
                            </a>
                        </li>
                        <li class="{{ Request::is('districts*') ? 'active' : '' }}">
                            <a href="{{ route('districts.index') }}">
                                <i class="fa fa-building"></i>DISTRICTS
                            </a>
                        </li>
                        <li class="{{ Request::is('cities*') ? 'active' : '' }}">
                            <a href="{{ route('cities.index') }}">
                                <i class="fa fa-building"></i>CITY/TOWN/VILLAGES
                            </a>
                        </li>
                        {{-- GST --}}
                        <li class="{{ Request::is('gst-rates*') ? 'active' : '' }}">
                            <a href="{{ route('gst-rates.index') }}">
                                <i class="fa fa-money"></i>GST RATES
                            </a>
                        </li>
                        <li class="{{ Request::is('gst-rto-rates*') ? 'active' : '' }}">
                            <a href="{{ route('gst-rto-rates.index') }}">
                                <i class="fa fa-money"></i>GST RTO RATES
                            </a>
                        </li>
                        <li class="{{ Request::is('tyreBrands*') ? 'active' : '' }}">
                            <a href="{{ route('tyreBrands.index') }}">
                                <i class="fa fa-motorcycle"></i>TYRE BRANDS
                            </a>
                        </li>
                        <li class="{{ Request::is('batteryBrands*') ? 'active' : '' }}">
                            <a href="{{ route('batteryBrands.index') }}">
                                <i class="fa fa-battery-full"></i>BATTERY BRANDS
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="treeview {{ Request::is('roles*') || Request::is('users*') || Request::is('brokers*') || Request::is('dealers*') || Request::is('bankFinancers*') || Request::is('rto-agents*') || Request::is('salesmans*') ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-database"></i>
                        <span>ALL USERS</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu"
                        {{ Request::is('roles*') || Request::is('users*') || Request::is('brokers*') || Request::is('dealers*') || Request::is('bankFinancers*') || Request::is('rto-agents*') || Request::is('salesmans*') ? 'display:"block"' : '' }}>

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
                        <li class="{{ Request::is('salesmans*') ? 'active' : '' }}">
                            <a href="{{ route('salesmans.index') }}"><i class="fa fa-male"></i>SALESMANS</a>
                        </li>
                        <li class="{{ Request::is('rto-agents*') ? 'active' : '' }}">
                            <a href="{{ route('rto-agents.index') }}"><i class="fa fa-user-secret"></i>RTO AGENTS</a>
                        </li>
                    </ul>
                </li>
            @endif
            <li
                class="treeview {{ Request::is('purchases*') || Request::is('purchaseInvoices*') || Request::is('purchaseTransfers*') || Request::is('purchaseDealerPayments*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-shopping-cart"></i>
                    <span>PURCHASE INVENTORIES</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu"
                    {{ Request::is('purchases*') || Request::is('purchaseInvoices*') || Request::is('purchaseTransfers*') || Request::is('purchaseDealerPayments*') ? 'display:"block"' : '' }}>

                    <li class="{{ Request::is('purchases*') ? 'active' : '' }}">
                        <a href="{{ route('purchases.index') }}"><i class="fa fa-rub"></i>PURCHASES
                        </a>
                    </li>
                    <li class="{{ Request::is('purchaseInvoices*') ? 'active' : '' }}">
                        <a href="{{ route('purchaseInvoices.index') }}"><i class="fa fa-book"></i>PURCHASE INVOICES
                        </a>
                    </li>
                    <li class="{{ Request::is('purchaseTransfers*') ? 'active' : '' }}">
                        <a href="{{ route('purchaseTransfers.index') }}"><i class="fa fa-exchange"></i>BROKER
                            TRANSFERS
                        </a>
                    </li>
                    <li class="{{ Request::is('purchaseDealerPayments*') ? 'active' : '' }}">
                        <a href="{{ route('purchaseDealerPayments.index') }}"><i class="fa fa-exchange"></i>
                            PURCHASE DEALER PAYMENTS
                        </a>
                    </li>
                </ul>
            </li>

            <li class="{{ Request::is('quotations*') ? 'active' : '' }}">
                <a href="{{ route('quotations.index') }}"><i class="fa fa-briefcase"></i>
                    <span>QUOTATIONS</span></a>
            </li>

            <li class="treeview {{ Request::is('sales*') || Request::is('saleAccounts*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-motorcycle"></i>
                    <span>SALES</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu"
                    {{ Request::is('sales*') || Request::is('saleAccounts*') ? 'display:"block"' : '' }}>

                    <li class="{{ Request::is('sales*') ? 'active' : '' }}">
                        <a href="{{ route('sales.index') }}"><i class="fa fa-registered"></i>
                            SALES LIST
                        </a>
                    </li>
                    <li class="{{ Request::is('saleAccounts*') ? 'active' : '' }}">
                        <a href="{{ route('saleAccounts.index') }}"><i class="fa fa-credit-card"></i>
                            SALES PAYMENT ACCOUNTS
                        </a>
                    </li>
                </ul>
            </li>

            <li class="{{ Request::is('customerReturns*') ? 'active' : '' }}">
                <a href="{{ route('customerReturns.index') }}"><i class="fa fa-undo"></i>
                    <span>CUSTOMER RETURNS</span></a>
            </li>

            <li
                class="treeview {{ Request::is('rtoRegistration*') || Request::is('rtoAgentPayments*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-book"></i>
                    <span>RTO</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu"
                    {{ Request::is('rtoRegistration*') || Request::is('rtoAgentPayments*') ? 'display:"block"' : '' }}>

                    <li class="{{ Request::is('rtoRegistration*') ? 'active' : '' }}">
                        <a href="{{ route('rtoRegistration.index') }}"><i
                                class="fa fa-registered"></i>REGISTRATIONS</a>
                    </li>
                    <li class="{{ Request::is('rtoAgentPayments*') ? 'active' : '' }}">
                        <a href="{{ route('rtoAgentPayments.index') }}"><i class="fa fa-credit-card"></i>AGENT
                            PAYMENTS</a>
                    </li>
                </ul>
            </li>

            <li class="{{ Request::is('reports*') ? 'active' : '' }}">
                <a href="{{ route('reports.index') }}"><i class="fa fa-bar-chart"></i>
                    <span>REPORTS</span></a>
            </li>

            <li class="{{ Request::is('documentUploads*') ? 'active' : '' }}">
                <a href="{{ route('documentUploads.index') }}"><i class="fa fa-upload"></i>
                    <span>DOCUMENT UPLOADS</span></a>
            </li>

            <li class="treeview {{ Request::is('profile') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-key"></i>
                    <span>MANAGE PROFILE</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu {{ Request::is('profile') ? 'display:"block"' : '' }}">
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
