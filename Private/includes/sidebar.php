<!-- SIDEBAR -->
<aside class="col-md-3 col-lg-2 p-3 min-vh-100 shadow-sm"
    style="background-color: #86b0aa; color: white; border-radius: 0 10px 10px 0;">

    <h4 class="fw-bold mb-4">Menu</h4>

    <nav>

        <a href="<?= BASE_URL ?>/Private/index.php" class="nav-link px-2 py-2 mb-2 d-block rounded"
            style="color: white; transition: 0.2s;">
            <i class="fas fa-chart-line me-2"></i> Dashboard
        </a>

        <a href="<?= BASE_URL ?>/Private/views/Equipamentos/lista.php" class="nav-link px-2 py-2 mb-2 d-block rounded"
            style="color: white; transition: 0.2s;">
            <i class="fas fa-stethoscope me-2"></i> Equipamentos
        </a>

        <a href="<?= BASE_URL ?>/Private/views/Fornecedores/lista_fornecedores.php"
            class="nav-link px-2 py-2 mb-2 d-block rounded" style="color: white; transition: 0.2s;">
            <i class="fas fa-truck-medical me-2"></i> Fornecedores
        </a>

        <a href="<?= BASE_URL ?>/Private/views/Localizações/lista_localizacoes.php"
            class="nav-link px-2 py-2 mb-2 d-block rounded" style="color: white; transition: 0.2s;">
            <i class="fas fa-location-dot me-2"></i> Localizações
        </a>

        <a href="<?= BASE_URL ?>/Private/views/Gestão da Área Pública/editar_public.php"
            class="nav-link px-2 py-2 mb-2 d-block rounded" style="color: white; transition: 0.2s;">
            <i class="fas fa-globe me-2"></i> Gestão da Área Pública
        </a>

    </nav>
</aside>