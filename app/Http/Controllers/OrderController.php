<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\NotificationLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Phone;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function phone(Phone $phone)
    {
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
        ]);
    }

    public function accessory(Accessory $accessory)
    {
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
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_type' => ['required', 'in:phone,accessory'],
            'item_id' => ['required', 'integer'],
            'product_variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['nullable', 'email', 'max:180'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_address' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:cash,bank_transfer,card'],
            'payment_reference' => ['nullable', 'string', 'max:120'],
            'delivery_method' => ['required', 'in:pickup,delivery'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $product = $this->findProduct($data['item_type'], (int) $data['item_id']);
        $variant = $this->findVariant($product, $data['product_variant_id'] ?? null);

        if ($product?->variants()->where('is_active', true)->exists() && ! $variant) {
            return back()->withErrors(['product_variant_id' => 'Please select an available variant.'])->withInput();
        }

        $availableStock = $variant?->stock ?? $product?->stock ?? 0;

        if (! $product || $availableStock < $data['quantity']) {
            return back()->withErrors(['quantity' => 'Requested quantity is not available in stock.'])->withInput();
        }

        $order = DB::transaction(function () use ($data, $product, $variant, $request) {
            $quantity = (int) $data['quantity'];
            $unitPrice = $variant?->price ?? $product->price;
            $offer = $product->activeOffer();
            $discountPerUnit = $offer ? (int) round($unitPrice * $offer->discount_percentage / 100) : 0;
            $lineTotal = ($unitPrice - $discountPerUnit) * $quantity;

            $variant ? $variant->decrement('stock', $quantity) : $product->decrement('stock', $quantity);

            $order = Order::create([
                'order_number' => $this->nextOrderNumber(),
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
                'delivery_method' => $data['delivery_method'],
                'subtotal' => $unitPrice * $quantity,
                'discount_total' => $discountPerUnit * $quantity,
                'total' => $lineTotal,
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

            NotificationLog::create([
                'order_id' => $order->id,
                'channel' => 'admin',
                'recipient' => 'shop-admin',
                'subject' => 'New order request '.$order->order_number,
                'message' => $order->customer_name.' reserved '.$product->name.' for Rs. '.number_format($order->total).'.',
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            return $order;
        });

        return redirect()->route('orders.success', $order)->with('status', 'Order request sent. We will contact you shortly.');
    }

    public function success(Order $order)
    {
        return view('store.order-success', ['order' => $order->load('items')]);
    }

    public function invoice(Order $order)
    {
        return view('store.invoice', ['order' => $order->load('items')]);
    }

    private function findProduct(string $type, int $id): ?Model
    {
        return $type === 'phone'
            ? Phone::with(['activeOffers', 'variants'])->find($id)
            : Accessory::with(['activeOffers', 'variants'])->find($id);
    }

    private function findVariant(?Model $product, mixed $variantId): ?ProductVariant
    {
        if (! $product || ! $variantId) {
            return null;
        }

        return ProductVariant::query()
            ->where('product_type', $product::class)
            ->where('product_id', $product->id)
            ->where('is_active', true)
            ->find($variantId);
    }

    private function nextOrderNumber(): string
    {
        do {
            $number = 'TS-' . now()->format('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }

    private function nextInvoiceNumber(): string
    {
        do {
            $number = 'INV-' . now()->format('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Order::where('invoice_number', $number)->exists());

        return $number;
    }
}
