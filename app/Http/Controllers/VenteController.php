<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{
    public function index()
    {
        return view('ventes.index');
    }

    public function historique()
    {
        $ventes = Vente::with('produits')->latest()->paginate(20);
        return view('ventes.historique', compact('ventes'));
    }

    public function ajouterProduit(Request $request)
    {
        $codeBarres = $request->code_barres;
        $quantite = $request->quantite;

        $produit = Produit::where('code_barres', $codeBarres)->firstOrFail();

        $vente = session()->get('vente', []);

        if (isset($vente[$produit->id])) {
            $vente[$produit->id]['quantite'] += $quantite;
        } else {
            $vente[$produit->id] = [
                'nom'      => $produit->nom,
                'prix'     => $produit->prix,
                'quantite' => $quantite,
            ];
        }

        session()->put('vente', $vente);

        return redirect()->route('ventes.index')->with('success', 'Produit ajouté à la vente.');
    }

    public function finaliserVente(Request $request)
    {
        $produits = $request->input('produits');
        $total = 0;

        $date = now();
        $numero_facture = 'FBTV' . $date->format('YmdHis');

        $vente = new Vente();
        $vente->numero_facture    = $numero_facture;
        $vente->nom_client        = $request->input('nom_client');
        $vente->user_id           = auth()->id();
        $vente->mode_paiement     = $request->input('mode_paiement');
        $vente->reference_paiement  = $request->input('reference_paiement');
        $vente->numero_transaction  = $request->input('numero_transaction');
        $vente->montant_recu      = $request->input('montantRecu', 0);

        try {
            DB::transaction(function () use ($vente, $produits, &$total) {
                $vente->save();

                foreach ($produits as $details) {
                    $produit = Produit::find($details['id']);
                    if ($produit && $details['quantite'] > 0) {
                        $quantite = $details['quantite'];
                        $prix     = $details['prix'];

                        if ($produit->quantite < $quantite) {
                            throw new \Exception("Stock insuffisant pour le produit : {$produit->nom}");
                        }

                        $totalProduit = $quantite * $prix;

                        $vente->produits()->attach($produit->id, [
                            'quantite' => $quantite,
                            'prix'     => $prix,
                            'total'    => $totalProduit,
                        ]);

                        $produit->quantite -= $quantite;
                        $produit->save();

                        $total += $totalProduit;
                    }
                }

                $vente->total          = $total;
                $vente->montant_rendu  = $vente->montant_recu - $total;
                $vente->status         = ($vente->montant_recu >= $total) ? 1 : 0;
                $vente->save();
            });
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'message' => 'Vente finalisée avec succès',
            'total'   => $total,
            'venteId' => $vente->id,
        ]);
    }

    public function getProduitParCodeBarres($codeBarres)
    {
        $produit = Produit::where('code_barres', $codeBarres)->first();

        if (!$produit) {
            return response()->json(['message' => 'Produit non trouvé'], 404);
        }

        return response()->json([
            'id'          => $produit->id,
            'nom'         => $produit->nom,
            'prix'        => $produit->prix,
            'code_barres' => $produit->code_barres,
        ]);
    }

    public function afficherRecu($id)
    {
        $vente = Vente::with(['produits'])->findOrFail($id);

        $details = $vente->produits;

        $total = $details->reduce(function ($carry, $item) {
            return $carry + ($item->pivot->quantite * $item->pivot->prix);
        }, 0);

        return view('ventes.recu', [
            'vente'      => $details,
            'vente_info' => $vente,
            'total'      => $total,
        ]);
    }
}
