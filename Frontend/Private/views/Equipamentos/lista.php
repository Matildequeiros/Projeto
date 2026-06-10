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
                        <label class="form-label">Criticidade</label>
                        <select id="filtroCriticidade" class="form-select">
                            <option value="">Todas</option>
                            <option>Baixa</option>
                            <option>Média</option>
                            <option>Alta</option>
                            <option>Suporte de vida</option>
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

                        <table id="tabela-equipamentos" class="table table-bordered table-striped align-middle" style="min-width: 1300px;">
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
                                        <td>
                                            <?php
                                            $badgeEstado = match ($equipamento->estado) {
                                                'Ativo' => 'badge-ativo',
                                                'Em manutenção' => 'badge-manutencao',
                                                'Inativo' => 'badge-inativo',
                                                'Em calibração' => 'badge-calibracao',
                                                'Em quarentena' => 'badge-quarentena',
                                                'Abatido' => 'badge-abatido',
                                                default => 'bg-secondary'
                                            };
                                            ?>
                                            <span class="badge <?= $badgeEstado ?>"><?= $equipamento->estado ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $badgeCriticidade = match ($equipamento->criticidade) {
                                                'Baixa' => 'badge-baixa',
                                                'Média' => 'badge-media',
                                                'Alta' => 'badge-alta',
                                                'Suporte de vida' => 'badge-suporte',
                                                default => 'bg-secondary'
                                            };
                                            ?>
                                            <span class="badge <?= $badgeCriticidade ?>"><?= $equipamento->criticidade ?></span>
                                        </td>
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

            <div class="d-flex align-items-center mt-3 mb-4">
                <span style="background-color: #d9efec; color: #1a826d; padding: 8px 18px; border-radius: 20px; font-weight: 700; font-size: 1rem;">
                    <i class="fa-solid fa-stethoscope me-2"></i> Total de equipamentos: <?= count($resultados) ?>
                </span>
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


<script>
    var tabela;

    $(document).ready(function() {
        tabela = $('#tabela-equipamentos').DataTable({
            pageLength: 10,
            pagingType: "full_numbers",
            searching: true,
            lengthChange: false,
            info: false,
            dom: 'tp',
            language: {
                paginate: {
                    first: "Primeira",
                    last: "Última",
                    next: "Seguinte",
                    previous: "Anterior"
                }
            }
        });
    });

    function aplicarFiltros() {
        const pesquisa = document.getElementById('pesquisa')?.value || '';
        const estado = document.getElementById('filtroEstado')?.value || '';
        const criticidade = document.getElementById('filtroCriticidade')?.value || '';

        tabela.search(pesquisa);
        tabela.column(2).search(estado ? '^' + estado + '$' : '', true, false);
        tabela.column(3).search(criticidade ? '^' + criticidade + '$' : '', true, false);
        tabela.draw();
    }

    function limparFiltros() {
        document.getElementById('pesquisa').value = '';
        document.getElementById('filtroEstado').value = '';
        document.getElementById('filtroCriticidade').value = '';
        tabela.search('').columns().search('').draw();
    }
</script>

<?php include '../../includes/footer.php'; ?>