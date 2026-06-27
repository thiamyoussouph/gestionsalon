@extends('layouts.app')
@section('title', 'Reçu de vente')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card" id="recu">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Reçu de Vente</h5>
                <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Imprimer
                </button>
            </div>
            <div class="card-body">
                {{-- Infos vente --}}
                <div class="row mb-3">
                    <div class="col-6">
                        <p class="mb-1"><strong>N° Facture :</strong> {{ $vente_info->numero_facture }}</p>
                        <p class="mb-1"><strong>Date :</strong> {{ $vente_info->created_at->format('d/m/Y H:i') }}</p>
                        <p class="mb-1"><strong>Client :</strong> {{ $vente_info->nom_client ?: 'Client anonyme' }}</p>
                    </div>
                    <div class="col-6 text-end">
                        <p class="mb-1"><strong>Mode de paiement :</strong> {{ ucfirst($vente_info->mode_paiement) }}</p>
                        <p class="mb-1"><strong>Statut :</strong>
                            @if($vente_info->status)
                                <span class="badge bg-success">Payée</span>
                            @else
                                <span class="badge bg-warning text-dark">En attente</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                {{-- Détail des produits --}}
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Produit</th>
                            <th class="text-center">Qté</th>
                            <th class="text-end">Prix unit.</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vente as $produit)
                            <tr>
                                <td>{{ $produit->nom }}</td>
                                <td class="text-center">{{ $produit->pivot->quantite }}</td>
                                <td class="text-end">{{ number_format($produit->pivot->prix, 0, ',', ' ') }} CFA</td>
                                <td class="text-end">{{ number_format($produit->pivot->quantite * $produit->pivot->prix, 0, ',', ' ') }} CFA</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Aucun produit</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td class="text-end fw-bold">{{ number_format($total, 0, ',', ' ') }} CFA</td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="3" class="text-end">Montant reçu</td>
                            <td class="text-end">{{ number_format($vente_info->montant_recu, 0, ',', ' ') }} CFA</td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="3" class="text-end">Monnaie rendue</td>
                            <td class="text-end text-success fw-bold">{{ number_format($vente_info->montant_rendu, 0, ',', ' ') }} CFA</td>
                        </tr>
                    </tfoot>
                </table>

                <div class="text-center mt-3 text-muted">
                    <small>Merci de votre visite !</small>
                </div>
            </div>
        </div>

        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('ventes.index') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Nouvelle Vente
            </a>
        </div>
    </div>
</div>

<style>
@media print {
    .navbar, #sidebar-wrapper, .btn, .mt-3.d-flex { display: none !important; }
    #page-content-wrapper { margin: 0 !important; }
    #recu { border: none !important; }
}
</style>
@endsection
