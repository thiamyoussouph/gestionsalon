<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nom', 'prix', 'quantite', 'categorie_id', 'code_barres', 'barcode_image_path'];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
}
