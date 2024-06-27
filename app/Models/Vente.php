<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;
    public function produits()
{
    return $this->belongsToMany(Produit::class, 'detail_ventes')
                ->withPivot('quantite', 'prix', 'total')
                ->withTimestamps();
}
}
