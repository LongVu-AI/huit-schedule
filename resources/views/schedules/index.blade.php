@extends('layouts.app')

@section('title', 'Thời Khóa Biểu & Quản Lý Lịch Dạy')

@section('content')
<div class="container-fluid p-0">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                📅 Tổng Quan Thời Khóa Biểu Đa Cơ Sở
            </h3>
            <p class="text-muted small mb-0">Hệ thống phân lịch giảng dạy & tối ưu thời gian di chuyển đa cơ sở trường Đại học Công Thương TP.HCM</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('schedules.matrix') }}" class="btn btn-outline-primary btn-sm px-3 py-2 fw-semibold">
                📊 Xem Ma Trận Tiết Trống
            </a>
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('schedules.create') }}" class="btn btn-huit-orange btn-sm px-3 py-2">
                ✨ Phân Lịch Giảng Dạy Mới
            </a>
            @endif
        </div>
    </div>

    <!-- Quick Stats Cards with Expressive Emojis -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">📚 Tổng Lịch Học Phần</div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $analytics['total_schedules'] }}</h3>
                    <div class="text-success small mt-1">✅ Đã phân bổ trong tuần</div>
                </div>
                <div class="stat-icon bg-primary-subtle">
                    📖
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">🏫 Cơ Sở Đào Tạo</div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $analytics['total_branches'] }}</h3>
                    <div class="text-muted small mt-1">📍 Khuôn viên trực thuộc</div>
                </div>
                <div class="stat-icon bg-warning-subtle">
                    🏛️
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">👨‍🏫 Đội Ngũ Giảng Viên</div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $analytics['total_teachers'] }}</h3>
                    <div class="text-info small mt-1">🎓 Đang công tác</div>
                </div>
                <div class="stat-icon bg-info-subtle">
                    🧑‍💻
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">⚡ Thuật Toán Tối Ưu</div>
                    <h3 class="fw-bold mb-0 text-success">Kích Hoạt</h3>
                    <div class="text-muted small mt-1">🛡️ Chống trùng lịch 100%</div>
                </div>
                <div class="stat-icon bg-success-subtle">
                    🚗
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts Section -->
    <div class="card card-custom mb-4">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <span>📊</span>
                <span>Biểu Đồ Phân Bổ Thời Gian & Tải Giảng Dạy Đa Cơ Sở</span>
            </div>
            <button class="btn btn-sm btn-light border text-muted" type="button" data-bs-toggle="collapse" data-bs-target="#analyticsCollapse">
                ↕️ Thu gọn / Mở rộng
            </button>
        </div>
        <div class="collapse show" id="analyticsCollapse">
            <div class="card-body">
                <div class="row g-4">
                    <!-- Chart 1: Branch Distribution -->
                    <div class="col-lg-4">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <h6 class="fw-bold text-dark mb-2 text-center">
                                🍩 Tỉ Lệ Phân Bổ Theo Cơ Sở
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
                                📊 Mật Độ Lịch Dạy Theo Thứ Trong Tuần
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
                                📈 Khối Lượng Tiết Dạy Giảng Viên
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
                    <label class="form-label small fw-bold text-muted mb-1">🏫 Lọc Theo Cơ Sở</label>
                    <select name="branch_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Tất cả cơ sở --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->emoji }} {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Teacher -->
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted mb-1">👨‍🏫 Lọc Theo Giảng Viên</label>
                    <select name="teacher_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Tất cả giảng viên --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->field_emoji }} {{ $teacher->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Day of Week -->
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted mb-1">📅 Lọc Theo Thứ</label>
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
                    <label class="form-label small fw-bold text-muted mb-1">🔍 Tìm Môn Học / Phòng</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Tên môn, phòng, GV..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">Tìm</button>
                    </div>
                </div>

                <!-- Reset Button -->
                <div class="col-md-1 d-flex align-items-end">
                    <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary btn-sm w-100" title="Xóa bộ lọc" style="margin-top: 22px;">
                        🔄
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Schedules Table -->
    <div class="card card-custom">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <span>📋</span>
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
                        <th style="width: 80px;" class="text-center">Thứ</th>
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
                                🕒 Ca chính quy
                            </span>
                        </td>

                        <!-- Subject with Emoji -->
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-4">{{ $schedule->subject_emoji }}</span>
                                <div>
                                    <div class="fw-bold text-primary fs-6">{{ $schedule->subject_name }}</div>
                                    <div class="text-muted small">Mã học phần: HP-{{ str_pad($schedule->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Teacher with Avatar -->
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $schedule->teacher->avatar_url }}" alt="{{ $schedule->teacher->full_name }}" 
                                    class="rounded-circle border" width="38" height="38" style="object-fit: cover;">
                                <div>
                                    <div class="fw-bold text-dark">{{ $schedule->teacher->full_name ?? 'Chưa phân' }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        {{ $schedule->teacher->field_emoji }} {{ $schedule->teacher->expertise ?? 'Chuyên môn chung' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Branch & Room -->
                        <td>
                            <div class="fw-bold text-danger">
                                🚪 Phòng: {{ $schedule->room }}
                            </div>
                            <div class="fw-semibold text-dark small">
                                {{ $schedule->branch->emoji }} {{ $schedule->branch->branch_name ?? 'Chưa rõ' }}
                            </div>
                            <div class="text-muted" style="font-size: 0.73rem;">
                                📍 {{ $schedule->branch->address ?? '' }}
                            </div>
                        </td>

                        <!-- Admin Actions -->
                        @if(auth()->user()->role === 'admin')
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-outline-primary" title="Chỉnh sửa lịch">
                                    ✏️
                                </a>
                                <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch dạy này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Xóa lịch">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 5 }}" class="text-center py-5 text-muted">
                            <div class="fs-1 mb-2">📅</div>
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
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });
        }

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
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });
        }
    });
</script>
@endpush
