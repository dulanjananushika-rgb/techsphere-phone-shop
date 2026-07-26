<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'discount_percentage',
        'description',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];

    public function phones(): BelongsToMany
    {
        return $this->belongsToMany(Phone::class)->withTimestamps();
    }

    public function accessories(): BelongsToMany
    {
        return $this->belongsToMany(Accessory::class)->withTimestamps();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->whereDate('starts_at', '<=', today())
            ->whereDate('ends_at', '>=', today());
    }

    public function getStatusAttribute(): string
    {
        if (today()->lt($this->starts_at->startOfDay())) {
            return 'upcoming';
        }

        if (today()->gt($this->ends_at->endOfDay())) {
            return 'expired';
        }

        return 'active';
    }
}
