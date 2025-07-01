<?php

namespace App\Http\Controllers;

use App\Models\Leverancier;
use Illuminate\Http\Request;

class LeverancierController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('leverancier_type');
        $leveranciers = Leverancier::when($type, function($query, $type) {
            return $query->where('leverancier_type', $type);
        })->get();
        $types = ['Bedrijf', 'Instelling', 'Overheid', 'Particulier', 'Donor'];
        return view('leveranciers.index', compact('leveranciers', 'types', 'type'));
    }

    public function create()
    {
        $types = ['Bedrijf', 'Instelling', 'Overheid', 'Particulier', 'Donor'];
        return view('leveranciers.create', compact('types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'naam' => 'required',
            'contactpersoon' => 'nullable',
            'email' => 'nullable|email',
            'mobiel' => 'nullable',
            'leveranciernummer' => 'required|unique:leveranciers',
            'leverancier_type' => 'required',
        ]);
        Leverancier::create($data);
        return redirect()->route('leveranciers.index')->with('success', 'Leverancier toegevoegd!');
    }

    public function show(Leverancier $leverancier)
    {
        $leverancier->load('products');
        return view('leveranciers.show', compact('leverancier'));
    }

    public function edit(Leverancier $leverancier)
    {
        $types = ['Bedrijf', 'Instelling', 'Overheid', 'Particulier', 'Donor'];
        return view('leveranciers.edit', compact('leverancier', 'types'));
    }

    public function update(Request $request, Leverancier $leverancier)
    {
        $data = $request->validate([
            'naam' => 'required',
            'contactpersoon' => 'nullable',
            'email' => 'nullable|email',
            'mobiel' => 'nullable',
            'leveranciernummer' => 'required',
            'leverancier_type' => 'required',
        ]);
        $leverancier->update($data);
        return redirect()->route('leveranciers.index')->with('updated', 'Leverancier bijgewerkt!');
    }

    public function destroy(Leverancier $leverancier)
    {
        $leverancier->delete();
        return redirect()->route('leveranciers.index')->with('deleted', 'Leverancier is verwijderd');
    }
}
