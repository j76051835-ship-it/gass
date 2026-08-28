<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->validate(['order_id' => ['required', 'string'], 'status_code' => ['required', 'string'], 'gross_amount' => ['required', 'string'], 'signature_key' => ['required', 'string'], 'transaction_status' => ['required', 'string'], 'fraud_status' => ['nullable', 'string'], 'transaction_id' => ['nullable', 'string'], 'payment_type' => ['nullable', 'string']]);
        $expected = hash('sha512', $payload['order_id'].$payload['status_code'].$payload['gross_amount'].config('services.midtrans.server_key'));
        abort_unless(hash_equals($expected, $payload['signature_key']), 403);
        $payment = Payment::query()->with(['invoice', 'order'])->where('midtrans_order_id', $payload['order_id'])->firstOrFail();
        abort_unless(number_format((float) $payment->gross_amount, 2, '.', '') === number_format((float) $payload['gross_amount'], 2, '.', ''), 422);
        $status = $payload['transaction_status'];
        $isPaid = $status === 'settlement' || ($status === 'capture' && ($payload['fraud_status'] ?? null) === 'accept');
        if ($payment->invoice->status === 'PAID' && ! $isPaid) {
            return response()->json(['received' => true]);
        }
        $invoiceStatus = $isPaid ? 'PAID' : match ($status) {
            'expire' => 'EXPIRED', 'cancel' => 'CANCELLED', 'deny' => 'FAILED', default => 'PENDING'
        };

        DB::transaction(function () use ($payment, $payload, $invoiceStatus, $isPaid): void {
            $payment->update(['transaction_id' => $payload['transaction_id'] ?? $payment->transaction_id, 'payment_type' => $payload['payment_type'] ?? $payment->payment_type, 'transaction_status' => $payload['transaction_status'], 'fraud_status' => $payload['fraud_status'] ?? null, 'paid_at' => $isPaid ? now() : null, 'raw_response' => $payload]);
            $payment->invoice->update(['status' => $invoiceStatus, 'paid_at' => $isPaid ? now() : null]);
            $payment->order->update(['status' => $isPaid ? 'PAID' : $invoiceStatus]);
        });
        Log::info('Midtrans payment notification processed', ['invoice' => $payment->invoice->number, 'status' => $status, 'transaction_id' => $payload['transaction_id'] ?? null]);

        return response()->json(['received' => true]);
    }
}
