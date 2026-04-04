<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public const AVATAR_COLORS = [
        '#A678A6',
        '#6F98C9',
        '#7BAF8E',
        '#D08C60',
        '#C56D7A',
        '#8E82C9',
    ];

    public const AVATAR_VARIANTS = [
        'classic',
        'soft',
        'round',
    ];

    protected $fillable = [
        'author_name',
        'rating',
        'review_text',
        'avatar_color',
        'avatar_variant',
        'reviewed_at',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function randomAvatarColor(): string
    {
        return self::AVATAR_COLORS[array_rand(self::AVATAR_COLORS)];
    }

    public static function randomAvatarVariant(): string
    {
        return self::AVATAR_VARIANTS[array_rand(self::AVATAR_VARIANTS)];
    }
}
