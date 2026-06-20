<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$idEncrypted = $_GET['id_fornecedor'] ?? null;
$idSubstitutoEncrypted = $_GET['id_substituto'] ?? null;

$id = aes_decrypt($idEncrypted);
$idSubstituto = aes_decrypt($idSubstitutoEncrypted);

if (!$id || !is_numeric($id) || !$idSubstituto || !is_numeric($idSubstituto)) {
    header('Location: lista_fornecedores.php');
    exit;
}

if ($id == $idSubstituto) {
    header('Location: lista_fornecedores.php');
    exit;
}

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $ligacao->beginTransaction();

    // Substituir o fornecedor antigo pelo novo em todas as associações equipamento_fornecedor
    $stmt = $ligacao->prepare("UPDATE equipamento_fornecedor SET fornecedor_id = :novo WHERE fornecedor_id = :antigo");
    $stmt->execute([
        ':novo'   => $idSubstituto,
        ':antigo' => $id,
    ]);

    // Desativar o fornecedor antigo
    $stmt = $ligacao->prepare("UPDATE fornecedores SET fornecedor_ativo = 0 WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $ligacao->commit();

    header('Location: lista_fornecedores.php');
    exit;
} catch (PDOException $e) {
    if ($ligacao->inTransaction()) {
        $ligacao->rollBack();
    }
    echo "<p class='text-danger'>Erro: " . $e->getMessage() . "</p>";
    exit;
}