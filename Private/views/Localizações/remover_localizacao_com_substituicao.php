<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
bloquear_se_nao_autorizado(pode_editar_dados());

$idEncrypted = $_GET['id_localizacao'] ?? null;
$idSubstitutaEncrypted = $_GET['id_substituta'] ?? null;

$id = aes_decrypt($idEncrypted);
$idSubstituta = aes_decrypt($idSubstitutaEncrypted);

if (!$id || !is_numeric($id) || !$idSubstituta || !is_numeric($idSubstituta)) {
    header('Location: lista_localizacoes.php');
    exit;
}

if ($id == $idSubstituta) {
    header('Location: lista_localizacoes.php');
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

    // Mover todos os equipamentos da localização antiga para a nova
    $stmt = $ligacao->prepare("UPDATE equipamentos SET localizacao_id = :nova WHERE localizacao_id = :antiga");
    $stmt->execute([
        ':nova'   => $idSubstituta,
        ':antiga' => $id,
    ]);

    // Desativar a localização antiga
    $stmt = $ligacao->prepare("UPDATE localizacoes SET localizacao_ativa = 0 WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $ligacao->commit();

    header('Location: lista_localizacoes.php');
    exit;
} catch (PDOException $e) {
    if ($ligacao->inTransaction()) {
        $ligacao->rollBack();
    }
    echo "<p class='text-danger'>Erro: " . $e->getMessage() . "</p>";
    exit;
}