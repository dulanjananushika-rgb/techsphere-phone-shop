<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use App\Models\Offer;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.offers.index', [
            'offers' => Offer::withCount(['phones', 'accessories'])->latest()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.offers.form', [
            'offer' => new Offer,
            'phones' => Phone::with('brand')->orderBy('name')->get(),
            'accessories' => Accessory::orderBy('name')->get(),
            'selectedPhones' => collect(),
            'selectedAccessories' => collect(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validated($request);
        $offer = Offer::create($data['offer']);
        $offer->phones()->sync($data['phone_ids']);
        $offer->accessories()->sync($data['accessory_ids']);

        return redirect()->route('admin.offers.index')->with('status', 'Offer created.');
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
    public function edit(Offer $offer)
    {
        $offer->load(['phones', 'accessories']);

        return view('admin.offers.form', [
            'offer' => $offer,
            'phones' => Phone::with('brand')->orderBy('name')->get(),
            'accessories' => Accessory::orderBy('name')->get(),
            'selectedPhones' => $offer->phones->pluck('id'),
            'selectedAccessories' => $offer->accessories->pluck('id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offer $offer)
    {
        $data = $this->validated($request);
        $offer->update($data['offer']);
        $offer->phones()->sync($data['phone_ids']);
        $offer->accessories()->sync($data['accessory_ids']);

        return redirect()->route('admin.offers.index')->with('status', 'Offer updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();

        return redirect()->route('admin.offers.index')->with('status', 'Offer deleted.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'discount_percentage' => ['required', 'integer', 'min:1', 'max:90'],
            'description' => ['nullable', 'string'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'phone_ids' => ['nullable', 'array'],
            'phone_ids.*' => ['integer', 'exists:phones,id'],
            'accessory_ids' => ['nullable', 'array'],
            'accessory_ids.*' => ['integer', 'exists:accessories,id'],
        ]);

        if (empty($validated['phone_ids']) && empty($validated['accessory_ids'])) {
            throw ValidationException::withMessages([
                'products' => 'Select at least one phone or accessory for this offer.',
            ]);
        }

        return [
            'offer' => collect($validated)->only(['title', 'discount_percentage', 'description', 'starts_at', 'ends_at'])->all(),
            'phone_ids' => $validated['phone_ids'] ?? [],
            'accessory_ids' => $validated['accessory_ids'] ?? [],
        ];
    }
}
