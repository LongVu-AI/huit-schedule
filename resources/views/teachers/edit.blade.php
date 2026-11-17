@extends('layouts.app')

@section('title', 'Chỉnh Sửa Thông Tin Giảng Viên')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-user-pen text-primary me-2"></i>Chỉnh Sửa Hồ Sơ Giảng Viên #{{ $teacher->id }}
            </h3>
            <p class="text-muted small mb-0">Cập nhật họ tên, thông tin liên lạc và chuyên môn giảng dạy</p>
        </div>
        <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card card-custom">
                <div class="card-header">
                    <span>Hồ Sơ Giảng Viên</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('teachers.update', $teacher) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">
                                Họ Và Tên Giảng Viên <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" 
                                value="{{ old('full_name', $teacher->full_name) }}" required>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark">
                                    Email HUIT <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email', $teacher->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark">
                                    Số Điện Thoại <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                    value="{{ old('phone', $teacher->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">
                                Chuyên Môn Giảng Dạy Chính <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="expertise" class="form-control @error('expertise') is-invalid @enderror" 
                                value="{{ old('expertise', $teacher->expertise) }}" required>
                            @error('expertise')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-dark">
                                Đổi Mật Khẩu Đăng Nhập Mới
                            </label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                placeholder="Để trống nếu không muốn đổi mật khẩu">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                            <button type="submit" class="btn btn-huit-primary px-4">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cập Nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
