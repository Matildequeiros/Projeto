<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
require_once __DIR__ . '/../../includes/validacoes.php';

if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    header('Location: ' . BASE_URL . '/Private/views/Equipamentos/lista.php');
    exit;
}

$idEquipamentoEncrypted = $_GET['id_equipamento'] ?? null;
$idEquipamento = aes_decrypt($idEquipamentoEncrypted);

if (!$idEquipamento || !is_numeric($idEquipamento)) {
    header('Location: ' . BASE_URL . '/Private/views/Equipamentos/lista.php');
    exit;
}

$erros = [];
$erro_sistema = "";

// Separador 1 — Equipamento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submeter_sep1'])) {

    $novaDesignacao  = trim($_POST['designacao']   ?? '');
    $novaMarca       = trim($_POST['marca']        ?? '');
    $novoModelo      = trim($_POST['modelo']       ?? '');
    $novoNumSerie    = trim($_POST['numero_serie'] ?? '');
    $novoFabricante  = trim($_POST['fabricante']   ?? '');
    $novoAnoFabrico  = trim($_POST['ano_fabrico']  ?? '');
    $novoEstado      = trim($_POST['estado']       ?? '');
    $novaCriticidade = trim($_POST['criticidade']  ?? '');
    $novaCategoria   = trim($_POST['categoria']    ?? '');
    $novasObs        = trim($_POST['observacoes']  ?? '');

    $erros = array_merge($erros, validar_texto_obrigatorio($novaDesignacao, 'A designação'));
    $erros = array_merge($erros, validar_select($novaCategoria, 'A categoria'));
    $erros = array_merge($erros, validar_texto_obrigatorio($novaMarca, 'A marca'));
    $erros = array_merge($erros, validar_texto_obrigatorio($novoModelo, 'O modelo'));
    $erros = array_merge($erros, validar_numero_serie($novoNumSerie));
    $erros = array_merge($erros, validar_texto_obrigatorio($novoFabricante, 'O fabricante'));
    $erros = array_merge($erros, validar_ano_fabrico($novoAnoFabrico));
    $erros = array_merge($erros, validar_select($novoEstado, 'O estado'));
    $erros = array_merge($erros, validar_select($novaCriticidade, 'A criticidade'));

    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
                MYSQL_USERNAME,
                MYSQL_PASSWORD
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $ligacao->prepare("
                UPDATE equipamentos
                SET designacao   = :designacao,
                    categoria    = :categoria,
                    marca        = :marca,
                    modelo       = :modelo,
                    numero_serie = :numero_serie,
                    fabricante   = :fabricante,
                    ano_fabrico  = :ano_fabrico,
                    estado       = :estado,
                    criticidade  = :criticidade,
                    observacoes  = :observacoes
                WHERE id = :id
            ");
            $stmt->execute([
                ':designacao'   => $novaDesignacao,
                ':categoria'    => $novaCategoria,
                ':marca'        => $novaMarca,
                ':modelo'       => $novoModelo,
                ':numero_serie' => $novoNumSerie,
                ':fabricante'   => $novoFabricante,
                ':ano_fabrico'  => $novoAnoFabrico,
                ':estado'       => $novoEstado,
                ':criticidade'  => $novaCriticidade,
                ':observacoes'  => $novasObs,
                ':id'           => $idEquipamento,
            ]);
            $ligacao = null;

            header("Location: editar.php?id_equipamento={$idEquipamentoEncrypted}&sep=componentes");
            exit;
        } catch (PDOException $err) {
            $erro_sistema = "Erro ao atualizar: " . $err->getMessage();
        }
    }
}

