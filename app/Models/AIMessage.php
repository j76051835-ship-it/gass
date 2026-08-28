<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIMessage extends Model
{
    protected $fillable = ['conversation_id', 'role', 'message'];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(AIConversation::class, 'conversation_id');
    }
}
