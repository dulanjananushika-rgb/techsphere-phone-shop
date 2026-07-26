<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use Illuminate\Http\Request;

class AccessoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.accessories.index', [
            'accessories' => Accessory::latest()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.accessories.form', ['accessory' => new Accessory()]);
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
        $accessory->delete();

        return redirect()->route('admin.accessories.index')->with('status', 'Accessory deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'category' => ['required', 'string', 'max:80'],
            'price' => ['required', 'integer', 'min:0'],
            'image_url' => ['required', 'url'],
            'description' => ['nullable', 'string'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);
    }
}
