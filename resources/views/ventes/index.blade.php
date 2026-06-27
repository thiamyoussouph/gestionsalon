@extends('layouts.app')
@section('title', 'Nouvelle Vente')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h4 class="mb-3">Nouvelle Vente</h4>

        {{-- Scanner code-barres --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="input-group">
                    <input type="text" id="codeBarresInput" class="form-control" placeholder="Scanner le code-barres..." autofocus>
                    <input type="number" id="quantiteInput" class="form-control" placeholder="Qté" value="1" min="1" style="max-width:90px">
                    <button class="btn btn-primary" type="button" onclick="ajouterProduit()">
                        <i class="fas fa-plus"></i> Ajouter
                    </button>
                </div>
            </div>
        </div>

        {{-- Tableau des produits du panier --}}
        <div class="card mb-3">
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Produit</th>
                            <th>Qté</th>
                            <th>Prix unit.</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="listeProduits">
                        <tr id="panier-vide">
                            <td colspan="5" class="text-center text-muted py-3">Aucun produit ajouté</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="table-dark">
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td colspan="2" class="fw-bold" id="totalAffiche">0 CFA</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header fw-bold">Informations de paiement</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Nom du Client</label>
                    <input type="text" id="nomClient" class="form-control" placeholder="Nom du client (optionnel)">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mode de Paiement</label>
                    <select id="modePaiement" class="form-select">
                        <option value="cash">Cash</option>
                        <option value="card">Carte</option>
                        <option value="check">Chèque</option>
                        <option value="other">Autre</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Montant Reçu (CFA)</label>
                    <input type="number" id="montantRecu" class="form-control" placeholder="0" min="0">
                </div>
                <div class="mb-3">
                    <label class="form-label">Monnaie à rendre</label>
                    <input type="text" id="montantRendu" class="form-control" readonly value="0 CFA">
                </div>
                <button class="btn btn-success w-100" onclick="finaliserVente()">
                    <i class="fas fa-check-circle me-1"></i> Finaliser la Vente
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let produits = [];

document.getElementById('codeBarresInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') ajouterProduit();
});

document.getElementById('montantRecu').addEventListener('input', calculerRendu);

function ajouterProduit() {
    const codeBarres = document.getElementById('codeBarresInput').value.trim();
    const quantite = parseInt(document.getElementById('quantiteInput').value) || 1;
    if (!codeBarres) return;

    fetch(`/produits/par-code-barres/${codeBarres}`)
        .then(r => r.ok ? r.json() : Promise.reject('Produit non trouvé'))
        .then(produit => {
            const index = produits.findIndex(p => p.id === produit.id);
            if (index >= 0) {
                produits[index].quantite += quantite;
                produits[index].total = produits[index].quantite * produits[index].prix;
            } else {
                produit.quantite = quantite;
                produit.total = produit.prix * quantite;
                produits.push(produit);
            }
            afficherProduits();
            document.getElementById('codeBarresInput').value = '';
            document.getElementById('quantiteInput').value = 1;
            document.getElementById('codeBarresInput').focus();
        })
        .catch(err => alert('Erreur : ' + err));
}

function retirerProduit(index) {
    produits.splice(index, 1);
    afficherProduits();
}

function afficherProduits() {
    const liste = document.getElementById('listeProduits');
    const panierVide = document.getElementById('panier-vide');

    if (produits.length === 0) {
        liste.innerHTML = '<tr id="panier-vide"><td colspan="5" class="text-center text-muted py-3">Aucun produit ajouté</td></tr>';
        document.getElementById('totalAffiche').textContent = '0 CFA';
        return;
    }

    let total = 0;
    liste.innerHTML = produits.map((p, i) => {
        total += p.total;
        return `<tr>
            <td>${p.nom}</td>
            <td>${p.quantite}</td>
            <td>${Number(p.prix).toLocaleString()} CFA</td>
            <td>${Number(p.total).toLocaleString()} CFA</td>
            <td><button class="btn btn-sm btn-danger" onclick="retirerProduit(${i})"><i class="fas fa-trash"></i></button></td>
        </tr>`;
    }).join('');

    document.getElementById('totalAffiche').textContent = total.toLocaleString() + ' CFA';
    calculerRendu();
}

function calculerRendu() {
    const total = produits.reduce((s, p) => s + p.total, 0);
    const recu = parseFloat(document.getElementById('montantRecu').value) || 0;
    const rendu = recu - total;
    document.getElementById('montantRendu').value = rendu.toLocaleString() + ' CFA';
    document.getElementById('montantRendu').className = 'form-control ' + (rendu < 0 ? 'text-danger' : 'text-success');
}

function finaliserVente() {
    if (produits.length === 0) {
        alert('Ajoutez au moins un produit.');
        return;
    }
    const montantRecu = parseFloat(document.getElementById('montantRecu').value);
    if (!montantRecu || montantRecu <= 0) {
        alert('Veuillez entrer le montant reçu.');
        return;
    }

    fetch('/ventes/finaliser', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            produits,
            nom_client: document.getElementById('nomClient').value,
            mode_paiement: document.getElementById('modePaiement').value,
            montantRecu
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.venteId) {
            window.location.href = `/ventes/recu/${data.venteId}`;
        } else {
            alert('Erreur : ' + data.message);
        }
    })
    .catch(() => alert('Erreur lors de la finalisation de la vente.'));
}
</script>
@endsection
