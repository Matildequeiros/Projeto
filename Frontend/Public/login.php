<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HospitalGest - Login</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="../assets/fontawesome/all.min.css">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="../assets/css/1241344.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/img/Logo.png" type="image/png">
</head>

<body>
    <!-- Bootstrap JS and custom JS -->
    <script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>

    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6 col-sm-8 col-10">
                <div class="card p-4">
                    <div class="d-flex align-items-center justify-content-center my-4">
                        <img src="../assets/img/Logo.png" alt="Logotipo do hospital">
                        <h2><strong>
                            <span class="hospital">Hospital</span><span class="gest">Gest</span>
                        </strong></h2>
                    </div>
                    <div class="row">
                        <div class="col">
                            <form action="../Private/index.html" method="post">
                                <div class="mb-3">
                                    <!-- Utilizador -->
                                    <label for="email"  class="form-label">Utilizador</label>
                                    <input type="email" name="email" id="email" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <!-- Password -->
                                    <label for="password"  class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control">
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
</body>

</html>