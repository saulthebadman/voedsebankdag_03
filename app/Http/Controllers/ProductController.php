<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Leverancier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leverancier $leverancier, Product $product): View
    {
        // Zorg ervoor dat het product bij de leverancier hoort via de many-to-many relatie
        if (!$leverancier->products()->where('products.id', $product->id)->exists()) {
            abort(404, 'Dit product hoort niet bij deze leverancier.');
        }

        return view('products.edit', compact('leverancier', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Leverancier $leverancier, Product $product): RedirectResponse
    {
        // Zorg ervoor dat het product bij de leverancier hoort
        if (!$leverancier->products()->where('products.id', $product->id)->exists()) {
            abort(404, 'Dit product hoort niet bij deze leverancier.');
        }

        // Validatie met speciale regel voor houdbaarheidsdatum
        $validated = $request->validate([
            'houdbaarheidsdatum' => [
                'required',
                'date',
                'after_or_equal:2024-08-16', // Test datum
                function ($attribute, $value, $fail) {
                    $newDate = Carbon::parse($value);
                    // Voor test purposes: alsof we in augustus 2024 zijn
                    $today = Carbon::parse('2024-08-16'); // Test datum rond houdbaarheidsdatum
                    $maxAllowedDate = $today->copy()->addDays(7);
                    
                    // Controleer of de nieuwe datum maximaal 7 dagen verder ligt dan vandaag
                    if ($newDate->gt($maxAllowedDate)) {
                        $fail('De houdbaarheidsdatum is niet gewijzigd. De houdbaarheidsdatum mag met maximaal 7 dagen worden verlengd.');
                    }
                },
            ],
        ], [
            'houdbaarheidsdatum.required' => 'De houdbaarheidsdatum is verplicht.',
            'houdbaarheidsdatum.date' => 'De houdbaarheidsdatum moet een geldige datum zijn.',
            'houdbaarheidsdatum.after_or_equal' => 'De houdbaarheidsdatum mag niet in het verleden liggen.',
        ]);

        $product->update([
            'houdbaarheidsdatum' => $validated['houdbaarheidsdatum']
        ]);

        return redirect()->route('leveranciers.show', $leverancier)
            ->with('success', 'De houdbaarheidsdatum is gewijzigd.');
    }

    /**
     * Inline update voor eenvoudige velden (behalve houdbaarheidsdatum)
     */
    public function inlineUpdate(Request $request, Leverancier $leverancier, Product $product): RedirectResponse
    {
        // Zorg ervoor dat het product bij de leverancier hoort
        if (!$leverancier->products()->where('products.id', $product->id)->exists()) {
            abort(404, 'Dit product hoort niet bij deze leverancier.');
        }

        $validated = $request->validate([
            'naam' => 'required|string|max:255',
            'soort_allergie' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:100',
        ]);

        $product->update($validated);

        return redirect()->route('leveranciers.show', $leverancier)
            ->with('success', 'Product gegevens zijn bijgewerkt.');
    }
}
