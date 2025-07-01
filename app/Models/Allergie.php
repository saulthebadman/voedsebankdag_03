<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Allergie extends Model
{
    protected $table = 'allergies';
    
    protected $fillable = [
        'naam',
        'omschrijving',
        'anafylactisch_risico',
        'is_actief',
        'opmerking',
        'datum_aangemaakt',
        'datum_gewijzigd'
    ];

    protected $casts = [
        'is_actief' => 'boolean',
        'datum_aangemaakt' => 'datetime',
        'datum_gewijzigd' => 'datetime'
    ];

    public function personen(): BelongsToMany
    {
        return $this->belongsToMany(Persoon::class, 'allergie_per_persoons', 'allergie_id', 'persoon_id')
                    ->withPivot('is_actief', 'opmerking', 'datum_aangemaakt', 'datum_gewijzigd')
                    ->withTimestamps();
    }
}
