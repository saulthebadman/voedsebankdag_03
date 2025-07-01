<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueAllergiePerPersoon implements ValidationRule
{
    private $persoonId;
    private $excludeAllergieId;

    public function __construct($persoonId, $excludeAllergieId = null)
    {
        $this->persoonId = $persoonId;
        $this->excludeAllergieId = $excludeAllergieId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = DB::table('allergie_per_persoons')
            ->where('persoon_id', $this->persoonId)
            ->where('allergie_id', $value);

        if ($this->excludeAllergieId) {
            $query->where('allergie_id', '!=', $this->excludeAllergieId);
        }

        if ($query->exists()) {
            $fail('Deze persoon heeft deze allergie al.');
        }
    }
}
