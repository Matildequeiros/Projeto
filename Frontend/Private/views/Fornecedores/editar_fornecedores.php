<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?> 

    <div class="container-fluid">
        <div class="row">

            <?php include '../../includes/sidebar.php'; ?>

            <!-- CONTEÚDO PRINCIPAL -->
            <main class="col-md-9 col-lg-10 p-4">

                <div class="card-form">

                    <h2 class="mb-4" style="color: #1a826d;">
                        <i class="fa-solid fa-pen-to-square me-2"></i> Editar Fornecedor
                    </h2>

                    <form>

                        <!-- Código Interno -->
                        <div class="mb-3">
                            <label class="form-label">Código Interno *</label>
                            <input type="text" class="form-control" placeholder="Ex: F001">
                        </div>

                        <!-- Nome + NIF -->
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Nome da Empresa *</label>
                                <input type="text" class="form-control" placeholder="Ex: MedTech Solutions">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">NIF *</label>
                                <input type="number" class="form-control" placeholder="123456789">
                            </div>
                        </div>

                        <!-- Contacto + Email -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telefone Geral *</label>
                                <input type="number" class="form-control" placeholder="253 000 000">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Geral *</label>
                                <input type="email" class="form-control" placeholder="email@empresa.com">
                            </div>
                        </div>

                        <!-- Morada -->
                        <div class="mb-3">
                            <label class="form-label">Morada *</label>
                            <input type="text" class="form-control" placeholder="Rua, nº, cidade">
                        </div>

                        <!-- Website + Pessoa de contacto -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Website</label>
                                <input type="text" class="form-control" placeholder="www.empresa.com">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pessoa de Contacto</label>
                                <input type="text" class="form-control" placeholder="Nome da pessoa">
                            </div>
                        </div>

                        <!-- Telefone pessoa contacto + Tipo -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telefone da Pessoa de Contacto</label>
                                <input type="number" class="form-control" placeholder="912345678">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipo *</label>
                                <select class="form-select">
                                    <option>Fabricante</option>
                                    <option>Distribuidor / Comercial</option>
                                    <option>Assistência Técnica</option>
                                    <option>Consumíveis / Acessórios</option>
                                </select>
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="mb-3">
                            <label class="form-label">Observações</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="lista_fornecedores.php" class="btn btn-secondary">Cancelar</a>

                            <a href="lista_fornecedores.php" class="btn"
                                style="background-color: #1a826d; color: white;">
                                Guardar Alterações
                            </a>
                        </div>

                    </form>

                </div>

            </main>


        </div>
    </div>

<?php include '../../includes/footer.php'; ?>
