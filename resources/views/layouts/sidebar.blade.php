        <div id="sidebar" class='active'>
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <h2><span>PPKD</span> HOTEL</h2>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}" class='sidebar-link'>
                                <i data-feather="home" width="20"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        {{-- Front Office --}}
                        @hasanyrole('Administrator|Front Office')
                            <li
                                class="sidebar-item has-sub {{ request()->routeIs('admin.reservations.*') ||
                                request()->routeIs('admin.guests.*') ||
                                request()->routeIs('admin.payments.*') ||
                                request()->routeIs('admin.calendar.*')
                                    ? 'active'
                                    : '' }}">

                                <a href="#" class="sidebar-link">
                                    <i data-feather="briefcase"></i>
                                    <span>Front Office</span>
                                </a>

                                <ul class="submenu">
                                    <li><a href="{{ route('admin.reservations.index') }}">Reservations</a></li>
                                    <li><a href="{{ route('admin.guests.index') }}">Guest Directory</a></li>
                                    <li><a href="{{ route('admin.payments.index') }}">Payments</a></li>
                                    <li><a href="{{ route('admin.calendar.index') }}">Occupancy Calendar</a></li>
                                </ul>
                            </li>
                        @endhasanyrole
                        {{-- Operasional --}}
                        @hasanyrole('Administrator|Front Office|Housekeeping|Food & Beverage')
                            <li
                                class="sidebar-item has-sub {{ request()->routeIs('admin.housekeeping.*') ||
                                request()->routeIs('admin.room-inspections.*') ||
                                request()->routeIs('admin.laundry-requests.*') ||
                                request()->routeIs('admin.fnb-orders.*')
                                    ? 'active'
                                    : '' }}">

                                <a href="#" class="sidebar-link">
                                    <i data-feather="layers"></i>
                                    <span>Operations</span>
                                </a>

                                <ul class="submenu">

                                    @hasanyrole('Administrator|Housekeeping')
                                        <li><a href="{{ route('admin.housekeeping.index') }}">Housekeeping Board</a></li>
                                    @endhasanyrole

                                    @hasanyrole('Administrator|Front Office|Housekeeping')
                                        <li><a href="{{ route('admin.room-inspections.index') }}">Room Inspections</a></li>
                                        <li><a href="{{ route('admin.laundry-requests.index') }}">Laundry Requests</a></li>
                                    @endhasanyrole

                                    @hasanyrole('Administrator|Front Office|Food & Beverage')
                                        <li><a href="{{ route('admin.fnb-orders.index') }}">F&B Requests</a></li>
                                    @endhasanyrole

                                </ul>
                            </li>
                        @endhasanyrole

                        {{-- Master Data (Admin only) --}}
                        @role('Administrator')
                            <li
                                class="sidebar-item has-sub {{ request()->routeIs('admin.rooms.*') ||
                                request()->routeIs('admin.room-types.*') ||
                                request()->routeIs('admin.fnb-menus.*') ||
                                request()->routeIs('admin.laundry-services.*') ||
                                request()->routeIs('admin.users.*') ||
                                request()->routeIs('admin.settings.*')
                                    ? 'active'
                                    : '' }}">

                                <a href="#" class="sidebar-link">
                                    <i data-feather="settings"></i>
                                    <span>Administration</span>
                                </a>

                                <ul class="submenu">
                                    <li><a href="{{ route('admin.rooms.index') }}">Rooms & Facilities</a></li>
                                    <li><a href="{{ route('admin.fnb-menus.index') }}">Additional Services</a></li>
                                    <li><a href="{{ route('admin.users.index') }}">User Management</a></li>
                                    <li><a href="{{ route('admin.settings.index') }}">Hotel Settings</a></li>
                                </ul>

                            </li>
                        @endrole

                        {{-- Reports --}}
                        @hasanyrole('Administrator|Front Office|Housekeeping|Food & Beverage')
                            <li class="sidebar-item has-sub {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">

                                <a href="#" class="sidebar-link">
                                    <i data-feather="bar-chart-2"></i>
                                    <span>Reports</span>
                                </a>

                                <ul class="submenu">
                                    <li><a href="{{ route('admin.reports.daily') }}">Daily Reports</a></li>
                                    <li><a href="{{ route('admin.reports.advanced') }}">Advanced Reports</a></li>
                                </ul>

                            </li>
                        @endhasanyrole

                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
