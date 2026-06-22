<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
bloquear_se_nao_autorizado(pode_editar_dados());
require_once __DIR__ . '/../../includes/validacoes.php';

if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
    exit;
}

$idEquipamentoEncrypted = $_GET['id_equipamento'] ?? null;
$idEquipamento = aes_decrypt($idEquipamentoEncrypted);

if (!$idEquipamento || !is_numeric($idEquipamento)) {
    header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
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

// Separador 3 — Aquisição
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submeter_sep3'])) {

    $erros = [];

    $data_aquisicao          = trim($_POST['data_aquisicao']          ?? '');
    $custo                   = trim($_POST['custo']                   ?? '');
    $tipo_entrada            = trim($_POST['tipo_entrada']            ?? '');
    $contrato_aquisicao_nome = trim($_POST['contrato_aquisicao_nome'] ?? '');
    $contrato_aquisicao_data = trim($_POST['contrato_aquisicao_data'] ?? '');
    $fatura_aquisicao_nome   = trim($_POST['fatura_aquisicao_nome']   ?? '');
    $fatura_aquisicao_data   = trim($_POST['fatura_aquisicao_data']   ?? '');
    $contrato_aquisicao_id   = trim($_POST['contrato_aquisicao_id']   ?? '');
    $fatura_aquisicao_id     = trim($_POST['fatura_aquisicao_id']     ?? '');

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
        try {
            $ligacao = new PDO(
                "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
                MYSQL_USERNAME,
                MYSQL_PASSWORD
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // UPDATE equipamento
            $stmt = $ligacao->prepare("UPDATE equipamentos SET data_aquisicao=:data_aquisicao, custo=:custo, tipo_entrada=:tipo_entrada WHERE id=:id");
            $stmt->execute([
                ':data_aquisicao' => $data_aquisicao,
                ':custo'          => $custo,
                ':tipo_entrada'   => $tipo_entrada,
                ':id'             => $idEquipamento,
            ]);

            // UPDATE contrato de aquisição
            $stmt = $ligacao->prepare("UPDATE documentacao SET nome_documento=:nome, data_documento=:data, data_validade=:validade WHERE id=:id");
            $stmt->execute([
                ':nome'    => $contrato_aquisicao_nome,
                ':data'    => $contrato_aquisicao_data,
                ':validade' => trim($_POST['contrato_aquisicao_validade'] ?? '') ?: null,
                ':id'      => $contrato_aquisicao_id,
            ]);

            // UPDATE fatura (só se compra)
            if ($tipo_entrada === 'compra' && !empty($fatura_aquisicao_id)) {
                $stmt = $ligacao->prepare("UPDATE documentacao SET nome_documento=:nome, data_documento=:data WHERE id=:id");
                $stmt->execute([
                    ':nome' => $fatura_aquisicao_nome,
                    ':data' => $fatura_aquisicao_data,
                    ':id'   => $fatura_aquisicao_id,
                ]);
            }

            $ligacao = null;
            header("Location: editar.php?id_equipamento={$idEquipamentoEncrypted}&sep=fornecedor");
            exit;
        } catch (PDOException $err) {
            $erro_sistema = "Erro ao atualizar aquisição: " . $err->getMessage();
        }
    }
}

// Separador 4 — Fornecedor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submeter_sep4'])) {

    $erros = [];
    $forn_ids            = $_POST['forn_id']            ?? [];
    $forn_fornecedor_ids = $_POST['forn_fornecedor_id'] ?? [];
    $forn_tipos          = $_POST['forn_tipo_relacao']  ?? [];
    $forn_moradas        = $_POST['forn_morada']        ?? [];
    $forn_pessoas        = $_POST['forn_pessoa_contacto'] ?? [];
    $forn_telefones      = $_POST['forn_telefone']      ?? [];
    $forn_obs            = $_POST['forn_observacoes']   ?? [];

    foreach ($forn_fornecedor_ids as $i => $forn_id) {
        if (empty($forn_id)) continue;
        if (empty(trim($forn_tipos[$i] ?? ''))) {
            $erros[] = "O tipo de relação do fornecedor é obrigatório.";
        }
        if (empty(trim($forn_moradas[$i] ?? ''))) {
            $erros[] = "A morada associada do fornecedor é obrigatória.";
        }
        if (empty(trim($forn_pessoas[$i] ?? ''))) {
            $erros[] = "A pessoa de contacto do fornecedor é obrigatória.";
        } else {
            $erros = array_merge($erros, validar_nome_pessoa(trim($forn_pessoas[$i]), 'A pessoa de contacto'));
        }
        if (empty(trim($forn_telefones[$i] ?? ''))) {
            $erros[] = "O telefone de contacto do fornecedor é obrigatório.";
        } elseif (!empty($forn_telefones[$i])) {
            $erros = array_merge($erros, validar_telefone(trim($forn_telefones[$i]), 'O telefone de contacto'));
        }
    }

    $pelo_menos_um = false;
    foreach ($forn_fornecedor_ids as $forn_id) {
        if (!empty($forn_id)) {
            $pelo_menos_um = true;
            break;
        }
    }
    if (!$pelo_menos_um) {
        $erros[] = "Tem de associar pelo menos um fornecedor.";
    }

    if (!empty($erros)) {
        $_SESSION['sep_ativo'] = 'fornecedor';
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

            foreach ($forn_fornecedor_ids as $i => $forn_id) {
                if (empty($forn_id)) continue;

                $id = trim($forn_ids[$i] ?? '');

                if (!empty($id)) {
                    $stmt = $ligacao->prepare("UPDATE equipamento_fornecedor SET fornecedor_id=:fid, tipo_relacao=:tipo, morada_associada=:morada, pessoa_contacto=:pessoa, telefone_contacto=:telefone, observacoes=:obs WHERE id=:id");
                    $stmt->execute([
                        ':fid'      => $forn_id,
                        ':tipo'     => trim($forn_tipos[$i] ?? ''),
                        ':morada'   => trim($forn_moradas[$i] ?? ''),
                        ':pessoa'   => trim($forn_pessoas[$i] ?? '') ?: null,
                        ':telefone' => trim($forn_telefones[$i] ?? '') ?: null,
                        ':obs'      => trim($forn_obs[$i] ?? '') ?: null,
                        ':id'       => $id,
                    ]);
                    $ids_mantidos[] = $id;
                } else {
                    $stmt = $ligacao->prepare("INSERT INTO equipamento_fornecedor (equipamento_id, fornecedor_id, tipo_relacao, morada_associada, pessoa_contacto, telefone_contacto, observacoes) VALUES (:eid, :fid, :tipo, :morada, :pessoa, :telefone, :obs)");
                    $stmt->execute([
                        ':eid'      => $idEquipamento,
                        ':fid'      => $forn_id,
                        ':tipo'     => trim($forn_tipos[$i] ?? ''),
                        ':morada'   => trim($forn_moradas[$i] ?? ''),
                        ':pessoa'   => trim($forn_pessoas[$i] ?? '') ?: null,
                        ':telefone' => trim($forn_telefones[$i] ?? '') ?: null,
                        ':obs'      => trim($forn_obs[$i] ?? '') ?: null,
                    ]);
                    $ids_mantidos[] = $ligacao->lastInsertId();
                }
            }

            if (!empty($ids_mantidos)) {
                $placeholders = implode(',', array_fill(0, count($ids_mantidos), '?'));
                $stmt = $ligacao->prepare("DELETE FROM equipamento_fornecedor WHERE equipamento_id = ? AND id NOT IN ($placeholders)");
                $stmt->execute(array_merge([$idEquipamento], $ids_mantidos));
            } else {
                $stmt = $ligacao->prepare("DELETE FROM equipamento_fornecedor WHERE equipamento_id = ?");
                $stmt->execute([$idEquipamento]);
            }

            $ligacao = null;
            header("Location: editar.php?id_equipamento={$idEquipamentoEncrypted}&sep=localizacao");
            exit;
        } catch (PDOException $err) {
            $erro_sistema = "Erro ao atualizar fornecedor: " . $err->getMessage();
        }
    }
}

