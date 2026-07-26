<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

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

    public function getStatusAttribute(): string
    {
        if (now()->lt($this->starts_at)) {
            return 'upcoming';
        }

        if (now()->gt($this->ends_at)) {
            return 'expired';
        }

        return 'active';
    }
}
