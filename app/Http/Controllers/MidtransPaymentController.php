<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ServicePackage;
use App\Services\MidtransPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class MidtransPaymentController extends Controller
{
    public function token(Request $request, MidtransPaymentService $midtrans): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:150'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.package' => ['required', 'string', 'max:150'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'items.*.custom' => ['nullable', 'array'],
        ]);

        try {
            $result = DB::transaction(function () use ($validated, $request, $midtrans): array {
                $packageNames = collect($validated['items'])->pluck('package')->unique()->values();
                $packages = ServicePackage::query()->whereIn('name', $packageNames)->where('is_active', true)->get()->keyBy('name');
                abort_if($packages->count() !== $packageNames->count(), 422, 'Paket tidak tersedia.');
                $items = collect($validated['items'])->map(fn (array $item): array => ['package' => $packages->get($item['package']), 'quantity' => $item['quantity'], 'custom' => $item['custom'] ?? null]);
                $total = $items->sum(fn (array $item): int => $item['package']->final_price * $item['quantity']);
                $invoiceNumber = 'INV-GASS-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4));
                $order = Order::create(['customer_name' => $validated['customer_name'], 'session_id' => $request->session()->getId(), 'status' => 'PENDING', 'total' => $total, 'currency' => 'IDR']);
                foreach ($items as $item) {
                    $order->items()->create(['service_package_id' => $item['package']->id, 'package_name' => $item['package']->name, 'quantity' => $item['quantity'], 'unit_price' => $item['package']->final_price, 'custom' => $item['custom']]);
                }
                $invoice = $order->invoice()->create(['number' => $invoiceNumber, 'total' => $total, 'status' => 'UNPAID']);
                $payment = $invoice->payments()->create(['order_id' => $order->id, 'midtrans_order_id' => $invoiceNumber, 'gross_amount' => $total, 'currency' => 'IDR', 'transaction_status' => 'pending']);
                $token = $midtrans->createSnapToken(['transaction_details' => ['order_id' => $invoiceNumber, 'gross_amount' => $total], 'item_details' => $items->map(fn (array $item): array => ['id' => (string) $item['package']->id, 'price' => $item['package']->final_price, 'quantity' => $item['quantity'], 'name' => $item['package']->name])->values()->all(), 'customer_details' => ['first_name' => $validated['customer_name']]]);
                $payment->update(['snap_token' => $token]);

                return ['invoice' => $invoice->number, 'token' => $token];
            });

            return response()->json($result);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json(['message' => 'Pembayaran belum dapat diproses. Silakan coba lagi.'], 502);
        }
    }
}
