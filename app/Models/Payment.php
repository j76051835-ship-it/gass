<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = ['invoice_id', 'order_id', 'transaction_id', 'midtrans_order_id', 'payment_type', 'gross_amount', 'transaction_status', 'fraud_status', 'currency', 'snap_token', 'paid_at', 'raw_response'];

    protected function casts(): array
    {
        return ['gross_amount' => 'decimal:2', 'paid_at' => 'datetime', 'raw_response' => 'array'];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
