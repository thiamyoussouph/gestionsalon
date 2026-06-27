@extends('layouts.app')
@section('title', 'Historique des Ventes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Historique des Ventes</h4>
    <a href="{{ route('ventes.index') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Nouvelle Vente
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>N° Facture</th>
                    <th>Client</th>
                    <th>Mode paiement</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventes as $vente)
                <tr>
                    <td><code>{{ $vente->numero_facture }}</code></td>
                    <td>{{ $vente->nom_client ?: 'Anonyme' }}</td>
                    <td>{{ ucfirst($vente->mode_paiement) }}</td>
                    <td>{{ number_format($vente->total, 0, ',', ' ') }} CFA</td>
                    <td>
                        @if($vente->status)
                            <span class="badge bg-success">Payée</span>
                        @else
                            <span class="badge bg-warning text-dark">En attente</span>
                        @endif
                    </td>
                    <td>{{ $vente->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('ventes.recu', $vente->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-receipt"></i> Reçu
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Aucune vente enregistrée</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $ventes->links() }}
</div>
@endsection
