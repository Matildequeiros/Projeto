<?php include '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?>

    <div class="container-fluid">
        <div class="row">

            <?php include '../../includes/sidebar.php'; ?>


            <!-- CONTEÚDO PRINCIPAL -->
            <main class="col-md-9 col-lg-10 p-4">

                <div class="card-form">

                    <h2 class="mb-4" style="color: #1a826d;">
                        <i class="fa-solid fa-plus me-2"></i> Nova Localização
                    </h2>

                    <form>

                        <!-- Código -->
                        <div class="mb-3">
                            <label class="form-label">Código *</label>
                            <input type="text" class="form-control" placeholder="Ex: LOC001">
                        </div>

                        <!-- Edifício -->
                        <div class="mb-3">
                            <label class="form-label">Edifício *</label>
                            <input type="text" class="form-control" placeholder="Ex: Edifício A">
                        </div>

                        <!-- Piso + Serviço -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Piso *</label>
                                <input type="text" class="form-control" placeholder="Ex: Piso 2">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Serviço / Departamento *</label>
                                <input type="text" class="form-control" placeholder="Ex: Urgência">
                            </div>
                        </div>

                        <!-- Sala -->
                        <div class="mb-3">
                            <label class="form-label">Sala / Gabinete *</label>
                            <input type="text" class="form-control" placeholder="Ex: Sala 12">
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="lista_localizacoes.php" class="btn btn-secondary">Cancelar</a>
                            <a href="lista_localizacoes.php" class="btn"
                                style="background-color: #1a826d; color: white;">
                                Guardar Localização
                            </a>

                        </div>

                    </form>

                </div>

            </main>

        </div>
    </div>

<?php include '../../includes/footer.php'; ?>