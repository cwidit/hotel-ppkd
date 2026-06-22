        <div id="sidebar" class='active'>
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <h2><span>PPKD</span> HOTEL</h2>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class='sidebar-title'>Menu Utama</li>
                        <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}" class='sidebar-link'>
                                <i data-feather="home" width="20"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        {{-- Front Office --}}
                        @hasanyrole('Administrator|Front Office')
                        <li class='sidebar-title'>Front Office</li>
                        <li class="sidebar-item {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.reservations.index') }}" class='sidebar-link'>
                                <i data-feather="calendar" width="20"></i> <span>Reservasi</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('admin.guests.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.guests.index') }}" class='sidebar-link'>
                                <i data-feather="users" width="20"></i> <span>Buku Tamu</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.payments.index') }}" class='sidebar-link'>
                                <i data-feather="credit-card" width="20"></i> <span>Pembayaran</span>
                            </a>
                        </li>
                        @endhasanyrole

                        {{-- Operasional --}}
                        @hasanyrole('Administrator|Front Office|Housekeeping|Food & Beverage')
                        <li class='sidebar-title'>Operasional Layanan</li>
                        @endhasanyrole

                        @hasanyrole('Administrator|Front Office|Food & Beverage')
                        <li class="sidebar-item {{ request()->routeIs('admin.fnb-orders.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.fnb-orders.index') }}" class='sidebar-link'>
                                <i data-feather="coffee" width="20"></i> <span>Request FnB</span>
                            </a>
                        </li>
                        @endhasanyrole

                        @hasanyrole('Administrator|Front Office|Housekeeping')
                        <li class="sidebar-item {{ request()->routeIs('admin.laundry-requests.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.laundry-requests.index') }}" class='sidebar-link'>
                                <i data-feather="wind" width="20"></i> <span>Request Laundry</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('admin.room-inspections.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.room-inspections.index') }}" class='sidebar-link'>
                                <i data-feather="check-square" width="20"></i> <span>Inspeksi Kamar</span>
                            </a>
                        </li>
                        @endhasanyrole

                        @hasanyrole('Administrator|Housekeeping')
                        <li class="sidebar-item {{ request()->routeIs('admin.housekeeping.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.housekeeping.index') }}" class='sidebar-link'>
                                <i data-feather="layers" width="20"></i> <span>Housekeeping Board</span>
                            </a>
                        </li>
                        @endhasanyrole

                        {{-- Calendar --}}
                        @hasanyrole('Administrator|Front Office')
                        <li class="sidebar-item {{ request()->routeIs('admin.calendar.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.calendar.index') }}" class='sidebar-link'>
                                <i data-feather="grid" width="20"></i> <span>Kalender Okupansi</span>
                            </a>
                        </li>
                        @endhasanyrole

                        {{-- Master Data (Admin only) --}}
                        @role('Administrator')
                        <li class='sidebar-title'>Master Data</li>
                        <li class="sidebar-item has-sub {{ request()->routeIs('admin.rooms.*') || request()->routeIs('admin.room-types.*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i data-feather="database" width="20"></i> <span>Kamar & Fasilitas</span>
                            </a>
                            <ul class="submenu {{ request()->routeIs('admin.rooms.*') || request()->routeIs('admin.room-types.*') ? 'submenu-open' : '' }}">
                                <li><a href="{{ route('admin.rooms.index') }}" class="{{ request()->routeIs('admin.rooms.*') ? 'text-primary' : '' }}">Data Kamar</a></li>
                                <li><a href="{{ route('admin.room-types.index') }}" class="{{ request()->routeIs('admin.room-types.*') ? 'text-primary' : '' }}">Tipe Kamar</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-item has-sub {{ request()->routeIs('admin.fnb-menus.*') || request()->routeIs('admin.laundry-services.*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i data-feather="list" width="20"></i> <span>Layanan Tambahan</span>
                            </a>
                            <ul class="submenu {{ request()->routeIs('admin.fnb-menus.*') || request()->routeIs('admin.laundry-services.*') ? 'submenu-open' : '' }}">
                                <li><a href="{{ route('admin.fnb-menus.index') }}" class="{{ request()->routeIs('admin.fnb-menus.*') ? 'text-primary' : '' }}">Menu FnB</a></li>
                                <li><a href="{{ route('admin.laundry-services.index') }}" class="{{ request()->routeIs('admin.laundry-services.*') ? 'text-primary' : '' }}">Layanan Laundry</a></li>
                            </ul>
                        </li>
                        @endrole

                        {{-- Reports --}}
                        @hasanyrole('Administrator|Front Office|Housekeeping|Food & Beverage')
                        <li class='sidebar-title'>Laporan</li>
                        <li class="sidebar-item {{ request()->routeIs('admin.reports.daily') ? 'active' : '' }}">
                            <a href="{{ route('admin.reports.daily') }}" class='sidebar-link'>
                                <i data-feather="file-text" width="20"></i> <span>Daily Reports</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('admin.reports.advanced') ? 'active' : '' }}">
                            <a href="{{ route('admin.reports.advanced') }}" class='sidebar-link'>
                                <i data-feather="pie-chart" width="20"></i> <span>Advanced Reports</span>
                            </a>
                        </li>
                        @endhasanyrole

                        {{-- Admin System --}}
                        @role('Administrator')
                        <li class="sidebar-title">Sistem</li>
                        <li class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.users.index') }}" class='sidebar-link'>
                                <i data-feather="user" width="20"></i> <span>Manajemen Pengguna</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings.index') }}" class='sidebar-link'>
                                <i data-feather="settings" width="20"></i> <span>Pengaturan Hotel</span>
                            </a>
                        </li>
                        @endrole
                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
