<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - HUIT Schedule Hub</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/huit-crest.svg') }}">
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
            max-width: 980px;
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
            padding: 2.75rem 2.5rem;
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
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .feature-emoji {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(255,255,255,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .demo-chip {
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
            text-align: left;
            width: 100%;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .demo-chip:hover {
            background-color: #e2e8f0;
            border-color: var(--huit-blue);
            transform: translateY(-1px);
        }

        .demo-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            object-fit: cover;
            border: 1.5px solid #cbd5e1;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="row g-0">
            <!-- Left Info Panel -->
            <div class="col-lg-5 login-banner d-none d-lg-flex">
                <div>
                    <!-- HUIT Crest & Title -->
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="{{ asset('images/huit-crest.svg') }}" alt="HUIT Crest" width="56" height="56">
                        <div>
                            <div class="fw-bold fs-4 text-white lh-1">HUIT Schedule</div>
                            <div class="text-white-50 small mt-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">ĐẠI HỌC CÔNG THƯƠNG TP.HCM</div>
                        </div>
                    </div>

                    <h4 class="fw-bold mb-3 text-white">Quản lý & Phân lịch Giảng dạy Đa Cơ sở</h4>
                    <p class="text-white-50 small mb-4">Hệ thống thông minh hỗ trợ Phòng Đào tạo tối ưu hóa cung đường di chuyển và giúp Giảng viên nắm bắt lịch dạy cá nhân theo tuần.</p>

                    <div class="feature-item">
                        <div class="feature-emoji">✨</div>
                        <div>
                            <div class="fw-bold small text-white">Gợi ý Giảng viên Thông minh</div>
                            <div class="text-white-50" style="font-size: 0.75rem;">Đối sánh chuyên môn & phân tích tiết trống</div>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-emoji">🚗</div>
                        <div>
                            <div class="fw-bold small text-white">Tối ưu Di chuyển Đa Cơ sở</div>
                            <div class="text-white-50" style="font-size: 0.75rem;">Giảm thời gian di chuyển giữa Tân Phú, Q1, xưởng máy</div>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-emoji">📅</div>
                        <div>
                            <div class="fw-bold small text-white">Cổng Tra cứu Trực quan</div>
                            <div class="text-white-50" style="font-size: 0.75rem;">Thời khóa biểu tuần, chỉ dẫn bản đồ và in lịch dạy</div>
                        </div>
                    </div>
                </div>

                <div class="border-top border-white border-opacity-10 pt-3 text-white-50 small d-flex align-items-center justify-content-between">
                    <span>🏛️ Trường ĐH Công Thương TP.HCM</span>
                    <span class="badge bg-warning text-dark fw-bold">v2.0 🚀</span>
                </div>
            </div>

            <!-- Right Login Form -->
            <div class="col-lg-7 login-form-area">
                <div class="mb-4">
                    <img src="{{ asset('images/logo-huit.svg') }}" alt="HUIT Logo" height="42" class="mb-3 d-block">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h3 class="fw-bold text-dark mb-0">Đăng Nhập Cổng Đào Tạo</h3>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                            🔐 Portal
                        </span>
                    </div>
                    <p class="text-muted small">Vui lòng đăng nhập với tài khoản HUIT được cấp quyền</p>
                </div>

                @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show small" role="alert">
                    ℹ️ {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                    ❌ {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mb-3">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Email HUIT</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">📧</span>
                            <input type="email" id="email" name="email" class="form-control bg-light border-start-0" placeholder="admin@huit.edu.vn" value="{{ old('email', 'admin@huit.edu.vn') }}" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Mật khẩu</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">🔑</span>
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
                        🚀 Đăng Nhập Hệ Thống
                    </button>
                </form>

                <!-- Quick Demo Login Selection with Avatars & Emojis -->
                <div class="pt-3 border-top">
                    <div class="fw-bold text-muted small mb-2">⚡ Bấm vào để chọn nhanh tài khoản kiểm tra mẫu:</div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <button type="button" class="demo-chip" onclick="fillCredentials('admin@huit.edu.vn', 'admin123')">
                                <img src="{{ asset('images/avatars/admin.svg') }}" alt="Admin" class="demo-avatar">
                                <div>
                                    <div class="fw-bold text-dark lh-1">🛡️ Quản trị viên</div>
                                    <div class="text-muted" style="font-size:0.7rem;">admin@huit.edu.vn</div>
                                </div>
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="demo-chip" onclick="fillCredentials('nguyenvanan@huit.edu.vn', 'password')">
                                <img src="{{ asset('images/avatars/teacher-1.svg') }}" alt="TS. Nguyễn Văn An" class="demo-avatar">
                                <div>
                                    <div class="fw-bold text-dark lh-1">💻 TS. Nguyễn Văn An</div>
                                    <div class="text-muted" style="font-size:0.7rem;">Giảng viên Web / PHP</div>
                                </div>
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="demo-chip" onclick="fillCredentials('tranthibich@huit.edu.vn', 'password')">
                                <img src="{{ asset('images/avatars/teacher-2.svg') }}" alt="ThS. Trần Thị Bích" class="demo-avatar">
                                <div>
                                    <div class="fw-bold text-dark lh-1">🧪 ThS. Trần Thị Bích</div>
                                    <div class="text-muted" style="font-size:0.7rem;">Giảng viên QA/QC</div>
                                </div>
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="demo-chip" onclick="fillCredentials('lehoangcuong@huit.edu.vn', 'password')">
                                <img src="{{ asset('images/avatars/teacher-3.svg') }}" alt="PGS.TS. Lê Hoàng Cường" class="demo-avatar">
                                <div>
                                    <div class="fw-bold text-dark lh-1">🤖 PGS.TS. Lê Hoàng Cường</div>
                                    <div class="text-muted" style="font-size:0.7rem;">Giảng viên Trí tuệ nhân tạo</div>
                                </div>
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
