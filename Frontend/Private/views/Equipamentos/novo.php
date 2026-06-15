<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
require_once __DIR__ . '/../../includes/validacoes.php';

$erros = [];
$erro_sistema = "";

// Carregar fornecedores e localizações da BD
try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $stmt = $ligacao->query("SELECT id, codigo, nome FROM fornecedores ORDER BY nome");
    $fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $ligacao->query("SELECT id, codigo, edificio, piso, sala FROM localizacoes ORDER BY codigo");
    $localizacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $ligacao = null;
} catch (PDOException $e) {
    $fornecedores = [];
    $localizacoes = [];
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
    $categoria    = trim($categoria);
    $marca        = trim($marca);
    $modelo       = trim($modelo);
    $numero_serie = trim($numero_serie);
    $fabricante   = trim($fabricante);
    $ano_fabrico  = trim($ano_fabrico);
    $estado       = trim($estado);
    $criticidade  = trim($criticidade);
    $observacoes  = trim($observacoes);

    $erros = array_merge($erros, validar_codigo($codigo));
    $erros = array_merge($erros, validar_texto_obrigatorio($designacao, 'A designação'));
    $erros = array_merge($erros, validar_select($categoria, 'A categoria'));
    $erros = array_merge($erros, validar_texto_obrigatorio($marca, 'A marca'));
    $erros = array_merge($erros, validar_texto_obrigatorio($modelo, 'O modelo'));
    $erros = array_merge($erros, validar_numero_serie($numero_serie));
    $erros = array_merge($erros, validar_texto_obrigatorio($fabricante, 'O fabricante'));
    $erros = array_merge($erros, validar_ano_fabrico($ano_fabrico));
    $erros = array_merge($erros, validar_select($estado, 'O estado'));
    $erros = array_merge($erros, validar_select($criticidade, 'A criticidade'));


    // 3. Normalizar dados
    $designacao = ucwords(strtolower($designacao));
    $marca      = ucwords(strtolower($marca));
    $modelo     = ucwords(strtolower($modelo));
    $fabricante = ucwords(strtolower($fabricante));
    $codigo     = strtoupper($codigo);


    // 4. Se não houver erros, guardar na sessão e avançar
    if (empty($erros)) {

        // Verificar se o código já existe na BD
        try {
            $ligacao = new PDO(
                "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
                MYSQL_USERNAME,
                MYSQL_PASSWORD
            );
            $stmt = $ligacao->prepare("SELECT id FROM equipamentos WHERE codigo = :codigo");
            $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetch()) {
                $erros[] = "O código {$codigo} já existe na base de dados.";
            }
            $ligacao = null;
        } catch (PDOException $e) {
            $erros[] = "Erro ao verificar o código.";
        }
    }

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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['voltar_sep1'])) {
    $nomes = $_POST['nome_componente'] ?? [];
    $componentes = [];

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

    $_SESSION['novo_equipamento']['sep2'] = $componentes;
    header("Location: novo.php?sep=dados");
    exit;
}

// Separador 2 — Componentes (opcional)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submeter_sep2'])) {

    $erros = [];
    $componentes = [];
    $nomes = $_POST['nome_componente'] ?? [];

    foreach ($nomes as $i => $nome) {
        $nome       = trim($nome);
        $tipo       = trim($_POST['tipo'][$i]               ?? '');
        $referencia = trim($_POST['referencia'][$i]         ?? '');
        $quantidade = trim($_POST['quantidade'][$i]         ?? '');
        $estado     = trim($_POST['estado_componente'][$i]  ?? '');


        $erros = array_merge($erros, validar_componente($nome, $tipo, $referencia, $quantidade, $estado));

        if (!empty($nome)) {
            $componentes[] = [
                'tipo'        => $tipo,
                'nome'        => $nome,
                'referencia'  => $referencia,
                'quantidade'  => $quantidade,
                'estado'      => $estado,
                'observacoes' => trim($_POST['observacoes_componente'][$i] ?? '')
            ];
        }
    }

    if (empty($erros)) {
        $_SESSION['novo_equipamento']['sep2'] = $componentes;
        header("Location: novo.php?sep=aquisicao");
        exit;
    } else {
        $_SESSION['sep_ativo'] = 'componentes';
    }
}

// Separador 3 — Aquisição
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submeter_sep3'])) {

    $erros = [];

    $data_aquisicao             = trim($_POST['data_aquisicao']          ?? '');
    $custo                      = trim($_POST['custo']                   ?? '');
    $tipo_entrada               = trim($_POST['tipo_entrada']            ?? '');
    $contrato_aquisicao_nome    = trim($_POST['contrato_aquisicao_nome'] ?? '');
    $contrato_aquisicao_data    = trim($_POST['contrato_aquisicao_data'] ?? '');
    $fatura_aquisicao_nome      = trim($_POST['fatura_aquisicao_nome']   ?? '');
    $fatura_aquisicao_data      = trim($_POST['fatura_aquisicao_data']   ?? '');

    $erros = array_merge($erros, validar_data_obrigatoria($data_aquisicao, 'A data de aquisição'));
    $erros = array_merge($erros, validar_custo($custo));
    $erros = array_merge($erros, validar_select($tipo_entrada, 'O tipo de entrada'));
    $erros = array_merge($erros, validar_texto_obrigatorio($contrato_aquisicao_nome, 'O nome do contrato de aquisição'));
    $erros = array_merge($erros, validar_data_obrigatoria($contrato_aquisicao_data, 'A data do contrato de aquisição'));
    $erros = array_merge($erros, validar_data_posterior($contrato_aquisicao_data, $data_aquisicao, 'A data do contrato', 'a data de aquisição'));

    if ($tipo_entrada === 'compra') {
        $erros = array_merge($erros, validar_texto_obrigatorio($fatura_aquisicao_nome, 'O nome da fatura de aquisição'));
        $erros = array_merge($erros, validar_data_obrigatoria($fatura_aquisicao_data, 'A data da fatura de aquisição'));
        $erros = array_merge($erros, validar_data_posterior($fatura_aquisicao_data, $data_aquisicao, 'A data da fatura', 'a data de aquisição'));
    }

    if (!empty($erros)) {
        $_SESSION['sep_ativo'] = 'aquisicao';
    }

    if (empty($erros)) {
        $_SESSION['novo_equipamento']['sep3'] = [
            'data_aquisicao'              => $data_aquisicao,
            'custo'                       => $custo,
            'tipo_entrada'                => $tipo_entrada,
            'contrato_aquisicao_nome'     => $contrato_aquisicao_nome,
            'contrato_aquisicao_data'     => $contrato_aquisicao_data,
            'contrato_aquisicao_validade' => trim($_POST['contrato_aquisicao_validade'] ?? ''),
            'fatura_aquisicao_nome'       => $fatura_aquisicao_nome,
            'fatura_aquisicao_data'       => $fatura_aquisicao_data,
            'fatura_aquisicao_pagamento'  => trim($_POST['fatura_aquisicao_pagamento']  ?? ''),
        ];

        header("Location: novo.php?sep=fornecedor");
        exit;
    }
}

