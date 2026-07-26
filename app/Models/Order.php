<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = [
        'new' => 'New',
        'processing' => 'Processing',
        'ready' => 'Ready for Pickup',
        'delivered' => 'Delivered',
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
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'status',
        'payment_method',
        'payment_status',
        'payment_reference',
        'paid_at',
        'invoice_number',
        'invoiced_at',
        'delivery_method',
        'subtotal',
        'discount_total',
        'total',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'invoiced_at' => 'datetime',
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
}
