<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Allergie;
use App\Models\Gezin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class AllergieController extends Controller
{
    /**
     * Toon overzicht van alle gezinnen met allergieën
     */
    public function index(Request $request)
    {
        try {
            $allergieId = $request->get('allergie_id');
            $geselecteerdeAllergie = null;
            $bericht = null;

            if ($allergieId) {
                // Filter op specifieke allergie
                $geselecteerdeAllergie = Allergie::find($allergieId);
                
                if ($geselecteerdeAllergie) {
                    $gezinnenMetAllergieen = $this->getGezinnenMetAllergie($allergieId);

                    if ($gezinnenMetAllergieen->isEmpty()) {
                        $bericht = "Er zijn geen gezinnen bekend die de geselecteerde allergie hebben";
                    }
                } else {
                    $gezinnenMetAllergieen = collect([]);
                    $bericht = "De geselecteerde allergie bestaat niet";
                }
            } else {
                // Toon alle gezinnen met allergieën
                $gezinnenMetAllergieen = $this->getAlleGezinnenMetAllergieen();
            }

            // Haal alle allergieën op voor de dropdown
            $allergieen = Allergie::orderBy('naam')->get();

            // Log successful action
            Log::info('Allergie index page accessed successfully', [
                'gezinnen_count' => $gezinnenMetAllergieen->count(),
                'allergieen_count' => $allergieen->count(),
                'filter_allergie_id' => $allergieId,
                'user_id' => auth()->id()
            ]);

            return view('allergieen.index', compact('gezinnenMetAllergieen', 'allergieen', 'geselecteerdeAllergie', 'bericht'));

        } catch (Exception $e) {
            // Log error details
            Log::error('Error loading allergie index page', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Er is een fout opgetreden bij het laden van de allergieën. Probeer het opnieuw.');
        }
    }



    /**
     * Private helper: Haal gezinnen met specifieke allergie op
     */
    private function getGezinnenMetAllergie($allergieId)
    {
        return Gezin::whereHas('personen.allergieen', function($query) use ($allergieId) {
            $query->where('allergie_id', $allergieId);
        })
        ->with(['personen' => function($query) use ($allergieId) {
            $query->whereHas('allergieen', function($subQuery) use ($allergieId) {
                $subQuery->where('allergie_id', $allergieId);
            })->with('allergieen');
        }])
        ->orderBy('naam')
        ->get()
        ->map(function($gezin) {
            return (object) [
                'id' => $gezin->id,
                'naam' => $gezin->naam,
                'code' => $gezin->code ?? 'GZ' . str_pad($gezin->id, 3, '0', STR_PAD_LEFT),
                'adres' => $gezin->adres ?? '',
                'postcode' => $gezin->postcode ?? '',
                'woonplaats' => $gezin->woonplaats ?? '',
                'telefoon' => $gezin->telefoon ?? '',
                'email' => $gezin->email ?? '',
                'aantal_personen' => $gezin->aantal_personen ?? $gezin->personen->count(),
                'omschrijving' => $gezin->omschrijving ?? "Gezin met " . $gezin->personen->count() . " personen",
                'aantal_volwassenen' => $gezin->aantal_volwassenen ?? 0,
                'aantal_kinderen' => $gezin->aantal_kinderen ?? 0,
                'aantal_babys' => $gezin->aantal_babys ?? 0,
                'personen' => $gezin->personen
            ];
        });
    }

    /**
     * Private helper: Haal alle gezinnen met allergieën op
     */
    private function getAlleGezinnenMetAllergieen()
    {
        return Gezin::whereHas('personen.allergieen')
            ->with(['personen.allergieen'])
            ->orderBy('naam')
            ->get()
            ->map(function($gezin) {
                return (object) [
                    'id' => $gezin->id,
                    'naam' => $gezin->naam,
                    'code' => $gezin->code ?? 'GZ' . str_pad($gezin->id, 3, '0', STR_PAD_LEFT),
                    'adres' => $gezin->adres ?? '',
                    'postcode' => $gezin->postcode ?? '',
                    'woonplaats' => $gezin->woonplaats ?? '',
                    'telefoon' => $gezin->telefoon ?? '',
                    'email' => $gezin->email ?? '',
                    'aantal_personen' => $gezin->aantal_personen ?? $gezin->personen->count(),
                    'aantal_allergie_registraties' => $gezin->personen->sum(function($persoon) {
                        return $persoon->allergieen->count();
                    }),
                    'omschrijving' => $gezin->omschrijving ?? "Gezin met " . $gezin->personen->count() . " personen",
                    'aantal_volwassenen' => $gezin->aantal_volwassenen ?? 0,
                    'aantal_kinderen' => $gezin->aantal_kinderen ?? 0,
                    'aantal_babys' => $gezin->aantal_babys ?? 0,
                    'personen' => $gezin->personen
                ];
            });
    }


}
