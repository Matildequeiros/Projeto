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

    $resultados = $ligacao->query("SELECT codigo, nome, nif, telefone, email, morada, tipo_fornecedor
        FROM fornecedores
        WHERE fornecedor_ativo = 1
        ORDER BY codigo
    ")->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    die("Erro ao exportar: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Listagem de Fornecedores - HospitalGest</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #333; padding: 30px; }
        h1 { color: #1a826d; border-bottom: 3px solid #1a826d; padding-bottom: 10px; }
        .data-geracao { color: #777; font-size: 13px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th { background-color: #1a826d; color: white; padding: 8px; text-align: left; }
        td { padding: 6px 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f4faf9; }
        .btn-imprimir {
            background-color: #1a826d; color: white; border: none;
            padding: 10px 20px; border-radius: 8px; font-size: 15px;
            cursor: pointer; margin-bottom: 20px;
        }
        .btn-imprimir:hover { background-color: #146957; }
        @media print { .btn-imprimir { display: none; } }
    </style>
</head>
<body>

    <button class="btn-imprimir" onclick="window.print()">
        Imprimir / Guardar como PDF
    </button>

    <h1>HospitalGest — Listagem de Fornecedores</h1>
    <p class="data-geracao">Documento gerado em <?= date('d/m/Y H:i') ?></p>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>NIF</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Morada</th>
                <th>Tipo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados as $f): ?>
                <tr>
                    <td><?= htmlspecialchars($f->codigo) ?></td>
                    <td><?= htmlspecialchars($f->nome) ?></td>
                    <td><?= htmlspecialchars($f->nif) ?></td>
                    <td><?= htmlspecialchars($f->telefone) ?></td>
                    <td><?= htmlspecialchars($f->email) ?></td>
                    <td><?= htmlspecialchars($f->morada) ?></td>
                    <td><?= htmlspecialchars($f->tipo_fornecedor) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>