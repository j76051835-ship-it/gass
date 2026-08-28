<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'service_package_id', 'package_name', 'quantity', 'unit_price', 'custom'];

    protected function casts(): array
    {
        return ['quantity' => 'integer', 'unit_price' => 'integer', 'custom' => 'array'];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function servicePackage(): BelongsTo
    {
        return $this->belongsTo(ServicePackage::class);
    }
}
