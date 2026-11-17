<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\User;
use App\Services\ScheduleOptimizerService;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // 1. Seed Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@huit.edu.vn'],
            [
                'name' => 'Quản trị viên HUIT',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // 2. Seed Branches (Các cơ sở thực tế của HUIT)
        $branchData = [
            [
                'branch_name' => 'Cơ sở chính (Tân Phú)',
                'address' => '140 Lê Trọng Tấn, P. Tây Thạnh, Q. Tân Phú, TP.HCM',
            ],
            [
                'branch_name' => 'Cơ sở 2 (Tân Kỳ Tân Quý)',
                'address' => '93 Tân Kỳ Tân Quý, P. Tân Sơn Nhì, Q. Tân Phú, TP.HCM',
            ],
            [
                'branch_name' => 'Cơ sở 3 - Xưởng thực hành (Nguyễn Đỗ Cung)',
                'address' => '73/1 Nguyễn Đỗ Cung, P. Tây Thạnh, Q. Tân Phú, TP.HCM',
            ],
            [
                'branch_name' => 'Cơ sở liên kết Quận 1 (Đặng Dung)',
                'address' => '31 Đặng Dung, P. Tân Định, Quận 1, TP.HCM',
            ],
        ];

        $branches = collect();
        foreach ($branchData as $data) {
            $branches->push(Branch::updateOrCreate(
                ['branch_name' => $data['branch_name']],
                ['address' => $data['address']]
            ));
        }

        // 3. Seed Teachers with linked Users
        $teachersData = [
            [
                'name' => 'TS. Nguyễn Văn An',
                'email' => 'nguyenvanan@huit.edu.vn',
                'phone' => '0903123456',
                'expertise' => 'Lập trình Web & Framework PHP (Laravel, VueJS, ReactJS)',
            ],
            [
                'name' => 'ThS. Trần Thị Bích',
                'email' => 'tranthibich@huit.edu.vn',
                'phone' => '0914234567',
                'expertise' => 'Công nghệ phần mềm & Kiểm thử tự động (QA/QC, Agile/Scrum)',
            ],
            [
                'name' => 'PGS.TS. Lê Hoàng Cường',
                'email' => 'lehoangcuong@huit.edu.vn',
                'phone' => '0985345678',
                'expertise' => 'Trí tuệ nhân tạo & Khoa học dữ liệu (Machine Learning, Python, AI)',
            ],
            [
                'name' => 'ThS. Phạm Minh Dũng',
                'email' => 'phamminhdung@huit.edu.vn',
                'phone' => '0976456789',
                'expertise' => 'Mạng máy tính & An toàn thông tin (Cyber Security, Cisco, Linux)',
            ],
            [
                'name' => 'ThS. Đỗ Thu Hằng',
                'email' => 'dothuhang@huit.edu.vn',
                'phone' => '0967567890',
                'expertise' => 'Hệ quản trị cơ sở dữ liệu lớn (MySQL, PostgreSQL, MongoDB, Big Data)',
            ],
            [
                'name' => 'TS. Vũ Hải Long',
                'email' => 'vuhailong@huit.edu.vn',
                'phone' => '0938678901',
                'expertise' => 'Lập trình ứng dụng di động (Flutter, Android Kotlin, iOS Swift)',
            ],
            [
                'name' => 'ThS. Hoàng Ngọc Mai',
                'email' => 'hoangngocmai@huit.edu.vn',
                'phone' => '0949789012',
                'expertise' => 'Kiến trúc máy tính & Vi điều khiển IoT (Arduino, Raspberry Pi)',
            ],
            [
                'name' => 'ThS. Ngô Quốc Tuấn',
                'email' => 'ngoguoctuan@huit.edu.vn',
                'phone' => '0922890123',
                'expertise' => 'Cấu trúc dữ liệu & Giải thuật nâng cao (Java, C/C++, Thuật toán)',
            ],
        ];

        $teachers = collect();
        foreach ($teachersData as $tData) {
            $user = User::updateOrCreate(
                ['email' => $tData['email']],
                [
                    'name' => $tData['name'],
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ]
            );

            $teachers->push(Teacher::updateOrCreate(
                ['email' => $tData['email']],
                [
                    'full_name' => $tData['name'],
                    'phone' => $tData['phone'],
                    'expertise' => $tData['expertise'],
                    'user_id' => $user->id,
                ]
            ));
        }

        // 4. Seed Realistic Schedules
        // Tránh trùng lịch bằng cách theo dõi (teacher_id + day_of_week + shift)
        Schedule::truncate();

        $shifts = array_keys(ScheduleOptimizerService::SHIFTS);
        $sampleSchedules = [
            // Thầy Nguyễn Văn An (Web / PHP)
            [
                'teacher_email' => 'nguyenvanan@huit.edu.vn',
                'branch_index' => 0, // Cơ sở chính Tân Phú
                'subject_name' => 'Lập trình Web với Framework mã nguồn mở (PHP & Laravel)',
                'room' => 'Lab B3.02',
                'day_of_week' => 2, // Thứ 2
                'shift' => $shifts[0], // Tiết 1-3
            ],
            [
                'teacher_email' => 'nguyenvanan@huit.edu.vn',
                'branch_index' => 0, // Cùng cơ sở Tân Phú trong ngày (Tối ưu di chuyển)
                'subject_name' => 'Thực hành Chuyên đề Web nâng cao (VueJS & API)',
                'room' => 'Lab B3.04',
                'day_of_week' => 2, // Thứ 2
                'shift' => $shifts[1], // Tiết 4-6
            ],
            [
                'teacher_email' => 'nguyenvanan@huit.edu.vn',
                'branch_index' => 3, // Quận 1
                'subject_name' => 'Thiết kế giao diện Người dùng Frontend',
                'room' => 'Phòng ĐD-201',
                'day_of_week' => 4, // Thứ 4
                'shift' => $shifts[2], // Tiết 7-9
            ],

            // Cô Trần Thị Bích (Software QA)
            [
                'teacher_email' => 'tranthibich@huit.edu.vn',
                'branch_index' => 0,
                'subject_name' => 'Kiểm thử phần mềm & Đảm bảo chất lượng (QA/QC)',
                'room' => 'Phòng A2.05',
                'day_of_week' => 2,
                'shift' => $shifts[2], // Tiết 7-9
            ],
            [
                'teacher_email' => 'tranthibich@huit.edu.vn',
                'branch_index' => 1, // Cơ sở 2 Tân Kỳ Tân Quý
                'subject_name' => 'Quản lý dự án phần mềm theo Agile/Scrum',
                'room' => 'Phòng TK-102',
                'day_of_week' => 5, // Thứ 5
                'shift' => $shifts[0], // Tiết 1-3
            ],

            // Thầy Lê Hoàng Cường (AI / Data Science)
            [
                'teacher_email' => 'lehoangcuong@huit.edu.vn',
                'branch_index' => 0,
                'subject_name' => 'Trí tuệ nhân tạo & Ứng dụng Machine Learning',
                'room' => 'Lab A3.01',
                'day_of_week' => 3, // Thứ 3
                'shift' => $shifts[0], // Tiết 1-3
            ],
            [
                'teacher_email' => 'lehoangcuong@huit.edu.vn',
                'branch_index' => 0,
                'subject_name' => 'Khoa học dữ liệu với ngôn ngữ Python',
                'room' => 'Lab A3.01',
                'day_of_week' => 3, // Thứ 3
                'shift' => $shifts[1], // Tiết 4-6
            ],
            [
                'teacher_email' => 'lehoangcuong@huit.edu.vn',
                'branch_index' => 3, // Đặng Dung
                'subject_name' => 'Thị giác máy tính và Xử lý ảnh nâng cao',
                'room' => 'Phòng ĐD-302',
                'day_of_week' => 6, // Thứ 6
                'shift' => $shifts[2], // Tiết 7-9
            ],

            // Thầy Phạm Minh Dũng (Network / Cyber Security)
            [
                'teacher_email' => 'phamminhdung@huit.edu.vn',
                'branch_index' => 2, // Xưởng Nguyễn Đỗ Cung
                'subject_name' => 'Thực hành Quản trị Mạng máy tính Cisco',
                'room' => 'Xưởng Mạng X-01',
                'day_of_week' => 3,
                'shift' => $shifts[2], // Tiết 7-9
            ],
            [
                'teacher_email' => 'phamminhdung@huit.edu.vn',
                'branch_index' => 2,
                'subject_name' => 'An toàn thông tin & Phòng thủ mã độc',
                'room' => 'Xưởng Mạng X-01',
                'day_of_week' => 3,
                'shift' => $shifts[3], // Tiết 10-12
            ],
            [
                'teacher_email' => 'phamminhdung@huit.edu.vn',
                'branch_index' => 0,
                'subject_name' => 'Hệ điều hành mạng Linux & Điện toán đám mây',
                'room' => 'Phòng B2.03',
                'day_of_week' => 5,
                'shift' => $shifts[1], // Tiết 4-6
            ],

            // Cô Đỗ Thu Hằng (Database)
            [
                'teacher_email' => 'dothuhang@huit.edu.vn',
                'branch_index' => 0,
                'subject_name' => 'Hệ quản trị cơ sở dữ liệu MySQL nâng cao',
                'room' => 'Lab B3.01',
                'day_of_week' => 4,
                'shift' => $shifts[0], // Tiết 1-3
            ],
            [
                'teacher_email' => 'dothuhang@huit.edu.vn',
                'branch_index' => 1,
                'subject_name' => 'Cơ sở dữ liệu NoSQL & Hệ thống dữ liệu lớn',
                'room' => 'Phòng TK-201',
                'day_of_week' => 6,
                'shift' => $shifts[0], // Tiết 1-3
            ],

            // Thầy Vũ Hải Long (Mobile App)
            [
                'teacher_email' => 'vuhailong@huit.edu.vn',
                'branch_index' => 0,
                'subject_name' => 'Lập trình ứng dụng di động đa nền tảng với Flutter',
                'room' => 'Lab B3.05',
                'day_of_week' => 4,
                'shift' => $shifts[1], // Tiết 4-6
            ],
            [
                'teacher_email' => 'vuhailong@huit.edu.vn',
                'branch_index' => 0,
                'subject_name' => 'Phát triển ứng dụng Android native với Kotlin',
                'room' => 'Lab B3.05',
                'day_of_week' => 7, // Thứ 7
                'shift' => $shifts[0], // Tiết 1-3
            ],

            // Cô Hoàng Ngọc Mai (IoT / Embedded)
            [
                'teacher_email' => 'hoangngocmai@huit.edu.vn',
                'branch_index' => 2, // Nguyễn Đỗ Cung
                'subject_name' => 'Thực hành Thiết kế mạch nhúng & Vi điều khiển',
                'room' => 'Xưởng IoT X-02',
                'day_of_week' => 5,
                'shift' => $shifts[2], // Tiết 7-9
            ],
            [
                'teacher_email' => 'hoangngocmai@huit.edu.vn',
                'branch_index' => 2,
                'subject_name' => 'Ứng dụng Internet vạn vật (IoT) trong Nhà máy thông minh',
                'room' => 'Xưởng IoT X-02',
                'day_of_week' => 5,
                'shift' => $shifts[3], // Tiết 10-12
            ],

            // Thầy Ngô Quốc Tuấn (DSA)
            [
                'teacher_email' => 'ngoguoctuan@huit.edu.vn',
                'branch_index' => 0,
                'subject_name' => 'Cấu trúc dữ liệu và Giải thuật (Java)',
                'room' => 'Phòng C1.02',
                'day_of_week' => 6,
                'shift' => $shifts[1], // Tiết 4-6
            ],
            [
                'teacher_email' => 'ngoguoctuan@huit.edu.vn',
                'branch_index' => 0,
                'subject_name' => 'Lập trình hướng đối tượng với Java nâng cao',
                'room' => 'Lab B3.03',
                'day_of_week' => 7,
                'shift' => $shifts[1], // Tiết 4-6
            ],
        ];

        foreach ($sampleSchedules as $item) {
            $teacher = $teachers->firstWhere('email', $item['teacher_email']);
            $branch = $branches[$item['branch_index']] ?? $branches->first();

            if ($teacher && $branch) {
                Schedule::create([
                    'teacher_id' => $teacher->id,
                    'branch_id' => $branch->id,
                    'subject_name' => $item['subject_name'],
                    'room' => $item['room'],
                    'day_of_week' => $item['day_of_week'],
                    'shift_or_periods' => $item['shift'],
                ]);
            }
        }
    }
}
