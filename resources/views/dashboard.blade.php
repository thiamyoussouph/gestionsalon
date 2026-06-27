@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<h4 class="mb-4">Dashboard</h4>

{{-- Statistiques du jour --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-4 fw-bold">{{ $ventesAujourdhui }}</div>
                    <div>Ventes aujourd'hui</div>
                </div>
                <i class="fas fa-shopping-bag fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-4 fw-bold">{{ number_format($totalAujourdhui, 0, ',', ' ') }} CFA</div>
                    <div>CA aujourd'hui</div>
                </div>
                <i class="fas fa-coins fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-4 fw-bold">{{ $totalProduits }}</div>
                    <div>Produits</div>
                </div>
                <i class="fas fa-box fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-secondary">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-4 fw-bold">{{ $totalCategories }}</div>
                    <div>Catégories</div>
                </div>
                <i class="fas fa-tags fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Dernières ventes --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-header fw-bold">Dernières ventes</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>N° Facture</th>
                            <th>Client</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dernieresVentes as $vente)
                        <tr>
                            <td>{{ $vente->numero_facture }}</td>
                            <td>{{ $vente->nom_client ?: 'Anonyme' }}</td>
                            <td>{{ number_format($vente->total, 0, ',', ' ') }} CFA</td>
                            <td>{{ $vente->created_at->format('d/m H:i') }}</td>
                            <td><a href="{{ route('ventes.recu', $vente->id) }}" class="btn btn-sm btn-outline-primary">Reçu</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Aucune vente</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Alertes stock --}}
    <div class="col-md-5">
        @if($rupture->count() > 0)
        <div class="card border-danger mb-3">
            <div class="card-header bg-danger text-white fw-bold">
                <i class="fas fa-exclamation-triangle me-1"></i> Rupture de stock ({{ $rupture->count() }})
            </div>
            <ul class="list-group list-group-flush">
                @foreach($rupture as $produit)
                <li class="list-group-item d-flex justify-content-between">
                    {{ $produit->nom }}
                    <span class="badge bg-danger">0</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($stockFaible->count() > 0)
        <div class="card border-warning">
            <div class="card-header bg-warning fw-bold">
                <i class="fas fa-exclamation-circle me-1"></i> Stock faible ({{ $stockFaible->count() }})
            </div>
            <ul class="list-group list-group-flush">
                @foreach($stockFaible as $produit)
                <li class="list-group-item d-flex justify-content-between">
                    {{ $produit->nom }}
                    <span class="badge bg-warning text-dark">{{ $produit->quantite }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($rupture->count() === 0 && $stockFaible->count() === 0)
        <div class="card">
            <div class="card-body text-center text-success">
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <p class="mb-0">Tous les stocks sont OK</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
