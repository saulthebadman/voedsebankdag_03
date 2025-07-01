<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'leverancier_id',
        'naam',
        'soort_allergie',
        'barcode',
        'houdbaarheidsdatum',
    ];

    public function leverancier()
    {
        return $this->belongsTo(Leverancier::class);
    }
}
