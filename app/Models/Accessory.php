<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

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
        $variants = $this->relationLoaded('variants')
            ? $this->variants
            : $this->variants()->get(['stock', 'is_active']);

        if ($variants->isNotEmpty()) {
            return (int) $variants->where('is_active', true)->sum('stock');
        }

        return $this->stock;
    }
}
