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
        ORDER BY e.codigo
    ")->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    die("Erro ao exportar: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Listagem de Equipamentos - HospitalGest</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
            padding: 30px;
        }
        h1 {
            color: #1a826d;
            border-bottom: 3px solid #1a826d;
            padding-bottom: 10px;
        }
        .data-geracao {
            color: #777;
            font-size: 13px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th {
            background-color: #1a826d;
            color: white;
            padding: 8px;
            text-align: left;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f4faf9;
        }
        .btn-imprimir {
            background-color: #1a826d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .btn-imprimir:hover {
            background-color: #146957;
        }

        /* Ao imprimir/exportar para PDF, esconder o botão */
        @media print {
            .btn-imprimir {
                display: none;
            }
        }
    </style>
</head>
<body>

    <button class="btn-imprimir" onclick="window.print()">
        Imprimir / Guardar como PDF
    </button>

    <h1>HospitalGest — Listagem de Equipamentos</h1>
    <p class="data-geracao">Documento gerado em <?= date('d/m/Y H:i') ?></p>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Designação</th>
                <th>Marca / Modelo</th>
                <th>Nº Série</th>
                <th>Categoria</th>
                <th>Estado</th>
                <th>Criticidade</th>
                <th>Localização</th>
                <th>Custo (€)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados as $eq): ?>
                <tr>
                    <td><?= htmlspecialchars($eq->codigo) ?></td>
                    <td><?= htmlspecialchars($eq->designacao) ?></td>
                    <td><?= htmlspecialchars($eq->marca . ' ' . $eq->modelo) ?></td>
                    <td><?= htmlspecialchars($eq->numero_serie) ?></td>
                    <td><?= htmlspecialchars($eq->categoria) ?></td>
                    <td><?= htmlspecialchars($eq->estado) ?></td>
                    <td><?= htmlspecialchars($eq->criticidade) ?></td>
                    <td><?= htmlspecialchars($eq->edificio . ' / ' . $eq->piso . ' / ' . $eq->sala) ?></td>
                    <td><?= number_format($eq->custo, 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>