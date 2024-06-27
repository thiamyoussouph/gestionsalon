@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ajouter des produits à la vente</h1>
    <div class="input-group mb-3">
        <input type="text" id="codeBarresInput" class="form-control" placeholder="Scanner le code-barres" required>
        <input type="number" id="quantiteInput" class="form-control" placeholder="Quantité" required>
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" onclick="ajouterProduit()">Ajouter</button>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Quantité</th>
                <th>Prix</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody id="listeProduits">
        </tbody>
    </table>

    <div class="form-group">
        <label for="nomClient">Nom du Client:</label>
        <input type="text" id="nomClient" class="form-control" placeholder="Entrer le nom du client">
    </div>

    <div class="form-group">
        <label for="modePaiement">Mode de Paiement:</label>
        <select id="modePaiement" class="form-control">
            <option value="cash">Cash</option>
            <option value="card">Carte</option>
            <option value="check">Chèque</option>
            <option value="other">Autre</option>
        </select>
    </div>

    <div class="form-group">
        <label for="montantRecu">Montant Reçu:</label>
        <input type="number" id="montantRecu" class="form-control" placeholder="Entrer le montant reçu">
    </div>

    <button class="btn btn-success" onclick="finaliserVente()">Finaliser Vente</button>
</div>

<script>
let produits = [];

function ajouterProduit() {
    const codeBarres = document.getElementById('codeBarresInput').value;
    const quantite = parseInt(document.getElementById('quantiteInput').value);
    console.log('Code barres scanné:', codeBarres);

    fetch(`/produits/par-code-barres/${codeBarres}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Produit non trouvé');
            }
            return response.json();
        })
        .then(produit => {
            console.log('Produit reçu:', produit);
            produit.quantite = quantite;
            produit.total = produit.prix * quantite;
            produits.push(produit);
            afficherProduits();
        })
        .catch(error => {
            console.error('Erreur lors de la récupération du produit:', error);
            alert('Erreur: ' + error.message);
        });
}

function afficherProduits() {
    const liste = document.getElementById('listeProduits');
    liste.innerHTML = '';
    produits.forEach(p => {
        const row = `<tr>
            <td>${p.nom}</td>
            <td>${p.quantite}</td>
            <td>${p.prix}</td>
            <td>${p.total}</td>
        </tr>`;
        liste.innerHTML += row;
    });
}

function finaliserVente() {
    const nomClient = document.getElementById('nomClient').value;
    const modePaiement = document.getElementById('modePaiement').value;
    const montantRecu = document.getElementById('montantRecu').value;

    // Vérifier si montantRecu est bien entré
    if (!montantRecu) {
        alert("Veuillez entrer le montant reçu.");
        return;
    }

    const donnees = JSON.stringify({ produits, nomClient, modePaiement, montantRecu });

    fetch('/ventes/finaliser', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: donnees
    })
    .then(response => {
        if (response.headers.get("Content-Type").includes("application/json")) {
            return response.json().then(data => {
                if (!response.ok) {
                    throw new Error('Échec de la finalisation de la vente: ' + data.message);
                }
                console.log('Réponse du serveur:', data);
                window.location.href = `/ventes/recu/${data.venteId}`;
            })
            
        } else {
            response.text().then(text => {
                console.error('Réponse non JSON reçue:', text);
                throw new Error('Erreur de serveur non JSON reçue');
            });
        }
    })
    .catch(error => {
        console.error('Erreur lors de la finalisation de la vente:', error);
        alert('Erreur lors de la finalisation de la vente: ' + error.message);
    });
}

</script>
@endsection
