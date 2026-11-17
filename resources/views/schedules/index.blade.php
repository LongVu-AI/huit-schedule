@extends('layouts.app')

@section('title', 'Thời Khóa Biểu & Quản Lý Lịch Dạy')

@section('content')
<div class="container-fluid p-0">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-calendar-days text-primary me-2"></i>Tổng Quan Thời Khóa Biểu Đa Cơ Sở
            </h3>
            <p class="text-muted small mb-0">Hệ thống phân lịch giảng dạy & tối ưu thời gian di chuyển đa cơ sở trường Đại học Công Thương TP.HCM</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('schedules.matrix') }}" class="btn btn-outline-primary btn-sm px-3 py-2 fw-semibold">
                <i class="fa-solid fa-table-cells me-1"></i> Xem Ma Trận Tiết Trống
            </a>
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('schedules.create') }}" class="btn btn-huit-orange btn-sm px-3 py-2">
                <i class="fa-solid fa-plus-circle me-1"></i> Phân Lịch Giảng Dạy Mới
            </a>
            @endif
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Tổng Lịch Học Phần</div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $analytics['total_schedules'] }}</h3>
                    <div class="text-success small mt-1"><i class="fa-solid fa-check-double me-1"></i>Đã phân bổ trong tuần</div>
                </div>
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="fa-solid fa-book-open"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Cơ Sở Đào Tạo</div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $analytics['total_branches'] }}</h3>
                    <div class="text-muted small mt-1"><i class="fa-solid fa-building me-1"></i>Khuôn viên trực thuộc</div>
                </div>
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Đội Ngũ Giảng Viên</div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $analytics['total_teachers'] }}</h3>
                    <div class="text-info small mt-1"><i class="fa-solid fa-users me-1"></i>Đang công tác</div>
                </div>
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Thuật Toán Tối Ưu</div>
                    <h3 class="fw-bold mb-0 text-success">Kích Hoạt</h3>
                    <div class="text-muted small mt-1"><i class="fa-solid fa-shield-halved me-1"></i>Chống trùng lịch 100%</div>
                </div>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts Section (Biểu đồ phân bổ thời gian - Đề bài Phần 2) -->
    <div class="card card-custom mb-4">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-chart-pie text-primary"></i>
                <span>Biểu Đồ Phân Bổ Thời Gian & Tải Giảng Dạy Đa Cơ Sở</span>
            </div>
            <button class="btn btn-sm btn-light border text-muted" type="button" data-bs-toggle="collapse" data-bs-target="#analyticsCollapse">
                <i class="fa-solid fa-arrows-up-down me-1"></i> Thu gọn / Mở rộng
            </button>
        </div>
        <div class="collapse show" id="analyticsCollapse">
            <div class="card-body">
                <div class="row g-4">
                    <!-- Chart 1: Branch Distribution -->
                    <div class="col-lg-4">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <h6 class="fw-bold text-dark mb-2 text-center">
                                <i class="fa-solid fa-chart-pie me-1 text-primary"></i> Tỉ Lệ Phân Bổ Theo Cơ Sở
                            </h6>
                            <p class="text-muted small text-center mb-3">Mật độ lớp học phân bổ tại các cơ sở trường</p>
                            <div style="height: 230px; position: relative;">
                                <canvas id="branchChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Chart 2: Day of Week Distribution -->
                    <div class="col-lg-4">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <h6 class="fw-bold text-dark mb-2 text-center">
                                <i class="fa-solid fa-chart-column me-1 text-success"></i> Mật Độ Lịch Dạy Theo Ngày Trong Tuần
                            </h6>
                            <p class="text-muted small text-center mb-3">Số lượng tiết giảng từ Thứ 2 đến Thứ 7</p>
                            <div style="height: 230px; position: relative;">
                                <canvas id="dayChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Chart 3: Top Teacher Workload -->
                    <div class="col-lg-4">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <h6 class="fw-bold text-dark mb-2 text-center">
                                <i class="fa-solid fa-chart-bar me-1 text-warning"></i> Khối Lượng Tiết Dạy Giảng Viên
                            </h6>
                            <p class="text-muted small text-center mb-3">Phân bổ ca dạy của các Giảng viên chính</p>
                            <div style="height: 230px; position: relative;">
                                <canvas id="teacherChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="card card-custom mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('schedules.index') }}" class="row g-2 align-items-center">
                <!-- Filter Branch -->
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted mb-1">Lọc Theo Cơ Sở</label>
                    <select name="branch_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Tất cả cơ sở --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Teacher -->
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted mb-1">Lọc Theo Giảng Viên</label>
                    <select name="teacher_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Tất cả giảng viên --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Day of Week -->
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted mb-1">Lọc Theo Thứ</label>
                    <select name="day_of_week" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Cả tuần --</option>
                        @for($d = 2; $d <= 7; $d++)
                            <option value="{{ $d }}" {{ request('day_of_week') == $d ? 'selected' : '' }}>
                                Thứ {{ $d }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Search Input -->
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted mb-1">Tìm Môn Học / Phòng</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Tên môn, phòng, GV..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>

                <!-- Reset Button -->
                <div class="col-md-1 d-flex align-items-end">
                    <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary btn-sm w-100" title="Xóa bộ lọc" style="margin-top: 22px;">
                        <i class="fa-solid fa-arrow-rotate-left"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Schedules Table -->
    <div class="card card-custom">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-list-check text-primary"></i>
                <span>Danh Sách Lịch Giảng Dạy Đang Vận Hành</span>
            </div>
            <span class="badge bg-primary-subtle text-primary border px-2 py-1">
                Hiển thị: {{ $schedules->total() }} kết quả
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width: 70px;" class="text-center">Thứ</th>
                        <th style="width: 220px;">Thời Gian / Tiết Học</th>
                        <th>Học Phần (Môn Học)</th>
                        <th>Giảng Viên Giảng Dạy</th>
                        <th>Cơ Sở & Phòng Học</th>
                        @if(auth()->user()->role === 'admin')
                        <th style="width: 140px;" class="text-center">Thao Tác</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                    <tr>
                        <!-- Day badge -->
                        <td class="text-center">
                            <span class="badge rounded-pill bg-primary fs-6 px-3 py-2">
                                {{ $schedule->day_name }}
                            </span>
                        </td>

                        <!-- Shift / Period -->
                        <td>
                            <div class="fw-bold text-dark">{{ $schedule->shift_or_periods }}</div>
                            <span class="badge bg-light text-secondary border mt-1">
                                <i class="fa-regular fa-clock me-1"></i>Ca học chính quy
                            </span>
                        </td>

                        <!-- Subject -->
                        <td>
                            <div class="fw-bold text-primary fs-6">{{ $schedule->subject_name }}</div>
                            <div class="text-muted small">Mã học phần: HP-{{ str_pad($schedule->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </td>

                        <!-- Teacher -->
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar bg-primary text-white" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    {{ strtoupper(substr($schedule->teacher->full_name ?? 'GV', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $schedule->teacher->full_name ?? 'Chưa phân' }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-graduation-cap me-1 text-warning"></i>{{ $schedule->teacher->expertise ?? 'Chuyên môn chung' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Branch & Room -->
                        <td>
                            <div class="fw-bold text-danger">
                                <i class="fa-solid fa-door-open me-1"></i>Phòng: {{ $schedule->room }}
                            </div>
                            <div class="fw-semibold text-dark small">
                                <i class="fa-solid fa-location-dot me-1 text-primary"></i>{{ $schedule->branch->branch_name ?? 'Chưa rõ' }}
                            </div>
                            <div class="text-muted" style="font-size: 0.73rem;">
                                {{ $schedule->branch->address ?? '' }}
                            </div>
                        </td>

                        <!-- Admin Actions -->
                        @if(auth()->user()->role === 'admin')
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-outline-primary" title="Chỉnh sửa lịch">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch dạy này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Xóa lịch">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 5 }}" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-calendar-xmark fs-1 text-black-50 mb-3 d-block"></i>
                            <div class="fw-bold fs-6">Không tìm thấy lịch dạy nào phù hợp với bộ lọc!</div>
                            <p class="small mb-3">Vui lòng thử điều chỉnh lại cơ sở, giảng viên hoặc bấm xóa bộ lọc.</p>
                            <a href="{{ route('schedules.index') }}" class="btn btn-sm btn-outline-primary">Xem toàn bộ lịch</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($schedules->hasPages())
        <div class="card-footer bg-white border-top p-3 d-flex justify-content-end">
            {{ $schedules->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Branch Distribution Chart
        const branchCtx = document.getElementById('branchChart');
        if (branchCtx) {
            new Chart(branchCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($analytics['branch_labels']) !!},
                    datasets: [{
                        data: {!! json_encode($analytics['branch_counts']) !!},
                        backgroundColor: ['#0b3c85', '#f06a19', '#10b981', '#6366f1', '#ec4899'],
                        borderWidth: 2,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                    }
                }
            });
        }

        // 2. Day of Week Chart
        const dayCtx = document.getElementById('dayChart');
        if (dayCtx) {
            new Chart(dayCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($analytics['day_labels']) !!},
                    datasets: [{
                        label: 'Số tiết dạy',
                        data: {!! json_encode($analytics['day_counts']) !!},
                        backgroundColor: '#10b981',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        }

        // 3. Teacher Workload Chart
        const teacherCtx = document.getElementById('teacherChart');
        if (teacherCtx) {
            new Chart(teacherCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($analytics['teacher_labels']) !!},
                    datasets: [{
                        label: 'Số lớp phụ trách',
                        data: {!! json_encode($analytics['teacher_counts']) !!},
                        backgroundColor: '#f06a19',
                        borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        }
    });
</script>
@endpush