// Separador 4 — Fornecedor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submeter_sep4'])) {

    $erros = [];
    $fornecedores_selecionados = [];
    $ids = $_POST['fornecedor_id'] ?? [];

    foreach ($ids as $i => $id) {
        if (!empty($id)) {
            $telefone = trim($_POST['telefone_contacto'][$i] ?? '');

            $erros = array_merge($erros, validar_telefone($telefone));


            $fornecedores_selecionados[] = [
                'fornecedor_id'    => $id,
                'tipo_relacao'     => trim($_POST['tipo_relacao'][$i]          ?? ''),
                'morada_associada' => trim($_POST['morada_associada'][$i]      ?? ''),
                'pessoa_contacto'  => trim($_POST['pessoa_contacto'][$i]       ?? ''),
                'telefone_contacto' => trim($_POST['telefone_contacto'][$i]    ?? ''),
                'observacoes'      => trim($_POST['observacoes_fornecedor'][$i] ?? '')
            ];
        }
    }

    if (empty($fornecedores_selecionados)) {
        $erros[] = "Tem de associar pelo menos um fornecedor.";
    }

    if (empty($erros)) {
        $_SESSION['novo_equipamento']['sep4'] = $fornecedores_selecionados;
        header("Location: novo.php?sep=localizacao");
        exit;
    }
}

// Separador 5 — Localização
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submeter_sep5'])) {

    $erros = [];
    $localizacao_id = trim($_POST['localizacao_id'] ?? '');

    $erros = array_merge($erros, validar_select($localizacao_id, 'A localização'));

    if (empty($erros)) {
        $_SESSION['novo_equipamento']['sep5'] = [
            'localizacao_id' => $localizacao_id
        ];
        header("Location: novo.php?sep=garantia");
        exit;
    }
}

// Separador 6 — Garantia
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submeter_sep6'])) {

    $erros = [];

    $garantia_data_inicio = trim($_POST['garantia_data_inicio'] ?? '');
    $garantia_data_fim    = trim($_POST['garantia_data_fim']    ?? '');
    $garantia_entidade    = trim($_POST['garantia_entidade']    ?? '');
    $cert_garantia_nome   = trim($_POST['cert_garantia_nome']   ?? '');
    $cert_garantia_data   = trim($_POST['cert_garantia_data']   ?? '');

    $erros = array_merge($erros, validar_data_obrigatoria($garantia_data_inicio, 'A data de início da garantia'));
    $erros = array_merge($erros, validar_data_obrigatoria($garantia_data_fim, 'A data de fim da garantia'));
    $erros = array_merge($erros, validar_data_anterior($garantia_data_fim, $garantia_data_inicio, 'A data de fim da garantia', ' data de início'));
    $erros = array_merge($erros, validar_select($garantia_entidade, 'A entidade responsável'));
    $erros = array_merge($erros, validar_texto_obrigatorio($cert_garantia_nome, 'O nome do certificado de garantia'));
    $erros = array_merge($erros, validar_data_obrigatoria($cert_garantia_data, 'A data do certificado de garantia'));
    $erros = array_merge($erros, validar_data_posterior($cert_garantia_data, $garantia_data_inicio, 'A data do certificado', ' data de início da garantia'));

    if (!empty($erros)) {
        $_SESSION['sep_ativo'] = 'garantia';
    }

    if (empty($erros)) {
        $_SESSION['novo_equipamento']['sep6'] = [
            'garantia_data_inicio'   => $garantia_data_inicio,
            'garantia_data_fim'      => $garantia_data_fim,
            'garantia_entidade'      => $garantia_entidade,
            'garantia_observacoes'   => trim($_POST['garantia_observacoes']    ?? ''),
            'cert_garantia_nome'     => $cert_garantia_nome,
            'cert_garantia_data'     => $cert_garantia_data,
            'cert_garantia_validade' => trim($_POST['cert_garantia_validade']  ?? ''),
        ];

        header("Location: novo.php?sep=contrato");
        exit;
    }
}

