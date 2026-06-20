<?php
require_once __DIR__ . '/../config/config.php';

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $ligacao->query("SELECT * FROM gestao_area_publica");
    $linhas = $stmt->fetchAll(PDO::FETCH_OBJ);

    $conteudo = [];
    foreach ($linhas as $linha) {
        $conteudo[$linha->secao] = $linha;
    }

    $mensagem_enviada = isset($_GET['sucesso_mensagem']);
    $erro_mensagem = isset($_GET['erro_mensagem']);
} catch (PDOException $e) {
    $conteudo = [];
}

$ligacao = null;
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HospitalGest</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">

    <!-- favicon -->
    <link rel="shortcut icon" href="../assets/img/Logo.png" type="image/png">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="../assets/fontawesome/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/1241344.css">
</head>

<body>

    <!-- NAVBAR RESPONSIVA -->
    <nav class="navbar navbar-expand-lg" style="background-color: #acd6d0; padding: 12px 0;">
        <div class="container d-flex align-items-center">

            <!-- LOGO + TEXTO -->
            <a href="#inicio" class="d-flex align-items-center text-decoration-none">
                <img src="../assets/img/Logo.png" alt="Logo da empresa" height="50" class="me-2">
                <h3 class="mb-0 logo-text">
                    <span class="verde">Hospital</span><span class="azul">Gest</span>
                </h3>
            </a>

            <!-- BOTÃO HAMBÚRGUER -->
            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#menu"
                style="border: none; box-shadow: none;">
                <i class="fa-solid fa-bars" style="font-size: 26px; color: white;"></i>
            </button>

            <!-- MENU -->
            <div class="collapse navbar-collapse" id="menu">

                <!-- LINKS CENTRAIS -->
                <ul class="navbar-nav mx-auto text-center" style="gap: 40px;">
                    <li class="nav-item">
                        <a class="nav-link" href="#sobre-nos"
                            style="font-size: 20px; color: white; font-weight: 600; letter-spacing: 1px;">Sobre Nós</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#servicos"
                            style="font-size: 20px; color: white; font-weight: 600; letter-spacing: 1px;">Serviços</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto"
                            style="font-size: 20px; color: white; font-weight: 600; letter-spacing: 1px;">Contacto</a>
                    </li>
                </ul>

                <!-- ÁREA PESSOAL -->
                <div class="text-center mt-3 mt-lg-0">
                    <a href="login.php" class="btn"
                        style="background-color: #86B0AA; color: white; border-radius: 8px; padding: 10px 22px; font-weight: 600;">
                        Área Pessoal
                    </a>
                </div>

            </div>

        </div>
    </nav>

    <!-- SECÇÃO HERO -->
    <section id="inicio" class="inicio-hero container py-5">

        <div class="row align-items-center">

            <div class="col-12 col-md-6 sobre-nos-texto">
                <h1><?= htmlspecialchars($conteudo['hero']->titulo ?? '') ?></h1>
                <p><?= htmlspecialchars($conteudo['hero']->texto ?? '') ?></p>
                <a href="#contacto" class="button">Fale Connosco</a>
            </div>

            <div class="col-12 col-md-6 sobre-nos-imagem text-center mt-4 mt-md-0">
                <img src="../assets/img/Logo_Hospitalgest.png" alt="HospitalGest" class="img-fluid">
            </div>

        </div>

    </section>

    <!-- SOBRE NÓS -->
    <section id="sobre-nos" class="secao-sobre-nos container py-5">
        <h2 class="text-center mb-4">Sobre Nós</h2>

        <p class="text-center mb-5">
            <?= htmlspecialchars($conteudo['sobre_nos_intro']->texto ?? '') ?>
        </p>

        <div class="row g-4">

            <div class="col-12 col-md-6 col-lg-3">
                <div class="box h-100">
                    <h3>O Problema</h3>
                    <p><?= htmlspecialchars($conteudo['sobre_nos_problema']->texto ?? '') ?></p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="box h-100">
                    <h3>A Nossa Solução</h3>
                    <p><?= htmlspecialchars($conteudo['sobre_nos_solucao']->texto ?? '') ?></p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="box h-100">
                    <h3>O Que Oferecemos</h3>
                    <p><?= htmlspecialchars($conteudo['sobre_nos_oferecemos']->texto ?? '') ?></p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="box h-100">
                    <h3>Objetivo</h3>
                    <p><?= htmlspecialchars($conteudo['sobre_nos_objetivo']->texto ?? '') ?></p>
                </div>
            </div>

        </div>

    </section>

    <!-- SERVIÇOS -->
    <section id="servicos" class="secao-servicos container py-5">
        <h2 class="text-center mb-4">Serviços</h2>

        <div class="row g-4">

            <div class="col-12 col-md-6 col-lg-4">
                <div class="servico-box h-100">
                    <h3>Gestão de Equipamentos</h3>
                    <p><?= htmlspecialchars($conteudo['servico_equipamentos']->texto ?? '') ?></p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="servico-box h-100">
                    <h3>Localizações</h3>
                    <p><?= htmlspecialchars($conteudo['servico_localizacoes']->texto ?? '') ?></p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="servico-box h-100">
                    <h3>Fornecedores</h3>
                    <p><?= htmlspecialchars($conteudo['servico_fornecedores']->texto ?? '') ?></p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="servico-box h-100">
                    <h3>Documentação</h3>
                    <p><?= htmlspecialchars($conteudo['servico_documentacao']->texto ?? '') ?></p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="servico-box h-100">
                    <h3>Garantias e Contratos</h3>
                    <p><?= htmlspecialchars($conteudo['servico_garantias']->texto ?? '') ?></p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="servico-box h-100">
                    <h3>Dashboard</h3>
                    <p><?= htmlspecialchars($conteudo['servico_dashboard']->texto ?? '') ?></p>
                </div>
            </div>

        </div>

    </section>

    <!-- CONTACTO -->
    <section id="contacto" class="secao-contacto container py-5">
        <h2 class="text-center mb-4">Contacto</h2>


        <div class="contacto-topo text-center mb-5">
            <h3><?= htmlspecialchars($conteudo['contactos']->titulo ?? '') ?></h3>
            <p><?= htmlspecialchars($conteudo['contactos']->texto ?? '') ?></p>
            <a class="contacto-botao" onclick="abrirFormulario()">Fale Connosco</a>
        </div>

        <div class="row g-4">

            <div class="col-12 col-md-4">
                <div class="contacto-box h-100">
                    <h3>Contactos</h3>
                    <p><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($conteudo['rodape_telefone']->titulo ?? '') ?></p>
                    <p><i class="fa-solid fa-mobile-screen-button"></i> <?= htmlspecialchars($conteudo['rodape_telefone']->texto ?? '') ?></p>
                    <p><i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($conteudo['rodape_email']->texto ?? '') ?></p>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="contacto-box h-100">
                    <h3>Morada</h3>
                    <p><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($conteudo['rodape_morada']->titulo ?? '') ?></p>
                    <p><?= htmlspecialchars($conteudo['rodape_morada']->texto ?? '') ?></p>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="contacto-box h-100">
                    <h3>Horário</h3>
                    <p><i class="fa-solid fa-clock"></i> <?= htmlspecialchars($conteudo['rodape_horario']->titulo ?? '') ?></p>
                    <p><?= htmlspecialchars($conteudo['rodape_horario']->texto ?? '') ?></p>
                </div>
            </div>

        </div>
    </section>

    <!-- POPUP -->
    <div id="popup-form" class="popup escondido">
        <div class="popup-content">
            <button class="popup-close" onclick="fecharFormulario()">✖</button>

            <h2>Como Podemos Ajudar?</h2>

            <?php if (!empty($mensagem_enviada)): ?>
                <div class="alert alert-success text-center mb-3">
                    Mensagem enviada com sucesso! Entraremos em contacto brevemente.
                </div>
            <?php endif; ?>

            <form class="form-box" method="post" action="processa_mensagem.php">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="assunto">Assunto:</label>
                <input type="text" id="assunto" name="assunto" required>

                <label for="mensagem">Mensagem:</label>
                <textarea id="mensagem" name="mensagem" required></textarea>

                <button type="submit" class="btn-enviar">Enviar</button>
            </form>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer-container">
        <div class="footer-content container">

            <div class="footer-title">
                <h2>CONTACTOS</h2>
                <div class="footer-socials">
                    <i class="fa-brands fa-facebook"></i>
                    <i class="fa-brands fa-instagram"></i>
                </div>
            </div>

            <div class="row mt-4">

                <div class="col-12 col-md-4 footer-column">
                    <p><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($conteudo['rodape_morada']->titulo ?? '') ?></p>
                    <p><?= htmlspecialchars($conteudo['rodape_morada']->texto ?? '') ?></p>
                </div>

                <div class="col-12 col-md-4 footer-column">
                    <p><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($conteudo['rodape_telefone']->titulo ?? '') ?></p>
                    <p><i class="fa-solid fa-mobile-screen-button"></i> <?= htmlspecialchars($conteudo['rodape_telefone']->texto ?? '') ?></p>
                </div>

                <div class="col-12 col-md-4 footer-column">
                    <p><i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($conteudo['rodape_email']->texto ?? '') ?></p>
                    <p><i class="fa-solid fa-clock"></i> <?= htmlspecialchars($conteudo['rodape_horario']->texto ?? '') ?></p>
                </div>

            </div>

        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function abrirFormulario() {
        document.getElementById("popup-form").classList.remove("escondido");
    }

    function fecharFormulario() {
        document.getElementById("popup-form").classList.add("escondido");
    }

    <?php if (!empty($mensagem_enviada)): ?>
    abrirFormulario();
    <?php endif; ?>
</script>

</body>

</html>