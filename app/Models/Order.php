<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = [
        'new' => 'Awaiting Confirmation',
        'confirmed' => 'Confirmed',
        'processing' => 'Preparing',
        'ready' => 'Ready for Pickup',
        'shipped' => 'Out for Delivery',
        'delivered' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    public const PAYMENT_STATUSES = [
        'pending' => 'Pending',
        'paid' => 'Paid',
        'failed' => 'Failed',
        'refunded' => 'Refunded',
    ];

    protected $fillable = [
        'order_number',
        'access_token',
        'checkout_token',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'status',
        'reserved_until',
        'payment_method',
        'payment_status',
        'payment_reference',
        'paid_at',
        'invoice_number',
        'invoiced_at',
        'delivery_method',
        'subtotal',
        'discount_total',
        'delivery_fee',
        'total',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'invoiced_at' => 'datetime',
        'reserved_until' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(NotificationLog::class);
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isReservationExpired(): bool
    {
        return $this->status === 'new'
            && $this->reserved_until?->isPast();
    }
}
