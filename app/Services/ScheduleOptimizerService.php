<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Schedule;
use App\Models\Teacher;
use Illuminate\Support\Collection;

class ScheduleOptimizerService
{
    /**
     * Standard shifts / periods in HUIT
     */
    public const SHIFTS = [
        'Tiết 1-3 (Sáng: 07h00 - 09h30)' => ['shift' => 'morning', 'order' => 1, 'short' => 'Tiết 1-3'],
        'Tiết 4-6 (Sáng: 09h45 - 12h15)' => ['shift' => 'morning', 'order' => 2, 'short' => 'Tiết 4-6'],
        'Tiết 7-9 (Chiều: 12h45 - 15h15)' => ['shift' => 'afternoon', 'order' => 3, 'short' => 'Tiết 7-9'],
        'Tiết 10-12 (Chiều: 15h30 - 18h00)' => ['shift' => 'afternoon', 'order' => 4, 'short' => 'Tiết 10-12'],
        'Tiết 13-15 (Tối: 18h15 - 20h45)' => ['shift' => 'evening', 'order' => 5, 'short' => 'Tiết 13-15'],
    ];

    /**
     * Check if a teacher has a conflict or transit issues.
     */
    public function checkConflict(
        int $teacherId,
        int $dayOfWeek,
        string $shiftOrPeriods,
        int $branchId,
        ?int $ignoreScheduleId = null
    ): array {
        // 1. Direct collision
        $conflictQuery = Schedule::with(['branch'])
            ->where('teacher_id', $teacherId)
            ->where('day_of_week', $dayOfWeek)
            ->where('shift_or_periods', $shiftOrPeriods);

        if ($ignoreScheduleId) {
            $conflictQuery->where('id', '!=', $ignoreScheduleId);
        }

        $directConflict = $conflictQuery->first();

        if ($directConflict) {
            return [
                'has_conflict' => true,
                'type' => 'error',
                'message' => "Trùng lịch: Giảng viên đã có lịch dạy môn \"{$directConflict->subject_name}\" tại {$directConflict->branch->branch_name} (Phòng {$directConflict->room}) vào khung giờ này!",
                'conflict_schedule' => $directConflict,
                'travel_warning' => null,
                'transit_score' => -100,
            ];
        }

        // 2. Multi-branch transit optimization check on the same day
        $otherQuery = Schedule::with('branch')
            ->where('teacher_id', $teacherId)
            ->where('day_of_week', $dayOfWeek);

        if ($ignoreScheduleId) {
            $otherQuery->where('id', '!=', $ignoreScheduleId);
        }

        $sameDaySchedules = $otherQuery->get();

        $travelWarning = null;
        $transitScore = 20; // Default free base score
        $optimizationNote = 'Lịch dạy hoàn toàn trống trong ngày này.';

        if ($sameDaySchedules->isNotEmpty()) {
            $diffBranches = $sameDaySchedules->where('branch_id', '!=', $branchId);
            $sameBranchSchedules = $sameDaySchedules->where('branch_id', $branchId);

            if ($diffBranches->isNotEmpty()) {
                $otherBranchNames = $diffBranches->pluck('branch.branch_name')->unique()->join(', ');
                $travelWarning = "Lưu ý di chuyển: Cùng ngày giảng viên có lịch dạy tại cơ sở khác ({$otherBranchNames}). Hãy đảm bảo thời gian di chuyển giữa các cơ sở.";
                $transitScore = 5;
            }

            if ($sameBranchSchedules->isNotEmpty()) {
                $optimizationNote = "Tối ưu hóa tốt: Giảng viên đã có lịch dạy cùng ngày tại cơ sở này, không mất thời gian di chuyển sang cơ sở khác.";
                $transitScore = 40;
            }
        }

        return [
            'has_conflict' => false,
            'type' => 'success',
            'message' => 'Khung giờ hợp lệ, không bị trùng lịch.',
            'conflict_schedule' => null,
            'travel_warning' => $travelWarning,
            'optimization_note' => $optimizationNote,
            'transit_score' => $transitScore,
        ];
    }

