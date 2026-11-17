@extends('layouts.app')

@section('title', 'Thêm Cơ Sở Đào Tạo Mới')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-plus-circle text-primary me-2"></i>Thêm Cơ Sở Đào Tạo Mới
            </h3>
            <p class="text-muted small mb-0">Thêm mới khuôn viên hoặc phòng máy thực hành của trường</p>
        </div>
        <a href="{{ route('branches.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card card-custom">
                <div class="card-header">
                    <span>Thông Tin Cơ Sở</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('branches.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">
                                Tên Cơ Sở <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="branch_name" class="form-control @error('branch_name') is-invalid @enderror" 
                                placeholder="Ví dụ: Cơ sở chính (Tân Phú), Cơ sở 2..." 
                                value="{{ old('branch_name') }}" required>
                            @error('branch_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-dark">
                                Địa Chỉ Chi Tiết <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                                placeholder="Ví dụ: 140 Lê Trọng Tấn, P. Tây Thạnh, Q. Tân Phú, TP.HCM" 
                                value="{{ old('address') }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('branches.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                            <button type="submit" class="btn btn-huit-primary px-4">
                                <i class="fa-solid fa-check me-1"></i> Lưu Cơ Sở
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
