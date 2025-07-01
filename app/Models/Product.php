<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'categorie_id',
        'naam',
        'soort_allergie',
        'barcode',
        'houdbaarheidsdatum',
        'omschrijving',
        'status',
        'is_actief',
        'opmerking'
    ];

    protected $casts = [
        'is_actief' => 'boolean',
        'houdbaarheidsdatum' => 'date',
        'datum_aangemaakt' => 'datetime',
        'datum_gewijzigd' => 'datetime'
    ];

    /**
     * Many-to-many relatie met leveranciers via product_per_leveranciers koppeltabel
     */
    public function leveranciers(): BelongsToMany
    {
        return $this->belongsToMany(
            Leverancier::class,
            'product_per_leveranciers',
            'product_id',
            'leverancier_id'
        )->withPivot('datum_aangemaakt', 'datum_gewijzigd');
    }

    /**
     * Scope voor actieve producten
     */
    public function scopeActive($query)
    {
        return $query->where('is_actief', true);
    }

    /**
     * Relatie met categorie
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    /**
     * Check of product actief is
     */
    public function isActive(): bool
    {
        return $this->is_actief;
    }

    /**
     * Formatted houdbaarheidsdatum
     */
    public function getFormattedExpiryDateAttribute(): string
    {
        return $this->houdbaarheidsdatum ? $this->houdbaarheidsdatum->format('d-m-Y') : 'Onbekend';
    }

    /**
     * Get route key name for route model binding
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