// Separador 7 — Contrato de Manutenção
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submeter_sep7'])) {

    $erros = [];
    $tem_contrato = trim($_POST['tem_contrato'] ?? 'nao');

    if ($tem_contrato === 'sim') {

        $contrato_tipo          = trim($_POST['contrato_tipo']          ?? '');
        $contrato_periodicidade = trim($_POST['contrato_periodicidade'] ?? '');
        $contrato_data_inicio   = trim($_POST['contrato_data_inicio']   ?? '');
        $contrato_data_fim      = trim($_POST['contrato_data_fim']      ?? '');
        $contrato_entidade      = trim($_POST['contrato_entidade']      ?? '');
        $doc_contrato_nome      = trim($_POST['doc_contrato_nome']      ?? '');
        $doc_contrato_data      = trim($_POST['doc_contrato_data']      ?? '');

        $erros = array_merge($erros, validar_select($contrato_tipo, 'O tipo de contrato'));
        $erros = array_merge($erros, validar_select($contrato_periodicidade, 'A periodicidade'));
        $erros = array_merge($erros, validar_data_obrigatoria_futura($contrato_data_inicio, 'A data de início do contrato'));
        $erros = array_merge($erros, validar_data_obrigatoria_futura($contrato_data_fim, 'A data de fim do contrato'));
        $erros = array_merge($erros, validar_data_anterior($contrato_data_fim, $contrato_data_inicio, 'A data de fim do contrato', ' data de início'));
        $erros = array_merge($erros, validar_select($contrato_entidade, 'A entidade responsável'));
        $erros = array_merge($erros, validar_texto_obrigatorio($doc_contrato_nome, 'O nome do documento do contrato'));
        $erros = array_merge($erros, validar_data_obrigatoria($doc_contrato_data, 'A data do documento do contrato'));
        $erros = array_merge($erros, validar_data_posterior($doc_contrato_data, $contrato_data_inicio, 'A data do documento', ' data de início do contrato'));
    }

    if (!empty($erros)) {
        $_SESSION['sep_ativo'] = 'contrato';
    }

    if (empty($erros)) {
        $_SESSION['novo_equipamento']['sep7'] = [
            'tem_contrato'           => $tem_contrato,
            'contrato_tipo'          => trim($_POST['contrato_tipo']          ?? ''),
            'contrato_periodicidade' => trim($_POST['contrato_periodicidade'] ?? ''),
            'contrato_data_inicio'   => trim($_POST['contrato_data_inicio']   ?? ''),
            'contrato_data_fim'      => trim($_POST['contrato_data_fim']      ?? ''),
            'contrato_entidade'      => trim($_POST['contrato_entidade']      ?? ''),
            'contrato_observacoes'   => trim($_POST['contrato_observacoes']   ?? ''),
            'doc_contrato_nome'      => trim($_POST['doc_contrato_nome']      ?? ''),
            'doc_contrato_data'      => trim($_POST['doc_contrato_data']      ?? ''),
            'doc_contrato_validade'  => trim($_POST['doc_contrato_validade']  ?? ''),
        ];

        header("Location: novo.php?sep=documentos");
        exit;
    }
}

