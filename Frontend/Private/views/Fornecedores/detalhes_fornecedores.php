<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$idFornecedorEncrypted = $_GET['id_fornecedor'] ?? null;
$idFornecedor = aes_decrypt($idFornecedorEncrypted);

if (!$idFornecedor || !is_numeric($idFornecedor)) {
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

    $stmt = $ligacao->prepare("SELECT * FROM fornecedores WHERE id = :id");
    $stmt->bindParam(':id', $idFornecedor, PDO::PARAM_INT);
    $stmt->execute();
    $fornecedor = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$fornecedor) {
        header('Location: lista_fornecedores.php');
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


        <!-- MAIN CONTENT -->
        <main class="col-md-9 col-lg-10 p-4">

            <div class="info-box">

                <h2 class="mb-4" style="color: #1a826d;">
                    <i class="fa-solid fa-eye me-2"></i> Consultar Fornecedor
                </h2>

                <!-- CAMPOS COMPLETOS -->
                <div class="info-row"><span class="info-label">Código Interno:</span> <span class="info-value"><?= htmlspecialchars($fornecedor->codigo) ?></span></div>
                <div class="info-row"><span class="info-label">Nome da Empresa:</span> <span class="info-value"><?= htmlspecialchars($fornecedor->nome) ?></span></div>
                <div class="info-row"><span class="info-label">NIF:</span> <span class="info-value"><?= htmlspecialchars($fornecedor->nif) ?></span></div>
                <div class="info-row"><span class="info-label">Telefone Geral:</span> <span class="info-value"><?= htmlspecialchars($fornecedor->telefone) ?></span></div>
                <div class="info-row"><span class="info-label">Email Geral:</span> <span class="info-value"><?= htmlspecialchars($fornecedor->email) ?></span></div>
                <div class="info-row"><span class="info-label">Morada:</span> <span class="info-value"><?= htmlspecialchars($fornecedor->morada) ?></span></div>
                <div class="info-row"><span class="info-label">Website:</span> <span class="info-value"><?= htmlspecialchars($fornecedor->website ?: '—') ?></span></div>
                <div class="info-row"><span class="info-label">Pessoa de Contacto:</span> <span class="info-value"><?= htmlspecialchars($fornecedor->pessoa_contacto) ?></span></div>
                <div class="info-row"><span class="info-label">Telefone da Pessoa de Contacto:</span> <span class="info-value"><?= htmlspecialchars($fornecedor->telefone_contacto) ?></span></div>
                <div class="info-row"><span class="info-label">Tipo:</span> <span class="info-value"><?= htmlspecialchars($fornecedor->tipo_fornecedor) ?></span></div>
                <div class="info-row"><span class="info-label">Observações:</span> <span class="info-value"><?= htmlspecialchars($fornecedor->observacoes ?: 'Sem observações registadas.') ?></span></div>

                <!-- BOTÕES -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="lista_fornecedores.php" class="btn btn-secondary">Voltar</a>
                    <a href="editar_fornecedores.php?id_fornecedor=<?= aes_encrypt($fornecedor->id) ?>" class="btn" style="background-color: #1a826d; color: white;">
                        Editar Fornecedor
                    </a>
                </div>

            </div>

        </main>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>