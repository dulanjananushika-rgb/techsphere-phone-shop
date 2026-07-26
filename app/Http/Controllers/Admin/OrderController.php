<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use App\Models\NotificationLog;
use App\Models\Order;
use App\Models\Phone;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.orders.index', [
            'orders' => Order::with('items')->latest()->paginate(12),
            'statuses' => Order::STATUSES,
            'paymentStatuses' => Order::PAYMENT_STATUSES,
        ]);
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', [
            'order' => $order->load('items'),
            'statuses' => Order::STATUSES,
            'paymentStatuses' => Order::PAYMENT_STATUSES,
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Order::STATUSES))],
            'payment_status' => ['required', 'in:' . implode(',', array_keys(Order::PAYMENT_STATUSES))],
            'payment_reference' => ['nullable', 'string', 'max:120'],
        ]);

        $oldStatus = $order->status;
        $newStatus = $data['status'];
        $oldPaymentStatus = $order->payment_status;

        if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled' && ! $this->hasStockFor($order)) {
            return back()->withErrors(['status' => 'Not enough stock to reactivate this order.']);
        }

        DB::transaction(function () use ($order, $oldStatus, $newStatus, $oldPaymentStatus, $data) {
            if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
                $this->adjustStock($order, +1);
            }

            if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                $this->adjustStock($order, -1);
            }

            $order->update([
                'status' => $newStatus,
                'payment_status' => $data['payment_status'],
                'payment_reference' => $data['payment_reference'] ?? $order->payment_reference,
                'paid_at' => $data['payment_status'] === 'paid' ? ($order->paid_at ?? now()) : null,
            ]);

            if ($oldStatus !== $newStatus || $oldPaymentStatus !== $data['payment_status']) {
                NotificationLog::create([
                    'order_id' => $order->id,
                    'channel' => 'customer',
                    'recipient' => $order->customer_phone,
                    'subject' => 'Order update '.$order->order_number,
                    'message' => 'Order status: '.Order::STATUSES[$newStatus].'. Payment: '.Order::PAYMENT_STATUSES[$data['payment_status']].'.',
                    'status' => 'queued',
                ]);
            }
        });

        return back()->with('status', 'Order status updated.');
    }

    private function hasStockFor(Order $order): bool
    {
        foreach ($order->items as $item) {
            $product = $this->findStockTarget($item);

            if (! $product || $product->stock < $item->quantity) {
                return false;
            }
        }

        return true;
    }

    private function adjustStock(Order $order, int $direction): void
    {
        foreach ($order->items as $item) {
            $product = $this->findStockTarget($item);

            if ($product) {
                $product->increment('stock', $direction * $item->quantity);
            }
        }
    }

    private function findProduct(string $type, int $id): Phone|Accessory|null
    {
        return $type === 'phone' ? Phone::find($id) : Accessory::find($id);
    }

    private function findStockTarget($item): Phone|Accessory|ProductVariant|null
    {
        if ($item->product_variant_id) {
            return ProductVariant::find($item->product_variant_id);
        }

        return $this->findProduct($item->item_type, $item->item_id);
    }
}
