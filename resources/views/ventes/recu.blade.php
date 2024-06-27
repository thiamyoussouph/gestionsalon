@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Résumé de la Vente</h1>
    <ul>
        @if($vente->count())
            @foreach($vente as $produit)
                <li>{{ $produit->nom }} - {{ $produit->pivot->quantite }} x {{ number_format($produit->pivot->prix, 2) }} CFA, Total: {{ number_format($produit->pivot->quantite * $produit->pivot->prix, 2) }} CFA</li>
            @endforeach
        @else
            <p>Aucun détail de vente disponible.</p>
        @endif
    </ul>
    <p>Total: {{ number_format($total, 2) }} CFA</p>
</div>
@endsection
