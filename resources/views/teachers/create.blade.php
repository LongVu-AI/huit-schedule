@extends('layouts.app')

@section('title', 'Thêm Giảng Viên Mới')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-user-plus text-primary me-2"></i>Thêm Giảng Viên Mới
            </h3>
            <p class="text-muted small mb-0">Hệ thống sẽ tự động tạo tài khoản Portal cho giảng viên để đăng nhập tra cứu lịch</p>
        </div>
        <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card card-custom">
                <div class="card-header">
                    <span>Hồ Sơ Giảng Viên & Tài Khoản Cổng Đào Tạo</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('teachers.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">
                                Họ Và Tên Giảng Viên <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" 
                                placeholder="Ví dụ: TS. Nguyễn Văn A..." 
                                value="{{ old('full_name') }}" required>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark">
                                    Email HUIT (Dùng để đăng nhập) <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                    placeholder="giangvien@huit.edu.vn" 
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark">
                                    Số Điện Thoại <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                    placeholder="0901234567" 
                                    value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">
                                Chuyên Môn Chính <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="expertise" class="form-control @error('expertise') is-invalid @enderror" 
                                placeholder="Ví dụ: Lập trình Web PHP, Trí tuệ nhân tạo, Mạng máy tính..." 
                                value="{{ old('expertise') }}" required>
                            <div class="form-text small">
                                <i class="fa-solid fa-lightbulb text-warning me-1"></i>Hệ thống sẽ so khớp chuyên môn này khi gợi ý giảng viên dạy các học phần tương ứng.
                            </div>
                            @error('expertise')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-dark">
                                Mật Khẩu Đăng Nhập (Tùy chọn)
                            </label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                placeholder="Để trống nếu muốn dùng mật khẩu mặc định: password">
                            <div class="form-text small text-muted">Nếu để trống, mật khẩu mặc định sẽ là <code>password</code></div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                            <button type="submit" class="btn btn-huit-primary px-4">
                                <i class="fa-solid fa-check me-1"></i> Lưu & Kích Hoạt Tài Khoản
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
