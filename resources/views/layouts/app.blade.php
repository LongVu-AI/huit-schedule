<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HUIT Schedule Hub') - Quản lý và Phân lịch Giảng dạy Đa Cơ sở</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --huit-blue: #0b3c85;
            --huit-blue-dark: #072554;
            --huit-blue-light: #e8f0fe;
            --huit-orange: #f06a19;
            --huit-orange-light: #fff3ec;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f6fa;
            color: #2b3648;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(180deg, #0b3c85 0%, #072554 100%);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.08);
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.12);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-badge {
            background-color: var(--huit-orange);
            color: white;
            font-weight: 800;
            font-size: 1.15rem;
            padding: 0.35rem 0.65rem;
            border-radius: 8px;
            letter-spacing: 0.5px;
        }

        .brand-title {
            font-size: 1.05rem;
            font-weight: 700;
            line-height: 1.2;
            color: #fff;
        }

        .brand-subtitle {
            font-size: 0.72rem;
            color: #9cb5db;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nav-section-title {
            padding: 1.25rem 1.25rem 0.5rem;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #83a5d8;
            letter-spacing: 0.8px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0.5rem 0.75rem;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 0.25rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #d1e0f5;
            text-decoration: none;
            border-radius: 10px;
            font-size: 0.92rem;
            font-weight: 500;
            transition: all 0.2s ease;
            gap: 0.85rem;
        }

        .sidebar-menu a i {
            width: 20px;
            font-size: 1.1rem;
            text-align: center;
            color: #9cb5db;
            transition: color 0.2s ease;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        .sidebar-menu a:hover i {
            color: var(--huit-orange);
        }

        .sidebar-menu a.active {
            background: var(--huit-orange);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(240, 106, 25, 0.35);
        }

        .sidebar-menu a.active i {
            color: #fff;
        }

        /* Main Content wrapper */
        #content-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        /* Top Header */
        .top-navbar {
            background: #fff;
            padding: 0.85rem 1.75rem;
            border-bottom: 1px solid #e3e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.35rem 0.75rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 40px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--huit-blue);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }

        /* Main Container */
        .page-content {
            padding: 1.75rem;
            flex: 1;
        }

        /* Stat cards */
        .stat-card {
            background: #fff;
            border: none;
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* Content Cards */
        .card-custom {
            background: #fff;
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card-custom .card-header {
            background: #fff;
            border-bottom: 1px solid #edf2f7;
            padding: 1rem 1.5rem;
            font-weight: 700;
            font-size: 1.05rem;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-custom .card-body {
            padding: 1.5rem;
        }

        /* Table custom */
        .table-custom {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table-custom thead th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.85rem 1rem;
        }

        .table-custom tbody td {
            padding: 0.95rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.92rem;
        }

        .table-custom tbody tr:hover {
            background-color: #f8fbff;
        }

        /* Buttons */
        .btn-huit-primary {
            background-color: var(--huit-blue);
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.15rem;
            transition: all 0.2s ease;
        }

        .btn-huit-primary:hover {
            background-color: var(--huit-blue-dark);
            color: white;
            transform: translateY(-1px);
        }

        .btn-huit-orange {
            background-color: var(--huit-orange);
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.15rem;
            transition: all 0.2s ease;
        }

        .btn-huit-orange:hover {
            background-color: #d8570d;
            color: white;
            transform: translateY(-1px);
        }

        /* Footer */
        .footer {
            background: #fff;
            border-top: 1px solid #e2e8f0;
            padding: 1rem 1.75rem;
            font-size: 0.85rem;
            color: #64748b;
            text-align: center;
        }

        /* Responsive sidebar */
        @media (max-width: 991.98px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            #sidebar.active {
                margin-left: 0;
            }
            #content-wrapper {
                margin-left: 0;
            }
        }

        /* Print styles */
        @media print {
            #sidebar, .top-navbar, .no-print, .footer {
                display: none !important;
            }
            #content-wrapper {
                margin-left: 0 !important;
            }
            .page-content {
                padding: 0 !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside id="sidebar">
        <div class="sidebar-brand">
            <span class="brand-badge">HUIT</span>
            <div>
                <div class="brand-title">Schedule Hub</div>
                <div class="brand-subtitle">Đa Cơ Sở Thông Minh</div>
            </div>
        </div>

        <div class="nav-section-title">Khám Phá & Phân Lịch</div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('schedules.index') }}" class="{{ request()->routeIs('schedules.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Thời Khóa Biểu Chung</span>
                </a>
            </li>
            <li>
                <a href="{{ route('schedules.matrix') }}" class="{{ request()->routeIs('schedules.matrix') ? 'active' : '' }}">
                    <i class="fa-solid fa-table-cells"></i>
                    <span>Ma Trận Tiết Trống</span>
                </a>
            </li>
            <li>
                <a href="{{ route('schedules.my') }}" class="{{ request()->routeIs('schedules.my') ? 'active' : '' }}">
                    <i class="fa-solid fa-id-badge"></i>
                    <span>Cổng Giảng Viên</span>
                </a>
            </li>
        </ul>

        @if(auth()->check() && auth()->user()->role === 'admin')
        <div class="nav-section-title">Quản Trị Đào Tạo</div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('schedules.create') }}" class="{{ request()->routeIs('schedules.create') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-plus"></i>
                    <span>Phân Lịch Thông Minh</span>
                </a>
            </li>
            <li>
                <a href="{{ route('branches.index') }}" class="{{ request()->routeIs('branches.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-building-columns"></i>
                    <span>Quản Lý Cơ Sở</span>
                </a>
            </li>
            <li>
                <a href="{{ route('teachers.index') }}" class="{{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <span>Quản Lý Giảng Viên</span>
                </a>
            </li>
        </ul>
        @endif

        <div class="nav-section-title">Hệ Thống</div>
        <ul class="sidebar-menu">
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-right-from-bracket text-danger"></i>
                    <span class="text-white">Đăng Xuất</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content Wrapper -->
    <div id="content-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebar-toggle">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="d-none d-sm-block">
                    <span class="badge bg-light text-primary border px-2 py-1 me-2">
                        <i class="fa-solid fa-location-dot me-1 text-danger"></i> ĐH Công Thương TP.HCM (HUIT)
                    </span>
                    <span class="text-muted small">
                        <i class="fa-regular fa-clock me-1"></i> Hôm nay: {{ \Carbon\Carbon::now()->locale('vi')->translatedFormat('l, d/m/Y') }}
                    </span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                @if(auth()->check())
                <div class="user-pill">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="d-none d-md-block text-start me-1">
                        <div class="fw-bold small text-dark lh-1">{{ auth()->user()->name }}</div>
                        <div class="text-muted" style="font-size: 0.72rem;">
                            @if(auth()->user()->role === 'admin')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Quản trị viên</span>
                            @else
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Giảng viên</span>
                            @endif
                        </div>
                    </div>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                @endif
            </div>
        </header>

        <!-- Page Content -->
        <main class="page-content">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check fs-5 text-success"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-triangle-exclamation fs-5 text-danger"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-info fs-5 text-info"></i>
                <div>{{ session('info') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <div class="fw-bold mb-1"><i class="fa-solid fa-circle-xmark me-1"></i> Đã xảy ra lỗi vui lòng kiểm tra lại:</div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div class="container-fluid">
                <strong>HUIT Schedule Hub</strong> &copy; 2026 - Trường Đại học Công Thương TP.HCM (HUIT).
                Hệ thống Quản lý và Phân lịch Giảng dạy Đa Cơ sở Thông minh.
            </div>
        </footer>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
    @stack('scripts')
</body>
</html>
