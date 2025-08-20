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
        'is_active',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

    /**
     * Appended attributes for URLs with fallback.
     */
    protected $appends = [
        'image1_url', 'image2_url', 'image3_url', 'image4_url',
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

    public function getImage1UrlAttribute(): string
    {
        return $this->image1 ? asset(self::uploadDir() . '/' . $this->image1) : asset(self::fallbackImage());
    }

    public function getImage2UrlAttribute(): string
    {
        return $this->image2 ? asset(self::uploadDir() . '/' . $this->image2) : asset(self::fallbackImage());
    }

    public function getImage3UrlAttribute(): string
    {
        return $this->image3 ? asset(self::uploadDir() . '/' . $this->image3) : asset(self::fallbackImage());
    }

    public function getImage4UrlAttribute(): string
    {
        return $this->image4 ? asset(self::uploadDir() . '/' . $this->image4) : asset(self::fallbackImage());
    }
}
