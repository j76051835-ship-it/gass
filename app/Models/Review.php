<?php

namespace App\Models;

use Database\Factories\ReviewFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /** @use HasFactory<ReviewFactory> */
    use HasFactory;

    protected $fillable = ['name', 'company', 'rating', 'comment', 'media', 'approved'];

    protected function casts(): array
    {
        return ['rating' => 'integer', 'media' => 'array', 'approved' => 'boolean'];
    }
}
