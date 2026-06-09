<?php include '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?>

<div class="container-fluid">
    <div class="row">

        <?php include '../../includes/sidebar.php'; ?>


        <!-- MAIN CONTENT -->
        <main class="col-md-9 col-lg-10 p-4">

            <div class="info-box">

                <h2 class="mb-4" style="color: #1a826d;">
                    <i class="fa-solid fa-eye me-2"></i> Consultar Fornecedor
                </h2>

                <!-- CAMPOS COMPLETOS -->
                <div class="info-row"><span class="info-label">Código Interno:</span> <span class="info-value">F001</span></div>
                <div class="info-row"><span class="info-label">Nome da Empresa:</span> <span class="info-value">MedTech Solutions</span></div>
                <div class="info-row"><span class="info-label">NIF:</span> <span class="info-value">509123456</span></div>
                <div class="info-row"><span class="info-label">Telefone Geral:</span> <span class="info-value">253 000 000</span></div>
                <div class="info-row"><span class="info-label">Email Geral:</span> <span class="info-value">contacto@medtech.com</span></div>
                <div class="info-row"><span class="info-label">Morada:</span> <span class="info-value">Rua da Saúde, nº 25, Porto</span></div>
                <div class="info-row"><span class="info-label">Website:</span> <span class="info-value">www.medtech.com</span></div>
                <div class="info-row"><span class="info-label">Pessoa de Contacto:</span> <span class="info-value">Marta Costa</span></div>
                <div class="info-row"><span class="info-label">Telefone da Pessoa de Contacto:</span> <span class="info-value">934567890</span></div>
                <div class="info-row"><span class="info-label">Tipo:</span> <span class="info-value">Distribuidor / Comercial</span></div>
                <div class="info-row"><span class="info-label">Observações:</span> <span class="info-value">Fornecedor habitual de monitores e sensores.</span></div>

                <!-- BOTÕES -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="lista_fornecedores.php" class="btn btn-secondary">Voltar</a>
                    <a href="editar_fornecedores.php" class="btn" style="background-color: #1a826d; color: white;">
                        Editar Fornecedor
                    </a>
                </div>

            </div>

        </main>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>