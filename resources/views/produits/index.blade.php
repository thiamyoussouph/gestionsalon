@extends('layouts.app')
@section('title', 'Produits')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Tableau des Produits</h4>
    <a href="{{ route('produits.create') }}" class="btn btn-dark">
        <i class="fas fa-plus me-1"></i> Ajouter un produit
    </a>
</div>

<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>Nom</th>
            <th>Prix</th>
            <th>Quantité</th>
            <th>Catégorie</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($produits as $produit)
        <tr class="{{ $produit->quantite == 0 ? 'table-danger' : ($produit->quantite <= 5 ? 'table-warning' : '') }}">
            <td>{{ $produit->nom }}</td>
            <td>{{ number_format($produit->prix, 0, ',', ' ') }} CFA</td>
            <td>
                {{ $produit->quantite }}
                @if($produit->quantite == 0)
                    <span class="badge bg-danger ms-1">Rupture</span>
                @elseif($produit->quantite <= 5)
                    <span class="badge bg-warning text-dark ms-1">Stock faible</span>
                @endif
            </td>
            <td>{{ $produit->categorie->name ?? '-' }}</td>
            <td>
                <a href="{{ route('code-barres', $produit->id) }}" class="btn btn-sm btn-primary">Étiquette</a>
                <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                <form action="{{ route('produits.destroy', $produit->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce produit ?')">Supprimer</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center text-muted py-3">Aucun produit enregistré</td></tr>
        @endforelse
    </tbody>
</table>

{{-- Scanner de code-barres pour mise à jour rapide du stock --}}
<div class="card mt-4">
    <div class="card-header fw-bold">
        <i class="fas fa-barcode me-1"></i> Scanner — Décrémenter le stock
    </div>
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="barcode" class="form-label">Code-barres</label>
                <input type="text" class="form-control" id="barcode" placeholder="Scanner ou saisir..." autofocus>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary" onclick="scannerBarcode()">
                    <i class="fas fa-search me-1"></i> Scanner
                </button>
            </div>
        </div>
        <div id="scan-message" class="mt-2"></div>
    </div>
</div>

<script>
document.getElementById('barcode').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') scannerBarcode();
});

function scannerBarcode() {
    const barcode = document.getElementById('barcode').value.trim();
    if (!barcode) return;

    fetch('/update-quantity', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ barcode })
    })
    .then(r => r.json())
    .then(data => {
        const msg = document.getElementById('scan-message');
        if (data.success) {
            msg.innerHTML = '<div class="alert alert-success py-2">Stock mis à jour avec succès.</div>';
            setTimeout(() => location.reload(), 1000);
        } else {
            msg.innerHTML = `<div class="alert alert-danger py-2">${data.message}</div>`;
        }
        document.getElementById('barcode').value = '';
    })
    .catch(() => {
        document.getElementById('scan-message').innerHTML = '<div class="alert alert-danger py-2">Erreur réseau.</div>';
    });
}
</script>
@endsection
