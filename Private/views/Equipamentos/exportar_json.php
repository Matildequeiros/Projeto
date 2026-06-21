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
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao exportar: " . $e->getMessage());
}

// Cabeçalhos HTTP para forçar o download como ficheiro JSON
header('Content-Type: application/json; charset=utf-8');
header('Content-Disposition: attachment; filename=equipamentos_' . date('Y-m-d') . '.json');

echo json_encode($resultados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
exit;