<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Phone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreRulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_compare_rejects_more_than_three_phones(): void
    {
        $brand = Brand::create(['name' => 'Test Brand']);
        $phones = collect(range(1, 4))->map(fn (int $index) => Phone::create([
            'brand_id' => $brand->id,
            'name' => "Phone {$index}",
            'slug' => "phone-{$index}",
            'price' => 100000 + $index,
            'image_url' => 'https://example.com/phone.jpg',
            'ram' => '8GB',
            'storage' => '128GB',
            'stock' => 5,
            'is_featured' => false,
            'is_active' => true,
        ]));

        $this->get(route('compare', ['phones' => $phones->pluck('id')->all()]))
            ->assertRedirect()
            ->assertSessionHasErrors('phones');
    }

    public function test_hidden_products_do_not_appear_in_the_public_catalog(): void
    {
        $brand = Brand::create(['name' => 'Test Brand']);
        $phone = Phone::create([
            'brand_id' => $brand->id,
            'name' => 'Hidden Phone',
            'slug' => 'hidden-phone',
            'price' => 100000,
            'image_url' => 'https://example.com/phone.jpg',
            'ram' => '8GB',
            'storage' => '128GB',
            'stock' => 5,
            'is_featured' => false,
            'is_active' => false,
        ]);

        $this->get(route('phones.index'))->assertOk()->assertDontSee($phone->name);
        $this->get(route('phones.show', $phone))->assertNotFound();
        $this->get(route('orders.phone', $phone))->assertNotFound();
    }

    public function test_non_admin_users_cannot_open_the_admin_dashboard(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_registration_rejects_weak_passwords(): void
    {
        $this->from(route('register'))
            ->post(route('register.store'), [
                'name' => 'New Customer',
                'email' => 'new@example.com',
                'password' => 'weakpass',
                'password_confirmation' => 'weakpass',
            ])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'new@example.com']);
    }
}
