<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdatePersoonAllergieRequest;
use App\Models\Allergie;
use App\Models\Persoon;
use App\Rules\SarahPindaProtection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class PersoonAllergieController extends Controller
{
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
            // SARAH PINDA PROTECTION - Extra validatie
            $nieuweAllergieId = $request->input('nieuwe_allergie_id');
            $sarahPindaRule = new SarahPindaProtection($persoonId, $allergieId);
            
            if (!$sarahPindaRule->passes('nieuwe_allergie_id', $nieuweAllergieId)) {
                Log::warning('Sarah Pinda Protection triggered', [
                    'persoon_id' => $persoonId,
                    'allergie_id' => $allergieId,
                    'nieuwe_allergie_id' => $nieuweAllergieId,
                    'user_id' => auth()->id()
                ]);
                
                return redirect()->back()
                    ->with('error', $sarahPindaRule->message());
            }

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
}
