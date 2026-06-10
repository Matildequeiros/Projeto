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

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="mb-0" style="color: #1a826d;">
                        <i class="fa-solid fa-location-dot me-2"></i>
                        <strong>Listagem de Localizações</strong>
                    </h2>

                    <a href="novo_localizacoes.php" class="btn" style="background-color: #1a826d; color: white;">
                        <i class="fa-solid fa-plus me-2"></i> Nova Localização
                    </a>
                </div>

                <hr>

                <!-- FILTROS LOCALIZAÇÕES -->
                <div class="filtros-box">

                    <!-- Pesquisa geral -->
                    <div class="mb-3">
                        <input type="text" id="pesquisaLocalizacao" class="form-control search-input"
                            placeholder="Edifício, piso, serviço...">
                    </div>

                    <div class="row g-3">

                        <!-- Código -->
                        <div class="col-md-3">
                            <label class="form-label">Código</label>
                            <input type="text" id="filtroCodigo" class="form-control" placeholder="Ex: LOC001">
                        </div>


                    </div>

                    <!-- Botões -->
                    <div class="mt-3 d-flex justify-content-end gap-2">
                        <button class="btn btn-secondary" onclick="limparFiltrosLocalizacao()">Limpar</button>
                        <button class="btn btn-filtrar" onclick="aplicarFiltrosLocalizacao()">Aplicar filtros</button>
                    </div>

                </div>



                <p class="text-muted">Não existem localizações registadas.</p>

                <div class="table-responsive rounded-4 shadow-sm border p-0">

                    <table class="table table-bordered table-striped align-middle" style="min-width: 1100px;">
                        <thead>
                            <tr style="background-color: #1a826d; color: white;">
                                <th>Código</th>
                                <th>Edifício</th>
                                <th>Piso</th>
                                <th>Serviço</th>
                                <th>Sala</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>[codigo]</td>
                                <td>[edificio]</td>
                                <td>[piso]</td>
                                <td>[servico]</td>
                                <td>[sala]</td>

                                <td class="text-center">
                                    <a href="detalhes_localizacoes.php" class="acao-box">
                                        <i class="fa-solid fa-eye"></i> Consultar
                                    </a>

                                    <a href="editar_localizacoes.php" class="acao-box">
                                        <i class="fa-solid fa-pen"></i> Editar
                                    </a>

                                    <a class="acao-box" style="cursor: pointer;" onclick="abrirModalApagarLocalizacao(
       'LOC001',
       'Edifício A',
       'Piso 2',
       'Urgência',
       'Sala 12'
   )">
                                        <i class="fa-solid fa-trash"></i> Eliminar
                                    </a>

                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </main>

        </div>
    </div>


    <!-- MODAL REMOVER LOCALIZAÇÃO -->
    <div class="modal fade" id="modalApagarLocalizacao" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 750px;">
            <div class="modal-content" style="border-radius: 12px; padding: 25px 35px;">

                <h2 class="mb-4" style="color: #1a826d; font-weight: 700;">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> Remover Localização
                </h2>

                <p class="mb-3" style="font-size: 17px;">
                    Tem a certeza que pretende remover a seguinte localização?
                    <br>
                    <strong>Esta ação é irreversível.</strong>
                </p>

                <hr>

                <!-- DADOS DA LOCALIZAÇÃO -->
                <div id="dadosLocalizacao" style="font-size: 16px;">
                    <!-- Preenchido dinamicamente pelo JS -->
                </div>

                <hr>

                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                    <a href="lista_localizacoes.php" id="btnConfirmarApagarLocalizacao" class="btn"
                        style="background-color: #1a826d; color: white;">
                        <i class="fa-solid fa-trash me-2"></i> Remover Localização
                    </a>

                </div>

            </div>
        </div>
    </div>

<?php include '../../includes/footer.php'; ?>