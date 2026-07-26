<?php

namespace Database\Seeders;

use App\Models\Accessory;
use App\Models\Brand;
use App\Models\Offer;
use App\Models\Phone;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'TechSphere Admin',
            'email' => 'admin@techsphere.test',
            'password' => 'password',
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Demo Customer',
            'email' => 'user@techsphere.test',
            'password' => 'password',
        ]);

        $brands = collect([
            ['name' => 'Apple', 'description' => 'Premium iOS devices with long software support.'],
            ['name' => 'Samsung', 'description' => 'Flagship Android phones with brilliant displays.'],
            ['name' => 'Xiaomi', 'description' => 'Value-focused smartphones with strong battery life.'],
            ['name' => 'Oppo', 'description' => 'Camera-friendly devices for daily creators.'],
        ])->mapWithKeys(fn ($brand) => [$brand['name'] => Brand::create($brand)]);

        $phones = [
            [
                'brand' => 'Apple',
                'name' => 'iPhone 16 Pro Max',
                'price' => 565000,
                'old_price' => 589000,
                'image_url' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?auto=format&fit=crop&w=900&q=80',
                'ram' => '8GB',
                'storage' => '256GB',
                'display' => '6.9-inch Super Retina XDR',
                'processor' => 'A18 Pro',
                'camera' => '48MP Fusion + Telephoto',
                'battery' => 'All-day battery',
                'os' => 'iOS',
                'description' => 'A premium flagship for creators, gamers, and professionals who want top-tier performance.',
                'stock' => 8,
                'is_featured' => true,
            ],
            [
                'brand' => 'Samsung',
                'name' => 'Galaxy S25 Ultra',
                'price' => 489000,
                'old_price' => 515000,
                'image_url' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?auto=format&fit=crop&w=900&q=80',
                'ram' => '12GB',
                'storage' => '512GB',
                'display' => '6.8-inch Dynamic AMOLED',
                'processor' => 'Snapdragon flagship chipset',
                'camera' => '200MP wide camera system',
                'battery' => '5000mAh',
                'os' => 'Android',
                'description' => 'A productivity flagship with S Pen support, excellent zoom, and a large display.',
                'stock' => 12,
                'is_featured' => true,
            ],
            [
                'brand' => 'Xiaomi',
                'name' => 'Redmi Note 13 Pro',
                'price' => 112000,
                'old_price' => 129000,
                'image_url' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?auto=format&fit=crop&w=900&q=80',
                'ram' => '8GB',
                'storage' => '256GB',
                'display' => '6.67-inch AMOLED',
                'processor' => 'Snapdragon midrange chipset',
                'camera' => '200MP main camera',
                'battery' => '5100mAh',
                'os' => 'Android',
                'description' => 'A balanced phone for students and everyday users who want strong specs for the price.',
                'stock' => 20,
                'is_featured' => true,
            ],
            [
                'brand' => 'Oppo',
                'name' => 'Oppo Reno 14F',
                'price' => 148000,
                'old_price' => null,
                'image_url' => 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?auto=format&fit=crop&w=900&q=80',
                'ram' => '12GB',
                'storage' => '256GB',
                'display' => '6.7-inch AMOLED',
                'processor' => 'Efficient 5G chipset',
                'camera' => 'Portrait camera system',
                'battery' => '5000mAh',
                'os' => 'Android',
                'description' => 'A stylish phone for social media, portraits, and smooth daily use.',
                'stock' => 15,
                'is_featured' => false,
            ],
        ];

        $createdPhones = collect();

        foreach ($phones as $phone) {
            $brand = $brands[$phone['brand']];
            unset($phone['brand']);

            $created = Phone::create([
                ...$phone,
                'brand_id' => $brand->id,
                'slug' => Str::slug($phone['name']),
            ]);

            $createdPhones->put($created->name, $created);
        }

        $createdAccessories = collect([
            [
                'name' => 'Anker 20W Fast Charger',
                'category' => 'Chargers',
                'price' => 9500,
                'image_url' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?auto=format&fit=crop&w=900&q=80',
                'description' => 'Compact USB-C charger for fast daily charging.',
                'stock' => 30,
            ],
            [
                'name' => 'MagSafe Clear Case',
                'category' => 'Covers',
                'price' => 6800,
                'image_url' => 'https://images.unsplash.com/photo-1603313011101-320f26a4f6f6?auto=format&fit=crop&w=900&q=80',
                'description' => 'Slim transparent protection with wireless charging support.',
                'stock' => 22,
            ],
            [
                'name' => 'AirPods Pro Style Earbuds',
                'category' => 'Audio',
                'price' => 42000,
                'image_url' => 'https://images.unsplash.com/photo-1606220945770-b5b6c2c55bf1?auto=format&fit=crop&w=900&q=80',
                'description' => 'Wireless audio with noise isolation and charging case.',
                'stock' => 18,
            ],
        ])->mapWithKeys(fn ($accessory) => [$accessory['name'] => Accessory::create($accessory)]);

        $campusOffer = Offer::create([
            'title' => 'Back to Campus Deals',
            'discount_percentage' => 12,
            'description' => 'Special discounts for students buying selected smartphones.',
            'starts_at' => Carbon::now()->subDays(3),
            'ends_at' => Carbon::now()->addDays(20),
        ]);
        $campusOffer->phones()->sync([
            $createdPhones['Redmi Note 13 Pro']->id,
            $createdPhones['Oppo Reno 14F']->id,
        ]);

        $bundleOffer = Offer::create([
            'title' => 'Accessory Bundle Week',
            'discount_percentage' => 18,
            'description' => 'Save more when you buy a charger, case, and earbuds together.',
            'starts_at' => Carbon::now(),
            'ends_at' => Carbon::now()->addDays(14),
        ]);
        $bundleOffer->accessories()->sync($createdAccessories->pluck('id')->all());

        ProductVariant::insert([
            [
                'product_type' => Phone::class,
                'product_id' => $createdPhones['iPhone 16 Pro Max']->id,
                'sku' => 'IPH16PM-256-BLK',
                'name' => '256GB Black Titanium',
                'color' => 'Black Titanium',
                'storage' => '256GB',
                'price' => 565000,
                'stock' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_type' => Phone::class,
                'product_id' => $createdPhones['iPhone 16 Pro Max']->id,
                'sku' => 'IPH16PM-512-NAT',
                'name' => '512GB Natural Titanium',
                'color' => 'Natural Titanium',
                'storage' => '512GB',
                'price' => 635000,
                'stock' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_type' => Phone::class,
                'product_id' => $createdPhones['Galaxy S25 Ultra']->id,
                'sku' => 'S25U-512-GRY',
                'name' => '512GB Titanium Gray',
                'color' => 'Titanium Gray',
                'storage' => '512GB',
                'price' => 489000,
                'stock' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_type' => Accessory::class,
                'product_id' => $createdAccessories['MagSafe Clear Case']->id,
                'sku' => 'CASE-CLR-IPH',
                'name' => 'iPhone Clear',
                'color' => 'Clear',
                'storage' => null,
                'price' => 6800,
                'stock' => 12,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Setting::insert([
            ['key' => 'whatsapp_number', 'value' => '94771234567', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'shop_email', 'value' => 'hello@techsphere.test', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
