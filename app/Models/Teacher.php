<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'expertise',
        'user_id',
    ];

    protected $appends = [
        'avatar_url',
        'field_emoji',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get avatar image URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        $avatarNum = (($this->id - 1) % 8) + 1;
        $path = "images/avatars/teacher-{$avatarNum}.svg";
        return asset($path);
    }

    /**
     * Get subject/field emoji icon.
     */
    public function getFieldEmojiAttribute(): string
    {
        $exp = mb_strtolower($this->expertise, 'UTF-8');
        if (str_contains($exp, 'web') || str_contains($exp, 'php')) return '💻';
        if (str_contains($exp, 'trí tuệ nhân tạo') || str_contains($exp, 'ai') || str_contains($exp, 'python')) return '🤖';
        if (str_contains($exp, 'mạng') || str_contains($exp, 'an toàn') || str_contains($exp, 'cisco')) return '🛡️';
        if (str_contains($exp, 'cơ sở dữ liệu') || str_contains($exp, 'sql')) return '🗄️';
        if (str_contains($exp, 'di động') || str_contains($exp, 'flutter')) return '📱';
        if (str_contains($exp, 'iot') || str_contains($exp, 'nhúng')) return '⚡';
        if (str_contains($exp, 'kiểm thử') || str_contains($exp, 'qa')) return '🧪';
        if (str_contains($exp, 'giải thuật') || str_contains($exp, 'dữ liệu')) return '🌲';
        return '🎓';
    }
}
