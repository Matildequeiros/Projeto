<?php
session_start();

$validation_errors = [];

if (!empty($_SESSION['validation_errors'])) {
   
    $validation_errors = $_SESSION['validation_errors'];
    
    unset($_SESSION['validation_errors']);
}

$server_error = [];
// Verifica se existe algum erro de servidor guardado na sessão
if (!empty($_SESSION['server_error'])) {
    // Se existir, copia-o para a variável local
    $server_error = $_SESSION['server_error'];
    // Remove o erro da sessão após ser lido
    unset($_SESSION['server_error']);
}
?>

<?php include '../Private/includes/header.php'; ?>


<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-6 col-sm-8 col-10">
            <div class="card p-4">
                <div class="d-flex align-items-center justify-content-center my-4">
                    <img src="/PROJETO/Frontend/assets/img/Logo.png" class="img-fluid me-3">
                    <h2 class="mb-0 logo-text">
                        <span class="verde"><?php echo explode("Gest", APP_NAME)[0]; ?></span>
                        <span class="azul">Gest</span>
                    </h2>

                </div>
                <div class="row">
                    <div class="col">
                        <form action="../Private/processa_login.php" method="post">
                            <div class="mb-3">
                                <!-- Utilizador -->
                                <label for="email" class="form-label">Utilizador</label>
                                <input type="email" name="text_username" id="email" class="form-control">
                            </div>
                            <div class="mb-3">
                                <!-- Password -->
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="text_password" id="password" class="form-control">
                            </div>
                            <div class="mb-3 text-center">
                                <!-- Submit -->
                                <button type="submit" class="btn btn-secondary px-4">
                                    Entrar <i class="fa-solid fa-right-to-bracket ms-2"></i>
                                </button>
                            </div>
                            <!-- -------------------------------------------------------------------- -->
                            <!-- APRESENTAÇÃO DE MENSAGENS DE ERRO (VALIDAÇÃO E SERVIDOR) -->
                            <!-- -------------------------------------------------------------------- -->
                            <!-- Verifica se existem erros de validação -->
                            <?php if (!empty($validation_errors)) : ?>
                                <!-- Se existirem, apresenta um alerta de erro (vermelho) usando as classes do Bootstrap -->
                                <div class="alert alert-danger p-2 text-center">
                                    <!-- Percorre todos os erros de validação -->
                                    <?php foreach ($validation_errors as $error) : ?>
                                        <!-- Mostra cada erro dentro de uma <div>, escapando caracteres especiais para segurança -->
                                        <div><?= htmlspecialchars($error) ?></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <!-- Verifica se existe um erro de servidor -->
                            <?php if (!empty($server_error)) : ?>
                                <!-- Apresenta também num alerta de erro (vermelho) -->
                                <div class="alert alert-danger p-2 text-center">
                                    <!-- Mostra o erro do servidor, também escapado com htmlspecialchars -->
                                    <div><?= htmlspecialchars($server_error) ?></div>
                                </div>
                            <?php endif; ?>
                            
                        </form>

                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<?php include '../Private/includes/footer.php'; ?>