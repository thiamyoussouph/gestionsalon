@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Modifier le produit</div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('produits.update', $produit->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="nom">Nom</label>
                            <input id="nom" type="text" class="form-control" name="nom" value="{{ old('nom', $produit->nom) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="prix">Prix</label>
                            <input id="prix" type="number" step="0.01" class="form-control" name="prix" value="{{ old('prix', $produit->prix) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="categorie_id">Catégorie</label>
                            <select id="categorie_id" class="form-control" name="categorie_id" required>
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->id }}" {{ $produit->categorie_id == $categorie->id ? 'selected' : '' }}>
                                        {{ $categorie->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="quantite">Quantité</label>
                            <input id="quantite" type="number" class="form-control" name="quantite" value="{{ old('quantite', $produit->quantite) }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        <a href="{{ route('produits.index') }}" class="btn btn-secondary">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
