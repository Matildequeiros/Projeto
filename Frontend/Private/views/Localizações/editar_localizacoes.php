<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
require_once __DIR__ . '/../../includes/validacoes.php';

$idLocalizacaoEncrypted = $_GET['id_localizacao'] ?? null;
$idLocalizacao = aes_decrypt($idLocalizacaoEncrypted);

if (!$idLocalizacao || !is_numeric($idLocalizacao)) {
    header('Location: lista_localizacoes.php');
    exit;
}

$erros = [];
$erro_sistema = "";

// Carregar serviços da BD
try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $stmt = $ligacao->query("SELECT * FROM servicos ORDER BY nome");
    $servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $ligacao = null;
} catch (PDOException $e) {
    $servicos = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submeter'])) {

    $codigo      = trim($_POST['codigo']      ?? '');
    $edificio    = trim($_POST['edificio']    ?? '');
    $piso        = trim($_POST['piso']        ?? '');
    $servico_id  = trim($_POST['servico_id']  ?? '');
    $sala        = trim($_POST['sala']        ?? '');
    $observacoes = trim($_POST['observacoes'] ?? '');

    $erros = array_merge($erros, validar_texto_obrigatorio($edificio,  'O edifício'));
    $erros = array_merge($erros, validar_texto_obrigatorio($piso,      'O piso'));
    $erros = array_merge($erros, validar_select($servico_id,           'O serviço / departamento'));
    $erros = array_merge($erros, validar_texto_obrigatorio($sala,      'A sala / gabinete'));

    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
                MYSQL_USERNAME,
                MYSQL_PASSWORD
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $ligacao->prepare("UPDATE localizacoes SET edificio=:edificio, piso=:piso, servico_id=:servico_id, sala=:sala, observacoes=:observacoes WHERE id=:id");
            $stmt->execute([
                ':edificio'    => ucwords(strtolower($edificio)),
                ':piso'        => $piso,
                ':servico_id'  => $servico_id,
                ':sala'        => $sala,
                ':observacoes' => $observacoes ?: null,
                ':id'          => $idLocalizacao,
            ]);

            $ligacao = null;
            header("Location: lista_localizacoes.php?sucesso=1");
            exit;
        } catch (PDOException $err) {
            $erro_sistema = "Erro ao atualizar a localização: " . $err->getMessage();
        }
    }
}

// Carregar dados da BD
try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $ligacao->prepare("SELECT * FROM localizacoes WHERE id = :id");
    $stmt->bindParam(':id', $idLocalizacao, PDO::PARAM_INT);
    $stmt->execute();
    $localizacao = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$localizacao) {
        header('Location: lista_localizacoes.php');
        exit;
    }
} catch (PDOException $err) {
    $erro_sistema = "Erro na ligação à base de dados.";
    $localizacao = null;
}

$ligacao = null;
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?>

<div class="container-fluid">
    <div class="row">

        <?php include '../../includes/sidebar.php'; ?>

        <main class="col-md-9 col-lg-10 p-4">

            <div class="card-form">

                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <h2 class="mb-0" style="color: #1a826d;">
                        <i class="fa-solid fa-pen-to-square me-2"></i> Editar Localização
                    </h2>

                    <div class="d-flex align-items-center gap-2 px-3 py-2" style="background-color: #d9efec; border-radius: 999px;">
                        <i class="fa-solid fa-location-dot" style="color: #1a826d; font-size: 1rem;"></i>
                        <span style="font-size: 0.95rem; font-weight: 700; color: #0d4d40;">
                            <?= htmlspecialchars($localizacao->codigo) ?> — <?= htmlspecialchars($localizacao->edificio) ?>
                        </span>
                    </div>
                </div>

                <?php if (!empty($erros)): ?>
                    <div class="alert alert-danger" role="alert">
                        <strong>Foram encontrados os seguintes erros:</strong>
                        <ul class="mb-0">
                            <?php foreach ($erros as $erro): ?>
                                <li><?= htmlspecialchars($erro) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($erro_sistema)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($erro_sistema) ?></div>
                <?php endif; ?>

                <form method="post" action="editar_localizacoes.php?id_localizacao=<?= $idLocalizacaoEncrypted ?>">

                    <!-- Código (não editável) -->
                    <div class="mb-3">
                        <label class="form-label">Código Interno</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($localizacao->codigo ?? '') ?>" disabled>
                        <input type="hidden" name="codigo" value="<?= htmlspecialchars($localizacao->codigo ?? '') ?>">
                    </div>

                    <!-- Edifício -->
                    <div class="mb-3">
                        <label class="form-label">Edifício *</label>
                        <input type="text" class="form-control" name="edificio"
                            value="<?= htmlspecialchars($_POST['edificio'] ?? $localizacao->edificio ?? '') ?>">
                    </div>

                    <!-- Piso + Serviço -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Piso *</label>
                            <input type="text" class="form-control" name="piso"
                                value="<?= htmlspecialchars($_POST['piso'] ?? $localizacao->piso ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Serviço / Departamento *</label>
                            <select class="form-select" name="servico_id">
                                <option value="">Selecione...</option>
                                <?php foreach ($servicos as $s): ?>
                                    <option value="<?= $s['id'] ?>"
                                        <?= (($_POST['servico_id'] ?? $localizacao->servico_id ?? '') == $s['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($s['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Sala -->
                    <div class="mb-3">
                        <label class="form-label">Sala / Gabinete *</label>
                        <input type="text" class="form-control" name="sala"
                            value="<?= htmlspecialchars($_POST['sala'] ?? $localizacao->sala ?? '') ?>">
                    </div>

                    <!-- Observações -->
                    <div class="mb-3">
                        <label class="form-label">Observações</label>
                        <textarea class="form-control" name="observacoes" rows="3"><?= htmlspecialchars($_POST['observacoes'] ?? $localizacao->observacoes ?? '') ?></textarea>
                    </div>

                    <!-- Botões -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="lista_localizacoes.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" name="submeter" class="btn" style="background-color: #1a826d; color: white;">
                            Guardar Alterações
                        </button>
                    </div>

                </form>

            </div>

        </main>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>