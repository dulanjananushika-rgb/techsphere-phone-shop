<?php

namespace App\Services;

use App\Models\Accessory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Phone;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class OrderInventoryService
{
    public function release(Order $order): void
    {
        foreach ($this->lockedItems($order) as $item) {
            $target = $this->stockTarget($item);

            if ($target) {
                $target->increment('stock', $item->quantity);
            }
        }
    }

    public function reserveAgain(Order $order): void
    {
        foreach ($this->lockedItems($order) as $item) {
            $target = $this->stockTarget($item);

            if (! $target) {
                throw ValidationException::withMessages([
                    'status' => "The stock item for {$item->item_name} no longer exists.",
                ]);
            }

            $updated = $target::query()
                ->whereKey($target->getKey())
                ->where('stock', '>=', $item->quantity)
                ->decrement('stock', $item->quantity);

            if ($updated !== 1) {
                throw ValidationException::withMessages([
                    'status' => "Not enough stock to reactivate {$item->item_name}.",
                ]);
            }
        }
    }

    private function lockedItems(Order $order)
    {
        return OrderItem::query()
            ->where('order_id', $order->id)
            ->lockForUpdate()
            ->get();
    }

    private function stockTarget(OrderItem $item): ?Model
    {
        if ($item->product_variant_id) {
            return ProductVariant::query()->lockForUpdate()->find($item->product_variant_id);
        }

        $class = $item->item_type === 'phone' ? Phone::class : Accessory::class;

        return $class::query()->lockForUpdate()->find($item->item_id);
    }
}
