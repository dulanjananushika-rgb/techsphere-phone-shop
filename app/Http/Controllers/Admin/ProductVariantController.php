<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use App\Models\OrderItem;
use App\Models\Phone;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.variants.index', [
            'variants' => ProductVariant::with('product')->latest()->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.variants.form', [
            'variant' => new ProductVariant,
            'phones' => Phone::with('brand')->orderBy('name')->get(),
            'accessories' => Accessory::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        ProductVariant::create($this->validated($request));

        return redirect()->route('admin.variants.index')->with('status', 'Variant created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductVariant $variant)
    {
        return view('admin.variants.form', [
            'variant' => $variant,
            'phones' => Phone::with('brand')->orderBy('name')->get(),
            'accessories' => Accessory::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductVariant $variant)
    {
        $variant->update($this->validated($request, $variant));

        return redirect()->route('admin.variants.index')->with('status', 'Variant updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductVariant $variant)
    {
        if (OrderItem::where('product_variant_id', $variant->id)->exists()) {
            return back()->withErrors([
                'delete' => 'This SKU belongs to an order history. Set it to Hidden instead of deleting it.',
            ]);
        }

        $variant->delete();

        return redirect()->route('admin.variants.index')->with('status', 'Variant deleted.');
    }

    private function validated(Request $request, ?ProductVariant $variant = null): array
    {
        $data = $request->validate([
            'product_target' => ['required', 'regex:/^(phone|accessory):[0-9]+$/'],
            'sku' => ['required', 'string', 'max:80', Rule::unique('product_variants', 'sku')->ignore($variant)],
            'name' => ['required', 'string', 'max:140'],
            'color' => ['nullable', 'string', 'max:80'],
            'storage' => ['nullable', 'string', 'max:80'],
            'price' => ['required', 'integer', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        [$type, $id] = explode(':', $data['product_target']);

        $productExists = $type === 'phone'
            ? Phone::whereKey($id)->exists()
            : Accessory::whereKey($id)->exists();

        if (! $productExists) {
            throw ValidationException::withMessages([
                'product_target' => 'Please select a valid product.',
            ]);
        }

        $data['product_type'] = $type === 'phone' ? Phone::class : Accessory::class;
        $data['product_id'] = (int) $id;
        $data['is_active'] = $request->boolean('is_active');
        unset($data['product_target']);

        return $data;
    }
}
