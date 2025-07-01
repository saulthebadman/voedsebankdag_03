<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'straat',
        'huisnummer',
        'toevoeging',
        'postcode',
        'woonplaats',
        'email',
        'mobiel',
        'is_actief',
        'opmerking'
    ];

    protected $casts = [
        'is_actief' => 'boolean',
        'datum_aangemaakt' => 'datetime',
        'datum_gewijzigd' => 'datetime'
    ];

    /**
     * Many-to-many relatie met leveranciers via contact_per_leveranciers koppeltabel
     */
    public function leveranciers(): BelongsToMany
    {
        return $this->belongsToMany(
            Leverancier::class,
            'contact_per_leveranciers',
            'contact_id',
            'leverancier_id'
        );
    }

    /**
     * Accessor voor volledig adres
     */
    public function getVolledigAdresAttribute(): string
    {
        $address = [];
        if ($this->straat) $address[] = $this->straat;
        if ($this->huisnummer) $address[] = $this->huisnummer;
        if ($this->toevoeging) $address[] = $this->toevoeging;
        if ($this->postcode) $address[] = $this->postcode;
        if ($this->woonplaats) $address[] = $this->woonplaats;
        
        return implode(' ', $address);
    }

    /**
     * Scope voor actieve contacten
     */
    public function scopeActive($query)
    {
        return $query->where('is_actief', true);
    }
}
