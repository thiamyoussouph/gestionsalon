<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    
    <style>
        #sidebar-wrapper {
        background-color: #343a40; /* Couleur de fond plus sombre pour un contraste élevé */
        color: #ffffff;
        height: 100vh;
        width: 250px;
      
    }
    
    .sidebar-heading {
        padding: 10px 15px;
        font-size: 1.2em;
        background-color: #6c757d; /* Couleur de fond pour l'en-tête */
    }
    
    .list-group-item {
        border: none; /* Retirer les bordures pour un look plus propre */
        background-color: #343a40;
        color: white;
        font-size: 1.5em;

    }
    
    .list-group-item:hover, .list-group-item:focus {
        background-color: #495057; /* Couleur de survol */
        color: #ffffff;
        text-decoration: none;
        
    }
    
    .list-group-item i {
        width: 30px; /* Espace pour les icônes */
        min-width: 30px;
        text-align: center;
        
    }
    
    .d-flex.align-items-center {
        display: flex;
        align-items: center;

    }
    </style>

    <div class=" border-right" id="sidebar-wrapper" >
        <div class="sidebar-heading">Salon de Beauté</div>
        <div class="list-group list-group-flush">
            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center text-decoration-none">
                <i class="fas fa-tachometer-alt fa-fw me-3"></i>Dashboard
            </a>
            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center text-decoration-none">
                <i class="fas fa-users fa-fw me-3"></i>Clients
            </a>
            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center text-decoration-none">
                <i class="fas fa-boxes fa-fw me-3"></i>Stock
            </a>
            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center text-decoration-none">
                <i class="fas fa-cash-register fa-fw me-3"></i>Caisses
            </a>
            <a href="{{ route('produits.index') }}" class="list-group-item list-group-item-action d-flex align-items-center text-decoration-none">
                <i class="fas fa-shopping-cart fa-fw me-3"></i>Produits
            </a>
            <a href="{{ route('ventes.index') }}" class="list-group-item list-group-item-action d-flex align-items-center text-decoration-none">
                <i class="fas fa-chart-line fa-fw me-3"></i>Ventes
            </a>
            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center text-decoration-none">
                <i class="fas fa-truck fa-fw me-3"></i>Fournisseurs
            </a>
        </div>
    </div>
    
    
</body>