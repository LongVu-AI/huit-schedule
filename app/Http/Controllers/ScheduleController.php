<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\Branch;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $query = Schedule::with(['teacher', 'branch']);

       //lọc
        if($request->has('teacher_id') && $request->teacher_id != null){
            $query->where('teacher_id', $request->teacher_id);
        }
        if($request->has('branch_id') && $request->branch_id != null){
            $query->where('branch_id', $request->branch_id);
        }

       $schedules = $query->orderBy('day_of_week')->get();
       $branches = Branch::all();
       $teachers = Teacher::all();
       return view('schedules.index', compact('schedules', 'branches', 'teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = Teacher::all();
        $branches = Branch::all();
        return view('schedules.create', compact('teachers', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'branch_id' => 'required|exists:branches,id',
            'subject_name' => 'required|string|max:255',
            'room' => 'required|string|max:50',
            'day_of_week' => 'required|integer|min:2|max:7',
            'shift_or_periods' => 'required|string',
        ]);
        $isBusy = Schedule::where('teacher_id', $request->teacher_id)
            ->where('day_of_week', $request->day_of_week)
            ->where('shift_or_periods', $request->shift_or_periods)
            ->exists();
        if ($isBusy) {
            return redirect()->back()->withErrors(['error' => 'Giáo viên đã bận vào thời gian này.']);
        }

        Schedule::create($request->all());
        return redirect()->route('schedules.index')->with('success', 'Lịch dạy đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $teachers = Teacher::all();
        $branches = Branch::all();
        return view('schedules.edit', compact('schedule', 'teachers', 'branches'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teachers = Teacher::all();
        $branches = Branch::all();
        return view('schedules.edit', compact('schedule', 'teachers', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
         $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'branch_id' => 'required|exists:branches,id',
            'subject_name' => 'required|string|max:255',
            'room' => 'required|string|max:50',
            'day_of_week' => 'required|integer|min:2|max:7',
            'shift_or_periods' => 'required|string',
        ]);

        // Kiểm tra trùng lịch (loại trừ ID hiện tại đang sửa)
        $isBusy = Schedule::where('teacher_id', $request->teacher_id)
            ->where('day_of_week', $request->day_of_week)
            ->where('shift_or_periods', $request->shift_or_periods)
            ->where('id', '!=', $schedule->id)
            ->exists();

        if ($isBusy) {
            return back()->withInput()->with('error', 'Lỗi: Giảng viên đã bị trùng lịch!');
        }

        $schedule->update($request->all());

        return redirect()->route('schedules.index')->with('success', 'Cập nhật lịch dạy thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Đã xóa lịch dạy!');
    }

    public function mySchedule()
    {
        // Lấy ID của giảng viên đang đăng nhập
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return redirect()->route('schedules.index')->with('error', 'Tài khoản của bạn chưa được liên kết với giảng viên!');
        }

        $schedules = Schedule::with('branch')
            ->where('teacher_id', $teacher->id)
            ->orderBy('day_of_week')
            ->get();

        return view('schedules.my_schedule', compact('schedules'));
    }
}
