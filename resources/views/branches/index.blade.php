@extends('layouts.app')

@section('title', 'Quản Lý Cơ Sở Đào Tạo')

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                🏫 Quản Lý Cơ Sở Đào Tạo Đa Khuôn Viên
            </h3>
            <p class="text-muted small mb-0">Hệ thống khuôn viên, trung tâm thực hành công nghệ và trung tâm liên kết thuộc HUIT</p>
        </div>
        <a href="{{ route('branches.create') }}" class="btn btn-huit-primary btn-sm px-3 py-2">
            ➕ Thêm Cơ Sở Mới
        </a>
    </div>

    <!-- Visual Campus Cards Grid -->
    <div class="row g-4 mb-4">
        @foreach($branches as $branch)
        <div class="col-md-6 col-xl-3">
            <div class="card card-custom h-100 border-0 shadow-sm overflow-hidden">
                <div style="height: 140px; overflow: hidden; background: #f1f5f9; position: relative;">
                    <img src="{{ $branch->image_url }}" alt="{{ $branch->branch_name }}" class="w-100 h-100" style="object-fit: cover;">
                    <span class="position-absolute top-0 end-0 m-2 badge bg-dark bg-opacity-75 fs-6">
                        {{ $branch->emoji }} #{{ $branch->id }}
                    </span>
                </div>
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <h6 class="fw-bold text-primary mb-1">{{ $branch->branch_name }}</h6>
                        <p class="text-muted small mb-2" style="font-size: 0.78rem; min-height: 38px;">
                            📍 {{ $branch->address }}
                        </p>
                    </div>
                    <div class="pt-2 border-top d-flex align-items-center justify-content-between">
                        <span class="badge bg-primary-subtle text-primary border" style="font-size: 0.75rem;">
                            📚 {{ $branch->schedules_count }} ca học
                        </span>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('branches.edit', $branch) }}" class="btn btn-outline-primary" title="Sửa">✏️</a>
                            <form action="{{ route('branches.destroy', $branch) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa cơ sở này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Xóa">🗑️</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Table of Branches -->
    <div class="card card-custom">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <span>📋</span>
                <span>Bảng Danh Sách Chi Tiết Cơ Sở</span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width: 80px;" class="text-center">#ID</th>
                        <th style="width: 120px;">Hình Ảnh</th>
                        <th>Tên Cơ Sở</th>
                        <th>Địa Chỉ Khuôn Viên</th>
                        <th style="width: 180px;" class="text-center">Số Lớp Đang Học</th>
                        <th style="width: 150px;" class="text-center">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $branch)
                    <tr>
                        <td class="text-center fw-bold text-muted">#{{ $branch->id }}</td>
                        <td>
                            <img src="{{ $branch->image_url }}" alt="{{ $branch->branch_name }}" class="rounded border" width="70" height="45" style="object-fit: cover;">
                        </td>
                        <td>
                            <div class="fw-bold text-primary fs-6">
                                {{ $branch->emoji }} {{ $branch->branch_name }}
                            </div>
                        </td>
                        <td>
                            <div class="text-dark">
                                📍 {{ $branch->address }}
                            </div>
                            <a href="https://maps.google.com/?q={{ urlencode($branch->address) }}" target="_blank" class="small text-decoration-none" style="font-size: 0.75rem;">
                                Mở trên Google Maps 🗺️
                            </a>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary-subtle text-primary border px-3 py-2 fs-6">
                                📚 {{ $branch->schedules_count }} ca học
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('branches.edit', $branch) }}" class="btn btn-outline-primary" title="Chỉnh sửa">
                                    ✏️ Sửa
                                </a>
                                <form action="{{ route('branches.destroy', $branch) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa cơ sở này? Toàn bộ lịch dạy tại cơ sở này cũng sẽ bị xóa!');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                        🗑️ Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Chưa có cơ sở nào. Vui lòng bấm thêm mới!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
