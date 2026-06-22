<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $resultados = $ligacao->query("SELECT e.codigo, e.designacao, e.marca, e.modelo, e.numero_serie,
        e.categoria, e.estado, e.criticidade, l.edificio, l.piso, l.sala, s.nome AS servico,
        e.data_aquisicao, e.custo
        FROM equipamentos e
        LEFT JOIN localizacoes l ON e.localizacao_id = l.id
        LEFT JOIN servicos s ON l.servico_id = s.id
        WHERE e.equipamento_ativo = 1
    ")->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    die("Erro ao exportar: " . $e->getMessage());
}

// Cabeçalhos HTTP para forçar o download como ficheiro CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=equipamentos_' . date('Y-m-d') . '.csv');

$output = fopen('php://output', 'w');

// Marcador BOM para o Excel reconhecer corretamente os acentos (UTF-8)
fwrite($output, "\xEF\xBB\xBF");

// Cabeçalho das colunas
fputcsv($output, [
    'Código', 'Designação', 'Marca', 'Modelo', 'Número de Série',
    'Categoria', 'Estado', 'Criticidade', 'Edifício', 'Piso', 'Sala', 'Serviço',
    'Data Aquisição', 'Custo (€)'
], ';');

// Uma linha por equipamento
foreach ($resultados as $eq) {
    fputcsv($output, [
        $eq->codigo,
        $eq->designacao,
        $eq->marca,
        $eq->modelo,
        $eq->numero_serie,
        $eq->categoria,
        $eq->estado,
        $eq->criticidade,
        $eq->edificio,
        $eq->piso,
        $eq->sala,
        $eq->servico,
        $eq->data_aquisicao,
        $eq->custo
    ], ';');
}

fclose($output);
exit;