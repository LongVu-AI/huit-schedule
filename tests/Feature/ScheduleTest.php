<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('HUIT');
        $response->assertSee('Schedule Hub');
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/schedules');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_schedules_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/schedules');
        $response->assertStatus(200);
        $response->assertSee('Thời Khóa Biểu');
    }

    public function test_teacher_can_view_my_schedule(): void
    {
        $teacherUser = User::where('role', 'teacher')->first();

        $response = $this->actingAs($teacherUser)->get('/my-schedule');
        $response->assertStatus(200);
        $response->assertSee('Cổng Thông Tin Lịch Giảng Dạy Cá Nhân');
    }

    public function test_smart_suggestion_api_returns_recommendations(): void
    {
        $admin = User::where('role', 'admin')->first();
        $branch = Branch::first();

        $response = $this->actingAs($admin)->postJson('/api/schedules/suggest-teachers', [
            'subject_name' => 'Lập trình Web với Laravel',
            'day_of_week' => 2,
            'shift_or_periods' => 'Tiết 1-3 (Sáng: 07h00 - 09h30)',
            'branch_id' => $branch->id,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data',
        ]);
    }

    public function test_conflict_detection_prevents_duplicate_schedule(): void
    {
        $admin = User::where('role', 'admin')->first();
        $existingSchedule = Schedule::first();

        $response = $this->actingAs($admin)->post('/schedules', [
            'teacher_id' => $existingSchedule->teacher_id,
            'branch_id' => $existingSchedule->branch_id,
            'subject_name' => 'Môn Học Thử Nghiệm Trùng Lịch',
            'room' => 'Lab Test 999',
            'day_of_week' => $existingSchedule->day_of_week,
            'shift_or_periods' => $existingSchedule->shift_or_periods,
        ]);

        $response->assertSessionHasErrors('shift_or_periods');
    }
}
