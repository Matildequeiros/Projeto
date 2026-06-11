<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$erros = [];
$erro_sistema = "";

// Carregar fornecedores da BD
try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $stmt = $ligacao->query("SELECT id, codigo, nome FROM fornecedores ORDER BY nome");
    $fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $ligacao = null;
} catch (PDOException $e) {
    $fornecedores = [];
}

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submeter_sep1'])) {

    // 1. Recolher dados
    $codigo       = $_POST["codigo"]       ?? "";
    $designacao   = $_POST["designacao"]   ?? "";
    $categoria    = $_POST["categoria"]    ?? "";
    $marca        = $_POST["marca"]        ?? "";
    $modelo       = $_POST["modelo"]       ?? "";
    $numero_serie = $_POST["numero_serie"] ?? "";
    $fabricante   = $_POST["fabricante"]   ?? "";
    $ano_fabrico  = $_POST["ano_fabrico"]  ?? "";
    $estado       = $_POST["estado"]       ?? "";
    $criticidade  = $_POST["criticidade"]  ?? "";
    $observacoes  = $_POST["observacoes"]  ?? "";

    // 2. Validar os dados
    $erros = [];

    $codigo       = trim($codigo);
    $designacao   = trim($designacao);
    $marca        = trim($marca);
    $modelo       = trim($modelo);
    $numero_serie = trim($numero_serie);
    $fabricante   = trim($fabricante);
    $ano_fabrico  = trim($ano_fabrico);
    $observacoes  = trim($observacoes);

    if (empty($codigo)) {
        $erros[] = "O código interno é obrigatório.";
    } elseif (preg_match('/\s/', $codigo)) {
        $erros[] = "O código interno não pode conter espaços.";
    }

    if (empty($designacao)) {
        $erros[] = "A designação é obrigatória.";
    }

    if (empty($marca)) {
        $erros[] = "A marca é obrigatória.";
    }

    if (empty($modelo)) {
        $erros[] = "O modelo é obrigatório.";
    }

    if (empty($numero_serie)) {
        $erros[] = "O número de série é obrigatório.";
    }

    if (empty($fabricante)) {
        $erros[] = "O fabricante é obrigatório.";
    }

    if (empty($ano_fabrico)) {
        $erros[] = "O ano de fabrico é obrigatório.";
    } elseif (!preg_match('/^\d{4}$/', $ano_fabrico) || $ano_fabrico < 1900 || $ano_fabrico > 2100) {
        $erros[] = "O ano de fabrico é inválido.";
    }

    // 3. Normalizar dados
    $designacao = ucwords(strtolower($designacao));
    $marca      = ucwords(strtolower($marca));
    $modelo     = ucwords(strtolower($modelo));
    $fabricante = ucwords(strtolower($fabricante));
    $codigo     = strtoupper($codigo);

    // 4. Se não houver erros, guardar na sessão e avançar
    if (empty($erros)) {
        $_SESSION['novo_equipamento']['sep1'] = [
            'codigo'       => $codigo,
            'designacao'   => $designacao,
            'categoria'    => $categoria,
            'marca'        => $marca,
            'modelo'       => $modelo,
            'numero_serie' => $numero_serie,
            'fabricante'   => $fabricante,
            'ano_fabrico'  => $ano_fabrico,
            'estado'       => $estado,
            'criticidade'  => $criticidade,
            'observacoes'  => $observacoes
        ];

        // Avançar para o separador 2
        header("Location: novo.php?sep=componentes");
        exit;
    }
}

