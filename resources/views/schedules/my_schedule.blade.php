@extends('layouts.app')

@section('title', 'Lịch Dạy Cá Nhân Giảng Viên')

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3 no-print">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-id-badge text-primary me-2"></i>Cổng Thông Tin Lịch Giảng Dạy Cá Nhân
            </h3>
            <p class="text-muted small mb-0">Tra cứu thời khóa biểu theo tuần, định vị phòng học & cơ sở đào tạo trường ĐH Công Thương TP.HCM</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm px-3 py-2">
                <i class="fa-solid fa-print me-1"></i> In Thời Khóa Biểu
            </button>
            <a href="{{ route('schedules.index') }}" class="btn btn-huit-primary btn-sm px-3 py-2">
                <i class="fa-solid fa-calendar-days me-1"></i> Xem Thời Khóa Biểu Chung
            </a>
        </div>
    </div>

    <!-- Admin Switcher (If Admin views this page) -->
    @if(auth()->user()->role === 'admin' && isset($allTeachers))
    <div class="alert alert-info border-0 shadow-sm mb-4 no-print d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-user-shield fs-4"></i>
            <div>
                <strong>Chế độ Quản Trị Viên Xem Lịch Giảng Viên:</strong>
                <span class="small d-block text-muted">Bạn có thể chọn bất kỳ giảng viên nào để xem trước lịch cá nhân của họ.</span>
            </div>
        </div>
        <form method="GET" action="{{ route('schedules.my') }}" class="d-flex align-items-center gap-2">
            <select name="teacher_id" class="form-select form-select-sm" onchange="this.form.submit()">
                @foreach($allTeachers as $t)
                    <option value="{{ $t->id }}" {{ $teacher->id === $t->id ? 'selected' : '' }}>
                        {{ $t->full_name }} — [{{ $t->expertise }}]
                    </option>
                @endforeach
            </select>
        </form>
    </div>
    @endif

    <!-- Teacher Info Profile Card -->
    <div class="card card-custom mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-auto">
                    <div class="user-avatar" style="width: 64px; height: 64px; font-size: 1.5rem; background: linear-gradient(135deg, #0b3c85, #f06a19);">
                        {{ strtoupper(substr($teacher->full_name, 0, 1)) }}
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <h4 class="fw-bold mb-0 text-dark">{{ $teacher->full_name }}</h4>
                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                            <i class="fa-solid fa-circle-check me-1"></i>Đang công tác
                        </span>
                    </div>
                    <div class="text-muted small mb-2">
                        <i class="fa-solid fa-graduation-cap text-warning me-1"></i>Chuyên môn chính: <strong>{{ $teacher->expertise }}</strong>
                    </div>
                    <div class="d-flex flex-wrap gap-3 small text-muted">
                        <span><i class="fa-solid fa-envelope me-1 text-primary"></i>{{ $teacher->email }}</span>
                        <span><i class="fa-solid fa-phone me-1 text-success"></i>{{ $teacher->phone }}</span>
                        <span><i class="fa-solid fa-building me-1 text-secondary"></i>Đại học Công Thương TP.HCM (HUIT)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Metrics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-md-4">
            <div class="stat-card">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Khối Lượng Ca Dạy</div>
                    <h3 class="fw-bold mb-0 text-primary">{{ $totalClasses }} Lớp</h3>
                    <div class="text-muted small mt-1">Trong tuần học kỳ</div>
                </div>
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="fa-solid fa-book-open-reader"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="stat-card">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Số Cơ Sở Cần Đến</div>
                    <h3 class="fw-bold mb-0 text-danger">{{ $uniqueBranches->count() }} Cơ sở</h3>
                    <div class="text-muted small mt-1">
                        @if($uniqueBranches->count() <= 1)
                            <span class="text-success"><i class="fa-solid fa-check me-1"></i>Cùng 1 cơ sở (Tối ưu)</span>
                        @else
                            <span class="text-warning"><i class="fa-solid fa-route me-1"></i>Cần di chuyển đa cơ sở</span>
                        @endif
                    </div>
                </div>
                <div class="stat-icon bg-danger-subtle text-danger">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="stat-card">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Ngày Lên Lớp</div>
                    <h3 class="fw-bold mb-0 text-success">{{ $teachingDays }} Ngày / Tuần</h3>
                    <div class="text-muted small mt-1">Thứ 2 đến Thứ 7</div>
                </div>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Branches List with Google Maps Links -->
    <div class="card card-custom mb-4 no-print">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-map-location-dot text-danger"></i>
                <span>Các Cơ Sở Bạn Cần Đến Giảng Dạy Tuần Này</span>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="row g-3">
                @forelse($uniqueBranches as $ub)
                <div class="col-md-6 col-lg-3">
                    <div class="p-3 rounded border bg-light h-100">
                        <div class="fw-bold text-primary mb-1">
                            <i class="fa-solid fa-building-columns me-1"></i>{{ $ub->branch_name }}
                        </div>
                        <div class="text-muted small mb-2" style="min-height: 40px;">
                            {{ $ub->address }}
                        </div>
                        <a href="https://maps.google.com/?q={{ urlencode($ub->address) }}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2 fw-semibold" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-diamond-turn-right me-1"></i>Mở Google Maps
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-muted py-3">
                    Chưa có cơ sở nào được phân bổ trong tuần này.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Timetable View Tabs -->
    <div class="card card-custom">
        <div class="card-header border-bottom-0 pb-0">
            <ul class="nav nav-tabs card-header-tabs" id="myScheduleTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-bold" id="grid-tab" data-bs-toggle="tab" data-bs-target="#grid-view" type="button" role="tab">
                        <i class="fa-solid fa-calendar-week me-1"></i> Lưới Lịch Dạy Theo Tuần
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold" id="list-tab" data-bs-toggle="tab" data-bs-target="#list-view" type="button" role="tab">
                        <i class="fa-solid fa-list-ul me-1"></i> Danh Sách Chi Tiết Theo Thứ
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-4">
            <div class="tab-content" id="myScheduleTabsContent">
                <!-- Tab 1: Weekly Grid View -->
                <div class="tab-pane fade show active" id="grid-view" role="tabpanel">
                    <div class="row g-3">
                        @for($d = 2; $d <= 7; $d++)
                        @php
                            $daySchedules = $schedulesByDay->get($d, collect());
                        @endphp
                        <div class="col-md-6 col-xl-4">
                            <div class="p-3 rounded-3 border h-100 {{ $daySchedules->isNotEmpty() ? 'bg-white border-primary border-opacity-50 shadow-xs' : 'bg-light border-dashed' }}">
                                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                    <h6 class="fw-bold mb-0 {{ $daySchedules->isNotEmpty() ? 'text-primary' : 'text-muted' }}">
                                        <i class="fa-regular fa-calendar-days me-1"></i>Thứ {{ $d }}
                                    </h6>
                                    <span class="badge {{ $daySchedules->isNotEmpty() ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ $daySchedules->count() }} Lớp
                                    </span>
                                </div>

                                @forelse($daySchedules as $s)
                                <div class="p-2 mb-2 rounded border bg-light shadow-2xs">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="badge bg-primary-subtle text-primary border" style="font-size: 0.75rem;">
                                            {{ $s->shift_or_periods }}
                                        </span>
                                        <span class="badge bg-danger-subtle text-danger fw-bold">
                                            Phòng {{ $s->room }}
                                        </span>
                                    </div>
                                    <div class="fw-bold text-dark mt-2" style="font-size: 0.9rem;">
                                        {{ $s->subject_name }}
                                    </div>
                                    <div class="text-muted small mt-1" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-location-dot text-danger me-1"></i>{{ $s->branch->branch_name }}
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="fa-regular fa-circle-check text-success fs-4 mb-1 d-block"></i>
                                    <span class="small">Lịch trống cả ngày</span>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

                <!-- Tab 2: List View -->
                <div class="tab-pane fade" id="list-view" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-custom table-hover">
                            <thead>
                                <tr>
                                    <th>Thứ</th>
                                    <th>Thời Gian / Tiết</th>
                                    <th>Tên Học Phần</th>
                                    <th>Phòng Học</th>
                                    <th>Cơ Sở Giảng Dạy</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $s)
                                <tr>
                                    <td>
                                        <span class="badge rounded-pill bg-primary fs-6 px-3 py-2">
                                            {{ $s->day_name }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $s->shift_or_periods }}</strong>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-primary fs-6">{{ $s->subject_name }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger-subtle text-danger fs-6 px-2 py-1">
                                            <i class="fa-solid fa-door-open me-1"></i>{{ $s->room }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $s->branch->branch_name }}</div>
                                        <div class="text-muted small">{{ $s->branch->address }}</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Hiện tại bạn chưa được phân lịch dạy môn nào.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
