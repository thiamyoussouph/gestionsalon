<style>
    #sidebar-wrapper {
        background-color: #343a40;
        color: #ffffff;
        height: 100vh;
        width: 250px;
        position: sticky;
        top: 0;
    }
    .sidebar-heading {
        padding: 15px;
        font-size: 1.1em;
        font-weight: bold;
        background-color: #23272b;
        border-bottom: 1px solid #495057;
    }
    .list-group-item {
        border: none;
        background-color: #343a40;
        color: #ced4da;
        font-size: 0.95em;
        padding: 12px 15px;
    }
    .list-group-item:hover, .list-group-item.active {
        background-color: #495057;
        color: #ffffff;
        text-decoration: none;
    }
    .list-group-item i {
        width: 25px;
        text-align: center;
    }
</style>

<div id="sidebar-wrapper">
    <div class="sidebar-heading">
        <i class="fas fa-cut me-2"></i>Salon de Beauté
    </div>
    <div class="list-group list-group-flush">
        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2 text-decoration-none {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt fa-fw"></i> Dashboard
        </a>
        <a href="{{ route('produits.index') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2 text-decoration-none {{ request()->routeIs('produits.*') ? 'active' : '' }}">
            <i class="fas fa-box fa-fw"></i> Produits
        </a>
        <a href="{{ route('categories.index') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2 text-decoration-none {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="fas fa-tags fa-fw"></i> Catégories
        </a>
        <a href="{{ route('ventes.index') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2 text-decoration-none {{ request()->routeIs('ventes.*') ? 'active' : '' }}">
            <i class="fas fa-cash-register fa-fw"></i> Ventes
        </a>
    </div>
</div>