// Separador 2 — Componentes (opcional)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submeter_sep2'])) {

    $componentes = [];
    $nomes = $_POST['nome_componente'] ?? [];

    foreach ($nomes as $i => $nome) {
        $nome = trim($nome);

        if (!empty($nome)) {
            $componentes[] = [
                'tipo'        => trim($_POST['tipo'][$i]                   ?? ''),
                'nome'        => $nome,
                'referencia'  => trim($_POST['referencia'][$i]             ?? ''),
                'quantidade'  => trim($_POST['quantidade'][$i]             ?? ''),
                'estado'      => trim($_POST['estado_componente'][$i]      ?? ''),
                'observacoes' => trim($_POST['observacoes_componente'][$i] ?? '')
            ];
        }
    }

    // Guardar na sessão (pode ser array vazio se não preencheu nada)
    $_SESSION['novo_equipamento']['sep2'] = $componentes;

    // Avançar para o Sep. 3
    header("Location: novo.php?sep=aquisicao");
    exit;
}

// Separador 3 — Aquisição
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submeter_sep3'])) {

    $erros = [];

    $data_aquisicao = trim($_POST['data_aquisicao'] ?? '');
    $custo          = trim($_POST['custo']          ?? '');
    $tipo_entrada   = trim($_POST['tipo_entrada']   ?? '');

    if (empty($data_aquisicao)) {
        $erros[] = "A data de aquisição é obrigatória.";
    }

    if (empty($custo)) {
        $erros[] = "O custo de aquisição é obrigatório.";
    } elseif (!is_numeric($custo) || $custo < 0) {
        $erros[] = "O custo de aquisição é inválido.";
    }

    if (empty($tipo_entrada)) {
        $erros[] = "O tipo de entrada é obrigatório.";
    }

    if (empty($erros)) {
        $_SESSION['novo_equipamento']['sep3'] = [
            'data_aquisicao'              => $data_aquisicao,
            'custo'                       => $custo,
            'tipo_entrada'                => $tipo_entrada,
            'contrato_aquisicao_nome'     => trim($_POST['contrato_aquisicao_nome']     ?? ''),
            'contrato_aquisicao_data'     => trim($_POST['contrato_aquisicao_data']     ?? ''),
            'contrato_aquisicao_validade' => trim($_POST['contrato_aquisicao_validade'] ?? ''),
            'fatura_aquisicao_nome'       => trim($_POST['fatura_aquisicao_nome']       ?? ''),
            'fatura_aquisicao_data'       => trim($_POST['fatura_aquisicao_data']       ?? ''),
            'fatura_aquisicao_pagamento'  => trim($_POST['fatura_aquisicao_pagamento']  ?? ''),
        ];

        header("Location: novo.php?sep=fornecedor");
        exit;
    }
}
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?>


