<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['utilizador'])) {
    header('Location: ../public/login.php');
    exit;
}

$nome = $_SESSION['utilizador'];
?>

<!-- NAVBAR -->
<header class="container-fluid" style="background-color: #acd6d0;">
    <div class="row align-items-center">

        <!-- LOGO + TÍTULO -->
        <div class="col-6 d-flex align-items-center p-3">
            <a href="<?= BASE_URL ?>/Private/index.php">
                <img src="<?= BASE_URL ?>/assets/img/Logo.png" alt="Logo HospitalGest" height="50" class="me-3">
            </a>
            <h3 class="mb-0 logo-text">
                <span class="verde"><?php echo explode("Gest", APP_NAME)[0]; ?></span><span class="azul">Gest</span>
            </h3>


        </div>

        <!-- UTILIZADOR (dropdown) -->
        <div class="col-6 text-end p-3">
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    style="background-color: #86B0AA; color: white; border: none;">
                    <i class="fa-regular fa-user me-2"></i> <?= htmlspecialchars($nome) ?>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">
                            <i class="fa-solid fa-key me-2"></i> Alterar password
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/Public/logout.php">
                            <i class="fa-solid fa-right-from-bracket me-2"></i> Sair
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</header>