<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
bloquear_se_nao_autorizado(pode_editar_dados());
require_once __DIR__ . '/../../includes/validacoes.php';

// Gerar próximo código
try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $stmt = $ligacao->query("SELECT codigo FROM fornecedores ORDER BY codigo DESC LIMIT 1");
    $ultimo = $stmt->fetchColumn();
    if ($ultimo) {
        $num = intval(substr($ultimo, 1)) + 1;
        $proximo_codigo = 'F' . str_pad($num, 3, '0', STR_PAD_LEFT);
    } else {
        $proximo_codigo = 'F001';
    }
    $ligacao = null;
} catch (PDOException $e) {
    $proximo_codigo = 'F001';
}
$erros = [];
$erro_sistema = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submeter'])) {

    $codigo             = trim($_POST['codigo']             ?? '');
    $nome               = trim($_POST['nome']               ?? '');
    $nif                = trim($_POST['nif']                ?? '');
    $telefone           = trim($_POST['telefone']           ?? '');
    $email              = trim($_POST['email']              ?? '');
    $morada             = trim($_POST['morada']             ?? '');
    $website            = trim($_POST['website']            ?? '');
    $pessoa_contacto    = trim($_POST['pessoa_contacto']    ?? '');
    $telefone_contacto  = trim($_POST['telefone_contacto']  ?? '');
    $tipo_fornecedor    = trim($_POST['tipo_fornecedor']    ?? '');
    $observacoes        = trim($_POST['observacoes']        ?? '');

    $erros = array_merge($erros, validar_texto_obrigatorio($nome, 'O nome'));
    $erros = array_merge($erros, validar_nif($nif));
    $erros = array_merge($erros, validar_texto_obrigatorio($telefone, 'O telefone'));
    if (!empty($telefone)) {
        $erros = array_merge($erros, validar_telefone($telefone, 'O telefone'));
    }
    $erros = array_merge($erros, validar_email($email));
    $erros = array_merge($erros, validar_texto_obrigatorio($morada, 'A morada'));
    $erros = array_merge($erros, validar_texto_obrigatorio($pessoa_contacto, 'A pessoa de contacto'));
    $erros = array_merge($erros, validar_nome_pessoa($pessoa_contacto, 'A pessoa de contacto'));
    $erros = array_merge($erros, validar_texto_obrigatorio($telefone_contacto, 'O telefone de contacto'));
    if (!empty($telefone_contacto)) {
        $erros = array_merge($erros, validar_telefone($telefone_contacto, 'O telefone de contacto'));
    }
    $erros = array_merge($erros, validar_select($tipo_fornecedor, 'O tipo'));

    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
                MYSQL_USERNAME,
                MYSQL_PASSWORD
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $ligacao->prepare("INSERT INTO fornecedores (codigo, nome, nif, telefone, email, morada, website, pessoa_contacto, telefone_contacto, tipo_fornecedor, observacoes) VALUES (:codigo, :nome, :nif, :telefone, :email, :morada, :website, :pessoa_contacto, :telefone_contacto, :tipo_fornecedor, :observacoes)");
            $stmt->execute([
                ':codigo'            => strtoupper($codigo),
                ':nome'              => $nome,
                ':nif'               => $nif,
                ':telefone'          => $telefone,
                ':email'             => $email,
                ':morada'            => $morada,
                ':website'           => $website ?: null,
                ':pessoa_contacto'   => $pessoa_contacto ?: null,
                ':telefone_contacto' => $telefone_contacto ?: null,
                ':tipo_fornecedor'   => $tipo_fornecedor,
                ':observacoes'       => $observacoes ?: null,
            ]);

            $ligacao = null;
            header("Location: lista_fornecedores.php?sucesso=1");
            exit;
        } catch (PDOException $err) {
            $erro_sistema = "Erro ao guardar o fornecedor: " . $err->getMessage();
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
                    <i class="fa-solid fa-plus me-2"></i> Novo Fornecedor
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

                    <div class="mb-3">
                        <label class="form-label">Código Interno</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($proximo_codigo) ?>" disabled>
                        <input type="hidden" name="codigo" value="<?= htmlspecialchars($proximo_codigo) ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nome da Empresa *</label>
                            <input type="text" class="form-control" name="nome" placeholder="Ex: MedTech Solutions"
                                value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">NIF *</label>
                            <input type="text" class="form-control" name="nif" placeholder="123456789"
                                value="<?= htmlspecialchars($_POST['nif'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telefone *</label>
                            <input type="text" class="form-control" name="telefone" placeholder="253000000"
                                value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" placeholder="email@empresa.com"
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Morada *</label>
                        <input type="text" class="form-control" name="morada" placeholder="Rua, nº, cidade"
                            value="<?= htmlspecialchars($_POST['morada'] ?? '') ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Website</label>
                            <input type="text" class="form-control" name="website" placeholder="www.empresa.com"
                                value="<?= htmlspecialchars($_POST['website'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pessoa de Contacto *</label>
                            <input type="text" class="form-control" name="pessoa_contacto" placeholder="Nome da pessoa"
                                value="<?= htmlspecialchars($_POST['pessoa_contacto'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telefone de Contacto *</label>
                            <input type="text" class="form-control" name="telefone_contacto" placeholder="912345678"
                                value="<?= htmlspecialchars($_POST['telefone_contacto'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo *</label>
                            <select class="form-select" name="tipo_fornecedor">
                                <option value="">Selecione...</option>
                                <option value="Fabricante" <?= ($_POST['tipo_fornecedor'] ?? '') == 'Fabricante' ? 'selected' : '' ?>>Fabricante</option>
                                <option value="Distribuidor / Comercial" <?= ($_POST['tipo_fornecedor'] ?? '') == 'Distribuidor / Comercial' ? 'selected' : '' ?>>Distribuidor / Comercial</option>
                                <option value="Assistência Técnica" <?= ($_POST['tipo_fornecedor'] ?? '') == 'Assistência Técnica' ? 'selected' : '' ?>>Assistência Técnica</option>
                                <option value="Consumíveis / Acessórios" <?= ($_POST['tipo_fornecedor'] ?? '') == 'Consumíveis / Acessórios' ? 'selected' : '' ?>>Consumíveis / Acessórios</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observações</label>
                        <textarea class="form-control" name="observacoes" rows="3"><?= htmlspecialchars($_POST['observacoes'] ?? '') ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="lista_fornecedores.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" name="submeter" class="btn" style="background-color: #1a826d; color: white;">
                            Guardar Fornecedor
                        </button>
                    </div>

                </form>

            </div>

        </main>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>