<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Services\ScheduleOptimizerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleOptimizerService $optimizerService
    ) {}

    /**
     * Display a listing of schedules with filters and analytics.
     */
    public function index(Request $request): View
    {
        $query = Schedule::with(['teacher.user', 'branch']);

        // Filters
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->filled('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject_name', 'like', "%{$search}%")
                    ->orWhere('room', 'like', "%{$search}%")
                    ->orWhereHas('teacher', function ($tq) use ($search) {
                        $tq->where('full_name', 'like', "%{$search}%");
                    });
            });
        }

        $schedules = $query->orderBy('day_of_week')
            ->orderBy('shift_or_periods')
            ->paginate(15)
            ->withQueryString();

        $branches = Branch::all();
        $teachers = Teacher::orderBy('full_name')->get();
        $analytics = $this->optimizerService->getAnalyticsData();
        $shifts = ScheduleOptimizerService::SHIFTS;

        return view('schedules.index', compact(
            'schedules',
            'branches',
            'teachers',
            'analytics',
            'shifts'
        ));
    }

    /**
     * Visual matrix of weekly timetable and free slots.
     */
    public function matrix(Request $request): View
    {
        $branchId = $request->filled('branch_id') ? (int) $request->branch_id : null;
        $teacherId = $request->filled('teacher_id') ? (int) $request->teacher_id : null;

        $branches = Branch::all();
        $teachers = Teacher::orderBy('full_name')->get();
        $matrixData = $this->optimizerService->getWeeklyMatrix($branchId, $teacherId);

        return view('schedules.matrix', compact(
            'branches',
            'teachers',
            'matrixData',
            'branchId',
            'teacherId'
        ));
    }

    /**
     * Show form to create a new schedule.
     */
    public function create(Request $request): View
    {
        $teachers = Teacher::orderBy('full_name')->get();
        $branches = Branch::all();
        $shifts = ScheduleOptimizerService::SHIFTS;

        // Pre-filled values if clicking from matrix
        $prefilled = [
            'day_of_week' => $request->get('day_of_week', 2),
            'shift_or_periods' => $request->get('shift_or_periods', array_key_first($shifts)),
            'branch_id' => $request->get('branch_id', $branches->first()?->id),
            'teacher_id' => $request->get('teacher_id', null),
        ];

        return view('schedules.create', compact('teachers', 'branches', 'shifts', 'prefilled'));
    }

    /**
     * Store newly created schedule.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'teacher_id' => ['required', 'exists:teachers,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'subject_name' => ['required', 'string', 'max:255'],
            'room' => ['required', 'string', 'max:50'],
            'day_of_week' => ['required', 'integer', 'min:2', 'max:7'],
            'shift_or_periods' => ['required', 'string'],
        ]);

        $conflictCheck = $this->optimizerService->checkConflict(
            (int) $validated['teacher_id'],
            (int) $validated['day_of_week'],
            $validated['shift_or_periods'],
            (int) $validated['branch_id']
        );

        if ($conflictCheck['has_conflict']) {
            return back()->withInput()->withErrors([
                'shift_or_periods' => $conflictCheck['message'],
            ]);
        }

        Schedule::create($validated);

        $successMsg = 'Đã xếp lịch dạy thành công!';
        if ($conflictCheck['travel_warning']) {
            $successMsg .= ' ' . $conflictCheck['travel_warning'];
        }

        return redirect()->route('schedules.index')->with('success', $successMsg);
    }

    /**
     * Show edit form.
     */
    public function edit(Schedule $schedule): View
    {
        $teachers = Teacher::orderBy('full_name')->get();
        $branches = Branch::all();
        $shifts = ScheduleOptimizerService::SHIFTS;

        return view('schedules.edit', compact('schedule', 'teachers', 'branches', 'shifts'));
    }

    /**
     * Update schedule.
     */
    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $validated = $request->validate([
            'teacher_id' => ['required', 'exists:teachers,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'subject_name' => ['required', 'string', 'max:255'],
            'room' => ['required', 'string', 'max:50'],
            'day_of_week' => ['required', 'integer', 'min:2', 'max:7'],
            'shift_or_periods' => ['required', 'string'],
        ]);

        $conflictCheck = $this->optimizerService->checkConflict(
            (int) $validated['teacher_id'],
            (int) $validated['day_of_week'],
            $validated['shift_or_periods'],
            (int) $validated['branch_id'],
            $schedule->id
        );

        if ($conflictCheck['has_conflict']) {
            return back()->withInput()->withErrors([
                'shift_or_periods' => $conflictCheck['message'],
            ]);
        }

        $schedule->update($validated);

        $successMsg = 'Cập nhật lịch dạy thành công!';
        if ($conflictCheck['travel_warning']) {
            $successMsg .= ' ' . $conflictCheck['travel_warning'];
        }

        return redirect()->route('schedules.index')->with('success', $successMsg);
    }

    /**
     * Delete schedule.
     */
    public function destroy(Schedule $schedule): RedirectResponse
    {
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Đã xóa lịch dạy khỏi hệ thống!');
    }

    /**
     * Teacher portal: My Schedule.
     */
    public function mySchedule(Request $request): View|RedirectResponse
    {
        $user = auth()->user();
        $teacher = $user->teacher;

        // If admin views this page, allow previewing any teacher's schedule
        if (!$teacher && $user->isAdmin()) {
            $previewTeacherId = $request->get('teacher_id', Teacher::first()?->id);
            if ($previewTeacherId) {
                $teacher = Teacher::find($previewTeacherId);
            }
        }

        if (!$teacher) {
            return redirect()->route('schedules.index')->with('error', 'Tài khoản của bạn chưa được liên kết với hồ sơ giảng viên.');
        }

        $schedules = Schedule::with('branch')
            ->where('teacher_id', $teacher->id)
            ->orderBy('day_of_week')
            ->orderBy('shift_or_periods')
            ->get();

        // Calculate teaching metrics
        $totalClasses = $schedules->count();
        $uniqueBranches = $schedules->pluck('branch')->unique('id');
        $teachingDays = $schedules->pluck('day_of_week')->unique()->count();

        // Group schedules by day of week
        $schedulesByDay = $schedules->groupBy('day_of_week');

        $allTeachers = $user->isAdmin() ? Teacher::orderBy('full_name')->get() : null;

        return view('schedules.my_schedule', compact(
            'teacher',
            'schedules',
            'schedulesByDay',
            'totalClasses',
            'uniqueBranches',
            'teachingDays',
            'allTeachers'
        ));
    }

    /**
     * AJAX API: Smart Suggest Teachers.
     */
    public function suggestTeachers(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject_name' => ['required', 'string'],
            'day_of_week' => ['required', 'integer', 'min:2', 'max:7'],
            'shift_or_periods' => ['required', 'string'],
            'branch_id' => ['required', 'integer'],
            'ignore_id' => ['nullable', 'integer'],
        ]);

        $suggestions = $this->optimizerService->suggestTeachers(
            $validated['subject_name'],
            (int) $validated['day_of_week'],
            $validated['shift_or_periods'],
            (int) $validated['branch_id'],
            !empty($validated['ignore_id']) ? (int) $validated['ignore_id'] : null
        );

        return response()->json([
            'status' => 'success',
            'data' => $suggestions,
        ]);
    }

    /**
     * AJAX API: Instant Conflict Check.
     */
    public function checkConflict(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'teacher_id' => ['required', 'integer'],
            'day_of_week' => ['required', 'integer', 'min:2', 'max:7'],
            'shift_or_periods' => ['required', 'string'],
            'branch_id' => ['required', 'integer'],
            'ignore_id' => ['nullable', 'integer'],
        ]);

        $result = $this->optimizerService->checkConflict(
            (int) $validated['teacher_id'],
            (int) $validated['day_of_week'],
            $validated['shift_or_periods'],
            (int) $validated['branch_id'],
            !empty($validated['ignore_id']) ? (int) $validated['ignore_id'] : null
        );

        return response()->json($result);
    }
}