    /**
     * Suggest the best-matching teachers for a given schedule requirement.
     */
    public function suggestTeachers(
        string $subjectName,
        int $dayOfWeek,
        string $shiftOrPeriods,
        int $branchId,
        ?int $ignoreScheduleId = null
    ): Collection {
        $teachers = Teacher::with(['schedules' => function ($q) use ($dayOfWeek) {
            $q->where('day_of_week', $dayOfWeek)->with('branch');
        }])->get();

        $subjectKeywords = $this->extractKeywords($subjectName);

        $results = $teachers->map(function (Teacher $teacher) use ($subjectName, $subjectKeywords, $dayOfWeek, $shiftOrPeriods, $branchId, $ignoreScheduleId) {
            $conflictCheck = $this->checkConflict(
                $teacher->id,
                $dayOfWeek,
                $shiftOrPeriods,
                $branchId,
                $ignoreScheduleId
            );

            $isBusy = $conflictCheck['has_conflict'];

            // Calculate expertise score
            $expertiseKeywords = $this->extractKeywords($teacher->expertise);
            $commonKeywords = array_intersect($subjectKeywords, $expertiseKeywords);

            $expertiseScore = 0;
            $matchLevel = 'none';

            // Check direct contains or keyword match
            $lowerSubject = mb_strtolower($subjectName, 'UTF-8');
            $lowerExpertise = mb_strtolower($teacher->expertise, 'UTF-8');

            if (str_contains($lowerExpertise, $lowerSubject) || str_contains($lowerSubject, $lowerExpertise)) {
                $expertiseScore = 50;
                $matchLevel = 'high';
            } elseif (count($commonKeywords) > 0) {
                $expertiseScore = 30 + (count($commonKeywords) * 5);
                $matchLevel = 'high';
            } else {
                // Topic category match
                if ($this->hasCategoryOverlap($lowerSubject, $lowerExpertise)) {
                    $expertiseScore = 25;
                    $matchLevel = 'medium';
                }
            }

            // Total Score
            $totalScore = $conflictCheck['transit_score'] + $expertiseScore;

            if ($isBusy) {
                $recommendationTag = 'Bận lịch';
                $badgeClass = 'bg-danger text-white';
            } elseif ($expertiseScore >= 30 && $conflictCheck['transit_score'] >= 30) {
                $recommendationTag = 'Rất phù hợp (Chuyên môn & Cùng cơ sở)';
                $badgeClass = 'bg-success text-white';
            } elseif ($expertiseScore >= 20) {
                $recommendationTag = 'Phù hợp chuyên môn';
                $badgeClass = 'bg-primary text-white';
            } elseif (!$isBusy && $conflictCheck['transit_score'] >= 30) {
                $recommendationTag = 'Tối ưu di chuyển (Cùng cơ sở)';
                $badgeClass = 'bg-info text-dark';
            } else {
                $recommendationTag = 'Trống lịch';
                $badgeClass = 'bg-secondary text-white';
            }

            return [
                'teacher' => $teacher,
                'is_busy' => $isBusy,
                'score' => $isBusy ? -1 : $totalScore,
                'match_level' => $matchLevel,
                'expertise_score' => $expertiseScore,
                'conflict_info' => $conflictCheck,
                'recommendation_tag' => $recommendationTag,
                'badge_class' => $badgeClass,
                'day_schedules' => $teacher->schedules,
            ];
        });

        // Sort: Free first, highest score descending
        return $results->sort(function ($a, $b) {
            if ($a['is_busy'] !== $b['is_busy']) {
                return $a['is_busy'] ? 1 : -1;
            }
            return $b['score'] <=> $a['score'];
        })->values();
    }

    /**
     * Generate weekly timetable matrix for visual overview of free/busy slots.
     */
    public function getWeeklyMatrix(?int $branchId = null, ?int $teacherId = null): array
    {
        $query = Schedule::with(['teacher', 'branch']);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }

        $schedules = $query->get();

        $days = [
            2 => 'Thứ 2',
            3 => 'Thứ 3',
            4 => 'Thứ 4',
            5 => 'Thứ 5',
            6 => 'Thứ 6',
            7 => 'Thứ 7',
        ];

        $matrix = [];

