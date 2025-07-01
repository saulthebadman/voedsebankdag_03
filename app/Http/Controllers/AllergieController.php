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
}
