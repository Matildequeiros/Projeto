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
    ")->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    die("Erro ao exportar: " . $e->getMessage());
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=fornecedores_' . date('Y-m-d') . '.csv');

$output = fopen('php://output', 'w');
fwrite($output, "\xEF\xBB\xBF");

fputcsv($output, [
    'Código', 'Nome', 'NIF', 'Telefone', 'Email', 'Morada', 'Website',
    'Pessoa de Contacto', 'Telefone Contacto', 'Tipo de Fornecedor', 'Observações'
]);

foreach ($resultados as $f) {
    fputcsv($output, [
        $f->codigo, $f->nome, $f->nif, $f->telefone, $f->email, $f->morada, $f->website,
        $f->pessoa_contacto, $f->telefone_contacto, $f->tipo_fornecedor, $f->observacoes
    ]);
}

fclose($output);
exit;