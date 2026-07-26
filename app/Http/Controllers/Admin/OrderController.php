<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Services\OrderInventoryService;
use App\Services\OrderNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.orders.index', [
            'orders' => Order::query()
                ->with('items')
                ->when($request->search, fn ($query, $search) => $query->where(fn ($q) => $q
                    ->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")))
                ->when($request->status, fn ($query, $status) => $query->where('status', $status))
                ->when($request->payment_status, fn ($query, $status) => $query->where('payment_status', $status))
                ->latest()
                ->paginate(12)
                ->withQueryString(),
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

    public function update(
        Request $request,
        Order $order,
        OrderInventoryService $inventory,
        OrderNotificationService $notifications
    ) {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Order::STATUSES))],
            'payment_status' => ['required', 'in:'.implode(',', array_keys(Order::PAYMENT_STATUSES))],
            'payment_reference' => ['nullable', 'string', 'max:120'],
        ]);

        $oldStatus = $order->status;
        $newStatus = $data['status'];
        $oldPaymentStatus = $order->payment_status;
        $reservationHours = max(1, (int) Setting::getValue('reservation_hours', '24'));

        DB::transaction(function () use ($order, $oldStatus, $newStatus, $data, $inventory, $reservationHours) {
            if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
                $inventory->release($order);
            }

            if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                $inventory->reserveAgain($order);
            }

            $order->update([
                'status' => $newStatus,
                'payment_status' => $data['payment_status'],
                'payment_reference' => $data['payment_reference'] ?? $order->payment_reference,
                'paid_at' => $data['payment_status'] === 'paid' ? ($order->paid_at ?? now()) : null,
                'reserved_until' => $newStatus === 'new' ? now()->addHours($reservationHours) : null,
            ]);

        });

        if ($oldStatus !== $newStatus || $oldPaymentStatus !== $data['payment_status']) {
            $notifications->orderUpdated($order->fresh());
        }

        return back()->with('status', 'Order status updated.');
    }
}
