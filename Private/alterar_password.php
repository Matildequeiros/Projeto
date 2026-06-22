<?php
require_once __DIR__ . '/includes/funcoes.php';
redirect_if_not_logged();

$mensagem_sucesso = '';
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_atual = $_POST['password_atual'] ?? '';
    $password_nova = $_POST['password_nova'] ?? '';
    $password_confirmar = $_POST['password_confirmar'] ?? '';

    if (strlen($password_nova) < 6 || strlen($password_nova) > 12) {
        $erros[] = 'A nova password deve ter entre 6 e 12 caracteres.';
    }

    if ($password_nova !== $password_confirmar) {
        $erros[] = 'A confirmação não coincide com a nova password.';
    }

    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
                MYSQL_USERNAME,
                MYSQL_PASSWORD
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Vai buscar a password atual guardada, para confirmar que a pessoa a sabe
            $stmt = $ligacao->prepare("SELECT password FROM utilizadores WHERE id = :id");
            $stmt->execute([':id' => $_SESSION['utilizador_id']]);
            $utilizador = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$utilizador || !password_verify($password_atual, $utilizador->password)) {
                $erros[] = 'A password atual está incorreta.';
            } else {
                $novo_hash = password_hash($password_nova, PASSWORD_DEFAULT);

                $stmt = $ligacao->prepare("UPDATE utilizadores SET password = :p WHERE id = :id");
                $stmt->execute([':p' => $novo_hash, ':id' => $_SESSION['utilizador_id']]);

                registar_evento("Password alterada: utilizador {$_SESSION['utilizador']}");
                $mensagem_sucesso = 'Password alterada com sucesso.';
            }
        } catch (PDOException $e) {
            $erros[] = 'Erro ao ligar à base de dados.';
        }
    }
}
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card p-4">
                <h4 class="mb-4" style="color: #1a826d;">
                    <i class="fa-solid fa-key me-2"></i> Alterar Password
                </h4>

                <?php if ($mensagem_sucesso): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($mensagem_sucesso) ?></div>
                <?php endif; ?>

                <?php if (!empty($erros)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($erros as $erro): ?>
                            <div><?= htmlspecialchars($erro) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label for="password_atual" class="form-label">Password atual</label>
                        <input type="password" name="password_atual" id="password_atual" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_nova" class="form-label">Nova password</label>
                        <input type="password" name="password_nova" id="password_nova" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmar" class="form-label">Confirmar nova password</label>
                        <input type="password" name="password_confirmar" id="password_confirmar" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn" style="background-color: #1a826d; color: white;">
                            Guardar Nova Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>