<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

// --- GUEST ROUTES ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// --- AUTHENTICATED ROUTES ---
Route::middleware('auth')->group(function () {
    // Root redirect
    Route::get('/', function () {
        return auth()->user()->role === 'teacher'
            ? redirect()->route('schedules.my')
            : redirect()->route('schedules.index');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // General Schedule Views (All authenticated users can view)
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/matrix', [ScheduleController::class, 'matrix'])->name('schedules.matrix');

    // Teacher portal (Teachers and Admin preview)
    Route::get('/my-schedule', [ScheduleController::class, 'mySchedule'])->name('schedules.my');

    // Smart Optimization AJAX APIs
    Route::post('/api/schedules/suggest-teachers', [ScheduleController::class, 'suggestTeachers'])->name('api.schedules.suggest');
    Route::post('/api/schedules/check-conflict', [ScheduleController::class, 'checkConflict'])->name('api.schedules.conflict');

    // --- ADMIN MANAGEMENT ROUTES ---
    Route::middleware('role:admin')->group(function () {
        // Schedule CRUD
        Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

        // Branch CRUD
        Route::resource('branches', BranchController::class)->except(['show']);

        // Teacher CRUD
        Route::resource('teachers', TeacherController::class)->except(['show']);
    });
});
