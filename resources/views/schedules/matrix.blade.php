@extends('layouts.app')

@section('title', 'Ma Trận Tiết Trống & Thời Khóa Biểu')

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-table-cells text-success me-2"></i>Ma Trận Lịch Dạy & Tiết Trống Tuần
            </h3>
            <p class="text-muted small mb-0">Hỗ trợ nhận diện khoảng trống chết, phân lịch vào tiết trống và tối ưu cung đường di chuyển đa cơ sở</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-2">
                <i class="fa-solid fa-table-list me-1"></i> Xem Dạng Bảng Chi Tiết
            </a>
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('schedules.create') }}" class="btn btn-huit-primary btn-sm px-3 py-2">
                <i class="fa-solid fa-plus-circle me-1"></i> Phân Lịch Mới
            </a>
            @endif
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card card-custom mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('schedules.matrix') }}" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-muted mb-1">Lọc Theo Cơ Sở Cần Kiểm Tra</label>
                    <select name="branch_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Tất cả cơ sở --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label small fw-bold text-muted mb-1">Lọc Theo Giảng Viên</label>
                    <select name="teacher_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Tất cả giảng viên --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ $teacherId == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->full_name }} — [{{ $teacher->expertise }}]
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('schedules.matrix') }}" class="btn btn-outline-secondary btn-sm w-100" style="margin-top: 22px;">
                        <i class="fa-solid fa-arrow-rotate-left me-1"></i> Xem Tất Cả
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Matrix Timetable Grid -->
    <div class="card card-custom">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-calendar-check text-primary"></i>
                <span>Lưới Phân Lịch Thứ 2 - Thứ 7 (Bấm vào ô màu xanh để phân lịch vào tiết trống)</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                    <i class="fa-solid fa-square me-1"></i>Tiết Trống Khả Dụng
                </span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                    <i class="fa-solid fa-square me-1"></i>Đã Có Lịch Dạy
                </span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered mb-0 align-middle" style="min-width: 980px;">
                <thead>
                    <tr class="bg-light text-center">
                        <th style="width: 140px;" class="py-3 bg-light">Ca / Tiết Học</th>
                        @foreach($matrixData['days'] as $dayNum => $dayName)
                            <th class="py-3 bg-light text-dark fw-bold" style="width: calc(86% / 6);">
                                {{ $dayName }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($matrixData['matrix'] as $shiftLabel => $shiftRow)
                    <tr>
                        <!-- Shift Meta column -->
                        <td class="bg-light fw-bold text-center p-2">
                            <div class="text-primary fw-bold" style="font-size: 0.88rem;">{{ $shiftRow['meta']['short'] }}</div>
                            <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.72rem;">
                                {{ $shiftRow['meta']['shift'] === 'morning' ? 'Buổi Sáng' : ($shiftRow['meta']['shift'] === 'afternoon' ? 'Buổi Chiều' : 'Buổi Tối') }}
                            </span>
                        </td>

                        <!-- Day columns -->
                        @foreach($shiftRow['days'] as $dayNum => $dayCell)
                        <td class="p-2 align-top" style="height: 115px; background-color: {{ $dayCell['is_free'] ? '#fcfff9' : '#ffffff' }};">
                            @if(!$dayCell['is_free'])
                                @foreach($dayCell['schedules'] as $s)
                                    <div class="p-2 mb-2 rounded border border-primary border-opacity-25 bg-primary-subtle bg-opacity-25 shadow-xs" style="font-size: 0.8rem;">
                                        <div class="fw-bold text-primary text-truncate" title="{{ $s->subject_name }}">
                                            {{ $s->subject_name }}
                                        </div>
                                        <div class="text-dark small fw-semibold">
                                            <i class="fa-solid fa-chalkboard-user me-1 text-secondary"></i>{{ $s->teacher->full_name ?? 'GV' }}
                                        </div>
                                        <div class="text-danger small fw-bold">
                                            <i class="fa-solid fa-door-open me-1"></i>Phòng: {{ $s->room }}
                                        </div>
                                        <div class="text-muted" style="font-size: 0.7rem;">
                                            <i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $s->branch->branch_name ?? '' }}
                                        </div>
                                        @if(auth()->user()->role === 'admin')
                                        <div class="text-end mt-1 pt-1 border-top border-primary border-opacity-10">
                                            <a href="{{ route('schedules.edit', $s) }}" class="text-decoration-none text-primary small me-2" title="Chỉnh sửa">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <!-- Free slot with 1-click booking -->
                                <div class="h-100 d-flex flex-column align-items-center justify-content-center p-2 rounded border border-dashed border-success border-opacity-50 text-center" style="min-height: 85px;">
                                    <span class="badge bg-success-subtle text-success mb-1" style="font-size: 0.7rem;">
                                        <i class="fa-regular fa-circle-check me-1"></i>Tiết Trống
                                    </span>
                                    @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('schedules.create', ['day_of_week' => $dayNum, 'shift_or_periods' => $shiftLabel, 'branch_id' => $branchId, 'teacher_id' => $teacherId]) }}" 
                                        class="btn btn-outline-success btn-sm py-1 px-2 fw-semibold" style="font-size: 0.72rem;">
                                        <i class="fa-solid fa-plus me-1"></i>Phân lịch
                                    </a>
                                    @else
                                    <span class="text-muted" style="font-size: 0.7rem;">Chưa có lịch</span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
