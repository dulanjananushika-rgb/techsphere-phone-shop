<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'price',
        'image_url',
        'description',
        'stock',
    ];

    public function offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class)->withTimestamps();
    }

    public function variants(): MorphMany
    {
        return $this->morphMany(ProductVariant::class, 'product', 'product_type', 'product_id');
    }

    public function activeOffers(): BelongsToMany
    {
        return $this->offers()
            ->whereDate('starts_at', '<=', now())
            ->whereDate('ends_at', '>=', now())
            ->orderByDesc('discount_percentage');
    }

    public function activeOffer(): ?Offer
    {
        if ($this->relationLoaded('activeOffers')) {
            return $this->activeOffers->first();
        }

        return $this->activeOffers()->first();
    }

    public function discountAmount(): int
    {
        $offer = $this->activeOffer();

        return $offer ? (int) round($this->price * $offer->discount_percentage / 100) : 0;
    }

    public function salePrice(): int
    {
        return max(0, $this->price - $this->discountAmount());
    }

    public function availableStock(): int
    {
        if ($this->relationLoaded('variants') && $this->variants->count() > 0) {
            return (int) $this->variants->where('is_active', true)->sum('stock');
        }

        return $this->stock;
    }
}
