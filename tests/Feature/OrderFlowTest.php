<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Phone;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_order_pages_use_private_access_tokens(): void
    {
        $order = $this->createOrder();
        OrderItem::create([
            'order_id' => $order->id,
            'item_type' => 'phone',
            'item_id' => 1,
            'item_name' => 'Test Phone',
            'quantity' => 1,
            'unit_price' => 100000,
            'discount_amount' => 0,
            'line_total' => 100000,
        ]);

        $this->get("/orders/{$order->id}/success")->assertNotFound();
        $this->get("/orders/{$order->id}/invoice")->assertNotFound();

        $this->get(route('orders.success', $order->access_token))
            ->assertOk()
            ->assertSee($order->order_number);

        $this->get(route('orders.invoice', $order->access_token))
            ->assertOk()
            ->assertSee($order->invoice_number);
    }

    public function test_checkout_reserves_variant_stock_and_is_idempotent(): void
    {
        Mail::fake();
        Setting::create(['key' => 'delivery_fee', 'value' => '1800']);

        $phone = $this->createPhone();
        $variant = ProductVariant::create([
            'product_type' => Phone::class,
            'product_id' => $phone->id,
            'sku' => 'TEST-256-BLK',
            'name' => '256GB Black',
            'color' => 'Black',
            'storage' => '256GB',
            'price' => 120000,
            'stock' => 5,
            'is_active' => true,
        ]);
        $token = (string) Str::uuid();

        $payload = [
            'checkout_token' => $token,
            'item_type' => 'phone',
            'item_id' => $phone->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
            'customer_name' => 'Test Customer',
            'customer_email' => 'customer@example.com',
            'customer_phone' => '0771234567',
            'customer_address' => '10 Test Road, Colombo',
            'payment_method' => 'cash',
            'delivery_method' => 'delivery',
            'notes' => null,
        ];

        $first = $this->post(route('orders.store'), $payload);
        $order = Order::sole();

        $first->assertRedirect(route('orders.success', $order->access_token));
        $this->assertSame(3, $variant->fresh()->stock);
        $this->assertSame(241800, $order->total);
        $this->assertSame(1800, $order->delivery_fee);
        $this->assertNotNull($order->reserved_until);

        $second = $this->post(route('orders.store'), $payload);

        $second->assertRedirect(route('orders.success', $order->access_token));
        $this->assertDatabaseCount('orders', 1);
        $this->assertSame(3, $variant->fresh()->stock);
    }

    public function test_delivery_requires_an_address(): void
    {
        $phone = $this->createPhone();

        $this->from(route('orders.phone', $phone))
            ->post(route('orders.store'), [
                'checkout_token' => (string) Str::uuid(),
                'item_type' => 'phone',
                'item_id' => $phone->id,
                'quantity' => 1,
                'customer_name' => 'Test Customer',
                'customer_phone' => '0771234567',
                'payment_method' => 'cash',
                'delivery_method' => 'delivery',
            ])
            ->assertRedirect(route('orders.phone', $phone))
            ->assertSessionHasErrors('customer_address');

        $this->assertDatabaseCount('orders', 0);
        $this->assertSame(10, $phone->fresh()->stock);
    }

    public function test_admin_cancellation_restores_stock_and_reactivation_reserves_it_again(): void
    {
        Mail::fake();
        Setting::create(['key' => 'reservation_hours', 'value' => '6']);

        $admin = User::factory()->create(['is_admin' => true]);
        $phone = $this->createPhone();
        $phone->update(['stock' => 9]);
        $order = $this->createOrder();
        OrderItem::create([
            'order_id' => $order->id,
            'item_type' => 'phone',
            'item_id' => $phone->id,
            'item_name' => $phone->name,
            'quantity' => 1,
            'unit_price' => 100000,
            'discount_amount' => 0,
            'line_total' => 100000,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.orders.update', $order), [
                'status' => 'cancelled',
                'payment_status' => 'pending',
            ])
            ->assertRedirect();

        $this->assertSame(10, $phone->fresh()->stock);
        $this->assertNull($order->fresh()->reserved_until);

        $this->actingAs($admin)
            ->patch(route('admin.orders.update', $order), [
                'status' => 'new',
                'payment_status' => 'pending',
            ])
            ->assertRedirect();

        $this->assertSame(9, $phone->fresh()->stock);
        $this->assertTrue($order->fresh()->reserved_until->between(
            now()->addHours(5)->addMinutes(59),
            now()->addHours(6)->addMinute(),
        ));
    }

    private function createOrder(): Order
    {
        return Order::create([
            'order_number' => 'TS-TEST-0001',
            'access_token' => Str::random(48),
            'checkout_token' => (string) Str::uuid(),
            'invoice_number' => 'INV-TEST-0001',
            'invoiced_at' => now(),
            'customer_name' => 'Private Customer',
            'customer_email' => 'private@example.com',
            'customer_phone' => '0771234567',
            'status' => 'new',
            'reserved_until' => now()->addDay(),
            'payment_method' => 'cash',
            'payment_status' => 'pending',
            'delivery_method' => 'pickup',
            'subtotal' => 100000,
            'discount_total' => 0,
            'delivery_fee' => 0,
            'total' => 100000,
        ]);
    }

    private function createPhone(): Phone
    {
        $brand = Brand::create(['name' => 'Test Brand']);

        return Phone::create([
            'brand_id' => $brand->id,
            'name' => 'Test Phone',
            'slug' => 'test-phone',
            'price' => 100000,
            'image_url' => 'https://example.com/phone.jpg',
            'ram' => '8GB',
            'storage' => '128GB',
            'stock' => 10,
            'is_featured' => false,
            'is_active' => true,
        ]);
    }
}
