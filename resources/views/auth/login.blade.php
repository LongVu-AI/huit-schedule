<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - HUIT Schedule Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --huit-blue: #0b3c85;
            --huit-blue-dark: #072554;
            --huit-orange: #f06a19;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #072554 0%, #0b3c85 50%, #1552a8 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .login-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
            max-width: 950px;
            width: 100%;
        }

        .login-banner {
            background: linear-gradient(145deg, #0b3c85, #072554);
            color: white;
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .login-form-area {
            padding: 3rem 2.5rem;
        }

        .brand-badge {
            background-color: var(--huit-orange);
            color: white;
            font-weight: 800;
            font-size: 1.25rem;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            display: inline-block;
        }

        .btn-huit-primary {
            background-color: var(--huit-blue);
            color: white;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            width: 100%;
            transition: all 0.2s ease;
        }

        .btn-huit-primary:hover {
            background-color: var(--huit-blue-dark);
            color: white;
            transform: translateY(-1px);
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin-bottom: 1.25rem;
        }

        .feature-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(255,255,255,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--huit-orange);
            font-size: 1.1rem;
        }

        .demo-chip {
            background-color: #f1f5f9;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 0.45rem 0.75rem;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
            text-align: left;
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .demo-chip:hover {
            background-color: #e2e8f0;
            border-color: var(--huit-blue);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="row g-0">
            <!-- Left Info Panel -->
            <div class="col-lg-5 login-banner d-none d-lg-flex">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="brand-badge">HUIT</span>
                        <div class="fw-bold fs-4 text-white">Schedule Hub</div>
                    </div>

                    <h4 class="fw-bold mb-3 text-white">Quản lý & Phân lịch Giảng dạy Đa Cơ sở</h4>
                    <p class="text-white-50 small mb-4">Hệ thống thông minh hỗ trợ Phòng Đào tạo tối ưu thời gian di chuyển giữa các cơ sở và giúp Giảng viên tra cứu lịch dạy trực tuyến thời gian thực.</p>

                    <div class="feature-item">
                        <div class="feature-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                        <div>
                            <div class="fw-bold small text-white">Gợi ý Giảng viên Thông minh</div>
                            <div class="text-white-50" style="font-size: 0.75rem;">Đối sánh chuyên môn & tránh trùng lịch tự động</div>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon"><i class="fa-solid fa-route"></i></div>
                        <div>
                            <div class="fw-bold small text-white">Tối ưu Di chuyển Đa Cơ sở</div>
                            <div class="text-white-50" style="font-size: 0.75rem;">Phân lịch cùng cơ sở, cảnh báo khoảng cách di chuyển</div>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon"><i class="fa-solid fa-calendar-check"></i></div>
                        <div>
                            <div class="fw-bold small text-white">Cổng Tra cứu Cá nhân</div>
                            <div class="text-white-50" style="font-size: 0.75rem;">Thời khóa biểu trực quan theo tuần và phòng học</div>
                        </div>
                    </div>
                </div>

                <div class="border-top border-white border-opacity-10 pt-3 text-white-50 small">
                    <i class="fa-solid fa-graduation-cap me-1"></i> Trường Đại học Công Thương TP.HCM
                </div>
            </div>

            <!-- Right Login Form -->
            <div class="col-lg-7 login-form-area">
                <div class="mb-4">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle mb-2">
                        <i class="fa-solid fa-shield-halved me-1"></i> Cổng Đăng Nhập
                    </span>
                    <h3 class="fw-bold text-dark mb-1">Đăng Nhập Hệ Thống</h3>
                    <p class="text-muted small">Vui lòng nhập tài khoản được cấp bởi Phòng Đào tạo HUIT</p>
                </div>

                @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show small" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mb-3">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Email đăng nhập</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                            <input type="email" id="email" name="email" class="form-control bg-light border-start-0" placeholder="admin@huit.edu.vn" value="{{ old('email', 'admin@huit.edu.vn') }}" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Mật khẩu</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                            <input type="password" id="password" name="password" class="form-control bg-light border-start-0" placeholder="••••••••" value="admin123" required>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                            <label class="form-check-label small text-muted" for="remember">Ghi nhớ đăng nhập</label>
                        </div>
                        <span class="small text-muted">Mặc định: password</span>
                    </div>

                    <button type="submit" class="btn btn-huit-primary">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Đăng Nhập
                    </button>
                </form>

                <!-- Quick Demo Login Selection -->
                <div class="pt-3 border-top">
                    <div class="fw-bold text-muted small mb-2"><i class="fa-solid fa-bolt me-1 text-warning"></i> Đăng nhập nhanh kiểm tra mẫu:</div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <button type="button" class="demo-chip text-start w-100" onclick="fillCredentials('admin@huit.edu.vn', 'admin123')">
                                <span class="badge bg-danger text-white me-1">Admin</span>
                                <strong>admin@huit.edu.vn</strong>
                                <div class="text-muted" style="font-size:0.7rem;">Mật khẩu: admin123</div>
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="demo-chip text-start w-100" onclick="fillCredentials('nguyenvanan@huit.edu.vn', 'password')">
                                <span class="badge bg-primary text-white me-1">Giảng viên</span>
                                <strong>nguyenvanan@huit.edu.vn</strong>
                                <div class="text-muted" style="font-size:0.7rem;">TS. Nguyễn Văn An (Web/PHP)</div>
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="demo-chip text-start w-100" onclick="fillCredentials('lehoangcuong@huit.edu.vn', 'password')">
                                <span class="badge bg-primary text-white me-1">Giảng viên</span>
                                <strong>lehoangcuong@huit.edu.vn</strong>
                                <div class="text-muted" style="font-size:0.7rem;">PGS.TS. Lê Hoàng Cường (AI)</div>
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="demo-chip text-start w-100" onclick="fillCredentials('phamminhdung@huit.edu.vn', 'password')">
                                <span class="badge bg-primary text-white me-1">Giảng viên</span>
                                <strong>phamminhdung@huit.edu.vn</strong>
                                <div class="text-muted" style="font-size:0.7rem;">ThS. Phạm Minh Dũng (Mạng/Bảo mật)</div>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
