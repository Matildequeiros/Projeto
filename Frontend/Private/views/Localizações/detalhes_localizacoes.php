<?php include '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?> 

    <div class="container-fluid">
        <div class="row">

            <?php include '../../includes/sidebar.php'; ?>


            <!-- MAIN -->
            <main class="col-md-9 col-lg-10 p-4">

                <div class="info-box">

                    <h2 class="mb-4" style="color: #1a826d;">
                        <i class="fa-solid fa-eye me-2"></i> Consultar Localização
                    </h2>

                    <!-- CAMPOS DA LOCALIZAÇÃO -->
                    <div class="col-md-6">
                        <div class="info-row">
                            <span class="info-label">Código:</span>
                            <span class="info-value">LOC001</span>
                        </div>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Edifício:</span>
                        <span class="info-value">Edifício A</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Piso:</span>
                        <span class="info-value">Piso 2</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Serviço / Departamento:</span>
                        <span class="info-value">Urgência</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Sala / Gabinete:</span>
                        <span class="info-value">Sala 12</span>
                    </div>

                    <!-- BOTÕES -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="lista_localizacoes.html" class="btn btn-secondary">Voltar</a>

                        <a href="editar_localizacoes.html" class="btn" style="background-color: #1a826d; color: white;">
                            Editar Localização
                        </a>
                    </div>

                </div>

            </main>

        </div>
    </div>

<?php include '../../includes/footer.php'; ?>
