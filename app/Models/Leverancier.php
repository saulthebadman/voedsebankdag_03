<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leverancier extends Model
{
    use HasFactory;

    protected $fillable = [
        'naam',
        'contactpersoon',
        'email',
        'mobiel',
        'leveranciernummer',
        'leverancier_type',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