// Separador 2 — Componentes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submeter_sep2'])) {

    $erros = [];
    $comp_ids     = $_POST['comp_id']          ?? [];
    $comp_tipos   = $_POST['comp_tipo']        ?? [];
    $comp_nomes   = $_POST['comp_nome']        ?? [];
    $comp_refs    = $_POST['comp_referencia']  ?? [];
    $comp_qtds    = $_POST['comp_quantidade']  ?? [];
    $comp_estados = $_POST['comp_estado']      ?? [];
    $comp_obs     = $_POST['comp_observacoes'] ?? [];

    foreach ($comp_nomes as $i => $nome) {
        $nome       = trim($nome);
        $tipo       = trim($comp_tipos[$i]   ?? '');
        $referencia = trim($comp_refs[$i]    ?? '');
        $quantidade = trim($comp_qtds[$i]    ?? '');
        $estado     = trim($comp_estados[$i] ?? '');
        $erros = array_merge($erros, validar_componente($nome, $tipo, $referencia, $quantidade, $estado));
    }

    if (!empty($erros)) {
        $_SESSION['sep_ativo'] = 'componentes';
    }

    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
                MYSQL_USERNAME,
                MYSQL_PASSWORD
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $ids_mantidos = [];

            foreach ($comp_nomes as $i => $nome) {
                $nome = trim($nome);
                if (empty($nome)) continue;

                $id = trim($comp_ids[$i] ?? '');

                if (!empty($id)) {
                    $stmt = $ligacao->prepare("UPDATE componentes_consumiveis SET tipo=:tipo, nome=:nome, referencia=:referencia, quantidade=:quantidade, estado=:estado, observacoes=:observacoes WHERE id=:id");
                    $stmt->execute([
                        ':tipo'        => trim($comp_tipos[$i]   ?? ''),
                        ':nome'        => $nome,
                        ':referencia'  => trim($comp_refs[$i]    ?? ''),
                        ':quantidade'  => trim($comp_qtds[$i]    ?? '') ?: null,
                        ':estado'      => trim($comp_estados[$i] ?? ''),
                        ':observacoes' => trim($comp_obs[$i]     ?? ''),
                        ':id'          => $id,
                    ]);
                    $ids_mantidos[] = $id;
                } else {
                    $stmt = $ligacao->prepare("INSERT INTO componentes_consumiveis (equipamento_id, tipo, nome, referencia, quantidade, estado, observacoes) VALUES (:equipamento_id, :tipo, :nome, :referencia, :quantidade, :estado, :observacoes)");
                    $stmt->execute([
                        ':equipamento_id' => $idEquipamento,
                        ':tipo'           => trim($comp_tipos[$i]   ?? ''),
                        ':nome'           => $nome,
                        ':referencia'     => trim($comp_refs[$i]    ?? ''),
                        ':quantidade'     => trim($comp_qtds[$i]    ?? '') ?: null,
                        ':estado'         => trim($comp_estados[$i] ?? ''),
                        ':observacoes'    => trim($comp_obs[$i]     ?? ''),
                    ]);
                    $ids_mantidos[] = $ligacao->lastInsertId();
                }
            }

            if (!empty($ids_mantidos)) {
                $placeholders = implode(',', array_fill(0, count($ids_mantidos), '?'));
                $stmt = $ligacao->prepare("DELETE FROM componentes_consumiveis WHERE equipamento_id = ? AND id NOT IN ($placeholders)");
                $stmt->execute(array_merge([$idEquipamento], $ids_mantidos));
            } else {
                $stmt = $ligacao->prepare("DELETE FROM componentes_consumiveis WHERE equipamento_id = ?");
                $stmt->execute([$idEquipamento]);
            }

            $ligacao = null;
            header("Location: editar.php?id_equipamento={$idEquipamentoEncrypted}&sep=aquisicao");
            exit;
        } catch (PDOException $err) {
            $erro_sistema = "Erro ao atualizar componentes: " . $err->getMessage();
        }
    }
}

// Carregar dados da BD
try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $ligacao->prepare("SELECT * FROM equipamentos WHERE id = :id");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $equipamento = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$equipamento) {
        header('Location: ' . BASE_URL . '/Private/views/Equipamentos/lista.php');
        exit;
    }

    $stmt = $ligacao->prepare("SELECT * FROM componentes_consumiveis WHERE equipamento_id = :id ORDER BY id");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $componentes = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $err) {
    $erro_sistema = "Erro na ligação à base de dados.";
    $equipamento = null;
    $componentes = [];
}

$ligacao = null;

