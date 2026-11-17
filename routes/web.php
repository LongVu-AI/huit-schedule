<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

// --- ROUTES CHO KHÁCH (CHƯA ĐĂNG NHẬP) ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- ROUTES YÊU CẦU ĐĂNG NHẬP ---
Route::middleware('auth')->group(function () {
    
    // Tất cả mọi người (Admin + Teacher) đều xem được trang chủ lịch dạy chung
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');

    // CHỈ TEACHER MỚI VÀO ĐƯỢC ĐÂY (Xem lịch cá nhân)
    Route::middleware('role:teacher')->group(function () {
        Route::get('/my-schedule', [ScheduleController::class, 'mySchedule'])->name('schedules.my');
    });

    // CHỈ ADMIN MỚI VÀO ĐƯỢC ĐÂY (Thêm, sửa, xóa)
    Route::middleware('role:admin')->group(function () {
        Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
        
        // (Tương tự cho Teacher và Branch)
    });
 
});
