<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$idEquipamentoEncrypted = $_GET['id_equipamento'] ?? null;
$idEquipamento = aes_decrypt($idEquipamentoEncrypted);

if (!$idEquipamento || !is_numeric($idEquipamento)) {
    header('Location: lista.php');
    exit;
}

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $ligacao->prepare("SELECT e.*, l.codigo AS loc_codigo, l.edificio, l.piso, l.sala, s.nome AS servico
    FROM equipamentos e
    LEFT JOIN localizacoes l ON e.localizacao_id = l.id
    LEFT JOIN servicos s ON l.servico_id = s.id
    WHERE e.id = :id");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $equipamento = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$equipamento) {
        header('Location: lista.php');
        exit;
    }

    $stmt = $ligacao->prepare("SELECT * FROM componentes_consumiveis WHERE equipamento_id = :id");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $componentes = $stmt->fetchAll(PDO::FETCH_OBJ);

    if (!$equipamento) {
        header('Location: lista.php');
        exit;
    }

    $stmt = $ligacao->prepare("SELECT * FROM documentacao WHERE equipamento_id = :id ORDER BY data_documento ASC");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $documentos = $stmt->fetchAll(PDO::FETCH_OBJ);

    $stmt = $ligacao->prepare("SELECT ef.*, f.codigo AS forn_codigo, f.nome AS forn_nome, f.nif, f.telefone, f.email, f.morada, f.website
    FROM equipamento_fornecedor ef
    JOIN fornecedores f ON ef.fornecedor_id = f.id
    WHERE ef.equipamento_id = :id");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $fornecedores = $stmt->fetchAll(PDO::FETCH_OBJ);

    $stmt = $ligacao->prepare("SELECT * FROM garantias WHERE equipamento_id = :id LIMIT 1");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $garantia = $stmt->fetch(PDO::FETCH_OBJ);

    $stmt = $ligacao->prepare("SELECT * FROM contratos WHERE equipamento_id = :id LIMIT 1");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $contrato = $stmt->fetch(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    echo "<p class='text-danger'>Erro: " . $e->getMessage() . "</p>";
    exit;
}
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?>


<div class="container-fluid">
    <div class="row">

        <?php include '../../includes/sidebar.php'; ?>

        <!-- CONTEÚDO PRINCIPAL -->

        <main class="col-md-9 col-lg-10 p-4">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h2 class="mb-0" style="color: #1a826d;">
                    <i class="fa-solid fa-eye me-2"></i> Consultar Equipamento
                </h2>

                <div class="d-flex align-items-center gap-2 px-3 py-2" style="background-color: #d9efec; border-radius: 999px;">
                    <i class="fa-solid fa-stethoscope" style="color: #1a826d; font-size: 1rem;"></i>
                    <span style="font-size: 0.95rem; font-weight: 700; color: #0d4d40;">
                        <?= htmlspecialchars($equipamento->codigo) ?> — <?= htmlspecialchars($equipamento->designacao) ?>
                    </span>
                    <?php if ($equipamento->equipamento_ativo == 0): ?>
                        <span class="badge me-1" style="background-color: #D3D1C7; color: #2C2C2A;">Removido do Sistema</span>
                    <?php else: ?>
                        <span class="badge bg-success" style="border-radius: 999px;">No Sistema</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- SEPARADORES -->
            <ul class="nav nav-tabs mb-4 flex-nowrap" id="equipTabs" role="tablist">

                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#dados" type="button">
                        Equipamento
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#componentes" type="button">
                        Componentes <br> e Consumíveis
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#aquisicao" type="button">
                        Aquisição
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#fornecedor" type="button">
                        Fornecedor <br> Associado
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#localizacao" type="button">
                        Localização <br> Associada
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#garantia" type="button">
                        Garantia
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#contrato" type="button">
                        Contrato de <br> Manutenção
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#documentos" type="button">
                        Documentação <br> Associada
                    </button>
                </li>

            </ul>

            <!-- TODO O CONTEÚDO TEM DE ESTAR AQUI DENTRO -->
            <div class="tab-content">

                <!-- SEPARADOR 1 - EQUIPAMENTO -->
                <div class="tab-pane fade show active" id="dados">
                    <div class="info-box">

                        <h5 class="section-title">Identificação</h5>
                        <div class="info-row"><span class="info-label">Código Interno:</span> <span
                                class="info-value"><?= htmlspecialchars($equipamento->codigo) ?></span></div>
                        <div class="info-row"><span class="info-label">Designação:</span> <span
                                class="info-value"><?= htmlspecialchars($equipamento->designacao) ?></span></div>
                        <div class="info-row"><span class="info-label">Categoria:</span> <span
                                class="info-value"><?= htmlspecialchars($equipamento->categoria) ?></span></div>

                        <hr>

                        <h5 class="section-title">Especificações</h5>
                        <div class="info-row"><span class="info-label">Marca:</span> <span
                                class="info-value"><?= htmlspecialchars($equipamento->marca) ?></span></div>
                        <div class="info-row"><span class="info-label">Modelo:</span> <span
                                class="info-value"><?= htmlspecialchars($equipamento->modelo) ?></span></div>
                        <div class="info-row"><span class="info-label">Número de Série:</span> <span
                                class="info-value"><?= htmlspecialchars($equipamento->numero_serie) ?></span></div>
                        <div class="info-row"><span class="info-label">Fabricante:</span> <span
                                class="info-value"><?= htmlspecialchars($equipamento->fabricante) ?></span></div>

                        <hr>

                        <h5 class="section-title">Estado e Criticidade</h5>
                        <div class="info-row"><span class="info-label">Ano de Fabrico:</span> <span
                                class="info-value"><?= htmlspecialchars($equipamento->ano_fabrico) ?></span></div>
                        <div class="info-row"><span class="info-label">Estado:</span> <span
                                class="info-value"><?= htmlspecialchars($equipamento->estado) ?></span></div>
                        <div class="info-row"><span class="info-label">Criticidade:</span> <span
                                class="info-value"><?= htmlspecialchars($equipamento->criticidade) ?></span></div>

                        <hr>

                        <h5 class="section-title">Observações</h5>
                        <div class="info-row"><span class="info-label">Notas:</span> <span class="info-value">
                                <?= !empty($equipamento->observacoes) ? htmlspecialchars($equipamento->observacoes) : 'Sem observações registadas.' ?>
                            </span></div>

                    </div>
                </div>

                <!-- SEPARADOR 2 — COMPONENTES E CONSUMÍVEIS -->
                <div class="tab-pane fade" id="componentes">

                    <h5 class="section-title">Componentes e Consumíveis Associados</h5>


                    <?php if (empty($componentes)): ?>
                        <p class="text-muted">Não existem componentes ou consumíveis associados.</p>
                    <?php else: ?>
                        <div class="accordion" id="accordionComponentes">

                            <?php foreach ($componentes as $i => $comp): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingComp<?= $i ?>">
                                        <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseComp<?= $i ?>">
                                            <?= htmlspecialchars(ucfirst($comp->tipo)) ?> — <?= htmlspecialchars($comp->nome) ?>
                                        </button>
                                    </h2>

                                    <div id="collapseComp<?= $i ?>" class="accordion-collapse collapse <?= $i == 0 ? 'show' : '' ?>"
                                        data-bs-parent="#accordionComponentes">
                                        <div class="accordion-body">

                                            <h6 class="section-title">Dados do Item</h6>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="info-row">
                                                        <span class="info-label">Tipo:</span>
                                                        <span class="info-value"><?= htmlspecialchars(ucfirst($comp->tipo)) ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info-row">
                                                        <span class="info-label">Nome:</span>
                                                        <span class="info-value"><?= htmlspecialchars($comp->nome) ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="info-row">
                                                        <span class="info-label">Referência:</span>
                                                        <span class="info-value"><?= htmlspecialchars($comp->referencia ?: '—') ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info-row">
                                                        <span class="info-label">Quantidade:</span>
                                                        <span class="info-value"><?= htmlspecialchars($comp->quantidade ?: '—') ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="info-row">
                                                        <span class="info-label">Estado:</span>
                                                        <span class="info-value"><?= htmlspecialchars($comp->estado ?: '—') ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info-row">
                                                        <span class="info-label">Observações:</span>
                                                        <span class="info-value"><?= htmlspecialchars($comp->observacoes ?: '—') ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    <?php endif; ?>

                </div>


                <!-- SEPARADOR 3 — AQUISIÇÃO -->
                <div class="tab-pane fade" id="aquisicao">
                    <div class="info-box">

                        <h5 class="section-title">Aquisição</h5>

                        <div class="info-row"><span class="info-label">Data de Aquisição:</span>
                            <span class="info-value"><?= !empty($equipamento->data_aquisicao) ? date('d/m/Y', strtotime($equipamento->data_aquisicao)) : '—' ?></span>
                        </div>

                        <div class="info-row"><span class="info-label">Custo de Aquisição (€):</span>
                            <span class="info-value"><?= htmlspecialchars($equipamento->custo ?? '—') ?></span>
                        </div>

                        <div class="info-row"><span class="info-label">Tipo de Entrada:</span>
                            <span class="info-value"><?= match ($equipamento->tipo_entrada) {
                                                            'compra' => 'Compra',
                                                            'doacao' => 'Doação',
                                                            'aluguer' => 'Aluguer',
                                                            'emprestimo' => 'Empréstimo',
                                                            default => '—'
                                                        } ?></span>
                        </div>

                        <?php
                        $docContratoAquisicao = null;
                        $docFaturaAquisicao = null;
                        foreach ($documentos as $doc) {
                            if ($doc->contexto === 'aquisicao' && $docContratoAquisicao === null) {
                                $docContratoAquisicao = $doc;
                            } elseif ($doc->contexto === 'aquisicao' && $docFaturaAquisicao === null) {
                                $docFaturaAquisicao = $doc;
                            }
                        }
                        ?>

                        <?php if ($docContratoAquisicao): ?>
                            <!-- ACCORDION — CONTRATO DE AQUISIÇÃO -->
                            <div class="accordion mt-4" id="accordionContratoAquisicao">

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingContratoAquisicao">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseContratoAquisicao"
                                            aria-expanded="false" aria-controls="collapseContratoAquisicao">
                                            Contrato de Aquisição
                                        </button>
                                    </h2>

                                    <div id="collapseContratoAquisicao" class="accordion-collapse collapse"
                                        aria-labelledby="headingContratoAquisicao"
                                        data-bs-parent="#accordionContratoAquisicao">

                                        <div class="accordion-body">

                                            <div class="border rounded p-3 mb-3">

                                                <div class="info-row">
                                                    <span class="info-label">Nome do Documento:</span>
                                                    <span class="info-value"><?= htmlspecialchars($docContratoAquisicao->nome_documento) ?></span>
                                                </div>

                                                <div class="info-row">
                                                    <span class="info-label">Data do Documento:</span>
                                                    <span class="info-value"><?= !empty($docContratoAquisicao->data_documento) ? date('d/m/Y', strtotime($docContratoAquisicao->data_documento)) : '—' ?></span>
                                                </div>

                                                <div class="info-row">
                                                    <span class="info-label">Data de Validade:</span>
                                                    <span class="info-value"><?= !empty($docContratoAquisicao->data_validade) ? date('d/m/Y', strtotime($docContratoAquisicao->data_validade)) : '—' ?></span>
                                                </div>

                                                <?php if (!empty($docContratoAquisicao->ficheiro)): ?>
                                                    <div class="info-row">
                                                        <span class="info-label">Ficheiro (PDF):</span>
                                                        <span class="info-value">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                                onclick="verPDF('<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($docContratoAquisicao->ficheiro) ?>')">
                                                                <i class="fa-solid fa-eye me-1"></i> Ver PDF
                                                            </button>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        <?php endif; ?>

                        <?php if ($docFaturaAquisicao): ?>

                            <!-- ACCORDION — FATURA DA AQUISIÇÃO -->
                            <div class="accordion mt-4" id="accordionFaturaAquisicao">

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingFaturaAquisicao">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFaturaAquisicao"
                                            aria-expanded="false" aria-controls="collapseFaturaAquisicao">
                                            Fatura da Aquisição
                                        </button>
                                    </h2>

                                    <div id="collapseFaturaAquisicao" class="accordion-collapse collapse"
                                        aria-labelledby="headingFaturaAquisicao"
                                        data-bs-parent="#accordionFaturaAquisicao">

                                        <div class="accordion-body">

                                            <div class="border rounded p-3 mb-3">

                                                <div class="info-row">
                                                    <span class="info-label">Nome do Documento:</span>
                                                    <span class="info-value"><?= htmlspecialchars($docFaturaAquisicao->nome_documento) ?></span>
                                                </div>

                                                <div class="info-row">
                                                    <span class="info-label">Data da Fatura:</span>
                                                    <span class="info-value"><?= !empty($docFaturaAquisicao->data_documento) ? date('d/m/Y', strtotime($docFaturaAquisicao->data_documento)) : '—' ?></span>
                                                </div>

                                                <?php if (!empty($docFaturaAquisicao->ficheiro)): ?>
                                                    <div class="info-row">
                                                        <span class="info-label">Ficheiro (PDF):</span>
                                                        <span class="info-value">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                                onclick="verPDF('<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($docFaturaAquisicao->ficheiro) ?>')">
                                                                <i class="fa-solid fa-eye me-1"></i> Ver PDF
                                                            </button>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        <?php endif; ?>



                    </div>
                </div>


                <!-- SEPARADOR 4 — FORNECEDOR ASSOCIADO -->
                <div class="tab-pane fade" id="fornecedor">

                    <?php if (empty($fornecedores)): ?>
                        <p class="text-muted">Não existem fornecedores associados.</p>
                    <?php else: ?>
                        <div class="accordion" id="accordionFornecedores">

                            <?php foreach ($fornecedores as $i => $forn): ?>
                                <div class="accordion-item">

                                    <h2 class="accordion-header" id="heading<?= $i ?>">
                                        <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse<?= $i ?>">
                                            <?= htmlspecialchars($forn->forn_codigo . ' – ' . $forn->forn_nome) ?>
                                        </button>
                                    </h2>

                                    <div id="collapse<?= $i ?>" class="accordion-collapse collapse <?= $i == 0 ? 'show' : '' ?>"
                                        data-bs-parent="#accordionFornecedores">

                                        <div class="accordion-body">

                                            <!-- SECÇÃO 1 — DADOS DA ASSOCIAÇÃO -->
                                            <h5 class="section-title">Dados da Associação</h5>

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="info-row">
                                                        <span class="info-label">Tipo:</span>
                                                        <span class="info-value"><?= htmlspecialchars($forn->tipo_relacao) ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info-row">
                                                        <span class="info-label">Pessoa de Contacto:</span>
                                                        <span class="info-value"><?= htmlspecialchars($forn->pessoa_contacto) ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info-row">
                                                        <span class="info-label">Telefone Contacto:</span>
                                                        <span class="info-value"><?= htmlspecialchars($forn->telefone_contacto) ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info-row">
                                                        <span class="info-label">Morada Associada:</span>
                                                        <span class="info-value"><?= htmlspecialchars($forn->morada_associada) ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="info-row">
                                                        <span class="info-label">Observações:</span>
                                                        <span class="info-value"><?= htmlspecialchars($forn->observacoes ?: '—') ?></span>
                                                    </div>
                                                </div>

                                            </div>

                                            <hr>

                                            <!-- SECÇÃO 2 — DADOS DO FORNECEDOR -->
                                            <h5 class="section-title">Informação do Fornecedor</h5>

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="info-row">
                                                        <span class="info-label">Código Interno:</span>
                                                        <span class="info-value"><?= htmlspecialchars($forn->forn_codigo) ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info-row">
                                                        <span class="info-label">Empresa:</span>
                                                        <span class="info-value"><?= htmlspecialchars($forn->forn_nome) ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info-row">
                                                        <span class="info-label">NIF:</span>
                                                        <span class="info-value"><?= htmlspecialchars($forn->nif) ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info-row">
                                                        <span class="info-label">Telefone:</span>
                                                        <span class="info-value"><?= htmlspecialchars($forn->telefone) ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info-row">
                                                        <span class="info-label">Email:</span>
                                                        <span class="info-value"><?= htmlspecialchars($forn->email) ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info-row">
                                                        <span class="info-label">Morada:</span>
                                                        <span class="info-value"><?= htmlspecialchars($forn->morada) ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info-row">
                                                        <span class="info-label">Website:</span>
                                                        <span class="info-value"><?= htmlspecialchars($forn->website ?: '—') ?></span>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    <?php endif; ?>

                </div>


                <!-- SEPARADOR 5 — LOCALIZAÇÃO ASSOCIADA -->
                <div class="tab-pane fade" id="localizacao">
                    <div class="info-box">

                        <h5 class="section-title">Localização Associada</h5>

                        <div class="info-row">
                            <span class="info-label">Código:</span>
                            <span class="info-value"><?= htmlspecialchars($equipamento->loc_codigo ?? '—') ?></span>
                        </div>

                        <div class="info-row"><span class="info-label">Edifício:</span><span
                                class="info-value"><?= htmlspecialchars($equipamento->edificio ?? '—') ?></span></div>
                        <div class="info-row"><span class="info-label">Piso:</span><span class="info-value"><?= htmlspecialchars($equipamento->piso ?? '—') ?></span>
                        </div>
                        <div class="info-row"><span class="info-label">Serviço:</span><span
                                class="info-value"><?= htmlspecialchars($equipamento->servico ?? '—') ?></span></div>
                        <div class="info-row"><span class="info-label">Sala:</span><span class="info-value"><?= htmlspecialchars($equipamento->sala ?? '—') ?></span></div>
                    </div>
                </div>

                <!-- SEPARADOR 6 — GARANTIA -->
                <div class="tab-pane fade" id="garantia" role="tabpanel">
                    <div class="info-box">

                        <h5 class="section-title">Garantia</h5>

                        <?php if (!$garantia): ?>
                            <p class="text-muted">Não existe garantia registada.</p>
                        <?php else: ?>

                            <div class="info-row">
                                <span class="info-label">Data de Início:</span>
                                <span class="info-value"><?= !empty($garantia->data_inicio) ? date('d/m/Y', strtotime($garantia->data_inicio)) : '—' ?></span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">Data de Fim:</span>
                                <span class="info-value"><?= !empty($garantia->data_fim) ? date('d/m/Y', strtotime($garantia->data_fim)) : '—' ?></span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">Entidade Responsável</span>
                                <span class="info-value"><?= htmlspecialchars($garantia->entidade_responsavel) ?></span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">Observações:</span>
                                <span class="info-value"><?= htmlspecialchars($garantia->observacoes ?: 'Sem observações.') ?></span>
                            </div>

                            <?php
                            $docGarantia = null;
                            foreach ($documentos as $doc) {
                                if ($doc->contexto === 'garantia') {
                                    $docGarantia = $doc;
                                    break;
                                }
                            }
                            ?>


                            <?php if ($docGarantia): ?>
                                <!-- ACCORDION — CERTIFICADO DE GARANTIA -->
                                <div class="accordion mt-4" id="accordionCertificadoGarantia">

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingCertificadoGarantia">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseCertificadoGarantia" aria-expanded="true"
                                                aria-controls="collapseCertificadoGarantia">
                                                Certificado de Garantia
                                            </button>
                                        </h2>

                                        <div id="collapseCertificadoGarantia" class="accordion-collapse collapse show"
                                            aria-labelledby="headingCertificadoGarantia"
                                            data-bs-parent="#accordionCertificadoGarantia">

                                            <div class="accordion-body">

                                                <div class="border rounded p-3 mb-3">

                                                    <div class="info-row">
                                                        <span class="info-label">Nome do Documento:</span>
                                                        <span class="info-value"><?= htmlspecialchars($docGarantia->nome_documento) ?></span>
                                                    </div>

                                                    <div class="info-row">
                                                        <span class="info-label">Data do Documento:</span>
                                                        <span class="info-value"><?= !empty($docGarantia->data_documento) ? date('d/m/Y', strtotime($docGarantia->data_documento)) : '—' ?></span>
                                                    </div>

                                                    <div class="info-row">
                                                        <span class="info-label">Data de Validade:</span>
                                                        <span class="info-value"><?= !empty($docGarantia->data_validade) ? date('d/m/Y', strtotime($docGarantia->data_validade)) : '—' ?></span>
                                                    </div>

                                                    <?php if (!empty($docGarantia->ficheiro)): ?>
                                                        <div class="info-row">
                                                            <span class="info-label">Ficheiro (PDF):</span>
                                                            <span class="info-value">
                                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                                    onclick="verPDF('<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($docGarantia->ficheiro) ?>')">
                                                                    <i class="fa-solid fa-eye me-1"></i> Ver PDF
                                                                </button>
                                                            </span>
                                                        </div>
                                                    <?php endif; ?>

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            <?php endif; ?>

                        <?php endif;
                        ?>


                    </div>

                </div>

                <!-- SEPARADOR 7 — CONTRATO DE MANUTENÇÃO -->
                <div class="tab-pane fade" id="contrato" role="tabpanel">

                    <h4 class="mb-3" style="color:#1a826d;">Contrato de Manutenção</h4>

                    <div class="info-box">

                        <?php if ($contrato): ?>
                            <!-- BLOCO SE EXISTE CONTRATO = SIM -->
                            <div id="contratoSim">
                                <div class="info-row">
                                    <span class="info-label">Existe Contrato?</span>
                                    <span class="info-value">Sim</span>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <span class="info-label">Tipo de Contrato:</span>
                                            <span class="info-value"><?= htmlspecialchars($contrato->tipo_contrato) ?></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <span class="info-label">Periodicidade:</span>
                                            <span class="info-value"><?= htmlspecialchars($contrato->periodicidade) ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <span class="info-label">Data de Início:</span>
                                            <span class="info-value"><?= !empty($contrato->data_inicio) ? date('d/m/Y', strtotime($contrato->data_inicio)) : '—' ?></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <span class="info-label">Data de Fim:</span>
                                            <span class="info-value"><?= !empty($contrato->data_fim) ? date('d/m/Y', strtotime($contrato->data_fim)) : '—' ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <span class="info-label">Entidade Responsável:</span>
                                            <span class="info-value"><?= htmlspecialchars($contrato->entidade_responsavel) ?></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <span class="info-label">Observações:</span>
                                            <span class="info-value"><?= htmlspecialchars($contrato->observacoes ?: 'Sem observações.') ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                            $docContratoManutencao = null;
                            foreach ($documentos as $doc) {
                                if ($doc->contexto === 'contrato') {
                                    $docContratoManutencao = $doc;
                                    break;
                                }
                            }
                            ?>

                            <?php if ($docContratoManutencao): ?>
                                <!-- ACCORDION — DOCUMENTO DO CONTRATO DE MANUTENÇÃO -->
                                <div class="accordion mt-4" id="accordionDocContrato">

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingDocContrato">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseDocContrato"
                                                aria-expanded="false" aria-controls="collapseDocContrato">
                                                Documento do Contrato de Manutenção
                                            </button>
                                        </h2>

                                        <div id="collapseDocContrato" class="accordion-collapse collapse"
                                            aria-labelledby="headingDocContrato" data-bs-parent="#accordionDocContrato">

                                            <div class="accordion-body">

                                                <div class="border rounded p-3 mb-3">

                                                    <div class="info-row">
                                                        <span class="info-label">Nome do Documento:</span>
                                                        <span class="info-value"><?= htmlspecialchars($docContratoManutencao->nome_documento) ?></span>
                                                    </div>

                                                    <div class="info-row">
                                                        <span class="info-label">Data do Documento:</span>
                                                        <span class="info-value"><?= !empty($docContratoManutencao->data_documento) ? date('d/m/Y', strtotime($docContratoManutencao->data_documento)) : '—' ?></span>
                                                    </div>

                                                    <div class="info-row">
                                                        <span class="info-label">Data de Validade:</span>
                                                        <span class="info-value"><?= !empty($docContratoManutencao->data_validade) ? date('d/m/Y', strtotime($docContratoManutencao->data_validade)) : '—' ?></span>
                                                    </div>

                                                    <?php if (!empty($docContratoManutencao->ficheiro)): ?>
                                                        <div class="info-row">
                                                            <span class="info-label">Ficheiro (PDF):</span>
                                                            <span class="info-value">
                                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                                    onclick="verPDF('<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($docContratoManutencao->ficheiro) ?>')">
                                                                    <i class="fa-solid fa-eye me-1"></i> Ver PDF
                                                                </button>
                                                            </span>
                                                        </div>
                                                    <?php endif; ?>

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            <?php endif; ?>

                        <?php else: ?>

                            <!-- BLOCO SE EXISTE CONTRATO = NÃO -->
                            <div id="contratoNao">
                                <div class="info-row">
                                    <span class="info-label">Existe Contrato?</span>
                                    <span class="info-value">Não</span>
                                </div>
                            </div>

                        <?php endif; // fecha o if ($contrato) 
                        ?>

                    </div>

                </div>


                <!-- SEPARADOR 8 — DOCUMENTAÇÃO ASSOCIADA -->
                <div class="tab-pane fade" id="documentos" role="tabpanel">

                    <div class="info-box">

                        <h5 class="section-title">Documentação Associada</h5>

                        <!-- Tabela de documentos -->
                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Data</th>
                                    <th>Origem</th>
                                    <th>Ficheiro</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (empty($documentos)): ?>
                                    <tr>
                                        <td colspan="5" class="text-muted text-center">Não existem documentos associados.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($documentos as $doc): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($doc->nome_documento) ?></td>
                                            <td><?= !empty($doc->data_documento) ? date('d/m/Y', strtotime($doc->data_documento)) : '—' ?></td>
                                            <td>
                                                <?= match ($doc->contexto) {
                                                    'aquisicao' => 'Aquisição',
                                                    'garantia' => 'Garantia',
                                                    'contrato' => 'Contrato de Manutenção',
                                                    'geral' => 'Adicionado Manualmente',
                                                    default => htmlspecialchars($doc->contexto)
                                                } ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($doc->ficheiro)): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                        onclick="verPDF('<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($doc->ficheiro) ?>')">
                                                        <i class="fa-solid fa-eye me-1"></i> Ver PDF
                                                    </button>
                                                <?php else: ?>
                                                    —
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>

                </div>




            </div>

            <!-- BOTÕES -->
            <div class="d-flex justify-content-between mt-4">
                <a href="lista.php" class="btn btn-secondary">Voltar</a>
                <?php if ($equipamento->equipamento_ativo == 1 && pode_editar_dados()): ?>
                    <a href="editar.php?id_equipamento=<?= aes_encrypt($equipamento->id) ?>" class="btn" style="background-color: #1a826d; color: white;">
                        Editar Equipamento
                    </a>
                <?php endif; ?>
            </div>

        </main>


    </div>
</div>

<?php include '../../includes/footer.php'; ?>