// Separador 5 — Localização
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submeter_sep5'])) {

    $erros = [];
    $localizacao_id = trim($_POST['localizacao_id'] ?? '');

    $erros = array_merge($erros, validar_select($localizacao_id, 'A localização'));

    if (!empty($erros)) {
        $_SESSION['sep_ativo'] = 'localizacao';
    }

    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
                MYSQL_USERNAME,
                MYSQL_PASSWORD
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $ligacao->prepare("UPDATE equipamentos SET localizacao_id = :localizacao_id WHERE id = :id");
            $stmt->execute([
                ':localizacao_id' => $localizacao_id,
                ':id'             => $idEquipamento,
            ]);

            $ligacao = null;
            header("Location: editar.php?id_equipamento={$idEquipamentoEncrypted}&sep=garantia");
            exit;
        } catch (PDOException $err) {
            $erro_sistema = "Erro ao atualizar localização: " . $err->getMessage();
        }
    }
}

// Separador 6 — Garantia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submeter_sep6'])) {

    $erros = [];
    $garantia_id         = trim($_POST['garantia_id']         ?? '');
    $garantia_data_inicio = trim($_POST['garantia_data_inicio'] ?? '');
    $garantia_data_fim   = trim($_POST['garantia_data_fim']   ?? '');
    $garantia_entidade   = trim($_POST['garantia_entidade']   ?? '');
    $garantia_obs        = trim($_POST['garantia_observacoes'] ?? '');
    $cert_garantia_id    = trim($_POST['cert_garantia_id']    ?? '');
    $cert_garantia_nome  = trim($_POST['cert_garantia_nome']  ?? '');
    $cert_garantia_data  = trim($_POST['cert_garantia_data']  ?? '');
    $cert_garantia_val   = trim($_POST['cert_garantia_validade'] ?? '');

    $erros = array_merge($erros, validar_data_obrigatoria($garantia_data_inicio, 'A data de início da garantia'));
    if (empty($garantia_data_fim)) {
        $erros[] = "A data de fim da garantia é obrigatória.";
    } else {
        $erros = array_merge($erros, validar_data_anterior($garantia_data_fim, $garantia_data_inicio, 'A data de fim da garantia', 'a data de início'));
    }
    $erros = array_merge($erros, validar_select($garantia_entidade, 'A entidade responsável'));
    $erros = array_merge($erros, validar_texto_obrigatorio($cert_garantia_nome, 'O nome do certificado de garantia'));
    $erros = array_merge($erros, validar_data_obrigatoria($cert_garantia_data, 'A data do certificado de garantia'));
    $erros = array_merge($erros, validar_data_posterior($cert_garantia_data, $garantia_data_inicio, 'A data do documento do certificado', ' data de início da garantia'));

    if (!empty($cert_garantia_val)) {
        $erros = array_merge($erros, validar_data_anterior($cert_garantia_val, $cert_garantia_data, 'A data de validade do certificado', 'a data do documento'));
    }

    if (!empty($erros)) {
        $_SESSION['sep_ativo'] = 'garantia';
    }

    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
                MYSQL_USERNAME,
                MYSQL_PASSWORD
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (!empty($garantia_id)) {
                $stmt = $ligacao->prepare("UPDATE garantias SET data_inicio=:di, data_fim=:df, entidade_responsavel=:ent, observacoes=:obs WHERE id=:id");
                $stmt->execute([':di' => $garantia_data_inicio, ':df' => $garantia_data_fim, ':ent' => $garantia_entidade, ':obs' => $garantia_obs ?: null, ':id' => $garantia_id]);
            } else {
                $stmt = $ligacao->prepare("INSERT INTO garantias (equipamento_id, data_inicio, data_fim, entidade_responsavel, observacoes) VALUES (:eid, :di, :df, :ent, :obs)");
                $stmt->execute([':eid' => $idEquipamento, ':di' => $garantia_data_inicio, ':df' => $garantia_data_fim, ':ent' => $garantia_entidade, ':obs' => $garantia_obs ?: null]);
            }

            if (!empty($cert_garantia_id)) {
                $stmt = $ligacao->prepare("UPDATE documentacao SET nome_documento=:nome, data_documento=:data, data_validade=:val WHERE id=:id");
                $stmt->execute([':nome' => $cert_garantia_nome, ':data' => $cert_garantia_data, ':val' => $cert_garantia_val ?: null, ':id' => $cert_garantia_id]);
            }

            $ligacao = null;
            header("Location: editar.php?id_equipamento={$idEquipamentoEncrypted}&sep=contrato");
            exit;
        } catch (PDOException $err) {
            $erro_sistema = "Erro ao atualizar garantia: " . $err->getMessage();
        }
    }
}

