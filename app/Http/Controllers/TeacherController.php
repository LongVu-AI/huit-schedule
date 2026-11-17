<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers.
     */
    public function index(): View
    {
        $teachers = Teacher::withCount('schedules')
            ->with('user')
            ->orderBy('full_name')
            ->get();

        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show form for creating a new teacher.
     */
    public function create(): View
    {
        return view('teachers.create');
    }

    /**
     * Store newly created teacher and create login account.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:teachers,email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'expertise' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        // Auto-create login user account for teacher
        $user = User::create([
            'name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($request->filled('password') ? $validated['password'] : 'password'),
            'role' => 'teacher',
        ]);

        Teacher::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'expertise' => $validated['expertise'],
            'user_id' => $user->id,
        ]);

        return redirect()->route('teachers.index')->with(
            'success',
            'Đã thêm giảng viên và cấp tài khoản đăng nhập thành công (Mật khẩu mặc định: ' . ($request->filled('password') ? 'như bạn đã nhập' : 'password') . ')!'
        );
    }

    /**
     * Show the form for editing the teacher.
     */
    public function edit(Teacher $teacher): View
    {
        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update teacher and sync user account.
     */
    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:teachers,email,' . $teacher->id],
            'phone' => ['required', 'string', 'max:20'],
            'expertise' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $teacher->update([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'expertise' => $validated['expertise'],
        ]);

        // Sync or create associated user
        if ($teacher->user) {
            $updateData = [
                'name' => $validated['full_name'],
                'email' => $validated['email'],
            ];
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }
            $teacher->user->update($updateData);
        } else {
            $user = User::create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'password' => Hash::make($request->filled('password') ? $validated['password'] : 'password'),
                'role' => 'teacher',
            ]);
            $teacher->update(['user_id' => $user->id]);
        }

        return redirect()->route('teachers.index')->with('success', 'Cập nhật thông tin giảng viên thành công!');
    }

    /**
     * Delete teacher.
     */
    public function destroy(Teacher $teacher): RedirectResponse
    {
        $user = $teacher->user;
        $teacher->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('teachers.index')->with('success', 'Đã xóa giảng viên và tài khoản liên quan!');
    }
}
