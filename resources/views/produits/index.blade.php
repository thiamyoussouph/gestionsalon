@extends('layouts.app')
@section('content')
<body>
    <style>
        @media print {
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        background: none;
    }
    .etiquette {
        margin: 0;
        padding: 0;
        overflow: hidden;
        width: auto; /* ajustez selon la largeur de votre étiquette */
    }
}
    </style>
    <div class="container mt-4">
        <a  class="btn btn-dark" href="{{route('produits.create')}}">ajouter produit </a>
        <!-- Section pour le tableau des produits -->
        <h2 class="mt-4">Tableau des Produits</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Catégorie</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produits as $produit)
                <tr class="{{ $produit->quantite == 0 ? 'table-danger' : ($produit->quantite <= 5 ? 'table-warning' : '') }}">
                    <td>{{ $produit->nom }}</td>
                    <td>{{ number_format($produit->prix, 2) }} CFA</td>
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
                @endforeach
            </tbody>
        </table>

        <!-- Formulaire pour scanner le code-barres -->
        <div class="mt-4">
            <h2>Scanner un Code-barres</h2>
            <form id="barcode-form">
                <div class="mb-3">
                    <label for="barcode" class="form-label">Code-barres</label>
                    <input type="text" class="form-control" id="barcode" name="barcode" autofocus>
                </div>
                <button type="submit" class="btn btn-primary">Scanner</button>
            </form>
        </div>
    </div>
    <script>
       

        document.getElementById('barcode-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const barcode = document.getElementById('barcode').value;
            
            fetch('/update-quantity', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ barcode: barcode })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Quantité mise à jour avec succès');
                    location.reload();
                } else {
                    alert('Erreur lors de la mise à jour de la quantité : ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la mise à jour de la quantité');
            });
        });
      
</script>
@endsection
