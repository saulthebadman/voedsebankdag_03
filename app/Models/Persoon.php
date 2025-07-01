<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Persoon extends Model
{
    protected $table = 'persoons';
    
    protected $fillable = [
        'gezin_id',
        'voornaam',
        'tussenvoegsel',
        'achternaam',
        'geboortedatum',
        'type_persoon',
        'is_vertegenwoordiger',
        'is_actief',
        'opmerking',
        'datum_aangemaakt',
        'datum_gewijzigd'
    ];

    protected $casts = [
        'geboortedatum' => 'date',
        'is_vertegenwoordiger' => 'boolean',
        'is_actief' => 'boolean',
        'datum_aangemaakt' => 'datetime',
        'datum_gewijzigd' => 'datetime'
    ];

    public function gezin(): BelongsTo
    {
        return $this->belongsTo(Gezin::class);
    }

    public function allergieen(): BelongsToMany
    {
        return $this->belongsToMany(Allergie::class, 'allergie_per_persoons', 'persoon_id', 'allergie_id')
                    ->withPivot('is_actief', 'opmerking', 'datum_aangemaakt', 'datum_gewijzigd')
                    ->withTimestamps();
    }
    
    public function getVolledigeNaamAttribute(): string
    {
        $naam = $this->voornaam;
        if ($this->tussenvoegsel) {
            $naam .= ' ' . $this->tussenvoegsel;
        }
        $naam .= ' ' . $this->achternaam;
        return $naam;
    }
}