$sepAtivo = $_SESSION['sep_ativo'] ?? $_GET['sep'] ?? 'dados';
unset($_SESSION['sep_ativo']);
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
                    <i class="fa-solid fa-pen-to-square me-2"></i> Editar Equipamento
                </h2>

                <?php if (!empty($erros)) : ?>
                    <div class="alert alert-danger text-center" role="alert">
                        <?php foreach ($erros as $erro) : ?>
                            <div><?= htmlspecialchars($erro) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- SEPARADORES -->
                <ul class="nav nav-tabs mb-4 flex-nowrap" id="equipTabs" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $sepAtivo == 'dados' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#dados" type="button">
                            Equipamento
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $sepAtivo == 'componentes' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#componentes" type="button">
                            Componentes <br> e Consumíveis
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $sepAtivo == 'aquisicao' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#aquisicao" type="button">
                            Aquisição
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $sepAtivo == 'fornecedor' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#fornecedor" type="button">
                            Fornecedor <br> Associado
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $sepAtivo == 'localizacao' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#localizacao" type="button">
                            Localização <br> Associada
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $sepAtivo == 'garantia' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#garantia" type="button">
                            Garantia
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $sepAtivo == 'contrato' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#contrato" type="button">
                            Contrato de <br> Manutenção
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $sepAtivo == 'documentos' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#documentos" type="button">
                            Documentação <br> Associada
                        </button>
                    </li>

                </ul>


                <!-- CONTEÚDO DOS SEPARADORES -->
                <form id="formEquipamentoCompleto" method="post"
                    action="editar.php?id_equipamento=<?= $idEquipamentoEncrypted ?>">

                    <div class="tab-content" id="equipTabsContent">


                        <!-- SEPARADOR 1 — EQUIPAMENTO -->
                        <div class="tab-pane fade <?= $sepAtivo == 'dados' ? 'show active' : '' ?>" id="dados" role="tabpanel">

                            <!-- Código + Designação -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Código Interno De Inventário *</label>
                                    <input type="text" class="form-control"
                                        value="<?= htmlspecialchars($equipamento->codigo) ?>" disabled>
                                </div>

                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Designação Do Equipamento *</label>
                                    <input type="text" class="form-control" name="designacao"
                                        value="<?= htmlspecialchars($equipamento->designacao) ?>">
                                </div>
                            </div>

                            <!-- Categoria -->
                            <div class="mb-3">
                                <label class="form-label">
                                    Categoria *
                                    <i class="fa-solid fa-circle-info ms-1 text-muted" data-bs-toggle="popover"
                                        data-bs-trigger="hover focus" data-bs-html="true" title="Categorias"
                                        data-bs-content="
        Diagnóstico - Obtém informação clínica para diagnóstico.<br>
        Terapia - Utilizado no tratamento do paciente.<br>
        Monitorização - Acompanha sinais vitais e parâmetros clínicos.<br>
        Acessório - Complementa outro equipamento.<br>
        Laboratório - Utilizado em análises e testes.<br>
        Esterilização - Limpa, desinfeta ou esteriliza materiais.<br>
        Reabilitação - Apoia a recuperação funcional do paciente.
   ">
                                    </i>
                                </label>

                                <select class="form-select" name="categoria">
                                    <option value="">Selecione...</option>
                                    <option value="Monitorização" <?= $equipamento->categoria == 'Monitorização' ? 'selected' : '' ?>>Monitorização</option>
                                    <option value="Suporte de vida" <?= $equipamento->categoria == 'Suporte de vida' ? 'selected' : '' ?>>Suporte de vida</option>
                                    <option value="Terapia" <?= $equipamento->categoria == 'Terapia' ? 'selected' : '' ?>>Terapia</option>
                                    <option value="Diagnóstico" <?= $equipamento->categoria == 'Diagnóstico' ? 'selected' : '' ?>>Diagnóstico</option>
                                    <option value="Laboratório" <?= $equipamento->categoria == 'Laboratório' ? 'selected' : '' ?>>Laboratório</option>
                                    <option value="Esterilização" <?= $equipamento->categoria == 'Esterilização' ? 'selected' : '' ?>>Esterilização</option>
                                    <option value="Reabilitação" <?= $equipamento->categoria == 'Reabilitação' ? 'selected' : '' ?>>Reabilitação</option>
                                </select>
                            </div>

                            <!-- Marca + Modelo -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Marca *</label>
                                    <input type="text" class="form-control" name="marca"
                                        value="<?= htmlspecialchars($equipamento->marca) ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Modelo *</label>
                                    <input type="text" class="form-control" name="modelo"
                                        value="<?= htmlspecialchars($equipamento->modelo) ?>">
                                </div>
                            </div>

                            <!-- Nº Série + Fabricante -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Número de Série *</label>
                                    <input type="text" class="form-control" name="numero_serie"
                                        value="<?= htmlspecialchars($equipamento->numero_serie) ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fabricante *</label>
                                    <input type="text" class="form-control" name="fabricante"
                                        value="<?= htmlspecialchars($equipamento->fabricante) ?>">
                                </div>
                            </div>

                            <!-- Ano Fabrico + Estado + Criticidade -->
                            <div class="row">

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Ano de Fabrico *</label>
                                    <input type="number" class="form-control" name="ano_fabrico"
                                        min="1900" max="2100"
                                        value="<?= htmlspecialchars($equipamento->ano_fabrico) ?>">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">
                                        Estado *
                                        <i class="fa-solid fa-circle-info ms-1 text-muted" data-bs-toggle="popover"
                                            data-bs-trigger="hover focus" data-bs-html="true" title="Estados"
                                            data-bs-content="
        Ativo - Disponível para utilização.<br>
        Em manutenção - Em intervenção técnica.<br>
        Inativo - Temporariamente fora de uso.<br>
        Em calibração - Em ajuste ou validação técnica.<br>
        Em quarentena - Isolado para avaliação<br>
        Abatido - Removido definitivamente do inventário.
   ">
                                        </i>
                                    </label>

                                    <select class="form-select" name="estado">
                                        <option value="">Selecione...</option>
                                        <option value="Ativo" <?= $equipamento->estado == 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                                        <option value="Em manutenção" <?= $equipamento->estado == 'Em manutenção' ? 'selected' : '' ?>>Em manutenção</option>
                                        <option value="Inativo" <?= $equipamento->estado == 'Inativo' ? 'selected' : '' ?>>Inativo</option>
                                        <option value="Em calibração" <?= $equipamento->estado == 'Em calibração' ? 'selected' : '' ?>>Em calibração</option>
                                        <option value="Em quarentena" <?= $equipamento->estado == 'Em quarentena' ? 'selected' : '' ?>>Em quarentena</option>
                                        <option value="Abatido" <?= $equipamento->estado == 'Abatido' ? 'selected' : '' ?>>Abatido</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">
                                        Criticidade *
                                        <i class="fa-solid fa-circle-info ms-1 text-muted" data-bs-toggle="popover"
                                            data-bs-trigger="hover focus" data-bs-html="true" title="Criticidade"
                                            data-bs-content="
        Baixa - Equipamentos cuja falha não tem impacto direto na
