<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransPaymentService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createSnapToken(array $parameters): string
    {
        return Snap::getSnapToken($parameters);
    }
}
