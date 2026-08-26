<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoBanner extends Model
{
    protected $fillable = [
        'title',
        'media_path',
        'media_type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getMediaTypeAttribute(?string $value): string
    {
        $extension = strtolower(pathinfo($this->attributes['media_path'] ?? '', PATHINFO_EXTENSION));

        return $value === 'video' || in_array($extension, ['mp4', 'mov', 'webm'], true) ? 'video' : 'image';
    }
}
