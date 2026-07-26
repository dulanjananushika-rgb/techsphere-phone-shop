<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Phone;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Services\OrderNotificationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function phone(Phone $phone)
    {
        abort_unless($phone->is_active, 404);
        $phone->load(['brand', 'activeOffers', 'variants']);

        return view('store.order', [
            'item' => $phone,
            'type' => 'phone',
            'title' => $phone->name,
            'image' => $phone->image_url,
            'stock' => $phone->availableStock(),
            'variants' => $phone->variants->where('is_active', true),
            'salePrice' => $phone->salePrice(),
            'discount' => $phone->discountAmount(),
            'checkoutToken' => (string) Str::uuid(),
        ]);
    }

    public function accessory(Accessory $accessory)
    {
        abort_unless($accessory->is_active, 404);
        $accessory->load(['activeOffers', 'variants']);

        return view('store.order', [
            'item' => $accessory,
            'type' => 'accessory',
            'title' => $accessory->name,
            'image' => $accessory->image_url,
            'stock' => $accessory->availableStock(),
            'variants' => $accessory->variants->where('is_active', true),
            'salePrice' => $accessory->salePrice(),
            'discount' => $accessory->discountAmount(),
            'checkoutToken' => (string) Str::uuid(),
        ]);
    }

    public function store(Request $request, OrderNotificationService $notifications)
    {
        $data = $request->validate([
            'checkout_token' => ['required', 'uuid'],
            'item_type' => ['required', 'in:phone,accessory'],
            'item_id' => ['required', 'integer'],
            'product_variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['nullable', 'email', 'max:180'],
            'customer_phone' => ['required', 'string', 'regex:/^(?:\\+94|0)?[0-9]{9}$/'],
            'customer_address' => ['required_if:delivery_method,delivery', 'nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:cash,bank_transfer,card'],
            'payment_reference' => ['required_if:payment_method,bank_transfer', 'nullable', 'string', 'max:120'],
            'delivery_method' => ['required', 'in:pickup,delivery'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $existingOrder = Order::where('checkout_token', $data['checkout_token'])->first();

        if ($existingOrder) {
            if ($existingOrder->customer_phone !== $data['customer_phone']) {
                throw ValidationException::withMessages([
                    'checkout_token' => 'This checkout request has already been used.',
                ]);
            }

            return redirect()->route('orders.success', $existingOrder->access_token);
        }

        $order = DB::transaction(function () use ($data, $request) {
            $product = $this->findProductForUpdate($data['item_type'], (int) $data['item_id']);

            if (! $product) {
                throw ValidationException::withMessages([
                    'item_id' => 'This product is no longer available.',
                ]);
            }

            $variant = $this->findVariantForUpdate($product, $data['product_variant_id'] ?? null);
            $hasActiveVariants = $product->variants()->where('is_active', true)->exists();

            if ($hasActiveVariants && ! $variant) {
                throw ValidationException::withMessages([
                    'product_variant_id' => 'Please select an available variant.',
                ]);
            }

            $quantity = (int) $data['quantity'];
            $this->reserveStock($product, $variant, $quantity);

            $unitPrice = $variant?->price ?? $product->price;
            $offer = $product->activeOffer();
            $discountPerUnit = $offer ? (int) round($unitPrice * $offer->discount_percentage / 100) : 0;
            $lineTotal = ($unitPrice - $discountPerUnit) * $quantity;
            $deliveryFee = $data['delivery_method'] === 'delivery'
                ? (int) Setting::getValue('delivery_fee', '1500')
                : 0;
            $reservationHours = max(1, (int) Setting::getValue('reservation_hours', '24'));

            $order = Order::create([
                'order_number' => $this->nextOrderNumber(),
                'access_token' => Str::random(48),
                'checkout_token' => $data['checkout_token'],
                'invoice_number' => $this->nextInvoiceNumber(),
                'invoiced_at' => now(),
                'user_id' => $request->user()?->id,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'] ?? null,
                'customer_phone' => $data['customer_phone'],
                'customer_address' => $data['customer_address'] ?? null,
                'payment_method' => $data['payment_method'],
                'payment_reference' => $data['payment_reference'] ?? null,
                'payment_status' => 'pending',
                'reserved_until' => now()->addHours($reservationHours),
                'delivery_method' => $data['delivery_method'],
                'subtotal' => $unitPrice * $quantity,
                'discount_total' => $discountPerUnit * $quantity,
                'delivery_fee' => $deliveryFee,
                'total' => $lineTotal + $deliveryFee,
                'notes' => $data['notes'] ?? null,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'item_type' => $data['item_type'],
                'item_id' => $product->id,
                'product_variant_id' => $variant?->id,
                'item_name' => $product->name,
                'variant_name' => $variant?->name,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount_amount' => $discountPerUnit * $quantity,
                'line_total' => $lineTotal,
            ]);

            return $order;
        }, 3);

        $notifications->orderPlaced($order);

        return redirect()->route('orders.success', $order->access_token)
            ->with('status', 'Order request sent. We will contact you shortly.');
    }

    public function index(Request $request)
    {
        return view('store.orders', [
            'orders' => $request->user()
                ->orders()
                ->with('items')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function success(Order $order)
    {
        return view('store.order-success', ['order' => $order->load('items')]);
    }

    public function invoice(Order $order)
    {
        return view('store.invoice', ['order' => $order->load('items')]);
    }

    private function findProductForUpdate(string $type, int $id): ?Model
    {
        $class = $type === 'phone' ? Phone::class : Accessory::class;

        return $class::query()
            ->where('is_active', true)
            ->with('activeOffers')
            ->lockForUpdate()
            ->find($id);
    }

    private function findVariantForUpdate(Model $product, mixed $variantId): ?ProductVariant
    {
        if (! $variantId) {
            return null;
        }

        return ProductVariant::query()
            ->where('product_type', $product::class)
            ->where('product_id', $product->id)
            ->where('is_active', true)
            ->lockForUpdate()
            ->find($variantId);
    }

    private function reserveStock(Model $product, ?ProductVariant $variant, int $quantity): void
    {
        $target = $variant ?? $product;

        $updated = $target::query()
            ->whereKey($target->getKey())
            ->where('stock', '>=', $quantity)
            ->decrement('stock', $quantity);

        if ($updated !== 1) {
            throw ValidationException::withMessages([
                'quantity' => 'The requested quantity is no longer available. Please choose a lower quantity.',
            ]);
        }
    }

    private function nextOrderNumber(): string
    {
        do {
            $number = 'TS-'.now()->format('Ymd').'-'.str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }

    private function nextInvoiceNumber(): string
    {
        do {
            $number = 'INV-'.now()->format('Ymd').'-'.str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Order::where('invoice_number', $number)->exists());

        return $number;
    }
}
