<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Allergie;
use Illuminate\Support\Facades\Log;

class AllergieRisicoValidatie implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $allergie = Allergie::find($value);
        
        if (!$allergie) {
            $fail('De geselecteerde allergie bestaat niet.');
            return;
        }

        // Log waarschuwing voor hoog risico allergieën
        if ($allergie->anafylactisch_risico === 'hoog') {
            Log::warning('High risk allergie selected', [
                'allergie_id' => $value,
                'allergie_naam' => $allergie->naam,
                'anafylactisch_risico' => $allergie->anafylactisch_risico,
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
        }

        // Controleer of allergie actief is
        if (!$allergie->is_actief) {
            $fail('De geselecteerde allergie is niet meer actief.');
        }
    }
}
