<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_name',
        'address',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get campus illustration image URL.
     */
    public function getImageUrlAttribute(): string
    {
        $name = mb_strtolower($this->branch_name, 'UTF-8');
        if (str_contains($name, 'chính') || str_contains($name, 'lê trọng tấn')) {
            return asset('images/branches/campus-tan-phu.svg');
        }
        if (str_contains($name, 'cơ sở 2') || str_contains($name, 'tân kỳ')) {
            return asset('images/branches/campus-tan-ky.svg');
        }
        if (str_contains($name, 'xưởng') || str_contains($name, 'nguyễn đỗ cung')) {
            return asset('images/branches/campus-nguyen-do-cung.svg');
        }
        if (str_contains($name, 'quận 1') || str_contains($name, 'đặng dung')) {
            return asset('images/branches/campus-dang-dung.svg');
        }
        return asset('images/branches/campus-tan-phu.svg');
    }

    /**
     * Get branch emoji.
     */
    public function getEmojiAttribute(): string
    {
        $name = mb_strtolower($this->branch_name, 'UTF-8');
        if (str_contains($name, 'chính')) return '🏛️';
        if (str_contains($name, '2') || str_contains($name, 'thực nghiệm')) return '🧪';
        if (str_contains($name, 'xưởng')) return '⚙️';
        if (str_contains($name, 'quận 1')) return '🏙️';
        return '🏢';
    }
}
