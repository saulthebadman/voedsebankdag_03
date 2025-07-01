<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdatePersoonAllergieRequest;
use App\Models\Allergie;
use App\Models\Gezin;
use App\Models\Persoon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class AllergieController extends Controller
{

    public function index()
    {
        try {
            // Gebruik Eloquent om gezinnen met allergieën op te halen
            $gezinnenMetAllergieen = Gezin::whereHas('personen.allergieen')
                ->with(['personen.allergieen'])
                ->orderBy('naam')
                ->get()
                ->map(function($gezin) {
                    // Bereken statistieken
                    $personenMetAllergieen = $gezin->personen->filter(function($persoon) {
                        return $persoon->allergieen->isNotEmpty();
                    });
                    
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

            // Haal alle allergieën op voor de dropdown
            $allergieen = Allergie::orderBy('naam')->get();

            // Log successful action
            Log::info('Allergie index page accessed successfully', [
                'gezinnen_count' => $gezinnenMetAllergieen->count(),
                'allergieen_count' => $allergieen->count(),
                'user_id' => auth()->id()
            ]);

            return view('allergieen.index', compact('gezinnenMetAllergieen', 'allergieen'));

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
     * Filter gezinnen op specifieke allergie
     * Gebruikt stored procedure voor geoptimaliseerde filtering
     */
    public function filterByAllergie(Request $request)
    {
        try {
            // Valideer input
            $request->validate([
                'allergie_id' => 'required|integer|exists:allergie,id,is_actief,1'
            ], [
                'allergie_id.required' => 'Selecteer een allergie om te filteren.',
                'allergie_id.integer' => 'Ongeldige allergie geselecteerd.',
                'allergie_id.exists' => 'De geselecteerde allergie bestaat niet of is niet actief.'
            ]);

            $allergieId = $request->get('allergie_id');

            $allergie = Allergie::findOrFail($allergieId);
            
            // Probeer stored procedure te gebruiken
            try {
                $gezinnenData = DB::select('CALL FilterGezinnenByAllergie(?)', [$allergieId]);
                
                $gezinnenMetAllergie = collect($gezinnenData)->map(function($gezin) {
                    return (object) [
                        'id' => $gezin->id,
                        'naam' => $gezin->naam,
                        'code' => $gezin->code ?? 'GZ' . str_pad($gezin->id, 3, '0', STR_PAD_LEFT),
                        'adres' => $gezin->adres,
                        'postcode' => $gezin->postcode,
                        'woonplaats' => $gezin->woonplaats,
                        'telefoon' => $gezin->telefoon ?? '',
                        'email' => $gezin->email ?? '',
                        'allergie_naam' => $gezin->allergie_naam,
                        'aantal_personen_met_allergie' => $gezin->aantal_personen_met_allergie,
                        // Extra properties die de view verwacht
                        'omschrijving' => "Gezin met {$gezin->aantal_personen_met_allergie} personen met deze allergie",
                        'aantal_volwassenen' => $gezin->aantal_personen_met_allergie ?? 0,
                        'aantal_kinderen' => 0,
                        'aantal_babys' => 0,
                        // Simuleer personen collectie - voor nu leeg, later uit database halen
                        'personen' => collect([])
                    ];
                });
                
                Log::info('Using stored procedure for allergie filter', [
                    'procedure' => 'FilterGezinnenByAllergie',
                    'allergie_id' => $allergieId,
                    'user_id' => auth()->id()
                ]);
                
            } catch (Exception $spException) {
                // Fallback to Eloquent
                Log::warning('Stored procedure failed for filter, using Eloquent fallback', [
                    'error' => $spException->getMessage(),
                    'allergie_id' => $allergieId,
                    'user_id' => auth()->id()
                ]);
                
                $gezinnenMetAllergie = Gezin::whereHas('personen.allergieen', function($query) use ($allergieId) {
                    $query->where('allergie_id', $allergieId);
                })
                ->with(['personen' => function($query) use ($allergieId) {
                    $query->whereHas('allergieen', function($subQuery) use ($allergieId) {
                        $subQuery->where('allergie_id', $allergieId);
                    })->with('allergieen');
                }])
                ->orderBy('naam')
                ->get()
                ->map(function($gezin) use ($allergie) {
                    // Filter personen die deze specifieke allergie hebben
                    $personenMetAllergie = $gezin->personen->filter(function($persoon) use ($allergie) {
                        return $persoon->allergieen->contains('id', $allergie->id);
                    });
                    
                    return (object) [
                        'id' => $gezin->id,
                        'naam' => $gezin->naam,
                        'code' => $gezin->code ?? 'GZ' . str_pad($gezin->id, 3, '0', STR_PAD_LEFT),
                        'adres' => $gezin->adres,
                        'postcode' => $gezin->postcode,
                        'woonplaats' => $gezin->woonplaats,
                        'telefoon' => $gezin->telefoon ?? '',
                        'email' => $gezin->email ?? '',
                        'allergie_naam' => $allergie->naam,
                        'aantal_personen_met_allergie' => $personenMetAllergie->count(),
                        'omschrijving' => "Gezin met {$personenMetAllergie->count()} personen met {$allergie->naam}",
                        'aantal_volwassenen' => $personenMetAllergie->count(),
                        'aantal_kinderen' => 0,
                        'aantal_babys' => 0,
                        'personen' => $personenMetAllergie
                    ];
                });
            }

            // Haal alle allergieën op voor de dropdown
            $allergieen = Allergie::orderBy('naam')->get();

            // Check of er gezinnen gevonden zijn
            $bericht = null;
            if ($gezinnenMetAllergie->isEmpty()) {
                $bericht = "Er zijn geen gezinnen bekend die de geselecteerde allergie hebben";
            }

            // Log successful filter action
            Log::info('Allergie filter applied successfully', [
                'allergie_id' => $allergieId,
                'allergie_naam' => $allergie->naam,
                'gezinnen_found' => $gezinnenMetAllergie->count(),
                'user_id' => auth()->id()
            ]);

            return view('allergieen.filter', compact('gezinnenMetAllergie', 'allergieen', 'allergie', 'bericht'));

        } catch (Exception $e) {
            // Log error details
            Log::error('Error filtering allergieen', [
                'allergie_id' => $allergieId ?? null,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id()
            ]);

            return redirect()->route('allergieen.index')
                ->with('error', 'Er is een fout opgetreden bij het filteren. Probeer het opnieuw.');
        }
    }

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

    /**
     * Toon edit form voor een specifieke allergie van een persoon (Wireframe-04)
     */
    public function editPersoonAllergie($persoonId, $allergieId)
    {
        try {
            $persoon = Persoon::with(['allergieen', 'gezin'])->findOrFail($persoonId);
            $huidigeAllergie = $persoon->allergieen->firstWhere('id', $allergieId);
            
            if (!$huidigeAllergie) {
                Log::warning('Allergie not found for person', [
                    'persoon_id' => $persoonId,
                    'allergie_id' => $allergieId,
                    'user_id' => auth()->id()
                ]);
                return redirect()->back()->with('error', 'Allergie niet gevonden voor deze persoon.');
            }

            // Check voor hoog anafylactisch risico (Scenario 2 - Wireframe-06)
            $waarschuwing = null;
            if ($huidigeAllergie->anafylactisch_risico === 'hoog') {
                $waarschuwing = "Voor het wijzigen van deze allergie wordt geadviseerd eerst een arts te raadplegen vanwege een hoog risico op een anafylactisch shock";
                
                Log::info('High risk allergie edit accessed', [
                    'persoon_id' => $persoonId,
                    'allergie_id' => $allergieId,
                    'allergie_naam' => $huidigeAllergie->naam,
                    'user_id' => auth()->id()
                ]);
            }

            $allergieen = Allergie::orderBy('naam')->get();

            return view('allergieen.edit-persoon-allergie', compact('persoon', 'huidigeAllergie', 'allergieen', 'waarschuwing'));

        } catch (Exception $e) {
            // Log error details
            Log::error('Error loading edit allergie form', [
                'persoon_id' => $persoonId,
                'allergie_id' => $allergieId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Er is een fout opgetreden bij het laden van de wijzig pagina.');
        }
    }

    /**
     * Update allergie voor een persoon (Wireframe-05)
     * Gebruikt stored procedure voor geoptimaliseerde database operaties
     */
    public function updatePersoonAllergie(UpdatePersoonAllergieRequest $request, $persoonId, $allergieId)
    {
        try {
            // Gevalideerde data is al beschikbaar via FormRequest
            $validatedData = $request->validated();

            $persoon = Persoon::with(['allergieen', 'gezin'])->findOrFail($persoonId);
            $nieuweAllergieId = $validatedData['nieuwe_allergie_id'];
            
            // Extra controle: bestaat de oude allergie relatie?
            $oudAllergie = $persoon->allergieen()->where('allergie_id', $allergieId)->first();
            if (!$oudAllergie) {
                Log::warning('Old allergie relationship not found', [
                    'persoon_id' => $persoonId,
                    'allergie_id' => $allergieId,
                    'user_id' => auth()->id()
                ]);
                return redirect()->back()->with('error', 'Huidige allergie relatie niet gevonden.');
            }

            // Controleer of de persoon de nieuwe allergie al heeft (dubbele controle)
            if ($persoon->allergieen()->where('allergie_id', $nieuweAllergieId)->exists()) {
                Log::warning('Duplicate allergie attempt', [
                    'persoon_id' => $persoonId,
                    'existing_allergie_id' => $allergieId,
                    'nieuwe_allergie_id' => $nieuweAllergieId,
                    'user_id' => auth()->id()
                ]);
                return redirect()->back()->with('error', 'Deze persoon heeft deze allergie al.');
            }

            // Probeer stored procedure te gebruiken
            try {
                $result = DB::select('CALL UpdatePersoonAllergie(?, ?, ?, ?, ?, @success_message, @error_message)', [
                    $persoonId,
                    $allergieId,
                    $nieuweAllergieId,
                    'middel', // Default ernst
                    'Gewijzigd via systeem'
                ]);

                // Haal output parameters op
                $output = DB::select('SELECT @success_message as success_message, @error_message as error_message')[0];

                if ($output->error_message) {
                    Log::warning('Stored procedure returned error', [
                        'error_message' => $output->error_message,
                        'persoon_id' => $persoonId,
                        'allergie_id' => $allergieId,
                        'nieuwe_allergie_id' => $nieuweAllergieId,
                        'user_id' => auth()->id()
                    ]);
                    return redirect()->back()->with('error', $output->error_message);
                }

                // Haal allergie informatie op voor succesmelding
                $nieuweAllergie = Allergie::findOrFail($nieuweAllergieId);
                $oudeAllergie = Allergie::findOrFail($allergieId);

                Log::info('Allergie successfully updated via stored procedure', [
                    'persoon_id' => $persoonId,
                    'persoon_naam' => $persoon->volledige_naam,
                    'oude_allergie' => $oudeAllergie->naam,
                    'nieuwe_allergie' => $nieuweAllergie->naam,
                    'gezin_id' => $persoon->gezin_id,
                    'procedure' => 'UpdatePersoonAllergie',
                    'user_id' => auth()->id()
                ]);

                return redirect()->route('allergieen.gezin-details', $persoon->gezin_id)
                               ->with('success', $output->success_message ?: 'De allergie is succesvol gewijzigd.');

            } catch (Exception $spException) {
                // Fallback to Eloquent if stored procedure fails
                Log::warning('Stored procedure failed for update, using Eloquent fallback', [
                    'error' => $spException->getMessage(),
                    'persoon_id' => $persoonId,
                    'allergie_id' => $allergieId,
                    'nieuwe_allergie_id' => $nieuweAllergieId,
                    'user_id' => auth()->id()
                ]);

                // Database transactie voor data integriteit (Eloquent fallback)
                DB::beginTransaction();

                // Haal allergie informatie op voor logging
                $nieuweAllergie = Allergie::findOrFail($nieuweAllergieId);
                $oudeAllergie = Allergie::findOrFail($allergieId);

                // Verwijder oude allergie en voeg nieuwe toe (binnen transactie)
                $persoon->allergieen()->detach($allergieId);
                $persoon->allergieen()->attach($nieuweAllergieId, [
                    'ernst' => 'middel', // Default ernst
                    'opmerking' => 'Gewijzigd van ' . $oudeAllergie->naam . ' naar ' . $nieuweAllergie->naam,
                    'datum_vastgesteld' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Commit transactie
                DB::commit();

                // Log succesvolle wijziging
                Log::info('Allergie successfully updated via Eloquent fallback', [
                    'persoon_id' => $persoonId,
                    'persoon_naam' => $persoon->volledige_naam,
                    'oude_allergie' => $oudeAllergie->naam,
                    'nieuwe_allergie' => $nieuweAllergie->naam,
                    'gezin_id' => $persoon->gezin_id,
                    'user_id' => auth()->id()
                ]);

                return redirect()->route('allergieen.gezin-details', $persoon->gezin_id)
                               ->with('success', 'De allergie is succesvol gewijzigd van "' . $oudeAllergie->naam . '" naar "' . $nieuweAllergie->naam . '".');
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors - laat Laravel deze afhandelen
            DB::rollBack();
            throw $e;

        } catch (Exception $e) {
            // Rollback database transactie
            DB::rollBack();
            
            // Log fout details
            Log::error('Error updating allergie', [
                'persoon_id' => $persoonId,
                'allergie_id' => $allergieId,
                'nieuwe_allergie_id' => $request->nieuwe_allergie_id ?? null,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Er is een fout opgetreden bij het wijzigen van de allergie. Probeer het opnieuw.')
                ->withInput();
        }
    }

    /**
     * Haal allergie statistieken op voor dashboard
     * Gebruikt stored procedure voor geoptimaliseerde queries
     */
    public function getStatistieken()
    {
        try {
            $stats = DB::select('CALL GetAllergieStatistieken()')[0];
            
            Log::info('Allergie statistics retrieved via stored procedure', [
                'totaal_allergieen' => $stats->totaal_allergieen,
                'gezinnen_met_allergieen' => $stats->gezinnen_met_allergieen,
                'personen_met_allergieen' => $stats->personen_met_allergieen,
                'user_id' => auth()->id()
            ]);
            
            return $stats;
            
        } catch (Exception $e) {
            Log::error('Error getting allergie statistics', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id()
            ]);
            
            // Fallback to manual counting
            return (object) [
                'totaal_allergieen' => Allergie::where('is_actief', true)->count(),
                'gezinnen_met_allergieen' => Gezin::whereHas('personen.allergieen')->count(),
                'personen_met_allergieen' => Persoon::whereHas('allergieen')->count(),
                'totaal_allergie_registraties' => DB::table('allergie_per_persoon')->count(),
                'hoog_risico_allergieen' => DB::table('allergie_per_persoon')
                    ->join('allergie', 'allergie_per_persoon.allergie_id', '=', 'allergie.id')
                    ->where('allergie.ernst_niveau', 'hoog')
                    ->count()
            ];
        }
    }

    /**
     * Haal populaire allergieën op
     * Gebruikt stored procedure voor geoptimaliseerde queries
     */
    public function getPopulaireAllergieen($limiet = 10)
    {
        try {
            $populaireAllergieen = DB::select('CALL GetPopulaireAllergien(?)', [$limiet]);
            
            Log::info('Popular allergies retrieved via stored procedure', [
                'limiet' => $limiet,
                'aantal_gevonden' => count($populaireAllergieen),
                'user_id' => auth()->id()
            ]);
            
            return $populaireAllergieen;
            
        } catch (Exception $e) {
            Log::error('Error getting popular allergies', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id()
            ]);
            
            // Fallback to Eloquent
            return Allergie::withCount('personen')
                ->having('personen_count', '>', 0)
                ->orderBy('personen_count', 'desc')
                ->take($limiet)
                ->get();
        }
    }

    /**
     * API endpoint voor statistieken (voor AJAX calls)
     */
    public function apiStatistieken()
    {
        try {
            $stats = $this->getStatistieken();
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (Exception $e) {
            Log::error('Error in API statistics endpoint', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Er is een fout opgetreden bij het ophalen van statistieken.'
            ], 500);
        }
    }
}