<div class="container-fluid">
    <div class="row">

        <?php include '../../includes/sidebar.php'; ?>


        <main class="col-md-9 col-lg-10 p-4">

            <div class="card-form">

                <h2 class="mb-4" style="color: #1a826d;">
                    <i class="fa-solid fa-plus me-2"></i> Novo Equipamento
                </h2>

                <!-- SEPARADORES -->
                <?php
                $sepAtivo = $_GET['sep'] ?? (isset($_SESSION['novo_equipamento']['sep3']) ? 'fornecedor' : (isset($_SESSION['novo_equipamento']['sep2']) ? 'aquisicao' : (isset($_SESSION['novo_equipamento']['sep1']) ? 'componentes' : 'dados')));
                ?>

                <ul class="nav nav-tabs mb-4 flex-nowrap" id="equipTabs" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $sepAtivo == 'dados' ? 'active' : '' ?>"
                            data-bs-toggle="tab" data-bs-target="#dados" type="button">
                            Equipamento
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $sepAtivo == 'componentes' ? 'active' : (isset($_SESSION['novo_equipamento']['sep1']) ? '' : 'disabled') ?>"
                            data-bs-toggle="tab" data-bs-target="#componentes" type="button">
                            Componentes <br> e Consumíveis
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $sepAtivo == 'aquisicao' ? 'active' : (isset($_SESSION['novo_equipamento']['sep2']) ? '' : 'disabled') ?>"
                            data-bs-toggle="tab" data-bs-target="#aquisicao" type="button">
                            Aquisição
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $sepAtivo == 'fornecedor' ? 'active' : (isset($_SESSION['novo_equipamento']['sep3']) ? '' : 'disabled') ?>"
                            data-bs-toggle="tab" data-bs-target="#fornecedor" type="button">
                            Fornecedor <br> Associado
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link disabled" data-bs-toggle="tab" data-bs-target="#localizacao" type="button">
                            Localização <br> Associada
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link disabled" data-bs-toggle="tab" data-bs-target="#garantia" type="button">
                            Garantia
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link disabled" data-bs-toggle="tab" data-bs-target="#contrato" type="button">
                            Contrato de <br> Manutenção
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link disabled" data-bs-toggle="tab" data-bs-target="#documentos" type="button">
                            Documentação <br> Associada
                        </button>
                    </li>

                </ul>

                <?php if (!empty($erros)): ?>
                    <div class="alert alert-danger" role="alert">
                        <strong>Foram encontrados os seguintes erros:</strong>
                        <ul class="mb-0">
                            <?php foreach ($erros as $erro): ?>
                                <li><?= htmlspecialchars($erro) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($erro_sistema)): ?>
                    <div class="alert alert-danger" role="alert">
                        <strong>Erro:</strong>
                        <p><?= htmlspecialchars($erro_sistema) ?></p>
                    </div>
                <?php endif; ?>

                <!-- CONTEÚDO DOS SEPARADORES -->
                <form id="formEquipamentoCompleto" method="post" action="#">

                    <div class="tab-content" id="equipTabsContent">


                        <!-- SEPARADOR 1 — EQUIPAMENTO -->
                        <div class="tab-pane fade <?= $sepAtivo == 'dados' ? 'show active' : '' ?>" id="dados" role="tabpanel">

                            <!-- Código + Designação -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Código Interno De Inventário *</label>
                                    <input type="text" class="form-control" name="codigo" placeholder="Ex: EQ-2025-001"
                                        value="<?= htmlspecialchars($_POST['codigo'] ?? $_SESSION['novo_equipamento']['sep1']['codigo'] ?? '') ?>">
                                </div>

                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Designação Do Equipamento *</label>
                                    <input type="text" class="form-control" name="designacao" placeholder="Ex: Monitor multiparamétrico"
                                        value="<?= htmlspecialchars($_POST['designacao'] ?? $_SESSION['novo_equipamento']['sep1']['designacao'] ?? '') ?>">
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
Reabilitação — Apoia a recuperação funcional do paciente.
">
                                    </i>
                                </label>

                                <select class="form-select" name="categoria">
                                    <option value="Monitorização" <?= (($_POST['categoria'] ?? $_SESSION['novo_equipamento']['sep1']['categoria'] ?? '') == 'Monitorização') ? 'selected' : '' ?>>Monitorização</option>
                                    <option value="Suporte de vida" <?= (($_POST['categoria'] ?? $_SESSION['novo_equipamento']['sep1']['categoria'] ?? '') == 'Suporte de vida') ? 'selected' : '' ?>>Suporte de vida</option>
                                    <option value="Terapia" <?= (($_POST['categoria'] ?? $_SESSION['novo_equipamento']['sep1']['categoria'] ?? '') == 'Terapia') ? 'selected' : '' ?>>Terapia</option>
                                    <option value="Diagnóstico" <?= (($_POST['categoria'] ?? $_SESSION['novo_equipamento']['sep1']['categoria'] ?? '') == 'Diagnóstico') ? 'selected' : '' ?>>Diagnóstico</option>
                                    <option value="Laboratório" <?= (($_POST['categoria'] ?? $_SESSION['novo_equipamento']['sep1']['categoria'] ?? '') == 'Laboratório') ? 'selected' : '' ?>>Laboratório</option>
                                    <option value="Esterilização" <?= (($_POST['categoria'] ?? $_SESSION['novo_equipamento']['sep1']['categoria'] ?? '') == 'Esterilização') ? 'selected' : '' ?>>Esterilização</option>
                                    <option value="Reabilitação" <?= (($_POST['categoria'] ?? $_SESSION['novo_equipamento']['sep1']['categoria'] ?? '') == 'Reabilitação') ? 'selected' : '' ?>>Reabilitação</option>
                                </select>
                            </div>

                            <!-- Marca + Modelo -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Marca *</label>
                                    <input type="text" class="form-control" name="marca" placeholder="Ex: Philips"
                                        value="<?= htmlspecialchars($_POST['marca'] ?? $_SESSION['novo_equipamento']['sep1']['marca'] ?? '') ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Modelo *</label>
                                    <input type="text" class="form-control" name="modelo" placeholder="Ex: IntelliVue MP5"
                                        value="<?= htmlspecialchars($_POST['modelo'] ?? $_SESSION['novo_equipamento']['sep1']['modelo'] ?? '') ?>">
                                </div>
                            </div>

                            <!-- Nº Série + Fabricante -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Número de Série *</label>
                                    <input type="text" class="form-control" name="numero_serie" placeholder="Ex: MP5-2022-45873"
                                        value="<?= htmlspecialchars($_POST['numero_serie'] ?? $_SESSION['novo_equipamento']['sep1']['numero_serie'] ?? '') ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fabricante *</label>
                                    <input type="text" class="form-control" name="fabricante" placeholder="Ex: Philips Healthcare"
                                        value="<?= htmlspecialchars($_POST['fabricante'] ?? $_SESSION['novo_equipamento']['sep1']['fabricante'] ?? '') ?>">
                                </div>
                            </div>

                            <!-- Ano Fabrico + Estado + Criticidade -->
                            <div class="row">

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Ano de Fabrico *</label>
                                    <input type="number" class="form-control" name="ano_fabrico" placeholder="Ex: 2022" min="1900" max="2100"
                                        value="<?= htmlspecialchars($_POST['ano_fabrico'] ?? $_SESSION['novo_equipamento']['sep1']['ano_fabrico'] ?? '') ?>">
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
                                        <option value="Ativo" <?= (($_POST['estado'] ?? $_SESSION['novo_equipamento']['sep1']['estado'] ?? '') == 'Ativo') ? 'selected' : '' ?>>Ativo</option>
                                        <option value="Em manutenção" <?= (($_POST['estado'] ?? $_SESSION['novo_equipamento']['sep1']['estado'] ?? '') == 'Em manutenção') ? 'selected' : '' ?>>Em manutenção</option>
                                        <option value="Inativo" <?= (($_POST['estado'] ?? $_SESSION['novo_equipamento']['sep1']['estado'] ?? '') == 'Inativo') ? 'selected' : '' ?>>Inativo</option>
                                        <option value="Em calibração" <?= (($_POST['estado'] ?? $_SESSION['novo_equipamento']['sep1']['estado'] ?? '') == 'Em calibração') ? 'selected' : '' ?>>Em calibração</option>
                                        <option value="Em quarentena" <?= (($_POST['estado'] ?? $_SESSION['novo_equipamento']['sep1']['estado'] ?? '') == 'Em quarentena') ? 'selected' : '' ?>>Em quarentena</option>
                                        <option value="Abatido" <?= (($_POST['estado'] ?? $_SESSION['novo_equipamento']['sep1']['estado'] ?? '') == 'Abatido') ? 'selected' : '' ?>>Abatido</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">
                                        Criticidade *
                                        <i class="fa-solid fa-circle-info ms-1 text-muted" data-bs-toggle="popover"
                                            data-bs-trigger="hover focus" data-bs-html="true" title="Criticidade"
                                            data-bs-content="
