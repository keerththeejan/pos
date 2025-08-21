<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'title',
        'description',
        'is_active',
        'image',
    ];

    /**
     * Appended attributes for URL with fallback.
     */
    protected $appends = [
        'image_url',
    ];

    /**
     * Base upload directory under public path.
     */
    public static function uploadDir(): string
    {
        return 'uploads/banners';
    }

    /**
     * Fallback image relative to public/ when image not present.
     */
    public static function fallbackImage(): string
    {
        return 'img/default.png';
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset(self::uploadDir() . '/' . $this->image) : asset(self::fallbackImage());
    }
}