// Separador 7 — Contrato de Manutenção
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submeter_sep7'])) {

    $erros = [];
    $tem_contrato       = trim($_POST['tem_contrato']          ?? 'nao');
    $contrato_id        = trim($_POST['contrato_id']           ?? '');
    $contrato_tipo      = trim($_POST['contrato_tipo']         ?? '');
    $contrato_period    = trim($_POST['contrato_periodicidade'] ?? '');
    $contrato_inicio    = trim($_POST['contrato_data_inicio']  ?? '');
    $contrato_fim       = trim($_POST['contrato_data_fim']     ?? '');
    $contrato_entidade  = trim($_POST['contrato_entidade']     ?? '');
    $contrato_obs       = trim($_POST['contrato_observacoes']  ?? '');
    $doc_contrato_id    = trim($_POST['doc_contrato_id']       ?? '');
    $doc_contrato_nome  = trim($_POST['doc_contrato_nome']     ?? '');
    $doc_contrato_data  = trim($_POST['doc_contrato_data']     ?? '');
    $doc_contrato_val   = trim($_POST['doc_contrato_validade'] ?? '');

    if ($tem_contrato === 'sim') {
        $erros = array_merge($erros, validar_select($contrato_tipo, 'O tipo de contrato'));
        $erros = array_merge($erros, validar_select($contrato_period, 'A periodicidade'));
        $erros = array_merge($erros, validar_data_obrigatoria_futura($contrato_inicio, 'A data de início do contrato'));
        if (empty($contrato_fim)) {
            $erros[] = "A data de fim do contrato é obrigatória.";
        } else {
            $erros = array_merge($erros, validar_data_anterior($contrato_fim, $contrato_inicio, 'A data de fim do contrato', 'a data de início'));
        }
        $erros = array_merge($erros, validar_select($contrato_entidade, 'A entidade responsável'));
        $erros = array_merge($erros, validar_texto_obrigatorio($doc_contrato_nome, 'O nome do documento do contrato'));
        $erros = array_merge($erros, validar_data_obrigatoria($doc_contrato_data, 'A data do documento do contrato'));
        $erros = array_merge($erros, validar_data_posterior($doc_contrato_data, $contrato_inicio, 'A data do documento do contrato', 'a data de início do contrato'));
    }

    if (!empty($erros)) {
        $_SESSION['sep_ativo'] = 'contrato';
    }

    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
                MYSQL_USERNAME,
                MYSQL_PASSWORD
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($tem_contrato === 'sim') {
                if (!empty($contrato_id)) {
                    $stmt = $ligacao->prepare("UPDATE contratos SET tipo_contrato=:tipo, periodicidade=:period, data_inicio=:di, data_fim=:df, entidade_responsavel=:ent, observacoes=:obs WHERE id=:id");
                    $stmt->execute([':tipo' => $contrato_tipo, ':period' => $contrato_period, ':di' => $contrato_inicio, ':df' => $contrato_fim, ':ent' => $contrato_entidade, ':obs' => $contrato_obs ?: null, ':id' => $contrato_id]);
                } else {
                    $stmt = $ligacao->prepare("INSERT INTO contratos (equipamento_id, tipo_contrato, periodicidade, data_inicio, data_fim, entidade_responsavel, observacoes) VALUES (:eid, :tipo, :period, :di, :df, :ent, :obs)");
                    $stmt->execute([':eid' => $idEquipamento, ':tipo' => $contrato_tipo, ':period' => $contrato_period, ':di' => $contrato_inicio, ':df' => $contrato_fim, ':ent' => $contrato_entidade, ':obs' => $contrato_obs ?: null]);
                    $contrato_id = $ligacao->lastInsertId();
                }

                if (!empty($doc_contrato_id)) {
                    $stmt = $ligacao->prepare("UPDATE documentacao SET nome_documento=:nome, data_documento=:data, data_validade=:val WHERE id=:id");
                    $stmt->execute([':nome' => $doc_contrato_nome, ':data' => $doc_contrato_data, ':val' => $doc_contrato_val ?: null, ':id' => $doc_contrato_id]);
                } else {
                    $stmt = $ligacao->prepare("INSERT INTO documentacao (equipamento_id, contexto, tipo_documento_id, nome_documento, data_documento, data_validade, ficheiro) VALUES (:eid, 'contrato', 4, :nome, :data, :val, 'contrato/contrato_manutencao.pdf')");
                    $stmt->execute([':eid' => $idEquipamento, ':nome' => $doc_contrato_nome, ':data' => $doc_contrato_data, ':val' => $doc_contrato_val ?: null]);
                }
            } else {
                // Se não tem contrato, apaga se existia
                if (!empty($contrato_id)) {
                    $stmt = $ligacao->prepare("DELETE FROM contratos WHERE id = :id");
                    $stmt->execute([':id' => $contrato_id]);
                    if (!empty($doc_contrato_id)) {
                        $stmt = $ligacao->prepare("DELETE FROM documentacao WHERE id = :id");
                        $stmt->execute([':id' => $doc_contrato_id]);
                    }
                }
            }

            $ligacao = null;
            header("Location: editar.php?id_equipamento={$idEquipamentoEncrypted}&sep=documentos");
            exit;
        } catch (PDOException $err) {
            $erro_sistema = "Erro ao atualizar contrato: " . $err->getMessage();
        }
    }
}

