<?php include '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?> 


    <div class="container-fluid">
        <div class="row">

            <?php include '../../includes/sidebar.php'; ?>


            <!-- CONTEÚDO PRINCIPAL -->
            <main class="col-md-9 col-lg-10 p-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="mb-0" style="color: #1a826d;">
                        <i class="fa-solid fa-truck-medical me-2"></i>
                        <strong>Listagem de Fornecedores</strong>
                    </h2>

                    <a href="novo_fornecedores.html" class="btn" style="background-color: #1a826d; color: white;">
                        <i class="fa-solid fa-plus me-2"></i> Novo Fornecedor
                    </a>
                </div>

                <hr>

                <!-- FILTROS FORNECEDORES -->
                <div class="filtros-box">

                    <!-- Pesquisa geral -->
                    <div class="mb-3">
                        <input type="text" id="pesquisaFornecedor" class="form-control search-input"
                            placeholder="Código, nome, Contacto, Telefone, …">
                    </div>

                    <div class="row g-3">

                        <!-- Tipo de fornecedor -->
                        <div class="col-md-3">
                            <label class="form-label">Tipo de Fornecedor</label>
                            <select id="filtroTipoFornecedor" class="form-select">
                                <option value="">Todos</option>
                                <option>Fabricante</option>
                                <option>Distribuidor</option>
                                <option>Assistência técnica</option>
                                <option>Consumíveis</option>
                            </select>
                        </div>

                    </div>

                    <!-- Botões -->
                    <div class="mt-3 d-flex justify-content-end gap-2">
                        <button class="btn btn-secondary" onclick="limparFiltrosFornecedor()">Limpar</button>
                        <button class="btn btn-filtrar" onclick="aplicarFiltrosFornecedor()">Aplicar filtros</button>
                    </div>

                </div>


                <p class="text-muted">Não existem fornecedores registados.</p>

                <div class="table-responsive rounded-4 shadow-sm border p-0">

                    <table class="table table-bordered table-striped align-middle" style="min-width: 1100px;">
                        <thead>
                            <tr style="background-color: #1a826d; color: white;">
                                <th>Código Interno</th>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Telefone Geral</th>
                                <th>Telefone da Pessoa de Contacto</th>
                                <th>Estado</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>[codigo interno]</td>
                                <td>[nome]</td>
                                <td>[tipo]</td>
                                <td>[telefone geral]</td>
                                <td>[telefone da pessoa de contacto]</td>
                                <td>[estado]</td>

                                <td class="text-center">
                                    <a href="detalhes_fornecedores.html" class="acao-box">
                                        <i class="fa-solid fa-eye"></i> Consultar
                                    </a>

                                    <a href="editar_fornecedores.html" class="acao-box">
                                        <i class="fa-solid fa-pen"></i> Editar
                                    </a>

                                    <a class="acao-box" style="cursor: pointer;" onclick="abrirModalApagarFornecedor(
       'F001',
    'MedSupply Portugal',
    'Distribuidor / Comercial'
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

    <script src="../../../assets/bootstrap/bootstrap.bundle.min.js"></script>

    <!-- MODAL REMOVER FORNECEDOR (ESTILO IGUAL AO APAGAR.HTML) -->
    <div class="modal fade" id="modalApagarFornecedor" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 750px;">
            <div class="modal-content" style="border-radius: 12px; padding: 25px 35px;">

                <h2 class="mb-4" style="color: #1a826d; font-weight: 700;">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> Remover Fornecedor
                </h2>

                <p class="mb-3" style="font-size: 17px;">
                    Tem a certeza que pretende remover o seguinte fornecedor?
                    <br>
                    <strong>Esta ação é irreversível.</strong>
                </p>

                <hr>

                <!-- DADOS DO FORNECEDOR -->
                <div id="dadosFornecedor" style="font-size: 16px;">
                    <!-- Preenchido dinamicamente pelo JS -->
                </div>

                <hr>

                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                    <a href="lista_fornecedores.html" id="btnConfirmarApagarFornecedor" class="btn"
                        style="background-color: #1a826d; color: white;">
                        <i class="fa-solid fa-trash me-2"></i> Remover Fornecedor
                    </a>

                </div>

            </div>
        </div>
    </div>

    

<?php include '../../includes/footer.php'; ?>

