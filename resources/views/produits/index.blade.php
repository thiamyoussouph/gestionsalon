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
                    <th>Quantité</th>
                    <th>Catégorie</th>
                    <th>etiquette</th>

                </tr>
            </thead>
            <tbody>
                @foreach($produits as $produit)
                <tr>
                    <td>{{ $produit->nom }}</td>
                    <td>{{ $produit->quantite }}</td>
                    <td>{{ $produit->categorie->name }}</td>
                    <td>
                        <a href="{{ route('code-barres', $produit->id) }}" class="btn btn-primary">Voir</a>
                     
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
