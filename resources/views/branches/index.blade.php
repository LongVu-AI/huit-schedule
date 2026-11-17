@extends('layouts.app')

@section('title', 'Quản Lý Cơ Sở Đào Tạo')

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-building-columns text-primary me-2"></i>Quản Lý Cơ Sở Đào Tạo
            </h3>
            <p class="text-muted small mb-0">Quản lý các khuôn viên, cơ sở chính và trung tâm thực hành trực thuộc HUIT</p>
        </div>
        <a href="{{ route('branches.create') }}" class="btn btn-huit-primary btn-sm px-3 py-2">
            <i class="fa-solid fa-plus-circle me-1"></i> Thêm Cơ Sở Mới
        </a>
    </div>

    <!-- Table of Branches -->
    <div class="card card-custom">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-list text-primary"></i>
                <span>Danh Sách Cơ Sở Đào Tạo ({{ $branches->count() }} cơ sở)</span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width: 80px;" class="text-center">#ID</th>
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
                            <div class="fw-bold text-primary fs-6">
                                <i class="fa-solid fa-building me-1 text-secondary"></i>{{ $branch->branch_name }}
                            </div>
                        </td>
                        <td>
                            <div class="text-dark">
                                <i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $branch->address }}
                            </div>
                            <a href="https://maps.google.com/?q={{ urlencode($branch->address) }}" target="_blank" class="small text-decoration-none" style="font-size: 0.75rem;">
                                Mở trên Google Maps <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary-subtle text-primary border px-3 py-2 fs-6">
                                {{ $branch->schedules_count }} ca học
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('branches.edit', $branch) }}" class="btn btn-outline-primary" title="Chỉnh sửa">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('branches.destroy', $branch) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa cơ sở này? Toàn bộ lịch dạy tại cơ sở này cũng sẽ bị xóa!');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
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
