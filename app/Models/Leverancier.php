<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Leverancier extends Model
{
    use HasFactory;

    protected $table = 'leveranciers';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'naam',
        'contact_persoon',
        'leverancier_nummer',
        'leverancier_type',
        'is_actief',
        'opmerking'
    ];

    protected $casts = [
        'is_actief' => 'boolean',
        'datum_aangemaakt' => 'datetime',
        'datum_gewijzigd' => 'datetime'
    ];

    /**
     * Many-to-many relatie met producten via product_per_leveranciers koppeltabel
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'product_per_leveranciers',
            'leverancier_id',
            'product_id'
        )->withPivot('datum_aangemaakt', 'datum_gewijzigd');
    }

    /**
     * Many-to-many relatie met contacts via contact_per_leveranciers koppeltabel
     */
    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(
            Contact::class,
            'contact_per_leveranciers',
            'leverancier_id',
            'contact_id'
        );
    }

    /**
     * Alleen actieve producten via de many-to-many relatie
     */
    public function activeProducts(): BelongsToMany
    {
        return $this->products()->where('products.is_actief', true);
    }

    /**
     * Scope voor actieve leveranciers
     */
    public function scopeActive($query)
    {
        return $query->where('is_actief', true);
    }

    /**
     * Accessor voor formatted adres
     */
    public function getFullAddressAttribute(): string
    {
        $address = [];
        if ($this->adres) $address[] = $this->adres;
        if ($this->postcode) $address[] = $this->postcode;
        if ($this->stad) $address[] = $this->stad;
        if ($this->land) $address[] = $this->land;
        
        return implode(', ', $address);
    }

    /**
     * Get route key name for route model binding
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
