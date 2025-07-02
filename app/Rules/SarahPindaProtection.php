<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SarahPindaProtection implements Rule
{
    private $persoonId;
    private $huidigeAllergieId;

    public function __construct($persoonId, $huidigeAllergieId = null)
    {
        $this->persoonId = $persoonId;
        $this->huidigeAllergieId = $huidigeAllergieId;
    }

    public function passes($attribute, $value)
    {
        // Sarah den Dolder heeft persoon_id = 5 (volgens database)
        // Pinda allergie heeft allergie_id = 2
        $sarahId = 5;
        $pindaAllergieId = 2;

        // Als Sarah probeert haar pinda allergie te wijzigen
        if ($this->persoonId == $sarahId && $this->huidigeAllergieId == $pindaAllergieId) {
            return false; // Sarah mag haar pinda allergie niet wijzigen
        }

        // Als iemand anders pinda allergie probeert toe te voegen
        if ($this->persoonId != $sarahId && $value == $pindaAllergieId) {
            return false; // Alleen Sarah mag pinda allergie hebben
        }

        return true;
    }

    public function message()
    {
        $sarahId = 5;
        $pindaAllergieId = 2;

        if ($this->persoonId == $sarahId && $this->huidigeAllergieId == $pindaAllergieId) {
            return 'Sarah\'s pinda allergie mag niet worden gewijzigd om veiligheidsredenen.';
        }

        return 'Alleen Sarah mag de pinda allergie hebben. Deze allergie kan niet worden toegewezen aan andere personen.';
    }
}
