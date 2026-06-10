<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $resultados = $ligacao->query("SELECT e.*, l.edificio, l.piso, l.sala, s.nome AS servico
        FROM equipamentos e
        LEFT JOIN localizacoes l ON e.localizacao_id = l.id
        LEFT JOIN servicos s ON l.servico_id = s.id
    ")->fetchAll(PDO::FETCH_OBJ);

    $erro = '';
} catch (PDOException $err) {
    $erro = "Aconteceu um erro na ligação.";
    $resultados = [];
}

$ligacao = null;
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
                    <i class="fa-solid fa-stethoscope me-2"></i>
                    <strong>Listagem de Equipamentos</strong>
                </h2>

                <a href="novo.php" class="btn" style="background-color: #1a826d; color: white;">
                    <i class="fa-solid fa-plus me-2"></i> Novo Equipamento
                </a>
            </div>

            <hr>

            <!-- FILTROS -->
            <div class="filtros-box">

                <!-- Pesquisa -->
                <div class="mb-3">
                    <input type="text" id="pesquisa" class="form-control search-input"
                        placeholder="Código, designação, marca, modelo …">
                </div>

                <!-- Filtros avançados -->
                <div class="row g-3">

                    <div class="col-md-2">
                        <label class="form-label">Estado</label>
                        <select id="filtroEstado" class="form-select">
                            <option value="">Todos</option>
                            <option>Ativo</option>
                            <option>Em manutenção</option>
                            <option>Inativo</option>
                            <option>Em calibração</option>
                            <option>Em quarentena</option>
                            <option>Abatido</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Categoria</label>
                        <select id="filtroCategoria" class="form-select">
                            <option value="">Todas</option>
                            <option>Monitorização</option>
                            <option>Suporte de vida</option>
                            <option>Terapia</option>
                            <option>Diagnóstico</option>
                            <option>Laboratório</option>
                            <option>Esterilização</option>
                            <option>Reabilitação</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Criticidade</label>
                        <select id="filtroCriticidade" class="form-select">
                            <option value="">Todas</option>
                            <option>Baixa</option>
                            <option>Média</option>
                            <option>Alta</option>
                            <option>Suporte de vida</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Serviço</label>
                        <select id="filtroServico" class="form-select">
                            <option value="">Todos</option>
                            <option>Urgência</option>
                            <option>UCI</option>
                            <option>Medicina</option>
                            <option>Bloco Operatório</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Fornecedor</label>
                        <select id="filtroFornecedor" class="form-select">
                            <option value="">Todos</option>
                            <option>MedTech Solutions</option>
                            <option>Dräger</option>
                            <option>B. Braun</option>
                            <option>Zoll</option>
                        </select>
                    </div>

                </div>

                <!-- Botões -->
                <div class="mt-3 d-flex justify-content-end gap-2">
                    <button class="btn btn-secondary" onclick="limparFiltros()">Limpar</button>
                    <button class="btn btn-filtrar" onclick="aplicarFiltros()">Aplicar filtros</button>
                </div>

            </div>


            <?php if (!empty($erro)) : ?>
                <p class="text-center text-danger"><?= $erro ?></p>
            <?php else : ?>
                <?php if (count($resultados) == 0) : ?>
                    <p class="text-muted">Não existem equipamentos registados.</p>
                <?php else : ?>

                    <div class="table-responsive rounded-4 shadow-sm border p-0" style="overflow-x: auto;">

                        <table class="table table-bordered table-striped align-middle" style="min-width: 1300px;">
                            <thead>
                                <tr style="background-color: #1a826d; color: white;">
                                    <th>Código</th>
                                    <th>Designação</th>
                                    <th>Estado</th>
                                    <th>Criticidade</th>
                                    <th>Localização</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($resultados as $equipamento) : ?>
                                    <tr>
                                        <td><?= $equipamento->codigo ?></td>
                                        <td><?= $equipamento->designacao ?></td>
                                        <td><?= $equipamento->estado ?></td>
                                        <td><?= $equipamento->criticidade ?></td>
                                        <td><?= $equipamento->edificio . ' / ' . $equipamento->piso . ' / ' . $equipamento->sala ?></td>
                                        <td style="text-align: center;">
                                            <a href="detalhes.php?id=<?= $equipamento->id ?>" class="acao-box">
                                                <i class="fa-solid fa-eye"></i> Consultar
                                            </a>
                                            <a href="editar.php?id=<?= $equipamento->id ?>" class="acao-box">
                                                <i class="fa-solid fa-pen"></i> Editar
                                            </a>
                                            <a class="acao-box" style="cursor: pointer;"
                                                onclick="abrirModalApagar('lista.php', '<?= $equipamento->codigo ?>', '<?= $equipamento->designacao ?>')">
                                                <i class="fa-solid fa-trash"></i> Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                <?php endif; ?>
            <?php endif; ?>

            <div class="col">
                <p class="mb-5">Total: <strong><?= count($resultados) ?></strong></p>
            </div>



        </main>

    </div>
</div>


<!-- MODAL REMOVER EQUIPAMENTO (ESTILO IGUAL AO APAGAR.HTML) -->
<div class="modal fade" id="modalApagar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 750px;">
        <div class="modal-content" style="border-radius: 12px; padding: 25px 35px;">

            <h2 class="mb-3" style="color: #1a826d; font-weight: 700;">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> Remover Equipamento
            </h2>

            <p class="mb-3" style="font-size: 17px;">
                Tem a certeza que pretende remover o seguinte equipamento?
                <br>
                <strong>Esta ação é irreversível.</strong>
            </p>

            <hr>

            <!-- DADOS DO EQUIPAMENTO -->
            <div id="dadosApagar" style="font-size: 16px;">
                <!-- Estes dados são preenchidos pelo JS -->
            </div>

            <hr>

            <div class="d-flex justify-content-between mt-4">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                <a href="lista.php" id="btnConfirmarApagar" class="btn"
                    style="background-color: #1a826d; color: white;">
                    <i class="fa-solid fa-trash me-2"></i> Remover Equipamento
                </a>

            </div>

        </div>
    </div>
</div>


<?php include '../../includes/footer.php'; ?>