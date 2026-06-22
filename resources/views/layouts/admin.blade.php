<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - PPKD Hotel HMS</title>

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon">
    <style>
        /* ── Navbar ── */
        .navbar.navbar-header { background: #fff; box-shadow: 0 1px 8px rgba(0,0,0,0.07); border-bottom: 1px solid #f0f0f0; }
        .role-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; background: #e8f0fe; color: #1d7af3; margin-right: 8px; }

        /* ── Stat Cards ── */
        .stat-card { border-radius: 10px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.07); transition: transform .15s; overflow: hidden; }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-card .stat-icon { width: 52px; height: 52px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .stat-card .stat-value { font-size: 1.7rem; font-weight: 700; line-height: 1.2; }
        .stat-card .stat-label { font-size: 12px; color: #8a9bb0; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-card .trend { font-size: 11px; }
        .trend-up { color: #28a745; }
        .trend-down { color: #dc3545; }

        /* ── Tables ── */
        .table thead th { font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #8a9bb0; font-weight: 600; border-top: none; background: #f8f9fc; }
        .table tbody tr:hover { background: #f8f9fc; }

        /* ── Badge Pills ── */
        .badge { font-size: 10px; padding: 4px 8px; border-radius: 20px; font-weight: 600; }
        .badge-primary   { background-color: #1d7af3 !important; color: #fff !important; }
        .badge-success   { background-color: #28a745 !important; color: #fff !important; }
        .badge-danger    { background-color: #dc3545 !important; color: #fff !important; }
        .badge-warning   { background-color: #ffc107 !important; color: #212529 !important; }
        .badge-info      { background-color: #17a2b8 !important; color: #fff !important; }
        .badge-secondary { background-color: #6c757d !important; color: #fff !important; }
        .badge-light     { background-color: #e9ecef !important; color: #495057 !important; }

        /* ── Alert flash ── */
        .alert { border-radius: 8px; border: none; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-danger  { background: #f8d7da; color: #721c24; }
        .alert-warning { background: #fff3cd; color: #856404; }

        /* ── Footer ── */
        footer { background: #fff; border-top: 1px solid #f0f0f0; padding: 15px 20px; text-align: center; margin-top: auto; }
        .main-content { min-height: calc(100vh - 140px); }
        .card-header { background: #fff; border-bottom: 1px solid #f0f0f0; font-weight: 600; color: #333; }
        .card { border-radius: 10px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }

        /* ── Sidebar role tag ── */
        .sidebar-role-tag { background: rgba(29,122,243,0.12); color: #1d7af3; font-size: 10px; padding: 2px 8px; border-radius: 10px; margin-left: 8px; font-weight: 600; }

        /* ── Header to Menu Spacing Fix ── */
        .sidebar-wrapper .sidebar-header { padding-bottom: 0 !important; margin-bottom: 0 !important; }
        .sidebar-wrapper .sidebar-menu { padding-top: 15px !important; margin-top: 0 !important; }
        .sidebar-wrapper .menu { margin-top: 0 !important; }

        /* ── Fix Sidebar Scroll ── */
        .sidebar-wrapper { overflow-y: auto !important; overscroll-behavior: contain; }
    </style>
    @stack('styles')
</head>
<body>
    <div id="app">
        @include('layouts.sidebar')

        <div id="main">
            <nav class="navbar navbar-header navbar-expand navbar-light">
                <a class="sidebar-toggler" href="#"><span class="navbar-toggler-icon"></span></a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav d-flex align-items-center navbar-light ml-auto">
                        <li class="d-none d-md-flex align-items-center mr-2">
                            @php
                                $userRole = auth()->user()?->getRoleNames()->first() ?? 'User';
                            @endphp
                            <span class="role-badge">{{ $userRole }}</span>
                        </li>
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                <div class="d-none d-md-inline-block">
                                    <i data-feather="user" style="width:16px;height:16px;margin-right:4px;"></i>
                                    {{ auth()->user()->name ?? 'User' }}
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                    <i data-feather="settings" style="width:14px;height:14px;margin-right:6px;"></i> Profil
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i data-feather="log-out" style="width:14px;height:14px;margin-right:6px;"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="main-content container-fluid">
                {{-- Flash Messages --}}
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i data-feather="check-circle" style="width:16px;height:16px;margin-right:6px;"></i>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <i data-feather="alert-circle" style="width:16px;height:16px;margin-right:6px;"></i>
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
                @endif

                @yield('content')
            </div>

            <footer>
                <div class="footer clearfix mb-0 text-muted text-center">
                    <p class="mb-0">{{ date('Y') }} &copy; PPKD Hotel Management System</p>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/js/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
