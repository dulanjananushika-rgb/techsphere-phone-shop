<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PhoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.phones.index', [
            'phones' => Phone::with('brand')->latest()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.phones.form', [
            'phone' => new Phone(),
            'brands' => Brand::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Phone::create($this->validated($request));

        return redirect()->route('admin.phones.index')->with('status', 'Phone created.');
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
    public function edit(Phone $phone)
    {
        return view('admin.phones.form', [
            'phone' => $phone,
            'brands' => Brand::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Phone $phone)
    {
        $phone->update($this->validated($request, $phone));

        return redirect()->route('admin.phones.index')->with('status', 'Phone updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Phone $phone)
    {
        $phone->delete();

        return redirect()->route('admin.phones.index')->with('status', 'Phone deleted.');
    }

    private function validated(Request $request, ?Phone $phone = null): array
    {
        $data = $request->validate([
            'brand_id' => ['required', 'exists:brands,id'],
            'name' => ['required', 'string', 'max:180'],
            'price' => ['required', 'integer', 'min:0'],
            'old_price' => ['nullable', 'integer', 'min:0'],
            'image_url' => ['required', 'url'],
            'ram' => ['required', 'string', 'max:50'],
            'storage' => ['required', 'string', 'max:50'],
            'display' => ['nullable', 'string', 'max:180'],
            'processor' => ['nullable', 'string', 'max:180'],
            'camera' => ['nullable', 'string', 'max:180'],
            'battery' => ['nullable', 'string', 'max:180'],
            'os' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = Str::slug($data['name']);
        $baseSlug = $data['slug'];
        $counter = 2;

        while (Phone::where('slug', $data['slug'])->when($phone, fn ($query) => $query->whereKeyNot($phone->id))->exists()) {
            $data['slug'] = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $data['is_featured'] = $request->boolean('is_featured');

        return $data;
    }
}
