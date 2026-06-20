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

    $resultados = $ligacao->query("
        SELECT l.*, s.nome AS servico 
        FROM localizacoes l 
        LEFT JOIN servicos s ON l.servico_id = s.id 
        ORDER BY l.codigo
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

            <div class="filtros-box">
                <div class="mb-3">
                    <input type="text" id="pesquisaLocalizacao" class="form-control search-input"
                        placeholder="Edifício, piso, serviço, sala …">
                </div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Serviço</label>
                        <select id="filtroServico" class="form-select">
                            <option value="">Todos</option>
                            <?php
                            $servicos = array_unique(array_map(fn($r) => $r->servico, $resultados));
                            sort($servicos);
                            foreach ($servicos as $s): ?>
                                <option><?= htmlspecialchars($s) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Edifício</label>
                        <select id="filtroEdificio" class="form-select">
                            <option value="">Todos</option>
                            <?php
                            $edificios = array_unique(array_map(fn($r) => $r->edificio, $resultados));
                            sort($edificios);
                            foreach ($edificios as $e): ?>
                                <option><?= htmlspecialchars($e) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-end gap-2">
                    <button class="btn btn-secondary" onclick="limparFiltrosLocalizacao()">Limpar</button>
                    <button class="btn btn-filtrar" onclick="aplicarFiltrosLocalizacao()">Aplicar filtros</button>
                </div>
            </div>

            <?php if (!empty($erro)) : ?>
                <p class="text-center text-danger"><?= $erro ?></p>
            <?php elseif (count($resultados) == 0) : ?>
                <p class="text-muted">Não existem localizações registadas.</p>
            <?php else : ?>

                <div class="table-responsive rounded-4 shadow-sm border p-0">
                    <table id="tabela-localizacoes" class="table table-bordered table-striped align-middle w-100">
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
                            <?php foreach ($resultados as $loc) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($loc->codigo) ?></td>
                                    <td><?= htmlspecialchars($loc->edificio) ?></td>
                                    <td><?= htmlspecialchars($loc->piso) ?></td>
                                    <td><?= htmlspecialchars($loc->servico) ?></td>
                                    <td><?= htmlspecialchars($loc->sala) ?></td>
                                    <td class="text-center" style="white-space: nowrap;">
                                        <a href="detalhes_localizacoes.php?id_localizacao=<?= aes_encrypt($loc->id) ?>" class="acao-box" title="Consultar">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <?php if ($loc->localizacao_ativa == 1): ?>
                                            <a href="editar_localizacoes.php?id_localizacao=<?= aes_encrypt($loc->id) ?>" class="acao-box" title="Editar">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <a class="acao-box" style="cursor: pointer;" title="Eliminar"
                                                onclick="abrirModalApagarLocalizacao('<?= aes_encrypt($loc->id) ?>', '<?= $loc->codigo ?>', '<?= htmlspecialchars($loc->edificio, ENT_QUOTES) ?>', '<?= htmlspecialchars($loc->piso, ENT_QUOTES) ?>', '<?= htmlspecialchars($loc->servico, ENT_QUOTES) ?>', '<?= htmlspecialchars($loc->sala, ENT_QUOTES) ?>')">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="badge bg-dark me-1">Removido</span>
                                            <a class="acao-box" style="cursor: pointer;" title="Reativar"
                                                onclick="abrirModalReativarLocalizacao('<?= aes_encrypt($loc->id) ?>', '<?= $loc->codigo ?>', '<?= htmlspecialchars($loc->edificio, ENT_QUOTES) ?>', '<?= htmlspecialchars($loc->piso, ENT_QUOTES) ?>', '<?= htmlspecialchars($loc->servico, ENT_QUOTES) ?>', '<?= htmlspecialchars($loc->sala, ENT_QUOTES) ?>')">
                                                <i class="fa-solid fa-rotate-left"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

            <div class="d-flex align-items-center mt-3 mb-4">
                <span style="background-color: #d9efec; color: #1a826d; padding: 8px 18px; border-radius: 20px; font-weight: 700; font-size: 1rem;">
                    <i class="fa-solid fa-location-dot me-2"></i> Total de localizações: <?= count($resultados) ?>
                </span>
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
            </p>
            <hr>
            <div id="dadosLocalizacao" style="font-size: 16px;"></div>
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

<!-- MODAL REATIVAR LOCALIZAÇÃO -->
<div class="modal fade" id="modalReativarLocalizacao" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 750px;">
        <div class="modal-content" style="border-radius: 12px; padding: 25px 35px;">
            <h2 class="mb-4" style="color: #1a826d; font-weight: 700;">
                <i class="fa-solid fa-rotate-left me-2"></i> Reativar Localização
            </h2>
            <p class="mb-3" style="font-size: 17px;">
                Tem a certeza que pretende reativar a seguinte localização?
            </p>
            <hr>
            <div id="dadosReativarLocalizacao" style="font-size: 16px;"></div>
            <hr>
            <div class="d-flex justify-content-between mt-4">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="lista_localizacoes.php" id="btnConfirmarReativarLocalizacao" class="btn"
                    style="background-color: #1a826d; color: white;">
                    <i class="fa-solid fa-rotate-left me-2"></i> Reativar Localização
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    var tabela;

    $(document).ready(function() {
        tabela = $('#tabela-localizacoes').DataTable({
            pageLength: 10,
            pagingType: "full_numbers",
            searching: true,
            lengthChange: false,
            info: false,
            dom: 'tp',
            language: {
                zeroRecords: "Nenhuma localização encontrada.",
                paginate: {
                    first: "Primeira",
                    last: "Última",
                    next: "Seguinte",
                    previous: "Anterior"
                }
            }
        });
    });

    function aplicarFiltrosLocalizacao() {
        const pesquisa = document.getElementById('pesquisaLocalizacao')?.value || '';
        const servico = document.getElementById('filtroServico')?.value || '';
        const edificio = document.getElementById('filtroEdificio')?.value || '';

        $.fn.dataTable.ext.search.pop();

        $.fn.dataTable.ext.search.push(function(settings, data) {
            const codigo = data[0].toLowerCase();
            const edificioLinha = data[1].toLowerCase();
            const piso = data[2].toLowerCase();
            const servicoLinha = data[3].toLowerCase();
            const sala = data[4].toLowerCase();

            const matchPesquisa = pesquisa === '' ||
                codigo.includes(pesquisa.toLowerCase()) ||
                edificioLinha.includes(pesquisa.toLowerCase()) ||
                piso.includes(pesquisa.toLowerCase()) ||
                servicoLinha.includes(pesquisa.toLowerCase()) ||
                sala.includes(pesquisa.toLowerCase());

            const matchServico = servico === '' || servicoLinha.includes(servico.toLowerCase());
            const matchEdificio = edificio === '' || edificioLinha.includes(edificio.toLowerCase());

            return matchPesquisa && matchServico && matchEdificio;
        });

        tabela.draw();
    }

    function limparFiltrosLocalizacao() {
        document.getElementById('pesquisaLocalizacao').value = '';
        document.getElementById('filtroServico').value = '';
        document.getElementById('filtroEdificio').value = '';
        $.fn.dataTable.ext.search.pop();
        tabela.draw();
    }

    function abrirModalApagarLocalizacao(codigo, edificio, piso, servico, sala) {
        document.getElementById('dadosLocalizacao').innerHTML = `
            <p><strong>Código:</strong> ${codigo}</p>
            <p><strong>Edifício:</strong> ${edificio}</p>
            <p><strong>Piso:</strong> ${piso}</p>
            <p><strong>Serviço:</strong> ${servico}</p>
            <p><strong>Sala:</strong> ${sala}</p>
        `;
        new bootstrap.Modal(document.getElementById('modalApagarLocalizacao')).show();
    }
</script>

<?php include '../../includes/footer.php'; ?>