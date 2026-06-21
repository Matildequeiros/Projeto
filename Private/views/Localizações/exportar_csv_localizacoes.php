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

    $resultados = $ligacao->query("SELECT l.codigo, l.edificio, l.piso, l.sala, s.nome AS servico, l.observacoes
        FROM localizacoes l
        LEFT JOIN servicos s ON l.servico_id = s.id
        WHERE l.localizacao_ativa = 1
    ")->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    die("Erro ao exportar: " . $e->getMessage());
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=localizacoes_' . date('Y-m-d') . '.csv');

$output = fopen('php://output', 'w');
fwrite($output, "\xEF\xBB\xBF");

fputcsv($output, ['Código', 'Edifício', 'Piso', 'Sala', 'Serviço', 'Observações']);

foreach ($resultados as $l) {
    fputcsv($output, [$l->codigo, $l->edificio, $l->piso, $l->sala, $l->servico, $l->observacoes]);
}

fclose($output);
exit;