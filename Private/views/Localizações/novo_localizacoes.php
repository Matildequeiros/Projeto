<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
bloquear_se_nao_autorizado(pode_editar_dados());
require_once __DIR__ . '/../../includes/validacoes.php';

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

// Gerar próximo código
try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $stmt = $ligacao->query("SELECT codigo FROM localizacoes ORDER BY codigo DESC LIMIT 1");
    $ultimo = $stmt->fetchColumn();
    if ($ultimo) {
        $num = intval(substr($ultimo, 3)) + 1;
        $proximo_codigo = 'LOC' . str_pad($num, 3, '0', STR_PAD_LEFT);
    } else {
        $proximo_codigo = 'LOC001';
    }
    $ligacao = null;
} catch (PDOException $e) {
    $proximo_codigo = 'LOC001';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submeter'])) {

    $codigo    = trim($_POST['codigo']    ?? '');
    $edificio  = trim($_POST['edificio']  ?? '');
    $piso      = trim($_POST['piso']      ?? '');
    $servico_id = trim($_POST['servico_id'] ?? '');
    $sala      = trim($_POST['sala']      ?? '');
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

            $stmt = $ligacao->prepare("INSERT INTO localizacoes (codigo, edificio, piso, servico_id, sala, observacoes)
                                       VALUES (:codigo, :edificio, :piso, :servico_id, :sala, :observacoes)");
            $stmt->execute([
                ':codigo'      => strtoupper($codigo),
                ':edificio'    => ucwords(strtolower($edificio)),
                ':piso'        => $piso,
                ':servico_id'  => $servico_id,
                ':sala'        => $sala,
                ':observacoes' => $observacoes ?: null,
            ]);

            $ligacao = null;
            header("Location: lista_localizacoes.php?sucesso=1");
            exit;
        } catch (PDOException $err) {
            $erro_sistema = "Erro ao guardar a localização: " . $err->getMessage();
        }
    }
}
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?>

<div class="container-fluid">
    <div class="row">

        <?php include '../../includes/sidebar.php'; ?>

        <main class="col-md-9 col-lg-10 p-4">

            <div class="card-form">

                <h2 class="mb-4" style="color: #1a826d;">
                    <i class="fa-solid fa-plus me-2"></i> Nova Localização
                </h2>

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

                <form method="post">

                    <!-- Código -->
                    <div class="mb-3">
                        <label class="form-label">Código Interno</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($proximo_codigo) ?>" disabled>
                        <input type="hidden" name="codigo" value="<?= htmlspecialchars($proximo_codigo) ?>">
                    </div>

                    <!-- Edifício -->
                    <div class="mb-3">
                        <label class="form-label">Edifício *</label>
                        <input type="text" class="form-control" name="edificio" placeholder="Ex: Edifício A"
                            value="<?= htmlspecialchars($_POST['edificio'] ?? '') ?>">
                    </div>

                    <!-- Piso + Serviço -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Piso *</label>
                            <input type="text" class="form-control" name="piso" placeholder="Ex: Piso 2"
                                value="<?= htmlspecialchars($_POST['piso'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Serviço / Departamento *</label>
                            <select class="form-select" name="servico_id">
                                <option value="">Selecione...</option>
                                <?php foreach ($servicos as $s): ?>
                                    <option value="<?= $s['id'] ?>"
                                        <?= (($_POST['servico_id'] ?? '') == $s['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($s['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Sala -->
                    <div class="mb-3">
                        <label class="form-label">Sala / Gabinete *</label>
                        <input type="text" class="form-control" name="sala" placeholder="Ex: Sala 12"
                            value="<?= htmlspecialchars($_POST['sala'] ?? '') ?>">
                    </div>

                    <!-- Observações -->
                    <div class="mb-3">
                        <label class="form-label">Observações</label>
                        <textarea class="form-control" name="observacoes" rows="3"><?= htmlspecialchars($_POST['observacoes'] ?? '') ?></textarea>
                    </div>

                    <!-- Botões -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="lista_localizacoes.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" name="submeter" class="btn" style="background-color: #1a826d; color: white;">
                            Guardar Localização
                        </button>
                    </div>

                </form>

            </div>

        </main>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>