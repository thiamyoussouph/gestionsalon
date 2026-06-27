<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero_facture', 'nom_client', 'user_id', 'total',
        'montant_recu', 'montant_rendu', 'status',
        'mode_paiement', 'reference_paiement', 'numero_transaction',
    ];

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'detail_ventes')
                    ->withPivot('quantite', 'prix', 'total')
                    ->withTimestamps();
    }
}
