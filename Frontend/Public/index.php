<?php
require_once __DIR__ . '/../config/config.php';
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
                    <a href="../Login/login.html" class="btn"
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
                <h1>Soluções Digitais para Gestão Hospitalar</h1>
                <p>Desenvolvemos software para inventário hospitalar, gestão documental e manutenção de equipamentos
                    médicos.</p>
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
            A HospitalGest desenvolve soluções digitais para apoiar a gestão do inventário hospitalar,
            substituindo processos dispersos por uma plataforma centralizada, intuitiva e eficiente.
        </p>

        <div class="row g-4">

            <div class="col-12 col-md-6 col-lg-3">
                <div class="box h-100">
                    <h3>O Problema</h3>
                    <p>Hospitais usam Excel, documentos soltos e registos manuais.</p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="box h-100">
                    <h3>A Nossa Solução</h3>
                    <p>Aplicação web para organizar equipamentos, fornecedores e documentação.</p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="box h-100">
                    <h3>O Que Oferecemos</h3>
                    <ul>
                        <li>Inventário estruturado</li>
                        <li>Rastreabilidade completa</li>
                        <li>Gestão documental</li>
                        <li>Fornecedores e contratos</li>
                        <li>Pesquisa e filtros avançados</li>
                    </ul>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="box h-100">
                    <h3>Objetivo</h3>
                    <p>Criar uma plataforma que ajude os hospitais a organizar melhor os seus equipamentos,
                        documentação e fornecedores.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- SERVIÇOS -->
    <section id="servicos" class="secao-servicos container py-5">
        <h2 class="text-center mb-4">Serviços</h2>

        <p class="servicos-intro text-center mb-5">
            A HospitalGest oferece soluções digitais para apoiar a gestão do inventário hospitalar, garantindo
            organização e eficiência.
        </p>

        <div class="row g-4">

            <div class="col-12 col-md-6 col-lg-4">
                <div class="servico-box h-100">
                    <h3>Gestão de Equipamentos</h3>
                    <p>Registo, edição e consulta de equipamentos médicos com informação detalhada.</p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="servico-box h-100">
                    <h3>Localizações</h3>
                    <p>Organização dos equipamentos por edifício, piso, serviço e sala.</p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="servico-box h-100">
                    <h3>Fornecedores</h3>
                    <p>Associação de fabricantes, distribuidores e empresas de assistência técnica.</p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="servico-box h-100">
                    <h3>Documentação</h3>
                    <p>Gestão de manuais, certificados, contratos e relatórios técnicos.</p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="servico-box h-100">
                    <h3>Garantias e Contratos</h3>
                    <p>Consulta rápida de datas, validade e entidades responsáveis.</p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="servico-box h-100">
                    <h3>Dashboard</h3>
                    <p>Pesquisa avançada e visualização de indicadores que apoiam a tomada de decisão.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- CONTACTO -->
    <section id="contacto" class="secao-contacto container py-5">
        <h2 class="text-center mb-4">Contacto</h2>

        <div class="contacto-topo text-center mb-5">
            <h3>Como Podemos Ajudar?</h3>
            <p>A nossa equipa está disponível para esclarecer dúvidas e fornecer toda a informação necessária.</p>
            <a class="contacto-botao" onclick="abrirFormulario()">Fale Connosco</a>
        </div>

        <div class="row g-4">

            <div class="col-12 col-md-4">
                <div class="contacto-box h-100">
                    <h3>Contactos</h3>
                    <p><i class="fa-solid fa-phone"></i> 254 344 253</p>
                    <p><i class="fa-solid fa-mobile-screen-button"></i> 912 745 234</p>
                    <p><i class="fa-solid fa-envelope"></i> geral@hospitalgest.pt</p>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="contacto-box h-100">
                    <h3>Morada</h3>
                    <p><i class="fa-solid fa-location-dot"></i> Rua da Boa Saúde nº10</p>
                    <p>4523-089 Viana do Castelo</p>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="contacto-box h-100">
                    <h3>Horário</h3>
                    <p><i class="fa-solid fa-clock"></i> Todos os dias</p>
                    <p>07:00h - 22:00h</p>
                </div>
            </div>

        </div>
    </section>

    <!-- POPUP -->
    <div id="popup-form" class="popup escondido">
        <div class="popup-content">
            <button class="popup-close" onclick="fecharFormulario()">✖</button>

            <h2>Como Podemos Ajudar?</h2>

            <form class="form-box">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" required>

                <label for="email">Email:</label>
                <input type="email" id="email" required>

                <label for="assunto">Assunto:</label>
                <input type="text" id="assunto" required>

                <label for="mensagem">Mensagem:</label>
                <textarea id="mensagem" required></textarea>

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
                    <p><i class="fa-solid fa-location-dot"></i> Rua da Boa Saúde nº10</p>
                    <p>4523-089 Viana do Castelo</p>
                </div>

                <div class="col-12 col-md-4 footer-column">
                    <p><i class="fa-solid fa-phone"></i> 254 344 253</p>
                    <p><i class="fa-solid fa-mobile-screen-button"></i> 912 745 234</p>
                </div>

                <div class="col-12 col-md-4 footer-column">
                    <p><i class="fa-solid fa-envelope"></i> geral@hospitalgest.pt</p>
                    <p><i class="fa-solid fa-clock"></i> 07:00h - 22:00h</p>
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
    </script>

</body>

</html>