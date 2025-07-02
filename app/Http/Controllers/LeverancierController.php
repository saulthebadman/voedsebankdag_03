<?php

namespace App\Http\Controllers;

use App\Models\Leverancier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LeverancierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Leverancier::with(['products' => function($query) {
            $query->take(5);
        }, 'contacts']);

        // Filter op leverancier type
        if ($request->filled('leverancier_type')) {
            $query->where('leverancier_type', $request->leverancier_type);
        }

        // Zoeken op naam
        if ($request->filled('search')) {
            $query->where('naam', 'like', '%' . $request->search . '%');
        }

        // Filter op status
        if ($request->filled('status') && $request->status !== 'alle') {
            $isActief = $request->status === 'actief';
            $query->where('is_actief', $isActief);
        }

        $leveranciers = $query->get();

        $statistics = [
            'total_leveranciers' => Leverancier::count(),
            'total_producten' => \App\Models\Product::count(),
            'actieve_leveranciers' => Leverancier::where('is_actief', true)->count(),
            'inactieve_leveranciers' => Leverancier::where('is_actief', false)->count(),
        ];

        return view('leveranciers.index', compact('leveranciers', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $types = ['Bedrijf', 'Instelling', 'Overheid', 'Particulier', 'Donor'];
        return view('leveranciers.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'naam' => 'required|string|max:255',
            'contactpersoon' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'mobiel' => 'nullable|string|max:255',
            'leveranciernummer' => 'required|string|max:50|unique:leveranciers,leverancier_nummer',
            'leverancier_type' => 'required|in:Bedrijf,Instelling,Overheid,Particulier,Donor',
            'is_actief' => 'boolean',
            'opmerking' => 'nullable|string|max:255',
        ], [
            'naam.required' => 'De naam is verplicht.',
            'leveranciernummer.required' => 'Het leveranciernummer is verplicht.',
            'leveranciernummer.unique' => 'Dit leveranciernummer bestaat al.',
            'leverancier_type.required' => 'Het leveranciertype is verplicht.',
            'leverancier_type.in' => 'Het geselecteerde leveranciertype is ongeldig.',
            'email.email' => 'Het email adres moet geldig zijn.',
        ]);

        // Map form fields to database columns
        $data = [
            'naam' => $validated['naam'],
            'contact_persoon' => $validated['contactpersoon'] ?? null,
            'email' => $validated['email'] ?? null,
            'mobiel' => $validated['mobiel'] ?? null,
            'leverancier_nummer' => $validated['leveranciernummer'],
            'leverancier_type' => $validated['leverancier_type'],
            'is_actief' => true, // Default to true for new suppliers
            'opmerking' => $validated['opmerking'] ?? null,
        ];

        Leverancier::create($data);

        return redirect()->route('leveranciers.index')
            ->with('success', 'Leverancier succesvol aangemaakt.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Leverancier $leverancier): View
    {
        $leverancier->load('products');
        
        return view('leveranciers.show', compact('leverancier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leverancier $leverancier): View
    {
        $types = ['Bedrijf', 'Instelling', 'Overheid', 'Particulier', 'Donor'];
        return view('leveranciers.edit', compact('leverancier', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Leverancier $leverancier): RedirectResponse
    {
        $validated = $request->validate([
            'naam' => 'required|string|max:255',
            'contact_persoon' => 'nullable|string|max:255',
            'leverancier_nummer' => 'nullable|string|max:50|unique:leveranciers,leverancier_nummer,' . $leverancier->id,
            'leverancier_type' => 'nullable|in:Bedrijf,Instelling,Overheid,Particulier,Donor',
            'is_actief' => 'boolean',
            'opmerking' => 'nullable|string|max:255',
        ]);

        $validated['is_actief'] = $request->has('is_actief') ? true : false;

        $leverancier->update($validated);

        return redirect()->route('leveranciers.index')
            ->with('success', 'Leverancier succesvol bijgewerkt.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leverancier $leverancier): RedirectResponse
    {
        $leverancier->delete();

        return redirect()->route('leveranciers.index')
            ->with('success', 'Leverancier succesvol verwijderd.');
    }
}