        foreach (self::SHIFTS as $shiftLabel => $shiftMeta) {
            $matrix[$shiftLabel] = [
                'meta' => $shiftMeta,
                'days' => [],
            ];

            foreach ($days as $dayNumber => $dayName) {
                $matchedSchedules = $schedules->filter(function ($s) use ($dayNumber, $shiftLabel, $shiftMeta) {
                    if ((int) $s->day_of_week !== $dayNumber) {
                        return false;
                    }
                    // Check either exact match or contains short shift
                    return $s->shift_or_periods === $shiftLabel 
                        || str_contains($s->shift_or_periods, $shiftMeta['short']);
                });

                $matrix[$shiftLabel]['days'][$dayNumber] = [
                    'day_name' => $dayName,
                    'day_number' => $dayNumber,
                    'schedules' => $matchedSchedules->values(),
                    'is_free' => $matchedSchedules->isEmpty(),
                ];
            }
        }

        return [
            'days' => $days,
            'shifts' => self::SHIFTS,
            'matrix' => $matrix,
        ];
    }

    /**
     * Get chart analytics data for the dashboard.
     */
    public function getAnalyticsData(): array
    {
        $branches = Branch::withCount('schedules')->get();
        $branchLabels = $branches->pluck('branch_name')->toArray();
        $branchCounts = $branches->pluck('schedules_count')->toArray();

        $days = [2 => 'Thứ 2', 3 => 'Thứ 3', 4 => 'Thứ 4', 5 => 'Thứ 5', 6 => 'Thứ 6', 7 => 'Thứ 7'];
        $dayCounts = [];
        foreach ($days as $d => $name) {
            $dayCounts[] = Schedule::where('day_of_week', $d)->count();
        }

        // Top teachers by assigned schedules
        $teachers = Teacher::withCount('schedules')
            ->orderByDesc('schedules_count')
            ->limit(6)
            ->get();
        $teacherLabels = $teachers->pluck('full_name')->toArray();
        $teacherCounts = $teachers->pluck('schedules_count')->toArray();

        return [
            'total_schedules' => Schedule::count(),
            'total_teachers' => Teacher::count(),
            'total_branches' => Branch::count(),
            'branch_labels' => $branchLabels,
            'branch_counts' => $branchCounts,
            'day_labels' => array_values($days),
            'day_counts' => $dayCounts,
            'teacher_labels' => $teacherLabels,
            'teacher_counts' => $teacherCounts,
        ];
    }

    private function extractKeywords(string $text): array
    {
        $clean = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', mb_strtolower($text, 'UTF-8'));
        $words = preg_split('/\s+/u', $clean, -1, PREG_SPLIT_NO_EMPTY);
        $stopWords = ['và', 'của', 'trong', 'các', 'cho', 'với', 'về', 'ngành', 'bộ', 'môn', 'học', 'phần', 'lập', 'trình'];
        return array_values(array_diff($words, $stopWords));
    }

    private function hasCategoryOverlap(string $subject, string $expertise): bool
    {
        $categories = [
            'web' => ['web', 'php', 'laravel', 'frontend', 'backend', 'javascript', 'html', 'css', 'react', 'vue', 'nodejs'],
            'software' => ['phần mềm', 'công nghệ phần mềm', 'kiểm thử', 'qa', 'qc', 'agile', 'scrum', 'oop', 'java', 'c#', '.net', 'kiến trúc'],
            'ai_data' => ['trí tuệ nhân tạo', 'ai', 'data', 'dữ liệu', 'khoa học dữ liệu', 'machine learning', 'python', 'deep learning'],
            'database' => ['cơ sở dữ liệu', 'database', 'sql', 'mysql', 'oracle', 'nosql', 'mongodb', 'hệ quản trị'],
            'network_security' => ['mạng', 'an toàn thông tin', 'bảo mật', 'an ninh mạng', 'network', 'cisco', 'linux', 'cloud', 'aws'],
            'mobile' => ['di động', 'mobile', 'android', 'ios', 'flutter', 'react native'],
            'hardware_iot' => ['iot', 'vi điều khiển', 'điện tử', 'nhúng', 'arduino'],
        ];

        foreach ($categories as $catWords) {
            $inSubject = false;
            $inExpertise = false;

            foreach ($catWords as $w) {
                if (str_contains($subject, $w)) {
                    $inSubject = true;
                }
                if (str_contains($expertise, $w)) {
                    $inExpertise = true;
                }
            }

            if ($inSubject && $inExpertise) {
                return true;
            }
        }

        return false;
    }
}
