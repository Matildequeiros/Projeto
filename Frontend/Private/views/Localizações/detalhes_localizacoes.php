<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$idLocalizacaoEncrypted = $_GET['id_localizacao'] ?? null;
$idLocalizacao = aes_decrypt($idLocalizacaoEncrypted);

if (!$idLocalizacao || !is_numeric($idLocalizacao)) {
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

    $stmt = $ligacao->prepare("SELECT l.*, s.nome AS servico
        FROM localizacoes l
        LEFT JOIN servicos s ON l.servico_id = s.id
        WHERE l.id = :id");
    $stmt->bindParam(':id', $idLocalizacao, PDO::PARAM_INT);
    $stmt->execute();
    $localizacao = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$localizacao) {
        header('Location: lista_localizacoes.php');
        exit;
    }
} catch (PDOException $e) {
    echo "<p class='text-danger'>Erro: " . $e->getMessage() . "</p>";
    exit;
}
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?>

<div class="container-fluid">
    <div class="row">

        <?php include '../../includes/sidebar.php'; ?>


        <!-- MAIN -->
        <main class="col-md-9 col-lg-10 p-4">

            <div class="info-box">

                <h2 class="mb-4" style="color: #1a826d;">
                    <i class="fa-solid fa-eye me-2"></i> Consultar Localização
                </h2>

                <!-- CAMPOS DA LOCALIZAÇÃO -->
                <div class="info-row">
                    <span class="info-label">Código:</span>
                    <span class="info-value"><?= htmlspecialchars($localizacao->codigo) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Edifício:</span>
                    <span class="info-value"><?= htmlspecialchars($localizacao->edificio) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Piso:</span>
                    <span class="info-value"><?= htmlspecialchars($localizacao->piso) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Serviço / Departamento:</span>
                    <span class="info-value"><?= htmlspecialchars($localizacao->servico ?? '—') ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Sala / Gabinete:</span>
                    <span class="info-value"><?= htmlspecialchars($localizacao->sala) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Observações:</span>
                    <span class="info-value"><?= htmlspecialchars($localizacao->observacoes ?: 'Sem observações registadas.') ?></span>
                </div>

                <!-- BOTÕES -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="lista_localizacoes.php" class="btn btn-secondary">Voltar</a>

                    <a href="editar_localizacoes.php?id_localizacao=<?= aes_encrypt($localizacao->id) ?>" class="btn" style="background-color: #1a826d; color: white;">
                        Editar Localização
                    </a>
                </div>

            </div>

        </main>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>