<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function getValue(string $key, ?string $fallback = null): ?string
    {
        return static::query()->where('key', $key)->value('value') ?? $fallback;
    }

    public static function storeProfile(): array
    {
        $defaults = [
            'store_name' => 'TechSphere Mobile',
            'whatsapp_number' => '94771234567',
            'shop_email' => 'hello@techsphere.test',
            'shop_phone' => '+94 77 123 4567',
            'shop_address' => 'Colombo, Sri Lanka',
            'opening_hours' => 'Daily, 9.00 AM - 8.00 PM',
            'delivery_fee' => '1500',
            'reservation_hours' => '24',
            'bank_name' => 'Commercial Bank',
            'bank_account_name' => 'TechSphere Mobile',
            'bank_account_number' => '0000000000',
        ];

        $stored = static::query()
            ->whereIn('key', array_keys($defaults))
            ->pluck('value', 'key')
            ->all();

        return array_replace($defaults, $stored);
    }
}