segurança do doente.<br>
        Média - Equipamentos utilizados em procedimentos clínicos,
mas cuja falha não compromete imediatamente a vida
do doente.
<br>
        Alta - Equipamentos essenciais para diagnóstico ou
tratamento clínico.<br>
        Suporte de vida - Equipamentos cuja falha pode colocar em risco
imediato a vida do doente.
   ">
                                        </i>
                                    </label>

                                    <select class="form-select" name="criticidade">
                                        <option value="">Selecione...</option>
                                        <option value="Baixa" <?= $equipamento->criticidade == 'Baixa' ? 'selected' : '' ?>>Baixa</option>
                                        <option value="Média" <?= $equipamento->criticidade == 'Média' ? 'selected' : '' ?>>Média</option>
                                        <option value="Alta" <?= $equipamento->criticidade == 'Alta' ? 'selected' : '' ?>>Alta</option>
                                        <option value="Suporte de vida" <?= $equipamento->criticidade == 'Suporte de vida' ? 'selected' : '' ?>>Suporte de vida</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Observações -->
                            <div class="mb-3">
                                <label class="form-label">Observações</label>
                                <textarea class="form-control" name="observacoes" rows="3"><?= htmlspecialchars($equipamento->observacoes ?? '') ?></textarea>
                            </div>

                            <!-- Botões -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="lista.php" class="btn btn-secondary">← Voltar</a>

                                <button type="submit" name="submeter_sep1" class="btn" style="background-color:#1a826d; color:white;">
                                    Seguinte →
                                </button>
                            </div>

                        </div>

                        <!-- SEPARADOR 2 — COMPONENTES ASSOCIADOS -->
                        <div class="tab-pane fade <?= $sepAtivo == 'componentes' ? 'show active' : '' ?>" id="componentes" role="tabpanel">

                            <h4 class="mb-3" style="color:#1a826d;">Componentes Associados</h4>

                            <p class="text-muted mb-3">
                                Adicione componentes ou consumíveis que fazem parte do equipamento principal.
                            </p>

                            <div id="componentesContainer">

                                <?php
                                $componentes_editar = !empty($componentes) ? $componentes : [(object)['tipo' => '', 'nome' => '', 'referencia' => '', 'quantidade' => '', 'estado' => '', 'observacoes' => '']];
                                foreach ($componentes_editar as $comp):
                                ?>
                                    <div class="componente-bloco border rounded p-3 mb-3" style="border-color:#86b0aa;">
                                        <div class="row g-3">

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Tipo *</label>
                                                <select class="form-select" name="comp_tipo[]">
                                                    <option value="">Selecione...</option>
                                                    <option value="Componente" <?= ($comp->tipo ?? '') == 'Componente' ? 'selected' : '' ?>>Componente</option>
                                                    <option value="Consumivel" <?= ($comp->tipo ?? '') == 'Consumivel' ? 'selected' : '' ?>>Consumível</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Nome *</label>
                                                <input type="text" class="form-control" name="comp_nome[]"
                                                    placeholder="Ex: Sensor SpO2"
                                                    value="<?= htmlspecialchars($comp->nome ?? '') ?>">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Referência</label>
                                                <input type="text" class="form-control" name="comp_referencia[]"
                                                    placeholder="Ex: DS-100A"
                                                    value="<?= htmlspecialchars($comp->referencia ?? '') ?>">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Quantidade</label>
                                                <input type="number" class="form-control" name="comp_quantidade[]"
                                                    min="0" value="<?= htmlspecialchars($comp->quantidade ?? '') ?>">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Estado</label>
                                                <select class="form-select" name="comp_estado[]">
                                                    <option value="">—</option>
                                                    <option value="Ativo" <?= ($comp->estado ?? '') == 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                                                    <option value="Em manutenção" <?= ($comp->estado ?? '') == 'Em manutenção' ? 'selected' : '' ?>>Em manutenção</option>
                                                    <option value="Inativo" <?= ($comp->estado ?? '') == 'Inativo' ? 'selected' : '' ?>>Inativo</option>
                                                    <option value="Em calibração" <?= ($comp->estado ?? '') == 'Em calibração' ? 'selected' : '' ?>>Em calibração</option>
                                                    <option value="Abatido" <?= ($comp->estado ?? '') == 'Abatido' ? 'selected' : '' ?>>Abatido</option>
                                                </select>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label">Observações</label>
                                                <textarea class="form-control" name="comp_observacoes[]" rows="1"><?= htmlspecialchars($comp->observacoes ?? '') ?></textarea>
                                            </div>

                                            <input type="hidden" name="comp_id[]" value="<?= $comp->id ?? '' ?>">

                                            <div class="col-12 text-end mt-1">
                                                <button type="button" class="btn btn-danger btn-sm remover-componente">
                                                    Remover
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>

                            <button type="button" class="btn btn-success mb-4" id="adicionarComponente">
                                + Adicionar
                            </button>

                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary" onclick="mostrarSeparador('dados')">
                                    ← Anterior
                                </button>
                                <button type="submit" name="submeter_sep2" class="btn" style="background-color:#1a826d; color:white;">
                                    Seguinte →
                                </button>
                            </div>

                        </div>


                        <!-- SEPARADOR 3 — AQUISIÇÃO -->
                        <div class="tab-pane fade <?= $sepAtivo == 'aquisicao' ? 'show active' : '' ?>" id="aquisicao" role="tabpanel">

                            <h4 class="mb-3" style="color:#1a826d;">Aquisição</h4>

                            <div class="row">

                                <!-- Data de aquisição -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Data de Aquisição *</label>
                                    <input type="date" class="form-control">
                                </div>

                                <!-- Custo -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Custo de Aquisição (€) *</label>
                                    <input type="number" class="form-control" placeholder="Ex: 3500" min="0"
                                        step="0.01">
                                </div>

                                <!-- Tipo de entrada -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Tipo de Entrada *
                                        <i class="fa-solid fa-circle-info ms-1 text-muted" data-bs-toggle="popover"
                                            data-bs-trigger="hover focus" data-bs-html="true"
                                            title="Tipo de Entrada" data-bs-content="
            Compra - Adquirido pela instituição.<br>
            Doação - Recebido sem contrapartida financeira.<br>
            Empréstimo - Cedido temporariamente e devolvido após uso.<br>
            Aluguer - Obtido através de contrato de aluguer.
       ">
                                        </i>
                                    </label>

                                    <select class="form-select" id="tipoEntrada">
                                        <option value="compra">Compra</option>
                                        <option value="doacao">Doação</option>
                                        <option value="aluguer">Aluguer</option>
                                        <option value="emprestimo">Empréstimo</option>
                                    </select>

                                </div>

                            </div>

                            <hr class="my-4">

                            <div class="accordion" id="accordionAquisicao">

                                <!-- ITEM 1 — Documentos da Aquisição -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingDocAquisicao">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseDocAquisicao" aria-expanded="true"
                                            aria-controls="collapseDocAquisicao">
                                            Documentos da Aquisição
                                        </button>
                                    </h2>

                                    <div id="collapseDocAquisicao" class="accordion-collapse collapse show"
                                        aria-labelledby="headingDocAquisicao" data-bs-parent="#accordionAquisicao">

                                        <div class="accordion-body">

                                            <div class="row">

                                                <!-- BLOCO 1 — Contrato de Aquisição -->
                                                <div class="col-md-6">
                                                    <div class="border rounded p-3 mb-3">

                                                        <h5 class="mb-3" style="color:#1a826d;">Contrato de
                                                            Aquisição</h5>

                                                        <div class="row">
                                                            <!-- Tipo de Documento -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Tipo de Documento
                                                                    *</label>
                                                                <select class="form-select">
                                                                    <option>Contrato de Aquisição</option>
                                                                </select>
                                                            </div>

                                                            <!-- Nome do Documento -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Nome do Documento
                                                                    *</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Ex: Contrato de Compra">
                                                            </div>
                                                        </div>


                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Data do Documento
                                                                    *</label>
                                                                <input type="date" class="form-control">
                                                            </div>

                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Data de Validade</label>
                                                                <input type="date" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Ficheiro (PDF) *</label>
                                                            <input type="file" class="form-control"
                                                                accept="application/pdf">
                                                        </div>

                                                        <button type="button" class="btn btn-danger">Remover
                                                            Documento</button>

                                                    </div>
                                                </div>

                                                <!-- BLOCO 2 — Fatura da Aquisição -->
                                                <div class="col-md-6" id="blocoFatura">

                                                    <div class="border rounded p-3 mb-3">

                                                        <h5 class="mb-3" style="color:#1a826d;">Fatura da Aquisição
                                                        </h5>

                                                        <div class="row">
                                                            <!-- Tipo de Documento -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Tipo de Documento
                                                                    *</label>
                                                                <select class="form-select">
                                                                    <option>Fatura de Aquisição</option>
                                                                </select>
                                                            </div>

                                                            <!-- Nome do Documento -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Nome do Documento
                                                                    *</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Ex: Contrato de Compra">
                                                            </div>
                                                        </div>


                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Data da Fatura *</label>
                                                                <input type="date" class="form-control">
                                                            </div>

                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Data de Pagamento</label>
                                                                <input type="date" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Ficheiro (PDF) *</label>
                                                            <input type="file" class="form-control"
                                                                accept="application/pdf">
                                                        </div>

                                                        <button type="button" class="btn btn-danger">Remover
                                                            Documento</button>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>


                            <!-- Botões -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary"
                                    onclick="mostrarSeparador('componentes')">
                                    ← Anterior
                                </button>

                                <button type="button" class="btn" style="background-color:#1a826d; color:white;"
                                    onclick="validarEAvancar('aquisicao', 'fornecedor')">
                                    Seguinte →
                                </button>
                            </div>

                        </div>

                        <!-- SEPARADOR 4 — FORNECEDOR ASSOCIADO -->
                        <div class="tab-pane fade <?= $sepAtivo == 'fornecedor' ? 'show active' : '' ?>" id="fornecedor" role="tabpanel">

                            <h4 class="mb-3" style="color:#1a826d;">Fornecedor Associado</h4>

                            <div id="fornecedores-container">

                                <!-- BLOCO DE FORNECEDOR  -->
                                <div class="fornecedor-bloco border rounded p-3 mb-3" style="border-color:#86b0aa;">

                                    <div class="row g-3">

                                        <!-- Fornecedor -->
                                        <div class="col-md-4">
                                            <label class="form-label">Fornecedor *</label>
                                            <select class="form-select">
                                                <option value="">Selecione...</option>
                                                <option value="1">F001 – MedTech Solutions</option>
                                                <option value="2">F002 – Dräger Portugal</option>
                                                <option value="3">F003 – TecAssist</option>
                                            </select>
                                        </div>

                                        <!-- Tipo -->
                                        <div class="col-md-4">
                                            <label class="form-label">Tipo *</label>
                                            <select class="form-select">
                                                <option>Fabricante</option>
                                                <option>Distribuidor / Comercial</option>
                                                <option>Assistência Técnica</option>
                                                <option>Consumíveis / Acessórios</option>
                                            </select>
                                        </div>

                                        <!-- Morada -->
                                        <div class="col-md-4">
                                            <label class="form-label">Morada Associada *</label>
                                            <input type="text" class="form-control"
                                                placeholder="Ex: Armazém Norte – Braga">
                                        </div>

                                        <!-- Pessoa contacto -->
                                        <div class="col-md-4">
                                            <label class="form-label">Pessoa de Contacto</label>
                                            <input type="text" class="form-control" placeholder="Nome da pessoa">
                                        </div>

                                        <!-- Telefone -->
                                        <div class="col-md-4">
                                            <label class="form-label">Telefone da Pessoa de Contacto</label>
                                            <input type="number" class="form-control" placeholder="912345678">
                                        </div>

                                        <!-- Observações -->
                                        <div class="col-md-4">
                                            <label class="form-label">Observações</label>
                                            <textarea class="form-control" rows="1"></textarea>
                                        </div>

                                        <!-- Botão remover -->
                                        <div class="col-12 text-end mt-1">
                                            <button type="button" class="btn btn-danger btn-sm remover-fornecedor">
                                                Remover Fornecedor
                                            </button>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- Botão adicionar -->
                            <button type="button" class="btn btn-success mb-4" id="adicionarFornecedor">
                                + Adicionar Fornecedor
                            </button>

                            <!-- Botões navegação -->
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary"
                                    onclick="mostrarSeparador('aquisicao')">
                                    ← Anterior
                                </button>

                                <button type="button" class="btn" style="background-color:#1a826d; color:white;"
                                    onclick="validarEAvancar('fornecedor', 'localizacao')">
                                    Seguinte →
                                </button>
                            </div>

                        </div>

                        <!-- SEPARADOR 5 — LOCALIZAÇÃO ASSOCIADA -->
                        <div class="tab-pane fade <?= $sepAtivo == 'localizacao' ? 'show active' : '' ?>" id="localizacao" role="tabpanel">

                            <h4 class="mb-3" style="color:#1a826d;">Localização Associada</h4>

                            <form>

                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Localização *</label>
                                        <select class="form-select">
                                            <option value="">Selecione...</option>
                                            <option value="LOC001">LOC001 – Edifício A / Piso 1 / Sala 3</option>
                                            <option value="LOC002">LOC002 – Edifício B / Piso 0 / Urgência</option>
                                            <option value="LOC003">LOC003 – Edifício C / Piso 2 / Sala 12</option>
                                        </select>
                                    </div>

                                </div>

                                <!-- Botões -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="mostrarSeparador('fornecedor')">
                                        ← Anterior
                                    </button>

                                    <button type="button" class="btn" style="background-color:#1a826d; color:white;"
                                        onclick="mostrarSeparador('garantia')">
                                        Seguinte →
                                    </button>
                                </div>

                            </form>

                        </div>


                        <!-- SEPARADOR 6 — GARANTIA -->
                        <div class="tab-pane fade <?= $sepAtivo == 'garantia' ? 'show active' : '' ?>" id="garantia" role="tabpanel">

                            <h4 class="mb-3" style="color:#1a826d;">Garantia</h4>

                            <!-- Datas -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Data de Início da Garantia *</label>
                                    <input type="date" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Data de Fim da Garantia *</label>
                                    <input type="date" class="form-control">
                                </div>
                            </div>

                            <!-- Observações + Entidade Responsável -->
                            <div class="row">

                                <!-- Entidade Responsável -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Entidade Responsável *</label>
                                    <select class="form-select">
                                        <option value="">Selecione...</option>
                                        <option>Fabricante</option>
                                        <option>Fornecedor Comercial</option>
                                        <option>Distribuidor Autorizado</option>
                                        <option>Outro</option>
                                    </select>
                                </div>

                                <!-- Observações -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Observações</label>
                                    <textarea class="form-control" rows="3"></textarea>
                                </div>


                            </div>

                            <hr class="my-4">

                            <div class="accordion" id="accordionGarantia">

                                <!-- ITEM 1 — Certificado de Garantia -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingCertGarantia">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseCertGarantia" aria-expanded="true"
                                            aria-controls="collapseCertGarantia">
                                            Certificado de Garantia
                                        </button>
                                    </h2>

                                    <div id="collapseCertGarantia" class="accordion-collapse collapse show"
                                        aria-labelledby="headingCertGarantia" data-bs-parent="#accordionGarantia">

                                        <div class="accordion-body">

                                            <div class="border rounded p-3 mb-3">

                                                <h5 class="mb-3" style="color:#1a826d;">Certificado de Garantia</h5>

                                                <div class="row">
                                                    <!-- Tipo de Documento -->
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Tipo de Documento *</label>
                                                        <select class="form-select">
                                                            <option>Certificado de Garantia</option>
                                                        </select>
                                                    </div>

                                                    <!-- Nome do Documento -->
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Nome do Documento *</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="Ex: Certificado de Garntia">
                                                    </div>
                                                </div>


                                                <!-- Datas -->
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Data do Documento *</label>
                                                        <input type="date" class="form-control">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Data de Validade</label>
                                                        <input type="date" class="form-control">
                                                    </div>
                                                </div>

                                                <!-- PDF -->
                                                <div class="mb-3">
                                                    <label class="form-label">Ficheiro (PDF) *</label>
                                                    <input type="file" class="form-control"
                                                        accept="application/pdf">
                                                </div>

                                                <button type="button" class="btn btn-danger">Remover
                                                    Documento</button>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>


                            <!-- Botões -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary"
                                    onclick="mostrarSeparador('localizacao')">
                                    ← Anterior
                                </button>

                                <button type="button" class="btn" style="background-color:#1a826d; color:white;"
                                    onclick="validarEAvancar('garantia', 'contrato')">
                                    Seguinte →
                                </button>
                            </div>

                        </div>

                        <!-- SEPARADOR 7 — CONTRATO DE MANUTENÇÃO -->
                        <div class="tab-pane fade <?= $sepAtivo == 'contrato' ? 'show active' : '' ?>" id="contrato" role="tabpanel">

                            <h4 class="mb-3" style="color:#1a826d;">Contrato de Manutenção</h4>

                            <!-- Existe contrato? -->
                            <div class="mb-3">
                                <label class="form-label">Existe Contrato de Manutenção? *</label>
                                <select class="form-select" onchange="toggleContrato(this.value)">
                                    <option value="nao">Não</option>
                                    <option value="sim">Sim</option>
                                </select>
                            </div>

                            <!-- Campos do contrato (escondidos por padrão) -->
                            <div id="camposContrato" style="display:none;">

                                <!-- Tipo + Periodicidade -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tipo de Contrato *</label>
                                        <select class="form-select">
                                            <option>Manutenção Preventiva</option>
                                            <option>Manutenção Corretiva</option>
                                            <option>Full-Service</option>
                                            <option>Outsourcing</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Periodicidade *</label>
                                        <select class="form-select">
                                            <option>Mensal</option>
                                            <option>Trimestral</option>
                                            <option>Semestral</option>
                                            <option>Anual</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Datas -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Data de Início *</label>
                                        <input type="date" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Data de Fim *</label>
                                        <input type="date" class="form-control">
                                    </div>
                                </div>

                                <!-- Observações + Entidade Responsável -->
                                <div class="row">

                                    <!-- Entidade Responsável -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Entidade Responsável *</label>
                                        <select class="form-select">
                                            <option value="">Selecione...</option>
                                            <option>Empresa de assistência técnica</option>
                                            <option>Fabricante</option>
                                            <option>Distribuidor Autorizado</option>
                                            <option>Outro</option>
                                        </select>
                                    </div>

                                    <!-- Observações -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Observações</label>
                                        <textarea class="form-control" rows="3"></textarea>
                                    </div>

                                </div>

                                <hr class="my-4">

                                <div class="accordion" id="accordionContratoManutencao">

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingContratoManutencaoDoc">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseContratoManutencaoDoc" aria-expanded="true"
                                                aria-controls="collapseContratoManutencaoDoc">
                                                Documento do Contrato de Manutenção
                                            </button>
                                        </h2>

                                        <div id="collapseContratoManutencaoDoc"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="headingContratoManutencaoDoc"
                                            data-bs-parent="#accordionContratoManutencao">

                                            <div class="accordion-body">

                                                <div class="border rounded p-3 mb-3">

                                                    <h5 class="mb-3" style="color:#1a826d;">Contrato de Manutenção
                                                    </h5>

                                                    <div class="row">
                                                        <!-- Tipo de Documento -->
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Tipo de Documento *</label>
                                                            <select class="form-select">
                                                                <option>Contrato de Manutenção</option>
                                                            </select>
                                                        </div>

                                                        <!-- Nome do Documento -->
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Nome do Documento *</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="Ex: Contrato de Manutenção 2024-2025">
                                                        </div>
                                                    </div>


                                                    <!-- Datas -->
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Data do Documento *</label>
                                                            <input type="date" class="form-control">
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Data de Validade</label>
                                                            <input type="date" class="form-control">
                                                        </div>
                                                    </div>

                                                    <!-- PDF -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Ficheiro (PDF) *</label>
                                                        <input type="file" class="form-control"
                                                            accept="application/pdf">
                                                    </div>

                                                    <button type="button" class="btn btn-danger">Remover
                                                        Documento</button>

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>


                            </div>

                            <!-- Botões -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary"
                                    onclick="mostrarSeparador('garantia')">
                                    ← Anterior
                                </button>

                                <button type="button" class="btn" style="background-color:#1a826d; color:white;"
                                    onclick="validarEAvancar('contrato', 'documentos')">
                                    Seguinte →
                                </button>
                            </div>

                        </div>


                        <!-- SEPARADOR 8 — DOCUMENTAÇÃO ASSOCIADA -->
                        <div class="tab-pane fade <?= $sepAtivo == 'documentos' ? 'show active' : '' ?>" id="documentos" role="tabpanel">
                            <h4 class="mb-3" style="color:#1a826d;">Documentação Associada</h4>

                            <!-- CONTAINER DOS DOCUMENTOS -->
                            <div id="documentosContainer">

                                <!-- BLOCO BASE DE DOCUMENTO -->
                                <div class="documento-bloco border rounded p-3 mb-3" style="border-color:#86b0aa;">

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tipo de Documento *</label>
                                            <select class="form-select">
                                                <option>Manual</option>
                                                <option>Ficha</option>
                                                <option>Certificado</option>
                                                <option>Relatório</option>
                                                <option>Declaração</option>
                                                <option>Outro</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nome do Documento *</label>
                                            <input type="text" class="form-control"
                                                placeholder="Ex: Manual do Utilizador">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Data do Documento *</label>
                                            <input type="date" class="form-control">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Data de Validade</label>
                                            <input type="date" class="form-control">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Ficheiro (PDF) *</label>
                                        <input type="file" class="form-control" accept="application/pdf">
                                    </div>

                                    <button type="button" class="btn btn-danger btn-sm remover-documento">
                                        Remover Documento
                                    </button>

                                </div>

                            </div>

                            <!-- BOTÃO ADICIONAR -->
                            <button type="button" class="btn btn-success mb-3" id="adicionarDocumento">
                                + Adicionar Documento
                            </button>


                            <!-- Botões -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary"
                                    onclick="mostrarSeparador('contrato')">
                                    ← Anterior
                                </button>

                                <button type="submit" class="btn" style="background-color:#1a826d; color:white;">
                                    Guardar Equipamento ✔
                                </button>
                            </div>

                        </div>

                    </div>
                </form>


        </main>



    </div>
</div>

<?php include '../../includes/footer.php'; ?>