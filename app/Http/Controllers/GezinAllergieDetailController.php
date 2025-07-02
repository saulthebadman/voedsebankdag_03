<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Allergie;
use App\Models\Gezin;
use App\Models\Persoon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class GezinAllergieDetailController extends Controller
{
    /**
     * Toon allergieën detail pagina voor een specifiek gezin (Wireframe-03)
     * Gebruikt stored procedure voor geoptimaliseerde data ophaling
     */
    public function showGezinDetails($gezinId)
    {
        try {
            // Probeer stored procedure te gebruiken
            try {
                $allergieDetailsData = DB::select('CALL GetGezinAllergieDetails(?)', [$gezinId]);
                
                // Controleer of gezin bestaat
                $gezin = Gezin::findOrFail($gezinId);
                
                // Groepeer de data per persoon
                $personenMetAllergieen = collect($allergieDetailsData)->groupBy('persoon_id')->map(function($persoonAllergieen, $persoonId) {
                    $eerstePersoon = $persoonAllergieen->first();
                    return (object) [
                        'id' => $persoonId,
                        'volledige_naam' => $eerstePersoon->volledige_naam ?? 'Onbekende naam',
                        'voornaam' => $eerstePersoon->voornaam ?? '',
                        'tussenvoegsel' => $eerstePersoon->tussenvoegsel ?? '',
                        'achternaam' => $eerstePersoon->achternaam ?? '',
                        'geboortedatum' => isset($eerstePersoon->geboortedatum) ? \Carbon\Carbon::parse($eerstePersoon->geboortedatum) : null,
                        'geslacht' => $eerstePersoon->geslacht ?? 'onbekend',
                        'type_persoon' => $eerstePersoon->type_persoon ?? 'Klant',
                        'is_vertegenwoordiger' => $eerstePersoon->is_vertegenwoordiger ?? false,
                        'allergieen' => $persoonAllergieen->map(function($allergie) {
                            return (object) [
                                'id' => $allergie->allergie_id,
                                'naam' => $allergie->allergie_naam ?? 'Onbekende allergie',
                                'beschrijving' => $allergie->allergie_beschrijving ?? '',
                                'omschrijving' => $allergie->allergie_omschrijving ?? '',
                                'ernst_niveau' => $allergie->ernst_niveau ?? 'middel',
                                'anafylactisch_risico' => $allergie->anafylactisch_risico ?? 'laag',
                                'pivot' => (object) [
                                    'ernst' => $allergie->persoonlijke_ernst ?? 'middel',
                                    'opmerking' => $allergie->opmerking ?? '',
                                    'datum_vastgesteld' => $allergie->datum_vastgesteld ?? null,
                                    'created_at' => $allergie->created_at ?? null
                                ]
                            ];
                        })
                    ];
                });
                
                Log::info('Using stored procedure for gezin allergie details', [
                    'procedure' => 'GetGezinAllergieDetails',
                    'gezin_id' => $gezinId,
                    'user_id' => auth()->id()
                ]);
                
            } catch (Exception $spException) {
                // Fallback to Eloquent
                Log::warning('Stored procedure failed for gezin details, using Eloquent fallback', [
                    'error' => $spException->getMessage(),
                    'gezin_id' => $gezinId,
                    'user_id' => auth()->id()
                ]);
                
                $gezin = Gezin::with(['personen.allergieen'])->findOrFail($gezinId);
                
                // Map Eloquent data to match stored procedure structure
                $personenMetAllergieen = $gezin->personen->filter(function($persoon) {
                    return $persoon->allergieen->isNotEmpty();
                })->mapWithKeys(function($persoon) {
                    return [
                        $persoon->id => (object) [
                            'id' => $persoon->id,
                            'volledige_naam' => $persoon->volledige_naam ?? trim($persoon->voornaam . ' ' . $persoon->tussenvoegsel . ' ' . $persoon->achternaam),
                            'voornaam' => $persoon->voornaam ?? '',
                            'tussenvoegsel' => $persoon->tussenvoegsel ?? '',
                            'achternaam' => $persoon->achternaam ?? '',
                            'geboortedatum' => $persoon->geboortedatum ? \Carbon\Carbon::parse($persoon->geboortedatum) : null,
                            'geslacht' => $persoon->geslacht ?? 'onbekend',
                            'type_persoon' => $persoon->type_persoon ?? 'Klant',
                            'is_vertegenwoordiger' => $persoon->is_vertegenwoordiger ?? false,
                            'allergieen' => $persoon->allergieen->map(function($allergie) {
                                return (object) [
                                    'id' => $allergie->id,
                                    'naam' => $allergie->naam ?? 'Onbekende allergie',
                                    'beschrijving' => $allergie->beschrijving ?? '',
                                    'omschrijving' => $allergie->omschrijving ?? '',
                                    'ernst_niveau' => $allergie->ernst_niveau ?? 'middel',
                                    'anafylactisch_risico' => $allergie->anafylactisch_risico ?? 'laag',
                                    'pivot' => (object) [
                                        'ernst' => $allergie->pivot->ernst ?? 'middel',
                                        'opmerking' => $allergie->pivot->opmerking ?? '',
                                        'datum_vastgesteld' => $allergie->pivot->datum_vastgesteld ?? null,
                                        'created_at' => $allergie->pivot->created_at ?? null
                                    ]
                                ];
                            })
                        ]
                    ];
                });
            }

            // Log successful access
            Log::info('Gezin details accessed', [
                'gezin_id' => $gezinId,
                'gezin_naam' => $gezin->naam,
                'personen_met_allergieen' => $personenMetAllergieen->count(),
                'user_id' => auth()->id()
            ]);

            return view('allergieen.gezin-details', compact('gezin', 'personenMetAllergieen'));

        } catch (Exception $e) {
            // Log error details
            Log::error('Error loading gezin details', [
                'gezin_id' => $gezinId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id()
            ]);

            return redirect()->route('allergieen.index')
                ->with('error', 'Gezin niet gevonden of er is een fout opgetreden.');
        }
    }
}
