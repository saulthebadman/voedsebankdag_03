<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllergiePerPersoon extends Model
{
    protected $table = 'allergie_per_persoons';
    
    protected $fillable = [
        'persoon_id',
        'allergie_id',
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

    public function persoon(): BelongsTo
    {
        return $this->belongsTo(Persoon::class);
    }

    public function allergie(): BelongsTo
    {
        return $this->belongsTo(Allergie::class);
    }
}
