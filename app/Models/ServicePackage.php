<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'base_price',
        'discount_percent',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'integer',
            'discount_percent' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function getFinalPriceAttribute(): int
    {
        return (int) round($this->base_price * (100 - $this->discount_percent) / 100);
    }
}