// Separador 8 — Documentação Associada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submeter_sep8'])) {

    $erros = [];
    $doc_ids     = $_POST['doc_id']      ?? [];
    $doc_tipos   = $_POST['doc_tipo_id'] ?? [];
    $doc_nomes   = $_POST['doc_nome']    ?? [];
    $doc_datas   = $_POST['doc_data']    ?? [];
    $doc_valids  = $_POST['doc_validade'] ?? [];

    foreach ($doc_nomes as $i => $nome) {
        $nome = trim($nome);
        if (empty($nome)) continue;
        $erros = array_merge($erros, validar_data_obrigatoria($doc_datas[$i] ?? '', 'A data do documento'));
    }

    if (!empty($erros)) {
        $_SESSION['sep_ativo'] = 'documentos';
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

            foreach ($doc_nomes as $i => $nome) {
                $nome = trim($nome);
                if (empty($nome)) continue;

                $id = trim($doc_ids[$i] ?? '');

                if (!empty($id)) {
                    $stmt = $ligacao->prepare("UPDATE documentacao SET tipo_documento_id=:tipo, nome_documento=:nome, data_documento=:data, data_validade=:val WHERE id=:id");
                    $stmt->execute([
                        ':tipo' => $doc_tipos[$i],
                        ':nome' => $nome,
                        ':data' => $doc_datas[$i] ?: null,
                        ':val'  => trim($doc_valids[$i] ?? '') ?: null,
                        ':id'   => $id,
                    ]);
                    $ids_mantidos[] = $id;
                } else {
                    $stmt = $ligacao->prepare("INSERT INTO documentacao (equipamento_id, contexto, tipo_documento_id, nome_documento, data_documento, data_validade, ficheiro) VALUES (:eid, 'geral', :tipo, :nome, :data, :val, '')");
                    $stmt->execute([
                        ':eid'  => $idEquipamento,
                        ':tipo' => $doc_tipos[$i],
                        ':nome' => $nome,
                        ':data' => $doc_datas[$i] ?: null,
                        ':val'  => trim($doc_valids[$i] ?? '') ?: null,
                    ]);
                    $ids_mantidos[] = $ligacao->lastInsertId();
                }
            }

            if (!empty($ids_mantidos)) {
                $placeholders = implode(',', array_fill(0, count($ids_mantidos), '?'));
                $stmt = $ligacao->prepare("DELETE FROM documentacao WHERE equipamento_id = ? AND contexto = 'geral' AND id NOT IN ($placeholders)");
                $stmt->execute(array_merge([$idEquipamento], $ids_mantidos));
            } else {
                $stmt = $ligacao->prepare("DELETE FROM documentacao WHERE equipamento_id = ? AND contexto = 'geral'");
                $stmt->execute([$idEquipamento]);
            }

            $ligacao = null;
            header("Location: lista.php?sucesso=1");
            exit;
        } catch (PDOException $err) {
            $erro_sistema = "Erro ao atualizar documentação: " . $err->getMessage();
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
        header('Location: ' . BASE_URL . '/private/views/equipamentos/lista.php');
        exit;
    }

    $stmt = $ligacao->prepare("SELECT * FROM componentes_consumiveis WHERE equipamento_id = :id ORDER BY id");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $componentes = $stmt->fetchAll(PDO::FETCH_OBJ);

    // Carregar documentos da aquisição
    $stmt = $ligacao->prepare("SELECT * FROM documentacao WHERE equipamento_id = :id AND contexto = 'aquisicao' ORDER BY tipo_documento_id");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $docs_aquisicao = $stmt->fetchAll(PDO::FETCH_OBJ);

    $contrato_aquisicao = null;
    $fatura_aquisicao   = null;

    foreach ($docs_aquisicao as $doc) {
        if ($doc->tipo_documento_id == 6) {
            $contrato_aquisicao = $doc;
        } elseif ($doc->tipo_documento_id == 5) {
            $fatura_aquisicao = $doc;
        }
    }

    // Carregar fornecedores para o select
    $stmt = $ligacao->prepare("SELECT id, codigo, nome FROM fornecedores WHERE fornecedor_ativo = 1 ORDER BY codigo");
    $stmt->execute();
    $fornecedores_lista = $stmt->fetchAll(PDO::FETCH_OBJ);

    // Carregar fornecedores associados ao equipamento
    $stmt = $ligacao->prepare("SELECT * FROM equipamento_fornecedor WHERE equipamento_id = :id ORDER BY id");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $fornecedores_associados = $stmt->fetchAll(PDO::FETCH_OBJ);

    // Carregar localizações para o select
    $stmt = $ligacao->prepare("SELECT id, codigo, edificio, piso, sala FROM localizacoes WHERE localizacao_ativa = 1 ORDER BY codigo");
    $stmt->execute();
    $localizacoes_lista = $stmt->fetchAll(PDO::FETCH_OBJ);

    // Carregar garantia
    $stmt = $ligacao->prepare("SELECT * FROM garantias WHERE equipamento_id = :id");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $garantia = $stmt->fetch(PDO::FETCH_OBJ);

    // Carregar certificado de garantia
    $stmt = $ligacao->prepare("SELECT * FROM documentacao WHERE equipamento_id = :id AND contexto = 'garantia' ORDER BY id LIMIT 1");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $cert_garantia = $stmt->fetch(PDO::FETCH_OBJ);

    // Carregar contrato de manutenção
    $stmt = $ligacao->prepare("SELECT * FROM contratos WHERE equipamento_id = :id LIMIT 1");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $contrato = $stmt->fetch(PDO::FETCH_OBJ);

    // Carregar documento do contrato
    $stmt = $ligacao->prepare("SELECT * FROM documentacao WHERE equipamento_id = :id AND contexto = 'contrato' ORDER BY id LIMIT 1");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $doc_contrato = $stmt->fetch(PDO::FETCH_OBJ);

    // Carregar documentos gerais
    $stmt = $ligacao->prepare("SELECT * FROM documentacao WHERE equipamento_id = :id AND contexto = 'geral' ORDER BY id");
    $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
    $stmt->execute();
    $docs_geral = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $err) {
    $erro_sistema = "Erro na ligação à base de dados.";
    $equipamento = null;
    $componentes = [];
    $contrato_aquisicao = null;
    $fatura_aquisicao   = null;
    $fornecedores_lista = [];
    $fornecedores_associados = [];
    $localizacoes_lista = [];
    $garantia = null;
    $cert_garantia = null;
    $contrato = null;
    $doc_contrato = null;
    $docs_geral = [];
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

                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <h2 class="mb-0" style="color: #1a826d;">
                        <i class="fa-solid fa-pen-to-square me-2"></i> Editar Equipamento
                    </h2>

                    <div class="d-flex align-items-center gap-2 px-3 py-2" style="background-color: #d9efec; border-radius: 999px;">
                        <i class="fa-solid fa-stethoscope" style="color: #1a826d; font-size: 1rem;"></i>
                        <span style="font-size: 0.95rem; font-weight: 700; color: #0d4d40;">
                            <?= htmlspecialchars($equipamento->codigo) ?> — <?= htmlspecialchars($equipamento->designacao) ?>
                        </span>
                    </div>
                </div>

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

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Data de Aquisição *</label>
                                    <input type="text" class="form-control" id="data_aquisicao" name="data_aquisicao"
                                        value="<?= htmlspecialchars($equipamento->data_aquisicao ?? '') ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Custo de Aquisição (€) *</label>
                                    <input type="number" class="form-control" name="custo" placeholder="Ex: 3500" min="0" step="0.01"
                                        value="<?= htmlspecialchars($equipamento->custo ?? '') ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tipo de Entrada *</label>
                                    <select class="form-select" name="tipo_entrada" id="tipoEntrada">
                                        <option value="">Selecione...</option>
                                        <option value="compra" <?= $equipamento->tipo_entrada == 'compra' ? 'selected' : '' ?>>Compra</option>
                                        <option value="doacao" <?= $equipamento->tipo_entrada == 'doacao' ? 'selected' : '' ?>>Doação</option>
                                        <option value="aluguer" <?= $equipamento->tipo_entrada == 'aluguer' ? 'selected' : '' ?>>Aluguer</option>
                                        <option value="emprestimo" <?= $equipamento->tipo_entrada == 'emprestimo' ? 'selected' : '' ?>>Empréstimo</option>
                                    </select>
                                </div>

                            </div>

                            <hr class="my-4">

                            <div class="accordion" id="accordionAquisicao">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseDocAquisicao" aria-expanded="true">
                                            Documentos da Aquisição
                                        </button>
                                    </h2>
                                    <div id="collapseDocAquisicao" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            <div class="row">

                                                <!-- Contrato de Aquisição -->
                                                <div class="col-md-6">
                                                    <div class="border rounded p-3 mb-3">
                                                        <h5 class="mb-3" style="color:#1a826d;">Contrato de Aquisição</h5>
                                                        <input type="hidden" name="contrato_aquisicao_id" value="<?= $contrato_aquisicao->id ?? '' ?>">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Tipo de Documento *</label>
                                                                <select class="form-select">
                                                                    <option>Contrato de Aquisição</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Nome do Documento *</label>
                                                                <input type="text" class="form-control" name="contrato_aquisicao_nome"
                                                                    placeholder="Ex: Contrato de Compra"
                                                                    value="<?= htmlspecialchars($contrato_aquisicao->nome_documento ?? '') ?>">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Data do Documento *</label>
                                                                <input type="text" class="form-control" id="contrato_aquisicao_data" name="contrato_aquisicao_data"
                                                                    value="<?= htmlspecialchars($contrato_aquisicao->data_documento ?? '') ?>">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Data de Validade</label>
                                                                <input type="text" class="form-control" id="contrato_aquisicao_validade" name="contrato_aquisicao_validade"
                                                                    value="<?= htmlspecialchars($contrato_aquisicao->data_validade ?? '') ?>">
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Ficheiro (PDF)</label>
                                                            <?php if (!empty($contrato_aquisicao->ficheiro)): ?>
                                                                <div class="mb-2">
                                                                    <span class="text-muted small">Ficheiro atual: <?= htmlspecialchars($contrato_aquisicao->ficheiro) ?></span>
                                                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                                                                        onclick="verPDF('<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($contrato_aquisicao->ficheiro) ?>')">
                                                                        <i class="fa-solid fa-eye me-1"></i> Ver PDF
                                                                    </button>
                                                                </div>
                                                            <?php endif; ?>
                                                            <input type="file" class="form-control" name="contrato_aquisicao_ficheiro" accept="application/pdf">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Fatura da Aquisição -->
                                                <div class="col-md-6" id="blocoFatura">
                                                    <div class="border rounded p-3 mb-3">
                                                        <h5 class="mb-3" style="color:#1a826d;">Fatura da Aquisição</h5>
                                                        <input type="hidden" name="fatura_aquisicao_id" value="<?= $fatura_aquisicao->id ?? '' ?>">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Tipo de Documento *</label>
                                                                <select class="form-select">
                                                                    <option>Fatura de Aquisição</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Nome do Documento *</label>
                                                                <input type="text" class="form-control" name="fatura_aquisicao_nome"
                                                                    placeholder="Ex: Fatura de Compra"
                                                                    value="<?= htmlspecialchars($fatura_aquisicao->nome_documento ?? '') ?>">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Data do Documento *</label>
                                                                <input type="text" class="form-control" id="fatura_aquisicao_data" name="fatura_aquisicao_data"
                                                                    value="<?= htmlspecialchars($fatura_aquisicao->data_documento ?? '') ?>">
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Ficheiro (PDF)</label>
                                                            <?php if (!empty($fatura_aquisicao->ficheiro)): ?>
                                                                <div class="mb-2">
                                                                    <span class="text-muted small">Ficheiro atual: <?= htmlspecialchars($fatura_aquisicao->ficheiro) ?></span>
                                                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                                                                        onclick="verPDF('<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($fatura_aquisicao->ficheiro) ?>')">
                                                                        <i class="fa-solid fa-eye me-1"></i> Ver PDF
                                                                    </button>
                                                                </div>
                                                            <?php endif; ?>
                                                            <input type="file" class="form-control" name="fatura_aquisicao_ficheiro" accept="application/pdf">
                                                        </div>
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
                                $fornecedores_editar = !empty($fornecedores_associados) ? $fornecedores_associados : [(object)['id' => '', 'fornecedor_id' => '', 'tipo_relacao' => '', 'morada_associada' => '', 'pessoa_contacto' => '', 'telefone_contacto' => '', 'observacoes' => '']];
                                foreach ($fornecedores_editar as $forn):
                                ?>
                                    <div class="fornecedor-bloco border rounded p-3 mb-3" style="border-color:#86b0aa;">
                                        <div class="row g-3">

                                            <input type="hidden" name="forn_id[]" value="<?= $forn->id ?? '' ?>">

                                            <div class="col-md-4">
                                                <label class="form-label">Fornecedor *</label>
                                                <select class="form-select" name="forn_fornecedor_id[]">
                                                    <option value="">Selecione...</option>
                                                    <?php foreach ($fornecedores_lista as $f): ?>
                                                        <option value="<?= $f->id ?>" <?= ($forn->fornecedor_id ?? '') == $f->id ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($f->codigo) ?> – <?= htmlspecialchars($f->nome) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Tipo de Relação *</label>
                                                <select class="form-select" name="forn_tipo_relacao[]">
                                                    <option value="">Selecione...</option>
                                                    <option value="Fabricante" <?= ($forn->tipo_relacao ?? '') == 'Fabricante' ? 'selected' : '' ?>>Fabricante</option>
                                                    <option value="Distribuidor / Comercial" <?= ($forn->tipo_relacao ?? '') == 'Distribuidor / Comercial' ? 'selected' : '' ?>>Distribuidor / Comercial</option>
                                                    <option value="Assistência Técnica" <?= ($forn->tipo_relacao ?? '') == 'Assistência Técnica' ? 'selected' : '' ?>>Assistência Técnica</option>
                                                    <option value="Consumíveis / Acessórios" <?= ($forn->tipo_relacao ?? '') == 'Consumíveis / Acessórios' ? 'selected' : '' ?>>Consumíveis / Acessórios</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Morada Associada *</label>
                                                <input type="text" class="form-control" name="forn_morada[]"
                                                    placeholder="Ex: Armazém Norte – Braga"
                                                    value="<?= htmlspecialchars($forn->morada_associada ?? '') ?>">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Pessoa de Contacto *</label>
                                                <input type="text" class="form-control" name="forn_pessoa_contacto[]"
                                                    placeholder="Nome da pessoa"
                                                    value="<?= htmlspecialchars($forn->pessoa_contacto ?? '') ?>">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Telefone de Contacto *</label>
                                                <input type="text" class="form-control" name="forn_telefone[]"
                                                    placeholder="912345678"
                                                    value="<?= htmlspecialchars($forn->telefone_contacto ?? '') ?>">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Observações</label>
                                                <textarea class="form-control" name="forn_observacoes[]" rows="1"><?= htmlspecialchars($forn->observacoes ?? '') ?></textarea>
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

                            <button type="button" class="btn btn-success mb-4" id="adicionarFornecedor">
                                + Adicionar Fornecedor
                            </button>

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
                                        <?php foreach ($localizacoes_lista as $loc): ?>
                                            <option value="<?= $loc->id ?>" <?= $equipamento->localizacao_id == $loc->id ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($loc->codigo) ?> – <?= htmlspecialchars($loc->edificio) ?> / <?= htmlspecialchars($loc->piso) ?> / <?= htmlspecialchars($loc->sala) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

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

                            <input type="hidden" name="garantia_id" value="<?= $garantia->id ?? '' ?>">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Data de Início da Garantia *</label>
                                    <input type="date" class="form-control" name="garantia_data_inicio"
                                        value="<?= htmlspecialchars($garantia->data_inicio ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Data de Fim da Garantia *</label>
                                    <input type="date" class="form-control" name="garantia_data_fim"
                                        value="<?= htmlspecialchars($garantia->data_fim ?? '') ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Entidade Responsável *</label>
                                    <select class="form-select" name="garantia_entidade">
                                        <option value="">Selecione...</option>
                                        <option value="Fabricante" <?= ($garantia->entidade_responsavel ?? '') == 'Fabricante' ? 'selected' : '' ?>>Fabricante</option>
                                        <option value="Fornecedor Comercial" <?= ($garantia->entidade_responsavel ?? '') == 'Fornecedor Comercial' ? 'selected' : '' ?>>Fornecedor Comercial</option>
                                        <option value="Distribuidor Autorizado" <?= ($garantia->entidade_responsavel ?? '') == 'Distribuidor Autorizado' ? 'selected' : '' ?>>Distribuidor Autorizado</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Observações</label>
                                    <textarea class="form-control" name="garantia_observacoes" rows="3"><?= htmlspecialchars($garantia->observacoes ?? '') ?></textarea>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="accordion" id="accordionGarantia">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseCertGarantia" aria-expanded="true">
                                            Certificado de Garantia
                                        </button>
                                    </h2>
                                    <div id="collapseCertGarantia" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            <div class="border rounded p-3 mb-3">
                                                <h5 class="mb-3" style="color:#1a826d;">Certificado de Garantia</h5>
                                                <input type="hidden" name="cert_garantia_id" value="<?= $cert_garantia->id ?? '' ?>">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Tipo de Documento *</label>
                                                        <select class="form-select">
                                                            <option>Certificado de Garantia</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Nome do Documento *</label>
                                                        <input type="text" class="form-control" name="cert_garantia_nome"
                                                            placeholder="Ex: Certificado de Garantia"
                                                            value="<?= htmlspecialchars($cert_garantia->nome_documento ?? '') ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Data do Documento *</label>
                                                        <input type="date" class="form-control" name="cert_garantia_data"
                                                            value="<?= htmlspecialchars($cert_garantia->data_documento ?? '') ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Data de Validade</label>
                                                        <input type="date" class="form-control" name="cert_garantia_validade"
                                                            value="<?= htmlspecialchars($cert_garantia->data_validade ?? '') ?>">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Ficheiro (PDF)</label>
                                                    <?php if (!empty($cert_garantia->ficheiro)): ?>
                                                        <div class="mb-2">
                                                            <span class="text-muted small">Ficheiro atual: <?= htmlspecialchars($cert_garantia->ficheiro) ?></span>
                                                            <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                                                                onclick="verPDF('<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($cert_garantia->ficheiro) ?>')">
                                                                <i class="fa-solid fa-eye me-1"></i> Ver PDF
                                                            </button>
                                                        </div>
                                                    <?php endif; ?>
                                                    <input type="file" class="form-control" name="cert_garantia_ficheiro" accept="application/pdf">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

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

                            <input type="hidden" name="contrato_id" value="<?= $contrato->id ?? '' ?>">

                            <div class="mb-3">
                                <label class="form-label">Existe Contrato de Manutenção? *</label>
                                <select class="form-select" name="tem_contrato" id="temContrato" onchange="toggleContrato(this.value)">
                                    <option value="nao" <?= empty($contrato) ? 'selected' : '' ?>>Não</option>
                                    <option value="sim" <?= !empty($contrato) ? 'selected' : '' ?>>Sim</option>
                                </select>
                            </div>

                            <div id="camposContrato" style="display: <?= !empty($contrato) ? 'block' : 'none' ?>;">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tipo de Contrato *</label>
                                        <select class="form-select" name="contrato_tipo">
                                            <option value="">Selecione...</option>
                                            <option value="Manutenção Preventiva" <?= ($contrato->tipo_contrato ?? '') == 'Manutenção Preventiva' ? 'selected' : '' ?>>Manutenção Preventiva</option>
                                            <option value="Manutenção Corretiva" <?= ($contrato->tipo_contrato ?? '') == 'Manutenção Corretiva' ? 'selected' : '' ?>>Manutenção Corretiva</option>
                                            <option value="Full-Service" <?= ($contrato->tipo_contrato ?? '') == 'Full-Service' ? 'selected' : '' ?>>Full-Service</option>
                                            <option value="Outsourcing" <?= ($contrato->tipo_contrato ?? '') == 'Outsourcing' ? 'selected' : '' ?>>Outsourcing</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Periodicidade *</label>
                                        <select class="form-select" name="contrato_periodicidade">
                                            <option value="">Selecione...</option>
                                            <option value="Mensal" <?= ($contrato->periodicidade ?? '') == 'Mensal' ? 'selected' : '' ?>>Mensal</option>
                                            <option value="Trimestral" <?= ($contrato->periodicidade ?? '') == 'Trimestral' ? 'selected' : '' ?>>Trimestral</option>
                                            <option value="Semestral" <?= ($contrato->periodicidade ?? '') == 'Semestral' ? 'selected' : '' ?>>Semestral</option>
                                            <option value="Anual" <?= ($contrato->periodicidade ?? '') == 'Anual' ? 'selected' : '' ?>>Anual</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Data de Início *</label>
                                        <input type="date" class="form-control" name="contrato_data_inicio"
                                            value="<?= htmlspecialchars($contrato->data_inicio ?? '') ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Data de Fim *</label>
                                        <input type="date" class="form-control" name="contrato_data_fim"
                                            value="<?= htmlspecialchars($contrato->data_fim ?? '') ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Entidade Responsável *</label>
                                        <select class="form-select" name="contrato_entidade">
                                            <option value="">Selecione...</option>
                                            <option value="Empresa de assistência técnica" <?= ($contrato->entidade_responsavel ?? '') == 'Empresa de assistência técnica' ? 'selected' : '' ?>>Empresa de assistência técnica</option>
                                            <option value="Fabricante" <?= ($contrato->entidade_responsavel ?? '') == 'Fabricante' ? 'selected' : '' ?>>Fabricante</option>
                                            <option value="Distribuidor Autorizado" <?= ($contrato->entidade_responsavel ?? '') == 'Distribuidor Autorizado' ? 'selected' : '' ?>>Distribuidor Autorizado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Observações</label>
                                        <textarea class="form-control" name="contrato_observacoes" rows="3"><?= htmlspecialchars($contrato->observacoes ?? '') ?></textarea>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="accordion" id="accordionContratoManutencao">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseContratoDoc" aria-expanded="true">
                                                Documento do Contrato de Manutenção
                                            </button>
                                        </h2>
                                        <div id="collapseContratoDoc" class="accordion-collapse collapse show">
                                            <div class="accordion-body">
                                                <div class="border rounded p-3 mb-3">
                                                    <h5 class="mb-3" style="color:#1a826d;">Contrato de Manutenção</h5>
                                                    <input type="hidden" name="doc_contrato_id" value="<?= $doc_contrato->id ?? '' ?>">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Tipo de Documento *</label>
                                                            <select class="form-select">
                                                                <option>Contrato de Manutenção</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Nome do Documento *</label>
                                                            <input type="text" class="form-control" name="doc_contrato_nome"
                                                                placeholder="Ex: Contrato de Manutenção 2024-2025"
                                                                value="<?= htmlspecialchars($doc_contrato->nome_documento ?? '') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Data do Documento *</label>
                                                            <input type="date" class="form-control" name="doc_contrato_data"
                                                                value="<?= htmlspecialchars($doc_contrato->data_documento ?? '') ?>">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Data de Validade</label>
                                                            <input type="date" class="form-control" name="doc_contrato_validade"
                                                                value="<?= htmlspecialchars($doc_contrato->data_validade ?? '') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Ficheiro (PDF)</label>
                                                        <?php if (!empty($doc_contrato->ficheiro)): ?>
                                                            <div class="mb-2">
                                                                <span class="text-muted small">Ficheiro atual: <?= htmlspecialchars($doc_contrato->ficheiro) ?></span>
                                                                <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                                                                    onclick="verPDF('<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($doc_contrato->ficheiro) ?>')">
                                                                    <i class="fa-solid fa-eye me-1"></i> Ver PDF
                                                                </button>
                                                            </div>
                                                        <?php endif; ?>
                                                        <input type="file" class="form-control" name="doc_contrato_ficheiro" accept="application/pdf">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

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

                                <?php
                                $docs_editar = !empty($docs_geral) ? $docs_geral : [(object)['id' => '', 'tipo_documento_id' => '', 'nome_documento' => '', 'data_documento' => '', 'data_validade' => '', 'ficheiro' => '']];
                                foreach ($docs_editar as $doc):
                                ?>
                                    <div class="documento-bloco border rounded p-3 mb-3" style="border-color:#86b0aa;">

                                        <input type="hidden" name="doc_id[]" value="<?= $doc->id ?? '' ?>">

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tipo de Documento *</label>
                                                <select class="form-select" name="doc_tipo_id[]">
                                                    <option value="1" <?= ($doc->tipo_documento_id ?? '') == 1 ? 'selected' : '' ?>>Manual Utilizador</option>
                                                    <option value="2" <?= ($doc->tipo_documento_id ?? '') == 2 ? 'selected' : '' ?>>Manual de Serviço</option>
                                                    <option value="3" <?= ($doc->tipo_documento_id ?? '') == 3 ? 'selected' : '' ?>>Ficha Técnica</option>
                                                    <option value="7" <?= ($doc->tipo_documento_id ?? '') == 7 ? 'selected' : '' ?>>Declaração de Conformidade</option>
                                                    <option value="9" <?= ($doc->tipo_documento_id ?? '') == 9 ? 'selected' : '' ?>>Outro</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nome do Documento *</label>
                                                <input type="text" class="form-control" name="doc_nome[]"
                                                    placeholder="Ex: Manual do Utilizador"
                                                    value="<?= htmlspecialchars($doc->nome_documento ?? '') ?>">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Data do Documento *</label>
                                                <input type="date" class="form-control" name="doc_data[]"
                                                    value="<?= htmlspecialchars($doc->data_documento ?? '') ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Data de Validade</label>
                                                <input type="date" class="form-control" name="doc_validade[]"
                                                    value="<?= htmlspecialchars($doc->data_validade ?? '') ?>">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Ficheiro (PDF)</label>
                                            <?php if (!empty($doc->ficheiro)): ?>
                                                <div class="mb-2">
                                                    <span class="text-muted small">Ficheiro atual: <?= htmlspecialchars($doc->ficheiro) ?></span>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                                                        onclick="verPDF('<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($doc->ficheiro) ?>')">
                                                        <i class="fa-solid fa-eye me-1"></i> Ver PDF
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control" name="doc_ficheiro[]" accept="application/pdf">
                                        </div>

                                        <div class="text-end">
                                            <button type="button" class="btn btn-danger btn-sm remover-documento">
                                                Remover Documento
                                            </button>
                                        </div>

                                    </div>
                                <?php endforeach; ?>

                            </div>

                            <button type="button" class="btn btn-success mb-3" id="adicionarDocumento">
                                + Adicionar Documento
                            </button>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary" onclick="mostrarSeparador('contrato')">
                                    ← Anterior
                                </button>
                                <button type="submit" name="submeter_sep8" class="btn" style="background-color:#1a826d; color:white;">
                                    Guardar ✔
                                </button>
                            </div>

                        </div>

                    </div>
                </form>


        </main>



    </div>
</div>

<script>
    // Separador 3 — Aquisição
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

    // Separador 6 — Garantia
    flatpickr("[name='garantia_data_inicio']", {
        dateFormat: "Y-m-d"
    });
    flatpickr("[name='garantia_data_fim']", {
        dateFormat: "Y-m-d"
    });
    flatpickr("[name='cert_garantia_data']", {
        dateFormat: "Y-m-d"
    });
    flatpickr("[name='cert_garantia_validade']", {
        dateFormat: "Y-m-d"
    });

    // Separador 7 — Contrato
    flatpickr("[name='contrato_data_inicio']", {
        dateFormat: "Y-m-d"
    });
    flatpickr("[name='contrato_data_fim']", {
        dateFormat: "Y-m-d"
    });
    flatpickr("[name='doc_contrato_data']", {
        dateFormat: "Y-m-d"
    });
    flatpickr("[name='doc_contrato_validade']", {
        dateFormat: "Y-m-d"
    });

    // Separador 8 — Documentação
    flatpickr("[name='doc_data[]']", {
        dateFormat: "Y-m-d"
    });
    flatpickr("[name='doc_validade[]']", {
        dateFormat: "Y-m-d"
    });
</script>

<?php include '../../includes/footer.php'; ?>