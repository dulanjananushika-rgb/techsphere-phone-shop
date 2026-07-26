<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_type',
        'product_id',
        'sku',
        'name',
        'color',
        'storage',
        'price',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function product(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'product_type', 'product_id');
    }
}
