<?php

namespace App\Services;

use App\Models\NotificationLog;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Throwable;

class OrderNotificationService
{
    public function orderPlaced(Order $order): void
    {
        $shop = Setting::storeProfile();
        $summary = "{$order->customer_name} placed {$order->order_number} for Rs. "
            .number_format($order->total).'.';

        $this->send(
            $order,
            $shop['shop_email'],
            "New order {$order->order_number}",
            $summary."\n\nOpen the admin dashboard to confirm this reservation."
        );

        if ($order->customer_email) {
            $this->send(
                $order,
                $order->customer_email,
                "We received your order {$order->order_number}",
                "Hi {$order->customer_name},\n\nYour order request was received and stock is reserved until "
                    .$order->reserved_until?->format('M d, Y h:i A')
                    .'. We will contact you to confirm payment and delivery.'
            );
        }
    }

    public function orderUpdated(Order $order): void
    {
        if (! $order->customer_email) {
            return;
        }

        $status = Order::STATUSES[$order->status] ?? $order->status;
        $payment = Order::PAYMENT_STATUSES[$order->payment_status] ?? $order->payment_status;

        $this->send(
            $order,
            $order->customer_email,
            "Order update {$order->order_number}",
            "Hi {$order->customer_name},\n\nOrder status: {$status}\nPayment status: {$payment}"
        );
    }

    public function retry(NotificationLog $notification): void
    {
        $this->deliver($notification);
    }

    private function send(Order $order, string $recipient, string $subject, string $message): void
    {
        $notification = NotificationLog::create([
            'order_id' => $order->id,
            'channel' => 'email',
            'recipient' => $recipient,
            'subject' => $subject,
            'message' => $message,
            'status' => 'queued',
        ]);

        $this->deliver($notification);
    }

    private function deliver(NotificationLog $notification): void
    {
        try {
            Mail::raw($notification->message, function ($mail) use ($notification): void {
                $mail->to($notification->recipient)->subject($notification->subject);
            });

            $notification->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        } catch (Throwable $exception) {
            report($exception);
            $notification->update([
                'status' => 'failed',
                'sent_at' => null,
            ]);
        }
    }
}
