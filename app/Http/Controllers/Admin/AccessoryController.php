<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AccessoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.accessories.index', [
            'accessories' => Accessory::with('variants')->latest()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.accessories.form', ['accessory' => new Accessory]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Accessory::create($this->validated($request));

        return redirect()->route('admin.accessories.index')->with('status', 'Accessory created.');
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
    public function edit(Accessory $accessory)
    {
        return view('admin.accessories.form', ['accessory' => $accessory]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Accessory $accessory)
    {
        $accessory->update($this->validated($request));

        return redirect()->route('admin.accessories.index')->with('status', 'Accessory updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accessory $accessory)
    {
        if (OrderItem::where('item_type', 'accessory')->where('item_id', $accessory->id)->exists()) {
            return back()->withErrors([
                'delete' => 'This accessory belongs to an order history. Set it to Hidden instead of deleting it.',
            ]);
        }

        DB::transaction(function () use ($accessory): void {
            $accessory->variants()->delete();
            $this->deleteLocalImage($accessory->image_url);
            $accessory->delete();
        });

        return redirect()->route('admin.accessories.index')->with('status', 'Accessory deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'category' => ['required', 'string', 'max:80'],
            'price' => ['required', 'integer', 'min:1'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'description' => ['nullable', 'string'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $accessory = $request->route('accessory');

        if (! $request->hasFile('image_file') && blank($data['image_url'] ?? null) && blank($accessory?->image_url)) {
            throw ValidationException::withMessages([
                'image_url' => 'Add an image file or a valid image URL.',
            ]);
        }

        if ($request->hasFile('image_file')) {
            $this->deleteLocalImage($accessory?->image_url);
            $data['image_url'] = Storage::url(
                $request->file('image_file')->store('products', 'public')
            );
        } elseif (blank($data['image_url'] ?? null)) {
            $data['image_url'] = $accessory?->image_url;
        }

        unset($data['image_file']);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function deleteLocalImage(?string $url): void
    {
        if ($url && Str::startsWith($url, '/storage/')) {
            Storage::disk('public')->delete(Str::after($url, '/storage/'));
        }
    }
}
