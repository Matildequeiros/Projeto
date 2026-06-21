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

    $resultados = $ligacao->query("SELECT codigo, nome, nif, telefone, email, morada, website,
        pessoa_contacto, telefone_contacto, tipo_fornecedor, observacoes
        FROM fornecedores
        WHERE fornecedor_ativo = 1
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao exportar: " . $e->getMessage());
}

header('Content-Type: application/json; charset=utf-8');
header('Content-Disposition: attachment; filename=fornecedores_' . date('Y-m-d') . '.json');

echo json_encode($resultados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
exit;