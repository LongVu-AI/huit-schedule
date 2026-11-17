<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'branch_id',
        'subject_name',
        'room',
        'day_of_week',
        'shift_or_periods',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function getDayNameAttribute(): string
    {
        return match ((int) $this->day_of_week) {
            2 => 'Thứ 2',
            3 => 'Thứ 3',
            4 => 'Thứ 4',
            5 => 'Thứ 5',
            6 => 'Thứ 6',
            7 => 'Thứ 7',
            8, 1 => 'Chủ Nhật',
            default => 'Thứ ' . $this->day_of_week,
        };
    }

    /**
     * Get expressive subject emoji.
     */
    public function getSubjectEmojiAttribute(): string
    {
        $sub = mb_strtolower($this->subject_name, 'UTF-8');
        if (str_contains($sub, 'web') || str_contains($sub, 'php') || str_contains($sub, 'laravel') || str_contains($sub, 'frontend')) return '💻';
        if (str_contains($sub, 'trí tuệ nhân tạo') || str_contains($sub, 'ai') || str_contains($sub, 'machine learning') || str_contains($sub, 'thị giác')) return '🤖';
        if (str_contains($sub, 'kiểm thử') || str_contains($sub, 'qa') || str_contains($sub, 'agile')) return '🧪';
        if (str_contains($sub, 'mạng') || str_contains($sub, 'an toàn') || str_contains($sub, 'an ninh') || str_contains($sub, 'cisco')) return '🛡️';
        if (str_contains($sub, 'cơ sở dữ liệu') || str_contains($sub, 'mysql') || str_contains($sub, 'sql') || str_contains($sub, 'data')) return '🗄️';
        if (str_contains($sub, 'di động') || str_contains($sub, 'android') || str_contains($sub, 'flutter')) return '📱';
        if (str_contains($sub, 'iot') || str_contains($sub, 'nhúng') || str_contains($sub, 'vi điều khiển')) return '⚡';
        if (str_contains($sub, 'cấu trúc dữ liệu') || str_contains($sub, 'giải thuật') || str_contains($sub, 'java')) return '🌲';
        return '📚';
    }
}