Baixa - Equipamentos cuja falha não tem impacto direto na segurança do doente.<br>
Média - Equipamentos utilizados em procedimentos clínicos, mas cuja falha não compromete imediatamente a vida do doente.<br>
Alta - Equipamentos essenciais para diagnóstico ou tratamento clínico.<br>
Suporte de vida - Equipamentos cuja falha pode colocar em risco imediato a vida do doente.
">
                                        </i>
                                    </label>

                                    <select class="form-select" name="criticidade">
                                        <option value="Baixa" <?= (($_POST['criticidade'] ?? $_SESSION['novo_equipamento']['sep1']['criticidade'] ?? '') == 'Baixa') ? 'selected' : '' ?>>Baixa</option>
                                        <option value="Média" <?= (($_POST['criticidade'] ?? $_SESSION['novo_equipamento']['sep1']['criticidade'] ?? '') == 'Média') ? 'selected' : '' ?>>Média</option>
                                        <option value="Alta" <?= (($_POST['criticidade'] ?? $_SESSION['novo_equipamento']['sep1']['criticidade'] ?? '') == 'Alta') ? 'selected' : '' ?>>Alta</option>
                                        <option value="Suporte de vida" <?= (($_POST['criticidade'] ?? $_SESSION['novo_equipamento']['sep1']['criticidade'] ?? '') == 'Suporte de vida') ? 'selected' : '' ?>>Suporte de vida</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Observações -->
                            <div class="mb-3">
                                <label class="form-label">Observações</label>
                                <textarea class="form-control" name="observacoes" rows="3"><?= htmlspecialchars($_POST['observacoes'] ?? $_SESSION['novo_equipamento']['sep1']['observacoes'] ?? '') ?></textarea>
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
                                $componentes_sessao = $_SESSION['novo_equipamento']['sep2'] ?? [['tipo' => 'componente', 'nome' => '', 'referencia' => '', 'quantidade' => '', 'estado' => '', 'observacoes' => '']];
                                foreach ($componentes_sessao as $comp):
                                ?>
                                    <div class="componente-bloco border rounded p-3 mb-3" style="border-color:#86b0aa;">
                                        <div class="row g-3">

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Tipo *
                                                    <i class="fa-solid fa-circle-info ms-1 text-muted" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-html="true" title="Tipo de Item" data-bs-content="
