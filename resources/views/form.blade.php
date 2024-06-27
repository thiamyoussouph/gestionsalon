@extends('layouts.app')
@section('content')


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Ajouter un produit</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('produits.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input id="nom" type="text" class="form-control" name="nom" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="prix">Prix</label>
                            <input id="prix" type="number" class="form-control" name="prix" required>
                        </div>

                        <div class="form-group">
                            <label for="categorie_id">Catégorie</label>
                            <select id="categorie_id" class="form-control" name="categorie_id" required>
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="quantite">Quantité</label>
                            <input id="quantite" type="number" class="form-control" name="quantite" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection