<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $fillable = ['title', 'description', 'media', 'is_active'];

    protected function casts(): array
    {
        return ['media' => 'array', 'is_active' => 'boolean'];
    }
}
