@extends('layouts.app')
@section('title', 'Étiquette — ' . $produit->nom)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Étiquette du Produit</h4>
    <a href="{{ route('produits.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center" id="etiquette-zone">
                <h5 class="card-title mb-1">{{ $produit->nom }}</h5>
                <p class="card-text fw-bold fs-5 mb-2">{{ number_format($produit->prix, 0, ',', ' ') }} CFA</p>
                @if($produit->barcode_image_path)
                    <img src="{{ asset('storage/' . $produit->barcode_image_path) }}"
                         alt="Code-barres {{ $produit->code_barres }}"
                         class="img-fluid mb-2"
                         style="max-height: 80px;">
                    <p class="text-muted small mb-0">{{ $produit->code_barres }}</p>
                @else
                    <div class="alert alert-warning">Code-barres non généré</div>
                @endif
            </div>
            <div class="card-footer d-flex gap-2 justify-content-center">
                <button class="btn btn-outline-primary" onclick="imprimerEtiquette()">
                    <i class="fas fa-print me-1"></i> Imprimer
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body > *:not(#print-zone) { display: none !important; }
    #print-zone { display: block !important; }
}
#print-zone { display: none; }
</style>

<div id="print-zone">
    <h5>{{ $produit->nom }}</h5>
    <p><strong>{{ number_format($produit->prix, 0, ',', ' ') }} CFA</strong></p>
    @if($produit->barcode_image_path)
        <img src="{{ asset('storage/' . $produit->barcode_image_path) }}" alt="Code-barres" style="max-height:80px;">
        <p>{{ $produit->code_barres }}</p>
    @endif
</div>

<script>
function imprimerEtiquette() {
    window.print();
}
</script>
@endsection