// Separador 8 — Documentação (opcional)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submeter_sep8'])) {

    $erros = [];
    $documentos = [];
    $nomes = $_POST['doc_nome'] ?? [];

    foreach ($nomes as $i => $nome) {
        $nome = trim($nome);
        $data = trim($_POST['doc_data'][$i] ?? '');

        $erros = array_merge($erros, validar_documento($nome, $data));

        if (!empty($nome)) {
            $documentos[] = [
                'tipo'     => trim($_POST['doc_tipo'][$i]     ?? ''),
                'nome'     => $nome,
                'data'     => $data,
                'validade' => trim($_POST['doc_validade'][$i] ?? ''),
            ];
        }
    }

    if (!empty($erros)) {
        $_SESSION['sep_ativo'] = 'documentos';
    }

    if (empty($erros)) {
        $_SESSION['novo_equipamento']['sep8'] = $documentos;

        try {
            $ligacao = new PDO(
                "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
                MYSQL_USERNAME,
                MYSQL_PASSWORD
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 1. Inserir equipamento
            $sep1 = $_SESSION['novo_equipamento']['sep1'];
            $sep3 = $_SESSION['novo_equipamento']['sep3'];
            $sep5 = $_SESSION['novo_equipamento']['sep5'];

            $stmt = $ligacao->prepare("INSERT INTO equipamentos (
            codigo, designacao, categoria, marca, modelo,
            numero_serie, fabricante, ano_fabrico, estado, criticidade,
            localizacao_id, data_aquisicao, custo, tipo_entrada, observacoes
        ) VALUES (
            :codigo, :designacao, :categoria, :marca, :modelo,
            :numero_serie, :fabricante, :ano_fabrico, :estado, :criticidade,
            :localizacao_id, :data_aquisicao, :custo, :tipo_entrada, :observacoes
        )");

            $stmt->execute([
                ':codigo'         => $sep1['codigo'],
                ':designacao'     => $sep1['designacao'],
                ':categoria'      => $sep1['categoria'],
                ':marca'          => $sep1['marca'],
                ':modelo'         => $sep1['modelo'],
                ':numero_serie'   => $sep1['numero_serie'],
                ':fabricante'     => $sep1['fabricante'],
                ':ano_fabrico'    => $sep1['ano_fabrico'],
                ':estado'         => $sep1['estado'],
                ':criticidade'    => $sep1['criticidade'],
                ':localizacao_id' => $sep5['localizacao_id'],
                ':data_aquisicao' => $sep3['data_aquisicao'],
                ':custo'          => $sep3['custo'],
                ':tipo_entrada'   => $sep3['tipo_entrada'],
                ':observacoes'    => $sep1['observacoes'],
            ]);

            $equipamento_id = $ligacao->lastInsertId();

            // 2. Inserir componentes (opcional)
            $sep2 = $_SESSION['novo_equipamento']['sep2'] ?? [];
            foreach ($sep2 as $comp) {
                $stmt = $ligacao->prepare("INSERT INTO componentes_consumiveis (
                equipamento_id, tipo, nome, referencia, quantidade, estado, observacoes
            ) VALUES (
                :equipamento_id, :tipo, :nome, :referencia, :quantidade, :estado, :observacoes
            )");
                $stmt->execute([
                    ':equipamento_id' => $equipamento_id,
                    ':tipo'           => $comp['tipo'],
                    ':nome'           => $comp['nome'],
                    ':referencia'     => $comp['referencia'],
                    ':quantidade'     => $comp['quantidade'] ?: null,
                    ':estado'         => $comp['estado'],
                    ':observacoes'    => $comp['observacoes'],
                ]);
            }

            // 3. Inserir fornecedores
            $sep4 = $_SESSION['novo_equipamento']['sep4'] ?? [];
            foreach ($sep4 as $forn) {
                $stmt = $ligacao->prepare("INSERT INTO equipamento_fornecedor (
                equipamento_id, fornecedor_id, tipo_relacao, morada_associada,
                pessoa_contacto, telefone_contacto, observacoes
            ) VALUES (
                :equipamento_id, :fornecedor_id, :tipo_relacao, :morada_associada,
                :pessoa_contacto, :telefone_contacto, :observacoes
            )");
                $stmt->execute([
                    ':equipamento_id'    => $equipamento_id,
                    ':fornecedor_id'     => $forn['fornecedor_id'],
                    ':tipo_relacao'      => $forn['tipo_relacao'],
                    ':morada_associada'  => $forn['morada_associada'],
                    ':pessoa_contacto'   => $forn['pessoa_contacto'],
                    ':telefone_contacto' => $forn['telefone_contacto'],
                    ':observacoes'       => $forn['observacoes'],
                ]);
            }

            // 3b. Inserir documentos da aquisição
            if (!empty($sep3['contrato_aquisicao_nome'])) {
                $stmt = $ligacao->prepare("INSERT INTO documentacao (
        equipamento_id, contexto, tipo_documento_id, nome_documento,
        data_documento, data_validade, ficheiro
    ) VALUES (
        :equipamento_id, 'aquisicao', 1, :nome_documento,
        :data_documento, :data_validade, ''
    )");
                $stmt->execute([
                    ':equipamento_id' => $equipamento_id,
                    ':nome_documento' => $sep3['contrato_aquisicao_nome'],
                    ':data_documento' => $sep3['contrato_aquisicao_data'] ?: null,
                    ':data_validade'  => $sep3['contrato_aquisicao_validade'] ?: null,
                ]);
            }

            if (!empty($sep3['fatura_aquisicao_nome'])) {
                $stmt = $ligacao->prepare("INSERT INTO documentacao (
        equipamento_id, contexto, tipo_documento_id, nome_documento,
        data_documento, data_validade, ficheiro
    ) VALUES (
        :equipamento_id, 'aquisicao', 1, :nome_documento,
        :data_documento, :data_validade, ''
    )");
                $stmt->execute([
                    ':equipamento_id' => $equipamento_id,
                    ':nome_documento' => $sep3['fatura_aquisicao_nome'],
                    ':data_documento' => $sep3['fatura_aquisicao_data'] ?: null,
                    ':data_validade'  => null,
                ]);
            }

            // 4. Inserir garantia
            $sep6 = $_SESSION['novo_equipamento']['sep6'];
            $stmt = $ligacao->prepare("INSERT INTO garantias (
            equipamento_id, data_inicio, data_fim, entidade_responsavel, observacoes
        ) VALUES (
            :equipamento_id, :data_inicio, :data_fim, :entidade_responsavel, :observacoes
        )");
            $stmt->execute([
                ':equipamento_id'       => $equipamento_id,
                ':data_inicio'          => $sep6['garantia_data_inicio'],
                ':data_fim'             => $sep6['garantia_data_fim'],
                ':entidade_responsavel' => $sep6['garantia_entidade'],
                ':observacoes'          => $sep6['garantia_observacoes'],
            ]);

            // 4b. Inserir documento do certificado de garantia
            if (!empty($sep6['cert_garantia_nome'])) {
                $stmt = $ligacao->prepare("INSERT INTO documentacao (
        equipamento_id, contexto, tipo_documento_id, nome_documento,
        data_documento, data_validade, ficheiro
    ) VALUES (
        :equipamento_id, 'garantia', 1, :nome_documento,
        :data_documento, :data_validade, ''
    )");
                $stmt->execute([
                    ':equipamento_id' => $equipamento_id,
                    ':nome_documento' => $sep6['cert_garantia_nome'],
                    ':data_documento' => $sep6['cert_garantia_data'] ?: null,
                    ':data_validade'  => $sep6['cert_garantia_validade'] ?: null,
                ]);
            }

            // 5. Inserir contrato (se existir)
            $sep7 = $_SESSION['novo_equipamento']['sep7'];
            if ($sep7['tem_contrato'] === 'sim') {
                $stmt = $ligacao->prepare("INSERT INTO contratos (
                equipamento_id, tipo_contrato, periodicidade, data_inicio,
                data_fim, entidade_responsavel, observacoes
            ) VALUES (
                :equipamento_id, :tipo_contrato, :periodicidade, :data_inicio,
                :data_fim, :entidade_responsavel, :observacoes
            )");
                $stmt->execute([
                    ':equipamento_id'       => $equipamento_id,
                    ':tipo_contrato'        => $sep7['contrato_tipo'],
                    ':periodicidade'        => $sep7['contrato_periodicidade'],
                    ':data_inicio'          => $sep7['contrato_data_inicio'],
                    ':data_fim'             => $sep7['contrato_data_fim'],
                    ':entidade_responsavel' => $sep7['contrato_entidade'],
                    ':observacoes'          => $sep7['contrato_observacoes'],
                ]);
            }

            // 5b. Inserir documento do contrato de manutenção
            if ($sep7['tem_contrato'] === 'sim' && !empty($sep7['doc_contrato_nome'])) {
                $stmt = $ligacao->prepare("INSERT INTO documentacao (
        equipamento_id, contexto, tipo_documento_id, nome_documento,
        data_documento, data_validade, ficheiro
    ) VALUES (
        :equipamento_id, 'contrato', 1, :nome_documento,
        :data_documento, :data_validade, ''
    )");
                $stmt->execute([
                    ':equipamento_id' => $equipamento_id,
                    ':nome_documento' => $sep7['doc_contrato_nome'],
                    ':data_documento' => $sep7['doc_contrato_data'] ?: null,
                    ':data_validade'  => $sep7['doc_contrato_validade'] ?: null,
                ]);
            }

            // 6. Inserir documentação extra (opcional)
            $sep8 = $_SESSION['novo_equipamento']['sep8'] ?? [];
            foreach ($sep8 as $doc) {
                $stmt = $ligacao->prepare("INSERT INTO documentacao (
                equipamento_id, contexto, tipo_documento_id, nome_documento,
                data_documento, data_validade, ficheiro
            ) VALUES (
                :equipamento_id, 'geral', 1, :nome_documento,
                :data_documento, :data_validade, ''
            )");
                $stmt->execute([
                    ':equipamento_id' => $equipamento_id,
                    ':nome_documento' => $doc['nome'],
                    ':data_documento' => $doc['data'] ?: null,
                    ':data_validade'  => $doc['validade'] ?: null,
                ]);
            }

            // Limpar sessão
            unset($_SESSION['novo_equipamento']);

            header("Location: lista.php?sucesso=1");
            exit;
        } catch (PDOException $err) {
            $erro_sistema = "Erro ao guardar o equipamento: " . $err->getMessage();
        }
    } // ← fecha o if (empty($erros))
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
                $sepAtivo = $_SESSION['sep_ativo'] ?? $_GET['sep'] ?? (isset($_SESSION['novo_equipamento']['sep7']) ? 'documentos' : (isset($_SESSION['novo_equipamento']['sep6']) ? 'contrato' : (isset($_SESSION['novo_equipamento']['sep5']) ? 'garantia' : (isset($_SESSION['novo_equipamento']['sep4']) ? 'localizacao' : (isset($_SESSION['novo_equipamento']['sep3']) ? 'fornecedor' : (isset($_SESSION['novo_equipamento']['sep2']) ? 'aquisicao' : (isset($_SESSION['novo_equipamento']['sep1']) ? 'componentes' : 'dados')))))));
                unset($_SESSION['sep_ativo']);
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
                        <button class="nav-link <?= $sepAtivo == 'localizacao' ? 'active' : (isset($_SESSION['novo_equipamento']['sep4']) ? '' : 'disabled') ?>"
                            data-bs-toggle="tab" data-bs-target="#localizacao" type="button">
                            Localização <br> Associada
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $sepAtivo == 'garantia' ? 'active' : (isset($_SESSION['novo_equipamento']['sep5']) ? '' : 'disabled') ?>"
                            data-bs-toggle="tab" data-bs-target="#garantia" type="button">
                            Garantia
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $sepAtivo == 'contrato' ? 'active' : (isset($_SESSION['novo_equipamento']['sep6']) ? '' : 'disabled') ?>"
                            data-bs-toggle="tab" data-bs-target="#contrato" type="button">
                            Contrato de <br> Manutenção
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $sepAtivo == 'documentos' ? 'active' : (isset($_SESSION['novo_equipamento']['sep7']) ? '' : 'disabled') ?>"
                            data-bs-toggle="tab" data-bs-target="#documentos" type="button">
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
                                    <option value="">Selecione...</option>
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
                                        <option value="">Selecione...</option>
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
                                        <option value="">Selecione...</option>
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
                                $componentes_sessao = $_SESSION['novo_equipamento']['sep2'] ?? [];
                                if (empty($componentes_sessao)) {
                                    $componentes_sessao = [['tipo' => '', 'nome' => '', 'referencia' => '', 'quantidade' => '', 'estado' => '', 'observacoes' => '']];
                                }
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
                                                    <option value="">Selecione...</option>
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
                                <button type="submit" name="voltar_sep1" class="btn btn-secondary">
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
                                        <option value="">Selecione...</option>
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

                                <?php
                                $fornecedores_sessao = $_SESSION['novo_equipamento']['sep4'] ?? [['fornecedor_id' => '', 'tipo_relacao' => 'Fabricante', 'morada_associada' => '', 'pessoa_contacto' => '', 'telefone_contacto' => '', 'observacoes' => '']];
                                foreach ($fornecedores_sessao as $forn):
                                ?>
                                    <div class="fornecedor-bloco border rounded p-3 mb-3" style="border-color:#86b0aa;">
                                        <div class="row g-3">

                                            <div class="col-md-4">
                                                <label class="form-label">Fornecedor *</label>
                                                <select class="form-select" name="fornecedor_id[]">
                                                    <option value="">Selecione...</option>
                                                    <?php foreach ($fornecedores as $f): ?>
                                                        <option value="<?= $f['id'] ?>" <?= ($forn['fornecedor_id'] ?? '') == $f['id'] ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($f['codigo'] . ' – ' . $f['nome']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Tipo *</label>
                                                <select class="form-select" name="tipo_relacao[]">
                                                    <option <?= ($forn['tipo_relacao'] ?? '') == 'Fabricante' ? 'selected' : '' ?>>Fabricante</option>
                                                    <option <?= ($forn['tipo_relacao'] ?? '') == 'Distribuidor / Comercial' ? 'selected' : '' ?>>Distribuidor / Comercial</option>
                                                    <option <?= ($forn['tipo_relacao'] ?? '') == 'Assistência Técnica' ? 'selected' : '' ?>>Assistência Técnica</option>
                                                    <option <?= ($forn['tipo_relacao'] ?? '') == 'Consumíveis / Acessórios' ? 'selected' : '' ?>>Consumíveis / Acessórios</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Morada Associada *</label>
                                                <input type="text" class="form-control" name="morada_associada[]"
                                                    placeholder="Ex: Armazém Norte – Braga"
                                                    value="<?= htmlspecialchars($forn['morada_associada'] ?? '') ?>">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Pessoa de Contacto</label>
                                                <input type="text" class="form-control" name="pessoa_contacto[]"
                                                    placeholder="Nome da pessoa"
                                                    value="<?= htmlspecialchars($forn['pessoa_contacto'] ?? '') ?>">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Telefone da Pessoa de Contacto</label>
                                                <input type="text" class="form-control" name="telefone_contacto[]"
                                                    placeholder="Ex: 912345678" pattern="[0-9]{9}" title="Introduza um número com 9 dígitos"
                                                    value="<?= htmlspecialchars($forn['telefone_contacto'] ?? '') ?>">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Observações</label>
                                                <textarea class="form-control" name="observacoes_fornecedor[]" rows="1"><?= htmlspecialchars($forn['observacoes'] ?? '') ?></textarea>
                                            </div>

                                            <div class="col-12 text-end mt-1">
                                                <button type="button" class="btn btn-danger btn-sm remover-fornecedor">
                                                    Remover Fornecedor
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>

                            <!-- Botão adicionar -->
                            <button type="button" class="btn btn-success mb-4" id="adicionarFornecedor">
                                + Adicionar Fornecedor
                            </button>

                            <!-- Botões navegação -->
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary" onclick="mostrarSeparador('aquisicao')">
                                    ← Anterior
                                </button>

                                <button type="submit" name="submeter_sep4" class="btn" style="background-color:#1a826d; color:white;">
                                    Seguinte →
                                </button>
                            </div>

                        </div>

                        <!-- SEPARADOR 5 — LOCALIZAÇÃO ASSOCIADA -->
                        <div class="tab-pane fade <?= $sepAtivo == 'localizacao' ? 'show active' : '' ?>" id="localizacao" role="tabpanel">

                            <h4 class="mb-3" style="color:#1a826d;">Localização Associada</h4>

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Localização *</label>
                                    <select class="form-select" name="localizacao_id">
                                        <option value="">Selecione...</option>
                                        <?php foreach ($localizacoes as $l): ?>
                                            <option value="<?= $l['id'] ?>" <?= (($_POST['localizacao_id'] ?? $_SESSION['novo_equipamento']['sep5']['localizacao_id'] ?? '') == $l['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($l['codigo'] . ' – ' . $l['edificio'] . ' / ' . $l['piso'] . ' / ' . $l['sala']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>

                            <!-- Botões -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary" onclick="mostrarSeparador('fornecedor')">
                                    ← Anterior
                                </button>

                                <button type="submit" name="submeter_sep5" class="btn" style="background-color:#1a826d; color:white;">
                                    Seguinte →
                                </button>
                            </div>

                        </div>

                        <!-- SEPARADOR 6 — GARANTIA -->
                        <div class="tab-pane fade <?= $sepAtivo == 'garantia' ? 'show active' : '' ?>" id="garantia" role="tabpanel">

                            <h4 class="mb-3" style="color:#1a826d;">Garantia</h4>

                            <!-- Datas -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Data de Início da Garantia *</label>
                                    <input type="text" class="form-control" id="garantia_data_inicio" name="garantia_data_inicio"
                                        value="<?= htmlspecialchars($_POST['garantia_data_inicio'] ?? $_SESSION['novo_equipamento']['sep6']['garantia_data_inicio'] ?? '') ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Data de Fim da Garantia *</label>
                                    <input type="text" class="form-control" id="garantia_data_fim" name="garantia_data_fim"
                                        value="<?= htmlspecialchars($_POST['garantia_data_fim'] ?? $_SESSION['novo_equipamento']['sep6']['garantia_data_fim'] ?? '') ?>">
                                </div>
                            </div>

                            <!-- Entidade Responsável + Observações -->
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Entidade Responsável *</label>
                                    <select class="form-select" name="garantia_entidade">
                                        <option value="">Selecione...</option>
                                        <option value="Fabricante" <?= (($_POST['garantia_entidade'] ?? $_SESSION['novo_equipamento']['sep6']['garantia_entidade'] ?? '') == 'Fabricante') ? 'selected' : '' ?>>Fabricante</option>
                                        <option value="Fornecedor" <?= (($_POST['garantia_entidade'] ?? $_SESSION['novo_equipamento']['sep6']['garantia_entidade'] ?? '') == 'Fornecedor') ? 'selected' : '' ?>>Fornecedor</option>
                                        <option value="Distribuidor Autorizado" <?= (($_POST['garantia_entidade'] ?? $_SESSION['novo_equipamento']['sep6']['garantia_entidade'] ?? '') == 'Distribuidor Autorizado') ? 'selected' : '' ?>>Distribuidor Autorizado</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Observações</label>
                                    <textarea class="form-control" name="garantia_observacoes" rows="3"><?= htmlspecialchars($_POST['garantia_observacoes'] ?? $_SESSION['novo_equipamento']['sep6']['garantia_observacoes'] ?? '') ?></textarea>
                                </div>

                            </div>

                            <hr class="my-4">

                            <div class="accordion" id="accordionGarantia">
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
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Tipo de Documento *</label>
                                                        <select class="form-select" name="cert_garantia_tipo">
                                                            <option>Certificado de Garantia</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Nome do Documento *</label>
                                                        <input type="text" class="form-control" name="cert_garantia_nome"
                                                            placeholder="Ex: Certificado de Garantia"
                                                            value="<?= htmlspecialchars($_POST['cert_garantia_nome'] ?? $_SESSION['novo_equipamento']['sep6']['cert_garantia_nome'] ?? '') ?>">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Data do Documento *</label>
                                                        <input type="text" class="form-control" id="cert_garantia_data" name="cert_garantia_data"
                                                            value="<?= htmlspecialchars($_POST['cert_garantia_data'] ?? $_SESSION['novo_equipamento']['sep6']['cert_garantia_data'] ?? '') ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Data de Validade</label>
                                                        <input type="text" class="form-control" id="cert_garantia_validade" name="cert_garantia_validade"
                                                            value="<?= htmlspecialchars($_POST['cert_garantia_validade'] ?? $_SESSION['novo_equipamento']['sep6']['cert_garantia_validade'] ?? '') ?>">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Ficheiro (PDF) *</label>
                                                    <input type="file" class="form-control" name="cert_garantia_ficheiro" accept="application/pdf">
                                                </div>

                                                <button type="button" class="btn btn-danger">Remover Documento</button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary" onclick="mostrarSeparador('localizacao')">
                                    ← Anterior
                                </button>

                                <button type="submit" name="submeter_sep6" class="btn" style="background-color:#1a826d; color:white;">
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
                                <select class="form-select" name="tem_contrato" id="temContrato" onchange="toggleContrato(this.value)">
                                    <option value="nao" <?= (($_POST['tem_contrato'] ?? $_SESSION['novo_equipamento']['sep7']['tem_contrato'] ?? 'nao') == 'nao') ? 'selected' : '' ?>>Não</option>
                                    <option value="sim" <?= (($_POST['tem_contrato'] ?? $_SESSION['novo_equipamento']['sep7']['tem_contrato'] ?? '') == 'sim') ? 'selected' : '' ?>>Sim</option>
                                </select>
                            </div>

                            <!-- Campos do contrato (escondidos por padrão) -->
                            <div id="camposContrato" style="display:none;">

                                <!-- Tipo + Periodicidade -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            Tipo de Contrato *
                                            <i class="fa-solid fa-circle-info ms-1 text-muted" data-bs-toggle="popover"
                                                data-bs-trigger="hover focus" data-bs-html="true" title="Tipo de Contrato"
                                                data-bs-content="
Manutenção Preventiva - Intervenções periódicas programadas para prevenir avarias e prolongar a vida útil dos equipamentos.<br>
Manutenção Corretiva - Reparação de avarias após a sua ocorrência, restabelecendo o funcionamento normal dos equipamentos.<br>
Full-Service - Contrato completo que inclui peças, mão de obra e manutenção preventiva e corretiva.<br>
Outsourcing - Gestão integral da manutenção dos equipamentos por uma entidade externa especializada.
">
                                            </i>
                                        </label>
                                        <select class="form-select" name="contrato_tipo">
                                            <option value="">Selecione...</option>
                                            <option value="Manutenção Preventiva" <?= (($_POST['contrato_tipo'] ?? $_SESSION['novo_equipamento']['sep7']['contrato_tipo'] ?? '') == 'Manutenção Preventiva') ? 'selected' : '' ?>>Manutenção Preventiva</option>
                                            <option value="Manutenção Corretiva" <?= (($_POST['contrato_tipo'] ?? $_SESSION['novo_equipamento']['sep7']['contrato_tipo'] ?? '') == 'Manutenção Corretiva') ? 'selected' : '' ?>>Manutenção Corretiva</option>
                                            <option value="Full-Service" <?= (($_POST['contrato_tipo'] ?? $_SESSION['novo_equipamento']['sep7']['contrato_tipo'] ?? '') == 'Full-Service') ? 'selected' : '' ?>>Full-Service</option>
                                            <option value="Outsourcing" <?= (($_POST['contrato_tipo'] ?? $_SESSION['novo_equipamento']['sep7']['contrato_tipo'] ?? '') == 'Outsourcing') ? 'selected' : '' ?>>Outsourcing</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Periodicidade *</label>
                                        <select class="form-select" name="contrato_periodicidade">
                                            <option value="">Selecione...</option>
                                            <option value="Mensal" <?= (($_POST['contrato_periodicidade'] ?? $_SESSION['novo_equipamento']['sep7']['contrato_periodicidade'] ?? '') == 'Mensal') ? 'selected' : '' ?>>Mensal</option>
                                            <option value="Trimestral" <?= (($_POST['contrato_periodicidade'] ?? $_SESSION['novo_equipamento']['sep7']['contrato_periodicidade'] ?? '') == 'Trimestral') ? 'selected' : '' ?>>Trimestral</option>
                                            <option value="Semestral" <?= (($_POST['contrato_periodicidade'] ?? $_SESSION['novo_equipamento']['sep7']['contrato_periodicidade'] ?? '') == 'Semestral') ? 'selected' : '' ?>>Semestral</option>
                                            <option value="Anual" <?= (($_POST['contrato_periodicidade'] ?? $_SESSION['novo_equipamento']['sep7']['contrato_periodicidade'] ?? '') == 'Anual') ? 'selected' : '' ?>>Anual</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Datas -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Data de Início *</label>
                                        <input type="text" class="form-control" id="contrato_data_inicio" name="contrato_data_inicio"
                                            value="<?= htmlspecialchars($_POST['contrato_data_inicio'] ?? $_SESSION['novo_equipamento']['sep7']['contrato_data_inicio'] ?? '') ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Data de Fim *</label>
                                        <input type="text" class="form-control" id="contrato_data_fim" name="contrato_data_fim"
                                            value="<?= htmlspecialchars($_POST['contrato_data_fim'] ?? $_SESSION['novo_equipamento']['sep7']['contrato_data_fim'] ?? '') ?>">
                                    </div>
                                </div>

                                <!-- Entidade Responsável + Observações -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Entidade Responsável *</label>
                                        <select class="form-select" name="contrato_entidade">
                                            <option value="">Selecione...</option>
                                            <option value="Empresa de assistência técnica" <?= (($_POST['contrato_entidade'] ?? $_SESSION['novo_equipamento']['sep7']['contrato_entidade'] ?? '') == 'Empresa de assistência técnica') ? 'selected' : '' ?>>Empresa de assistência técnica</option>
                                            <option value="Fabricante" <?= (($_POST['contrato_entidade'] ?? $_SESSION['novo_equipamento']['sep7']['contrato_entidade'] ?? '') == 'Fabricante') ? 'selected' : '' ?>>Fabricante</option>
                                            <option value="Distribuidor Autorizado" <?= (($_POST['contrato_entidade'] ?? $_SESSION['novo_equipamento']['sep7']['contrato_entidade'] ?? '') == 'Distribuidor Autorizado') ? 'selected' : '' ?>>Distribuidor Autorizado</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Observações</label>
                                        <textarea class="form-control" name="contrato_observacoes" rows="3"><?= htmlspecialchars($_POST['contrato_observacoes'] ?? $_SESSION['novo_equipamento']['sep7']['contrato_observacoes'] ?? '') ?></textarea>
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

                                        <div id="collapseContratoManutencaoDoc" class="accordion-collapse collapse show"
                                            aria-labelledby="headingContratoManutencaoDoc"
                                            data-bs-parent="#accordionContratoManutencao">

                                            <div class="accordion-body">
                                                <div class="border rounded p-3 mb-3">
                                                    <h5 class="mb-3" style="color:#1a826d;">Contrato de Manutenção</h5>

                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Tipo de Documento *</label>
                                                            <select class="form-select" name="doc_contrato_tipo">
                                                                <option>Contrato de Manutenção</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Nome do Documento *</label>
                                                            <input type="text" class="form-control" name="doc_contrato_nome"
                                                                placeholder="Ex: Contrato de Manutenção 2024-2025"
                                                                value="<?= htmlspecialchars($_POST['doc_contrato_nome'] ?? $_SESSION['novo_equipamento']['sep7']['doc_contrato_nome'] ?? '') ?>">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Data do Documento *</label>
                                                            <input type="text" class="form-control" id="doc_contrato_data" name="doc_contrato_data"
                                                                value="<?= htmlspecialchars($_POST['doc_contrato_data'] ?? $_SESSION['novo_equipamento']['sep7']['doc_contrato_data'] ?? '') ?>">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Data de Validade</label>
                                                            <input type="text" class="form-control" id="doc_contrato_validade" name="doc_contrato_validade"
                                                                value="<?= htmlspecialchars($_POST['doc_contrato_validade'] ?? $_SESSION['novo_equipamento']['sep7']['doc_contrato_validade'] ?? '') ?>">
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Ficheiro (PDF) *</label>
                                                        <input type="file" class="form-control" name="doc_contrato_ficheiro" accept="application/pdf">
                                                    </div>

                                                    <button type="button" class="btn btn-danger">Remover Documento</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Botões -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary" onclick="mostrarSeparador('garantia')">
                                    ← Anterior
                                </button>

                                <button type="submit" name="submeter_sep7" class="btn" style="background-color:#1a826d; color:white;">
                                    Seguinte →
                                </button>
                            </div>

                        </div>


                        <!-- SEPARADOR 8 — DOCUMENTAÇÃO ASSOCIADA -->
                        <div class="tab-pane fade <?= $sepAtivo == 'documentos' ? 'show active' : '' ?>" id="documentos" role="tabpanel">

                            <h4 class="mb-3" style="color:#1a826d;">Documentação Associada</h4>

                            <div id="documentosContainer">

                                <div class="documento-bloco border rounded p-3 mb-3" style="border-color:#86b0aa;">

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tipo de Documento *</label>
                                            <select class="form-select" name="doc_tipo[]">
                                                <option value="Manual">Manual</option>
                                                <option value="Ficha">Ficha</option>
                                                <option value="Certificado">Certificado</option>
                                                <option value="Relatório">Relatório</option>
                                                <option value="Declaração">Declaração</option>
                                                <option value="Outro">Outro</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nome do Documento *</label>
                                            <input type="text" class="form-control" name="doc_nome[]"
                                                placeholder="Ex: Manual do Utilizador">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Data do Documento *</label>
                                            <input type="text" class="form-control doc-data" name="doc_data[]">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Data de Validade</label>
                                            <input type="text" class="form-control doc-validade" name="doc_validade[]">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Ficheiro (PDF) *</label>
                                        <input type="file" class="form-control" name="doc_ficheiro[]" accept="application/pdf">
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
                                <button type="button" class="btn btn-secondary" onclick="mostrarSeparador('contrato')">
                                    ← Anterior
                                </button>

                                <button type="submit" name="submeter_sep8" class="btn" style="background-color:#1a826d; color:white;">
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
    flatpickr("#garantia_data_inicio", {
        dateFormat: "Y-m-d"
    });
    flatpickr("#garantia_data_fim", {
        dateFormat: "Y-m-d"
    });
    flatpickr("#cert_garantia_data", {
        dateFormat: "Y-m-d"
    });
    flatpickr("#cert_garantia_validade", {
        dateFormat: "Y-m-d"
    });

    flatpickr("#contrato_data_inicio", {
        dateFormat: "Y-m-d"
    });
    flatpickr("#contrato_data_fim", {
        dateFormat: "Y-m-d"
    });
    flatpickr("#doc_contrato_data", {
        dateFormat: "Y-m-d"
    });
    flatpickr("#doc_contrato_validade", {
        dateFormat: "Y-m-d"
    });
    flatpickr(".doc-data", {
        dateFormat: "Y-m-d"
    });
    flatpickr(".doc-validade", {
        dateFormat: "Y-m-d"
    });
</script>

<?php if (($_SESSION['novo_equipamento']['sep7']['tem_contrato'] ?? 'nao') == 'sim'): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const camposContrato = document.getElementById('camposContrato');
            if (camposContrato) {
                camposContrato.style.display = 'block';
            }
        });
    </script>
<?php endif; ?>

<?php include '../../includes/footer.php'; ?>