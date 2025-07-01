<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Allergie;
use App\Models\Gezin;
use App\Models\Persoon;

class AllergieController extends Controller
{
    /**
     * Toon overzicht van alle gezinnen met allergieën
     */
    public function index()
    {
        // Haal alle gezinnen op die personen hebben met allergieën
        $gezinnenMetAllergieen = Gezin::whereHas('personen.allergieen')
            ->with(['personen.allergieen'])
            ->orderBy('naam')
            ->get();

        // Haal alle allergieën op voor de dropdown
        $allergieen = Allergie::orderBy('naam')->get();

        return view('allergieen.index', compact('gezinnenMetAllergieen', 'allergieen'));
    }

    /**
     * Filter gezinnen op specifieke allergie
     */
    public function filterByAllergie(Request $request)
    {
        $allergieId = $request->get('allergie_id');
        
        if (!$allergieId) {
            return redirect()->route('allergieen.index');
        }

        $allergie = Allergie::findOrFail($allergieId);
        
        // Zoek gezinnen die personen hebben met de geselecteerde allergie
        $gezinnenMetAllergie = Gezin::whereHas('personen.allergieen', function($query) use ($allergieId) {
            $query->where('allergie_id', $allergieId);
        })
        ->with(['personen' => function($query) use ($allergieId) {
            $query->whereHas('allergieen', function($subQuery) use ($allergieId) {
                $subQuery->where('allergie_id', $allergieId);
            })->with('allergieen');
        }])
        ->orderBy('naam')
        ->get();

        // Haal alle allergieën op voor de dropdown
        $allergieen = Allergie::orderBy('naam')->get();

        // Check of er gezinnen gevonden zijn
        $bericht = null;
        if ($gezinnenMetAllergie->isEmpty()) {
            $bericht = "Er zijn geen gezinnen bekend die de geselecteerde allergie hebben";
        }

        return view('allergieen.filter', compact('gezinnenMetAllergie', 'allergieen', 'allergie', 'bericht'));
    }

    /**
     * Toon allergieën detail pagina voor een specifiek gezin (Wireframe-03)
     */
    public function showGezinDetails($gezinId)
    {
        $gezin = Gezin::with(['personen.allergieen'])->findOrFail($gezinId);
        
        // Alleen personen met allergieën tonen
        $personenMetAllergieen = $gezin->personen->filter(function($persoon) {
            return $persoon->allergieen->isNotEmpty();
        });

        return view('allergieen.gezin-details', compact('gezin', 'personenMetAllergieen'));
    }

    /**
     * Toon edit form voor een specifieke allergie van een persoon (Wireframe-04)
     */
    public function editPersoonAllergie($persoonId, $allergieId)
    {
        $persoon = Persoon::with(['allergieen', 'gezin'])->findOrFail($persoonId);
        $huidigeAllergie = $persoon->allergieen->firstWhere('id', $allergieId);
        
        if (!$huidigeAllergie) {
            return redirect()->back()->with('error', 'Allergie niet gevonden voor deze persoon.');
        }

        // Check voor hoog anafylactisch risico (Scenario 2 - Wireframe-06)
        $waarschuwing = null;
        if ($huidigeAllergie->anafylactisch_risico === 'hoog') {
            $waarschuwing = "Voor het wijzigen van deze allergie wordt geadviseerd eerst een arts te raadplegen vanwege een hoog risico op een anafylactisch shock";
        }

        $allergieen = Allergie::orderBy('naam')->get();

        return view('allergieen.edit-persoon-allergie', compact('persoon', 'huidigeAllergie', 'allergieen', 'waarschuwing'));
    }

    /**
     * Update allergie voor een persoon (Wireframe-05)
     */
    public function updatePersoonAllergie(Request $request, $persoonId, $allergieId)
    {
        $request->validate([
            'nieuwe_allergie_id' => 'required|exists:allergies,id'
        ]);

        $persoon = Persoon::findOrFail($persoonId);
        $nieuweAllergieId = $request->nieuwe_allergie_id;

        // Controleer of de persoon de nieuwe allergie al heeft
        if ($persoon->allergieen()->where('allergie_id', $nieuweAllergieId)->exists()) {
            return redirect()->back()->with('error', 'Deze persoon heeft deze allergie al.');
        }

        // Verwijder oude allergie en voeg nieuwe toe
        $persoon->allergieen()->detach($allergieId);
        $persoon->allergieen()->attach($nieuweAllergieId, [
            'is_actief' => true,
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now()
        ]);

        return redirect()->route('allergieen.gezin-details', $persoon->gezin_id)
                       ->with('success', 'De wijziging is doorgevoerd');
    }
}