Componente - Parte técnica do equipamento (sensores, cabos, baterias, módulos).<br>
Consumível - Item usado e substituído regularmente (gel, filtros, papel térmico)."></i>
                                                </label>
                                                <select class="form-select" name="tipo[]">
                                                    <option value="componente" <?= ($comp['tipo'] ?? '') == 'componente' ? 'selected' : '' ?>>Componente</option>
                                                    <option value="consumivel" <?= ($comp['tipo'] ?? '') == 'consumivel' ? 'selected' : '' ?>>Consumível</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Nome *</label>
                                                <input type="text" class="form-control" name="nome_componente[]"
                                                    placeholder="Ex: Sensor SpO2, Gel, Cabo ECG"
                                                    value="<?= htmlspecialchars($comp['nome'] ?? '') ?>">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Referência</label>
                                                <input type="text" class="form-control" name="referencia[]"
                                                    placeholder="Ex: DS-100A"
                                                    value="<?= htmlspecialchars($comp['referencia'] ?? '') ?>">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Quantidade</label>
                                                <input type="number" class="form-control" name="quantidade[]"
                                                    placeholder="Ex: 3" min="0"
                                                    value="<?= htmlspecialchars($comp['quantidade'] ?? '') ?>">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Estado</label>
                                                <select class="form-select" name="estado_componente[]">
                                                    <option value="">—</option>
                                                    <option value="Ativo" <?= ($comp['estado'] ?? '') == 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                                                    <option value="Em manutenção" <?= ($comp['estado'] ?? '') == 'Em manutenção' ? 'selected' : '' ?>>Em manutenção</option>
                                                    <option value="Inativo" <?= ($comp['estado'] ?? '') == 'Inativo' ? 'selected' : '' ?>>Inativo</option>
                                                    <option value="Em calibração" <?= ($comp['estado'] ?? '') == 'Em calibração' ? 'selected' : '' ?>>Em calibração</option>
                                                    <option value="Abatido" <?= ($comp['estado'] ?? '') == 'Abatido' ? 'selected' : '' ?>>Abatido</option>
                                                </select>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label">Observações</label>
                                                <textarea class="form-control" name="observacoes_componente[]" rows="1"
                                                    placeholder="Notas adicionais"><?= htmlspecialchars($comp['observacoes'] ?? '') ?></textarea>
                                            </div>

                                            <div class="col-12 text-end mt-1">
                                                <button type="button" class="btn btn-danger btn-sm remover-componente">
                                                    Remover
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>



                            <!-- Botão adicionar -->
                            <button type="button" class="btn btn-success mb-4" id="adicionarComponente">
                                + Adicionar
                            </button>

                            <!-- Botões navegação -->
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
                                    <input type="text" class="form-control" id="data_aquisicao" name="data_aquisicao"
                                        value="<?= htmlspecialchars($_POST['data_aquisicao'] ?? $_SESSION['novo_equipamento']['sep3']['data_aquisicao'] ?? '') ?>">
                                </div>

                                <!-- Custo -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Custo de Aquisição (€) *</label>
                                    <input type="number" class="form-control" name="custo" placeholder="Ex: 3500" min="0" step="0.01"
                                        value="<?= htmlspecialchars($_POST['custo'] ?? $_SESSION['novo_equipamento']['sep3']['custo'] ?? '') ?>">
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

                                    <select class="form-select" name="tipo_entrada" id="tipoEntrada">
                                        <option value="compra" <?= (($_POST['tipo_entrada'] ?? $_SESSION['novo_equipamento']['sep3']['tipo_entrada'] ?? '') == 'compra') ? 'selected' : '' ?>>Compra</option>
                                        <option value="doacao" <?= (($_POST['tipo_entrada'] ?? $_SESSION['novo_equipamento']['sep3']['tipo_entrada'] ?? '') == 'doacao') ? 'selected' : '' ?>>Doação</option>
                                        <option value="aluguer" <?= (($_POST['tipo_entrada'] ?? $_SESSION['novo_equipamento']['sep3']['tipo_entrada'] ?? '') == 'aluguer') ? 'selected' : '' ?>>Aluguer</option>
                                        <option value="emprestimo" <?= (($_POST['tipo_entrada'] ?? $_SESSION['novo_equipamento']['sep3']['tipo_entrada'] ?? '') == 'emprestimo') ? 'selected' : '' ?>>Empréstimo</option>
                                    </select>

                                </div>

                            </div>

                            <hr class="my-4">

                            <div class="accordion" id="accordionAquisicao">
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
                                                        <h5 class="mb-3" style="color:#1a826d;">Contrato de Aquisição</h5>

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Tipo de Documento *</label>
                                                                <select class="form-select" name="contrato_aquisicao_tipo">
                                                                    <option>Contrato de Aquisição</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Nome do Documento *</label>
                                                                <input type="text" class="form-control" name="contrato_aquisicao_nome"
                                                                    placeholder="Ex: Contrato de Compra"
                                                                    value="<?= htmlspecialchars($_POST['contrato_aquisicao_nome'] ?? $_SESSION['novo_equipamento']['sep3']['contrato_aquisicao_nome'] ?? '') ?>">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Data do Documento *</label>
                                                                <input type="text" class="form-control" id="contrato_aquisicao_data" name="contrato_aquisicao_data"
                                                                    value="<?= htmlspecialchars($_POST['contrato_aquisicao_data'] ?? $_SESSION['novo_equipamento']['sep3']['contrato_aquisicao_data'] ?? '') ?>">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Data de Validade</label>
                                                                <input type="text" class="form-control" id="contrato_aquisicao_validade" name="contrato_aquisicao_validade"
                                                                    value="<?= htmlspecialchars($_POST['contrato_aquisicao_validade'] ?? $_SESSION['novo_equipamento']['sep3']['contrato_aquisicao_validade'] ?? '') ?>">
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Ficheiro (PDF) *</label>
                                                            <input type="file" class="form-control" name="contrato_aquisicao_ficheiro" accept="application/pdf">
                                                        </div>

                                                        <button type="button" class="btn btn-danger">Remover Documento</button>
                                                    </div>
                                                </div>

                                                <!-- BLOCO 2 — Fatura da Aquisição -->
                                                <div class="col-md-6" id="blocoFatura">
                                                    <div class="border rounded p-3 mb-3">
                                                        <h5 class="mb-3" style="color:#1a826d;">Fatura da Aquisição</h5>

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Tipo de Documento *</label>
                                                                <select class="form-select" name="fatura_aquisicao_tipo">
                                                                    <option>Fatura de Aquisição</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Nome do Documento *</label>
                                                                <input type="text" class="form-control" name="fatura_aquisicao_nome"
                                                                    placeholder="Ex: Fatura de Compra"
                                                                    value="<?= htmlspecialchars($_POST['fatura_aquisicao_nome'] ?? $_SESSION['novo_equipamento']['sep3']['fatura_aquisicao_nome'] ?? '') ?>">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Data do Documento *</label>
                                                                <input type="text" class="form-control" id="fatura_aquisicao_data" name="fatura_aquisicao_data"
                                                                    value="<?= htmlspecialchars($_POST['fatura_aquisicao_data'] ?? $_SESSION['novo_equipamento']['sep3']['fatura_aquisicao_data'] ?? '') ?>">
                                                            </div>

                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Ficheiro (PDF) *</label>
                                                            <input type="file" class="form-control" name="fatura_aquisicao_ficheiro" accept="application/pdf">
                                                        </div>

                                                        <button type="button" class="btn btn-danger">Remover Documento</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary" onclick="mostrarSeparador('componentes')">
                                    ← Anterior
                                </button>

                                <button type="submit" name="submeter_sep3" class="btn" style="background-color:#1a826d; color:white;">
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
                                            <select class="form-select" name="fornecedor_id[]">
                                                <option value="">Selecione...</option>
                                                <?php foreach ($fornecedores as $f): ?>
                                                    <option value="<?= $f['id'] ?>">
                                                        <?= htmlspecialchars($f['codigo'] . ' – ' . $f['nome']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Tipo de Fornecedor-->
                                        <div class="col-md-4">
                                            <label class="form-label">Tipo *</label>
                                            <select class="form-select">
                                                <option>Fabricante</option>
                                                <option>Distribuidor / Comercial</option>
                                                <option>Assistência Técnica</option>
                                                <option>Consumíveis / Acessórios</option>
                                            </select>
                                        </div>

                                        <!-- Morada Associada-->
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

                                        <!-- Telefone da Pessoa de Contacto-->
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
                        <div class="tab-pane fade" id="localizacao" role="tabpanel">

                            <h4 class="mb-3" style="color:#1a826d;">Localização Associada</h4>

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
                                    onclick="validarEAvancar('localizacao', 'garantia')">
                                    Seguinte →
                                </button>
                            </div>

                        </div>

                        <!-- SEPARADOR 6 — GARANTIA -->
                        <div class="tab-pane fade" id="garantia" role="tabpanel">

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
                                                            placeholder="Ex: Certificado de Garantia">
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
                        <div class="tab-pane fade" id="contrato" role="tabpanel">

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
                        <div class="tab-pane fade" id="documentos" role="tabpanel">

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

                                <button type="button" class="btn" style="background-color:#1a826d; color:white;"
                                    onclick="window.location.href='lista.php'">
                                    Guardar Equipamento ✔
                                </button>
                            </div>

                        </div>



                    </div>
                </form>


        </main>

    </div>
</div>

<script>
    flatpickr("#data_aquisicao", {
        dateFormat: "Y-m-d"
    });
    flatpickr("#contrato_aquisicao_data", {
        dateFormat: "Y-m-d"
    });
    flatpickr("#contrato_aquisicao_validade", {
        dateFormat: "Y-m-d"
    });
    flatpickr("#fatura_aquisicao_data", {
        dateFormat: "Y-m-d"
    });
    flatpickr("#fatura_aquisicao_pagamento", {
        dateFormat: "Y-m-d"
    });
</script>

<?php include '../../includes/footer.php'; ?>