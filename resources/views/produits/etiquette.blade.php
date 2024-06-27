@extends('layouts.app')
@section('content')
<h2 class="mb-4">Étiquette du Produit</h2>
<div class="row">
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-body text-center">
                <div id="etiquette-{{ $produit->id }}" class="etiquette">
                    <h5 class="card-title">{{ $produit->nom }}</h5>
                    <p class="card-text">{{ $produit->prix }} CFA</p>
                    <img src="{{ asset('storage/' . $produit->barcode_image_path) }}" alt="Code-barres" class="mb-3">
                </div>
                <button class="btn btn-primary" onclick="downloadEtiquette({{ $produit->id }})">Télécharger Étiquette</button>
                <button class="btn btn-primary" onclick="printEtiquette('etiquette-{{ $produit->id }}')">Imprimer Étiquette</button>
            </div>
        </div>
    </div>
</div>
        
       
<script>
     function downloadEtiquette(id) {
            const element = document.getElementById('etiquette-' + id);
            html2canvas(element).then(canvas => {
                const link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = 'etiquette-' + id + '.png';
                link.click();
            });
        }
        function printEtiquette(etiquetteId) {
    var content = document.getElementById(etiquetteId).innerHTML;
    var originalContent = document.body.innerHTML;

    document.body.innerHTML = content; // Remplace le contenu de la page par l'étiquette
    window.print(); // Lance l'impression

    document.body.innerHTML = originalContent; // Restaure le contenu original après l'impression
}
</script>
@endsection