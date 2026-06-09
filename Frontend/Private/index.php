<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
 // Redireciona para o formulário de login (interface pública)
 header('Location: ../public/login.php');
 // Encerra a execução do script imediatamente após o redirecionamento
 return;
} 
?> 

<?php
$username = isset($_POST['text_username']) ? $_POST['text_username'] : '';
// O mesmo para o campo da password.
$password = isset($_POST['text_password']) ? $_POST['text_password'] : '';

echo "Utilizador: " . $username . "<br>";
echo "Password: " . $password;
?>


<?php include 'includes/header.php'; ?>

<?php include 'includes/nav.php'; ?>     

    <div class="container-fluid">
        <div class="row">

            <?php include 'includes/sidebar.php'; ?> 


            <!-- CONTEÚDO PRINCIPAL -->
            <main class="col-md-9 col-lg-10 p-5 bg-light">

                <!-- TÍTULO -->
                <div class="text-center mb-5">
                    <h2 class="fw-bold" style="color: #1a826d;">Bem-vindo ao HospitalGest</h2>
                    <p class="text-muted">Selecione uma das funcionalidades no menu lateral para começar a gerir o
                        inventário hospitalar!</p>
                </div>

                <!-- 4 CARDS INICIAIS -->
                <div class="inicio-cards mb-5">

                    <div class="p-4 bg-white rounded shadow-sm text-center border"
                        style="border-color:#86b0aa!important;">
                        <i class="fas fa-stethoscope fa-2x mb-3" style="color:#47a894;;"></i>
                        <h5 class="fw-bold" style="color:#1a826d;">Equipamentos</h5>
                        <p class="text-muted small">Registe, edite e consulte o inventário hospitalar.</p>
                    </div>

                    <div class="p-4 bg-white rounded shadow-sm text-center border"
                        style="border-color:#86b0aa!important;">
                        <i class="fas fa-truck-medical fa-2x mb-3" style="color:#47a894;;"></i>
                        <h5 class="fw-bold" style="color:#1a826d;">Fornecedores</h5>
                        <p class="text-muted small">Gestão de empresas, contactos e suporte técnico.</p>
                    </div>

                    <div class="p-4 bg-white rounded shadow-sm text-center border"
                        style="border-color:#86b0aa!important;">
                        <i class="fas fa-location-dot fa-2x mb-3" style="color:#47a894;;"></i>
                        <h5 class="fw-bold" style="color:#1a826d;">Localizações</h5>
                        <p class="text-muted small">Organize edifícios, pisos, serviços e salas.</p>
                    </div>

                    <div class="p-4 bg-white rounded shadow-sm text-center border"
                        style="border-color:#86b0aa!important;">
                        <i class="fas fa-globe fa-2x mb-3" style="color:#47a894;;"></i>
                        <h5 class="fw-bold" style="color:#1a826d;">Gestão da Área Pública</h5>
                        <p class="text-muted small">Gestão e edição da área pública.</p>
                    </div>

                </div>

                <!-- INDICADORES OBRIGATÓRIOS -->
                <h3 class="fw-bold mb-4" style="color:#47a894;">Indicadores de Síntese</h3>

                <div class="row g-4 mb-5">

                    <div class="col-6 col-md-4 col-lg-2 d-flex">
                        <div class="p-4 bg-white rounded shadow-sm text-center border card-indicador total"
                            style="border-color:#86b0aa!important;">

                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <h3 class="fw-bold" style="color:#1a826d;">150</h3>
                                <i class="fa-solid fa-layer-group indicador-icon"></i>
                            </div>

                            <p class="text-muted small">Total</p>
                        </div>
                    </div>


                    <div class="col-6 col-md-4 col-lg-2 d-flex">
                        <div class="p-4 bg-white rounded shadow-sm text-center border card-indicador ativos"
                            style="border-color:#86b0aa!important;">

                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <h3 class="fw-bold">120</h3>
                                <i class="fa-solid fa-circle-check indicador-icon"></i>
                            </div>
                            <p class="text-muted small">Ativos</p>

                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-lg-2 d-flex">
                        <div class="p-4 bg-white rounded shadow-sm text-center border card-indicador manutencao"
                            style="border-color:#86b0aa!important;">

                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <h3 class="fw-bold">18</h3>
                                <i class="fa-solid fa-screwdriver-wrench indicador-icon"></i>
                            </div>
                            <p class="text-muted small">Em Manutenção</p>

                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-lg-2 d-flex">
                        <div class="p-4 bg-white rounded shadow-sm text-center border card-indicador inativos"
                            style="border-color:#86b0aa!important;">

                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <h3 class="fw-bold">12</h3>
                                <i class="fa-solid fa-circle-xmark indicador-icon"></i>
                            </div>
                            <p class="text-muted small">Inativos</p>

                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-lg-2 d-flex">
                        <div class="p-4 bg-white rounded shadow-sm text-center border card-indicador garantia"
                            style="border-color:#86b0aa!important;">

                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <h3 class="fw-bold">7</h3>
                                <i class="fa-solid fa-hourglass-end indicador-icon"></i>
                            </div>
                            <p class="text-muted small">Garantia Expirada</p>

                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-lg-2 d-flex">
                        <div class="p-4 bg-white rounded shadow-sm text-center border card-indicador sem-doc"
                            style="border-color:#86b0aa!important;">

                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <h3 class="fw-bold">9</h3>
                                <i class="fa-solid fa-file-circle-exclamation indicador-icon"></i>
                            </div>
                            <p class="text-muted small">Sem Documentação</p>

                        </div>
                    </div>

                </div>


                <div class="row g-4 mt-4">

                    <!-- GRÁFICO e MINI CARD DOS 30 DIAS -->
                    <div class="col-lg-6 d-flex flex-column align-items-center">

                        <!-- CAIXA DO GRÁFICO -->
                        <div class="p-4 bg-white rounded shadow-sm border w-100"
                            style="border-color:#86b0aa!important;">

                            <h5 class="fw-bold mb-3" style="color:#47a894;;">Número de Equipamentos por Serviço</h5>

                            <div class="grafico-pequeno mx-auto">
                                <canvas id="graficoServicos"></canvas>
                            </div>
                        </div>

                        <!-- MINI CARD DOS 30 DIAS -->
                        <div class="mt-3 w-100">
                            <div class="p-3 bg-white rounded shadow-sm border d-flex align-items-center justify-content-between"
                                style="border-color:#86b0aa!important; width: 100%;">

                                <!-- Número + texto -->
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center" style="background:#f6a76e ; color:white; width:45px; height:45px; 
                        border-radius:6px; font-weight:bold; font-size:1.2rem;">
                                        30
                                    </div>

                                    <div>
                                        <h6 class="fw-bold mb-0" style="color:#f6a76e ;">Garantias a expirar</h6>
                                        <small class="text-muted">Nos próximos 30 dias</small>
                                    </div>
                                </div>

                                <!-- Ícone -->
                                <i class="fa-solid fa-hourglass-end"
                                    style="font-size:28px; color:#f6a76e ; opacity:0.85;"></i>

                            </div>
                        </div>

                        <!-- MINI CARD CRITICIDADE ELEVADA -->
                        <div class="mt-3 w-100">
                            <div class="p-3 bg-white rounded shadow-sm border d-flex align-items-center justify-content-between"
                                style="border-color:#f28b82!important; width: 100%;">

                                <!-- Número + texto -->
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center" style="background:#f28b82; color:white; width:45px; height:45px; 
                        border-radius:6px; font-weight:bold; font-size:1.2rem;">
                                        12
                                    </div>

                                    <div>
                                        <h6 class="fw-bold mb-0" style="color:#f28b82;"> Equipamentos de Criticidade Elevada</h6>
                                        <small class="text-muted">Equipamentos de risco clínico</small>
                                    </div>
                                </div>

                                <!-- Ícone -->
                                <i class="fa-solid fa-triangle-exclamation"
                                    style="font-size:28px; color:#f28b82; opacity:0.85;"></i>

                            </div>
                        </div>




                    </div>


                    <!-- GRÁFICO 2 - Distribuição por Categoria -->
                    <div class="col-lg-6">
                        <div class="p-4 bg-white rounded shadow-sm border" style="border-color:#86b0aa!important;">
                            <h5 class="fw-bold mb-3" style="color:#47a894;;">Distribuição por Categoria</h5>

                            <div class="grafico-pequeno">
                                <canvas id="graficoCategorias"></canvas>
                            </div>
                        </div>
                    </div>

                </div>

        </div>

        </main>

        <!-- CHART.JS -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            new Chart(document.getElementById('graficoServicos'), {
                type: 'bar',
                data: {
                    labels: ['UCI', 'Urgência', 'Medicina', 'Bloco Operatório', 'Pediatria'],
                    datasets: [{
                        label: 'Equipamentos',
                        data: [40, 25, 30, 20, 15],
                        backgroundColor: '#86b0aa'
                    }]
                }
            });

            new Chart(document.getElementById('graficoCategorias'), {
                type: 'pie',
                data: {
                    labels: ['Monitorização', 'Terapia', 'Diagnóstico', 'Suporte de Vida', 'Laboratório'],
                    datasets: [{
                        data: [35, 20, 25, 10, 10],
                        backgroundColor: ['#acd6d0', '#86b0aa', '#1a826d', '#0f5a48', '#5fa59b']
                    }]
                }
            });
        </script>


        <?php include 'includes/footer.php'; ?>
