@extends('layouts.app')

@section('title', 'Quản Lý Giảng Viên')

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                👥 Quản Lý Đội Ngũ Giảng Viên HUIT
            </h3>
            <p class="text-muted small mb-0">Quản lý hồ sơ giảng viên, lĩnh vực chuyên môn và phân quyền truy cập portal thời khóa biểu</p>
        </div>
        <a href="{{ route('teachers.create') }}" class="btn btn-huit-primary btn-sm px-3 py-2">
            ➕ Thêm Giảng Viên Mới
        </a>
    </div>

    <!-- Table -->
    <div class="card card-custom">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <span>🎓</span>
                <span>Danh Sách Giảng Viên ({{ $teachers->count() }} giảng viên)</span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width: 70px;" class="text-center">#ID</th>
                        <th>Giảng Viên</th>
                        <th>Thông Tin Liên Hệ</th>
                        <th>Chuyên Môn Giảng Dạy</th>
                        <th style="width: 140px;" class="text-center">Số Lớp Phân Bổ</th>
                        <th style="width: 160px;" class="text-center">Tài Khoản Portal</th>
                        <th style="width: 130px;" class="text-center">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                    <tr>
                        <td class="text-center fw-bold text-muted">#{{ $teacher->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $teacher->avatar_url }}" alt="{{ $teacher->full_name }}" 
                                    class="rounded-circle border" width="44" height="44" style="object-fit: cover;">
                                <div>
                                    <div class="fw-bold text-dark fs-6">{{ $teacher->full_name }}</div>
                                    <div class="text-muted small">Mã GV: GV-{{ str_pad($teacher->id, 3, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>📧 {{ $teacher->email }}</div>
                            <div class="text-muted small">📞 {{ $teacher->phone }}</div>
                        </td>
                        <td>
                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle py-2 px-2 text-wrap text-start" style="max-width: 280px; line-height: 1.4;">
                                {{ $teacher->field_emoji }} {{ $teacher->expertise }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary-subtle text-primary border px-3 py-2 fs-6">
                                📚 {{ $teacher->schedules_count }} Lớp
                            </span>
                        </td>
                        <td class="text-center">
                            @if($teacher->user)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                    ✅ Đã kích hoạt
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1">
                                    Chưa liên kết
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-outline-primary" title="Chỉnh sửa">
                                    ✏️
                                </a>
                                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa giảng viên này? Toàn bộ lịch dạy liên quan sẽ bị xóa!');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            Chưa có giảng viên nào trong hệ thống.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
