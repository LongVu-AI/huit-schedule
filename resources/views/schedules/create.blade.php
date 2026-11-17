@extends('layouts.app')

@section('title', 'Phân Lịch Giảng Dạy Thông Minh')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-wand-magic-sparkles text-warning me-2"></i>Phân Lịch Giảng Dạy Đa Cơ Sở
            </h3>
            <p class="text-muted small mb-0">Hệ thống tự động gợi ý giảng viên theo chuyên môn và kiểm tra tối ưu hóa thời gian di chuyển đa cơ sở</p>
        </div>
        <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-lg-7">
            <div class="card card-custom">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-pen-nib text-primary"></i>
                        <span>Thông Tin Học Phần & Khung Giờ Phân Lịch</span>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('schedules.store') }}" id="scheduleForm">
                        @csrf

                        <!-- Subject Name -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">
                                Tên Học Phần (Môn Học) <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="subject_name" name="subject_name" class="form-control @error('subject_name') is-invalid @enderror" 
                                placeholder="Ví dụ: Lập trình Web với Laravel, Trí tuệ nhân tạo..." 
                                value="{{ old('subject_name') }}" required>
                            <div class="form-text small">
                                <i class="fa-solid fa-lightbulb text-warning me-1"></i>Hệ thống sẽ dựa vào tên môn học để tự động phân tích và gợi ý giảng viên có chuyên môn sát nhất.
                            </div>
                            @error('subject_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <!-- Day of week -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-dark">
                                    Thứ Trong Tuần <span class="text-danger">*</span>
                                </label>
                                <select id="day_of_week" name="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror" required>
                                    @for($d = 2; $d <= 7; $d++)
                                        <option value="{{ $d }}" {{ old('day_of_week', $prefilled['day_of_week']) == $d ? 'selected' : '' }}>
                                            Thứ {{ $d }}
                                        </option>
                                    @endfor
                                </select>
                                @error('day_of_week')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Shift or periods -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-dark">
                                    Buổi / Tiết Học Cụ Thể <span class="text-danger">*</span>
                                </label>
                                <select id="shift_or_periods" name="shift_or_periods" class="form-select @error('shift_or_periods') is-invalid @enderror" required>
                                    @foreach($shifts as $shiftKey => $shiftMeta)
                                        <option value="{{ $shiftKey }}" {{ old('shift_or_periods', $prefilled['shift_or_periods']) == $shiftKey ? 'selected' : '' }}>
                                            {{ $shiftKey }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('shift_or_periods')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <!-- Branch -->
                            <div class="col-md-7">
                                <label class="form-label fw-bold small text-dark">
                                    Cơ Sở Giảng Dạy <span class="text-danger">*</span>
                                </label>
                                <select id="branch_id" name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" required>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id', $prefilled['branch_id']) == $branch->id ? 'selected' : '' }} data-name="{{ $branch->branch_name }}">
                                            {{ $branch->branch_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Room -->
                            <div class="col-md-5">
                                <label class="form-label fw-bold small text-dark">
                                    Phòng Học / Lab <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="room" name="room" class="form-control @error('room') is-invalid @enderror" 
                                    placeholder="Lab B3.02, A2.05..." 
                                    value="{{ old('room', 'Lab B3.01') }}" required>
                                @error('room')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Teacher Select -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="form-label fw-bold small text-dark mb-0">
                                    Giảng Viên Phụ Trách <span class="text-danger">*</span>
                                </label>
                                <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none fw-semibold" id="btnRefreshSuggestions">
                                    <i class="fa-solid fa-arrows-rotate me-1"></i>Lấy danh sách gợi ý tốt nhất
                                </button>
                            </div>
                            <select id="teacher_id" name="teacher_id" class="form-select form-select-lg @error('teacher_id') is-invalid @enderror" required>
                                <option value="">-- Chọn giảng viên hoặc bấm vào gợi ý bên phải --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $prefilled['teacher_id']) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->full_name }} — [{{ $teacher->expertise }}]
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Conflict & Transit Alert Panel -->
                        <div id="conflictAlertBox" class="d-none mb-4">
                            <!-- Populated dynamically via JS -->
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                            <button type="submit" class="btn btn-huit-primary px-4">
                                <i class="fa-solid fa-check me-1"></i> Lưu & Phân Lịch Ngay
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Smart Recommendation Sidebar Column -->
        <div class="col-lg-5">
            <div class="card card-custom border-primary border-opacity-25 shadow-sm">
                <div class="card-header bg-primary-subtle text-primary border-primary border-opacity-10 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-wand-magic-sparkles text-warning fs-5"></i>
                        <div>
                            <div class="fw-bold fs-6">Gợi Ý Giảng Viên Phù Hợp</div>
                            <div class="text-muted" style="font-size: 0.72rem;">Phân tích chuyên môn & tối ưu hóa di chuyển</div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="alert alert-light border small mb-3">
                        <i class="fa-solid fa-circle-info text-primary me-1"></i>
                        Hệ thống xếp hạng giảng viên dựa trên:
                        <ul class="mb-0 ps-3 mt-1" style="font-size: 0.8rem;">
                            <li><strong>Khung giờ trống</strong>: Không bị trùng lịch dạy.</li>
                            <li><strong>Chuyên môn</strong>: Trùng khớp từ khóa học phần.</li>
                            <li><strong>Tối ưu di chuyển</strong>: Ưu tiên giảng viên có lịch cùng cơ sở trong ngày.</li>
                        </ul>
                    </div>

                    <!-- Live suggestion list container -->
                    <div id="suggestionsLoading" class="text-center py-4 d-none">
                        <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                        <div class="small text-muted">Đang phân tích dữ liệu chuyên môn & lịch di chuyển...</div>
                    </div>

                    <div id="suggestionsList" class="d-flex flex-column gap-2" style="max-height: 480px; overflow-y: auto;">
                        <div class="text-center py-4 text-muted">
                            <i class="fa-solid fa-keyboard fs-2 text-black-50 mb-2 d-block"></i>
                            <div class="fw-semibold small">Nhập tên môn học hoặc chọn thứ/ca</div>
                            <span class="text-muted" style="font-size: 0.75rem;">Hệ thống sẽ tự động đưa ra các gợi ý tối ưu nhất</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const subjectInput = document.getElementById('subject_name');
        const daySelect = document.getElementById('day_of_week');
        const shiftSelect = document.getElementById('shift_or_periods');
        const branchSelect = document.getElementById('branch_id');
        const teacherSelect = document.getElementById('teacher_id');
        const suggestionsList = document.getElementById('suggestionsList');
        const suggestionsLoading = document.getElementById('suggestionsLoading');
        const conflictAlertBox = document.getElementById('conflictAlertBox');
        const btnRefresh = document.getElementById('btnRefreshSuggestions');

        let debounceTimer = null;

        function fetchSuggestions() {
            const subject = subjectInput.value.trim();
            const day = daySelect.value;
            const shift = shiftSelect.value;
            const branch = branchSelect.value;

            if (!day || !shift || !branch) return;

            suggestionsLoading.classList.remove('d-none');
            suggestionsList.classList.add('d-none');

            fetch('{{ route("api.schedules.suggest") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    subject_name: subject || 'Công nghệ thông tin',
                    day_of_week: day,
                    shift_or_periods: shift,
                    branch_id: branch
                })
            })
            .then(res => res.json())
            .then(data => {
                suggestionsLoading.classList.add('d-none');
                suggestionsList.classList.remove('d-none');

                if (data.status === 'success' && data.data && data.data.length > 0) {
                    renderSuggestions(data.data);
                } else {
                    suggestionsList.innerHTML = `
                        <div class="text-center py-4 text-muted">
                            <i class="fa-solid fa-circle-question fs-3 text-secondary mb-2 d-block"></i>
                            <div class="small fw-bold">Không tìm thấy giảng viên khả dụng</div>
                        </div>
                    `;
                }
            })
            .catch(err => {
                suggestionsLoading.classList.add('d-none');
                suggestionsList.classList.remove('d-none');
                console.error(err);
            });
        }

        function renderSuggestions(teachers) {
            let html = '';
            teachers.forEach((item, index) => {
                const t = item.teacher;
                const isBusy = item.is_busy;
                const cardBorder = isBusy ? 'border-danger border-opacity-25 bg-danger-subtle bg-opacity-25' : (index === 0 ? 'border-success shadow-sm bg-light' : 'border');

                html += `
                    <div class="p-3 rounded-3 ${cardBorder} position-relative">
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <img src="${t.avatar_url}" width="38" height="38" class="rounded-circle border" style="object-fit:cover;" alt="${t.full_name}">
                                <div>
                                    <span class="badge ${item.badge_class} mb-1" style="font-size: 0.72rem;">
                                        ${item.recommendation_tag}
                                    </span>
                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">${t.full_name}</h6>
                                </div>
                            </div>
                            ${!isBusy ? `
                                <button type="button" class="btn btn-sm btn-outline-primary fw-semibold px-2 py-1 select-teacher-btn" 
                                    data-id="${t.id}" style="font-size: 0.78rem;">
                                    ✅ Chọn GV
                                </button>
                            ` : `
                                <span class="badge bg-danger small">❌ Trùng lịch</span>
                            `}
                        </div>

                        <div class="text-muted small mb-2" style="font-size: 0.78rem;">
                            ${t.field_emoji || '🎓'} ${t.expertise}
                        </div>

                        ${item.conflict_info.travel_warning ? `
                            <div class="alert alert-warning py-1 px-2 mb-0 small" style="font-size: 0.73rem;">
                                🚗 ${item.conflict_info.travel_warning}
                            </div>
                        ` : ''}

                        ${item.conflict_info.optimization_note ? `
                            <div class="text-success small fw-semibold" style="font-size: 0.73rem;">
                                ✨ ${item.conflict_info.optimization_note}
                            </div>
                        ` : ''}
                    </div>
                `;
            });

            suggestionsList.innerHTML = html;

            // Bind click events on "Chọn GV"
            document.querySelectorAll('.select-teacher-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    teacherSelect.value = id;
                    checkSelectedTeacherConflict();
                    
                    // Visual feedback
                    teacherSelect.classList.add('border-success');
                    setTimeout(() => teacherSelect.classList.remove('border-success'), 1500);
                });
            });
        }

        function checkSelectedTeacherConflict() {
            const teacherId = teacherSelect.value;
            const day = daySelect.value;
            const shift = shiftSelect.value;
            const branch = branchSelect.value;

            if (!teacherId || !day || !shift || !branch) {
                conflictAlertBox.classList.add('d-none');
                return;
            }

            fetch('{{ route("api.schedules.conflict") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    teacher_id: teacherId,
                    day_of_week: day,
                    shift_or_periods: shift,
                    branch_id: branch
                })
            })
            .then(res => res.json())
            .then(res => {
                conflictAlertBox.classList.remove('d-none');
                if (res.has_conflict) {
                    conflictAlertBox.innerHTML = `
                        <div class="alert alert-danger d-flex align-items-center gap-2 mb-0 shadow-sm">
                            <i class="fa-solid fa-triangle-exclamation fs-5"></i>
                            <div>
                                <strong>Cảnh báo Trùng Lịch!</strong><br>
                                <span class="small">${res.message}</span>
                            </div>
                        </div>
                    `;
                } else if (res.travel_warning) {
                    conflictAlertBox.innerHTML = `
                        <div class="alert alert-warning d-flex align-items-center gap-2 mb-0 shadow-sm">
                            <i class="fa-solid fa-route fs-5"></i>
                            <div>
                                <strong>Lưu ý di chuyển đa cơ sở:</strong><br>
                                <span class="small">${res.travel_warning}</span>
                            </div>
                        </div>
                    `;
                } else {
                    conflictAlertBox.innerHTML = `
                        <div class="alert alert-success d-flex align-items-center gap-2 mb-0 shadow-sm">
                            <i class="fa-solid fa-circle-check fs-5"></i>
                            <div>
                                <strong>Khung giờ tối ưu!</strong><br>
                                <span class="small">${res.optimization_note || res.message}</span>
                            </div>
                        </div>
                    `;
                }
            });
        }

        // Event listeners
        subjectInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchSuggestions, 500);
        });

        daySelect.addEventListener('change', () => { fetchSuggestions(); checkSelectedTeacherConflict(); });
        shiftSelect.addEventListener('change', () => { fetchSuggestions(); checkSelectedTeacherConflict(); });
        branchSelect.addEventListener('change', () => { fetchSuggestions(); checkSelectedTeacherConflict(); });
        teacherSelect.addEventListener('change', checkSelectedTeacherConflict);
        btnRefresh.addEventListener('click', fetchSuggestions);

        // Initial fetch
        fetchSuggestions();
        if (teacherSelect.value) {
            checkSelectedTeacherConflict();
        }
    });
</script>
@endpush
