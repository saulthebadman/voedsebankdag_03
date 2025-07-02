<?php

namespace App\Http\Controllers;

use App\Models\Allergie;
use App\Models\Gezin;
use App\Models\Persoon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class AllergieStatistiekController extends Controller
{
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
