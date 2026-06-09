<?php include '../private/includes/header.php'; ?>





<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-6 col-sm-8 col-10">
            <div class="card p-4">
                <div class="d-flex align-items-center justify-content-center my-4">
                    <img src="/PROJETO/Frontend/assets/img/Logo.png
" class="img-fluid me-3">
                    <h2 class="mb-0 logo-text">
                        <span class="verde"><?php echo explode("Gest", APP_NAME)[0]; ?></span>
                        <span class="azul">Gest</span>
                    </h2>

                </div>
                <div class="row">
                    <div class="col">
                        <form action="../Private/index.php" method="post">
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
                            <div class="alert alert-danger p-2 text-center">
                                <!-- Erros -->
                                Erro: Utilizador não registado
                            </div>
                        </form>

                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<?php include '../private/includes/footer.php'; ?>