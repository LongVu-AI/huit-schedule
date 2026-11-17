@extends('layouts.app')

@section('title', 'Chỉnh Sửa Cơ Sở Đào Tạo')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-pen-to-square text-primary me-2"></i>Chỉnh Sửa Cơ Sở #{{ $branch->id }}
            </h3>
            <p class="text-muted small mb-0">Cập nhật tên và địa chỉ của cơ sở đào tạo</p>
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
                    <form method="POST" action="{{ route('branches.update', $branch) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">
                                Tên Cơ Sở <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="branch_name" class="form-control @error('branch_name') is-invalid @enderror" 
                                value="{{ old('branch_name', $branch->branch_name) }}" required>
                            @error('branch_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-dark">
                                Địa Chỉ Chi Tiết <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                                value="{{ old('address', $branch->address) }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('branches.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                            <button type="submit" class="btn btn-huit-primary px-4">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Cập Nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
