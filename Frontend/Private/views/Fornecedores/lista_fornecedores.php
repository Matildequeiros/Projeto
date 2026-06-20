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
    SELECT f.*, 
        (SELECT COUNT(*) FROM equipamento_fornecedor ef WHERE ef.fornecedor_id = f.id) AS total_equipamentos
    FROM fornecedores f
    ORDER BY f.codigo
")->fetchAll(PDO::FETCH_OBJ);

    $fornecedores_ativos = $ligacao->query("
    SELECT id, codigo, nome FROM fornecedores WHERE fornecedor_ativo = 1 ORDER BY codigo
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
                    <i class="fa-solid fa-truck-medical me-2"></i>
                    <strong>Listagem de Fornecedores</strong>
                </h2>
                <a href="novo_fornecedores.php" class="btn" style="background-color: #1a826d; color: white;">
                    <i class="fa-solid fa-plus me-2"></i> Novo Fornecedor
                </a>
            </div>

            <hr>

            <div class="filtros-box">
                <div class="mb-3">
                    <input type="text" id="pesquisaFornecedor" class="form-control search-input"
                        placeholder="Código, nome, contacto, telefone …">
                </div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tipo de Fornecedor</label>
                        <select id="filtroTipoFornecedor" class="form-select">
                            <option value="">Todos</option>
                            <option>Fabricante</option>
                            <option>Distribuidor / Comercial</option>
                            <option>Assistência Técnica</option>
                            <option>Consumíveis / Acessórios</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-end gap-2">
                    <button class="btn btn-secondary" onclick="limparFiltrosFornecedor()">Limpar</button>
                    <button class="btn btn-filtrar" onclick="aplicarFiltrosFornecedor()">Aplicar filtros</button>
                </div>
            </div>

            <?php if (!empty($erro)) : ?>
                <p class="text-center text-danger"><?= $erro ?></p>
            <?php elseif (count($resultados) == 0) : ?>
                <p class="text-muted">Não existem fornecedores registados.</p>
            <?php else : ?>

                <div class="table-responsive rounded-4 shadow-sm border p-0">
                    <table id="tabela-fornecedores" class="table table-bordered table-striped align-middle w-100">
                        <thead class="text-center">
                            <tr style="background-color: #1a826d; color: white;">
                                <th>Código</th>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Telefone</th>
                                <th>Pessoa de Contacto</th>
                                <th>Telefone Contacto</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultados as $fornecedor) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($fornecedor->codigo) ?></td>
                                    <td><?= htmlspecialchars($fornecedor->nome) ?></td>
                                    <td>
                                        <?php
                                        $badgeTipo = match ($fornecedor->tipo_fornecedor) {
                                            'Fabricante'              => 'badge-ativo',
                                            'Distribuidor / Comercial' => 'badge-calibracao',
                                            'Assistência Técnica'     => 'badge-quarentena',
                                            'Consumíveis / Acessórios' => 'badge-manutencao',
                                            default                   => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $badgeTipo ?>"><?= htmlspecialchars($fornecedor->tipo_fornecedor) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($fornecedor->telefone) ?></td>
                                    <td><?= htmlspecialchars($fornecedor->pessoa_contacto) ?></td>
                                    <td><?= htmlspecialchars($fornecedor->telefone_contacto) ?></td>
                                    <td class="text-center" style="white-space: nowrap;">
                                        <a href="detalhes_fornecedores.php?id_fornecedor=<?= aes_encrypt($fornecedor->id) ?>" class="acao-box" title="Consultar">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <?php if ($fornecedor->fornecedor_ativo == 1): ?>
                                            <a href="editar_fornecedores.php?id_fornecedor=<?= aes_encrypt($fornecedor->id) ?>" class="acao-box" title="Editar">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <?php if ($fornecedor->total_equipamentos > 0): ?>
                                                <a class="acao-box" style="cursor: pointer;" title="Eliminar"
                                                    onclick="abrirModalApagarFornecedorComSubstituicao('<?= aes_encrypt($fornecedor->id) ?>', '<?= $fornecedor->codigo ?>', <?= $fornecedor->total_equipamentos ?>)">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            <?php else: ?>
                                                <a class="acao-box" style="cursor: pointer;" title="Eliminar"
                                                    onclick="abrirModalApagarFornecedor('<?= aes_encrypt($fornecedor->id) ?>', '<?= $fornecedor->codigo ?>', '<?= htmlspecialchars($fornecedor->nome, ENT_QUOTES) ?>', '<?= htmlspecialchars($fornecedor->tipo_fornecedor, ENT_QUOTES) ?>')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge me-1" style="background-color: #D3D1C7; color: #2C2C2A;">Removido</span>
                                            <a class="acao-box" style="cursor: pointer;" title="Reativar"
                                                onclick="abrirModalReativarFornecedor('<?= aes_encrypt($fornecedor->id) ?>', '<?= $fornecedor->codigo ?>', '<?= htmlspecialchars($fornecedor->nome, ENT_QUOTES) ?>')">
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
                    <i class="fa-solid fa-truck-medical me-2"></i> Total de fornecedores: <?= count($resultados) ?>
                </span>
            </div>

        </main>
    </div>
</div>

<!-- MODAL REMOVER -->
<div class="modal fade" id="modalApagarFornecedor" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 750px;">
        <div class="modal-content" style="border-radius: 12px; padding: 25px 35px;">
            <h2 class="mb-4" style="color: #1a826d; font-weight: 700;">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> Remover Fornecedor
            </h2>
            <p class="mb-3" style="font-size: 17px;">
                Tem a certeza que pretende remover o seguinte fornecedor?
            </p>
            <hr>
            <div id="dadosFornecedor" style="font-size: 16px;"></div>
            <hr>
            <div class="d-flex justify-content-between mt-4">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="lista_fornecedores.php" id="btnConfirmarApagarFornecedor" class="btn"
                    style="background-color: #1a826d; color: white;">
                    <i class="fa-solid fa-trash me-2"></i> Remover Fornecedor
                </a>
            </div>
        </div>
    </div>
</div>

<!-- MODAL REATIVAR -->
<div class="modal fade" id="modalReativarFornecedor" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 750px;">
        <div class="modal-content" style="border-radius: 12px; padding: 25px 35px;">
            <h2 class="mb-4" style="color: #1a826d; font-weight: 700;">
                <i class="fa-solid fa-rotate-left me-2"></i> Reativar Fornecedor
            </h2>
            <p class="mb-3" style="font-size: 17px;">
                Tem a certeza que pretende reativar o seguinte fornecedor?
            </p>
            <hr>
            <div id="dadosReativarFornecedor" style="font-size: 16px;"></div>
            <hr>
            <div class="d-flex justify-content-between mt-4">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="lista_fornecedores.php" id="btnConfirmarReativarFornecedor" class="btn"
                    style="background-color: #1a826d; color: white;">
                    <i class="fa-solid fa-rotate-left me-2"></i> Reativar Fornecedor
                </a>
            </div>
        </div>
    </div>
</div>

<!-- MODAL REMOVER FORNECEDOR COM SUBSTITUIÇÃO -->
<div class="modal fade" id="modalApagarFornecedorComSubstituicao" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 750px;">
        <div class="modal-content" style="border-radius: 12px; padding: 25px 35px;">
            <h2 class="mb-4" style="color: #1a826d; font-weight: 700;">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> Remover Fornecedor
            </h2>

            <p class="mb-3" style="font-size: 17px;" id="textoAvisoSubstituicaoFornecedor">
                <!-- Preenchido pelo JS -->
            </p>

            <div class="mb-3">
                <label class="form-label">Substituir por *</label>
                <select class="form-select" id="selectFornecedorSubstituto">
                    <option value="">Selecione...</option>
                    <?php foreach ($fornecedores_ativos as $fa): ?>
                        <option value="<?= aes_encrypt($fa->id) ?>">
                            <?= htmlspecialchars($fa->codigo) ?> – <?= htmlspecialchars($fa->nome) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <hr>

            <div class="d-flex justify-content-between mt-4">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn" style="background-color: #1a826d; color: white;" id="btnConfirmarSubstituicaoFornecedor">
                    <i class="fa-solid fa-trash me-2"></i> Remover e Substituir
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    var tabela;

    $(document).ready(function() {
        tabela = $('#tabela-fornecedores').DataTable({
            pageLength: 10,
            pagingType: "full_numbers",
            searching: true,
            lengthChange: false,
            info: false,
            dom: 'tp',
            language: {
                zeroRecords: "Nenhum fornecedor encontrado.",
                paginate: {
                    first: "Primeira",
                    last: "Última",
                    next: "Seguinte",
                    previous: "Anterior"
                }
            }
        });
    });

    function aplicarFiltrosFornecedor() {
        const pesquisa = document.getElementById('pesquisaFornecedor')?.value || '';
        const tipo = document.getElementById('filtroTipoFornecedor')?.value || '';

        $.fn.dataTable.ext.search.pop();

        $.fn.dataTable.ext.search.push(function(settings, data) {
            const codigo = data[0].toLowerCase();
            const nome = data[1].toLowerCase();
            const tipoLinha = data[2].toLowerCase();
            const telefone = data[3].toLowerCase();
            const pessoa = data[4].toLowerCase();

            const matchPesquisa = pesquisa === '' ||
                codigo.includes(pesquisa.toLowerCase()) ||
                nome.includes(pesquisa.toLowerCase()) ||
                telefone.includes(pesquisa.toLowerCase()) ||
                pessoa.includes(pesquisa.toLowerCase());

            const matchTipo = tipo === '' || tipoLinha.includes(tipo.toLowerCase());

            return matchPesquisa && matchTipo;
        });

        tabela.draw();
    }

    function limparFiltrosFornecedor() {
        document.getElementById('pesquisaFornecedor').value = '';
        document.getElementById('filtroTipoFornecedor').value = '';
        $.fn.dataTable.ext.search.pop();
        tabela.draw();
    }

</script>

<?php include '../../includes/footer.php'; ?>