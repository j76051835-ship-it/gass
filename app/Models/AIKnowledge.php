<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIKnowledge extends Model
{
    protected $table = 'a_i_knowledge';

    protected $fillable = ['category', 'title', 'content', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
