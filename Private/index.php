<?php
require_once 'includes/funcoes.php';
start_session();

if (!isset($_SESSION['utilizador'])) {
    header('Location: ../public/login.php');
    return;
}

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $totalEquipamentos = $ligacao->query("SELECT COUNT(*) FROM equipamentos WHERE equipamento_ativo = 1")->fetchColumn();
    $ativos = $ligacao->query("SELECT COUNT(*) FROM equipamentos WHERE equipamento_ativo = 1 AND estado = 'Ativo'")->fetchColumn();
    $emManutencao = $ligacao->query("SELECT COUNT(*) FROM equipamentos WHERE equipamento_ativo = 1 AND estado = 'Em manutenção'")->fetchColumn();
    $inativos = $ligacao->query("SELECT COUNT(*) FROM equipamentos WHERE equipamento_ativo = 1 AND estado = 'Inativo'")->fetchColumn();
    $garantiaExpirada = $ligacao->query("
    SELECT COUNT(*) FROM equipamentos e
    JOIN garantias g ON g.equipamento_id = e.id
    WHERE e.equipamento_ativo = 1 AND g.data_fim < CURDATE()
")->fetchColumn();

    $semDocumentacao = $ligacao->query("
    SELECT COUNT(*) FROM equipamentos e
    LEFT JOIN documentacao d ON d.equipamento_id = e.id
    WHERE e.equipamento_ativo = 1 AND d.id IS NULL
")->fetchColumn();

    $garantiasAExpirar = $ligacao->query("
    SELECT COUNT(*) FROM equipamentos e
    JOIN garantias g ON g.equipamento_id = e.id
    WHERE e.equipamento_ativo = 1 
    AND g.data_fim >= CURDATE() 
    AND g.data_fim <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
")->fetchColumn();

    $criticidadeElevada = $ligacao->query("
    SELECT COUNT(*) FROM equipamentos 
    WHERE equipamento_ativo = 1 AND criticidade = 'Suporte de vida'
")->fetchColumn();

    $stmt = $ligacao->query("
    SELECT s.nome AS servico, COUNT(e.id) AS total
    FROM equipamentos e
    JOIN localizacoes l ON e.localizacao_id = l.id
    JOIN servicos s ON l.servico_id = s.id
    WHERE e.equipamento_ativo = 1
    GROUP BY s.nome
    ORDER BY total DESC
");
    $dadosServicos = $stmt->fetchAll(PDO::FETCH_OBJ);

    $labelsServicos = [];
    $valoresServicos = [];
    foreach ($dadosServicos as $linha) {
        $labelsServicos[] = explode(' ', $linha->servico);
        $valoresServicos[] = (int) $linha->total;
    }

    $stmt = $ligacao->query("
    SELECT categoria, COUNT(*) AS total
    FROM equipamentos
    WHERE equipamento_ativo = 1
    GROUP BY categoria
    ORDER BY total DESC
");
    $dadosCategorias = $stmt->fetchAll(PDO::FETCH_OBJ);

    $labelsCategorias = [];
    $valoresCategorias = [];
    foreach ($dadosCategorias as $linha) {
        $labelsCategorias[] = $linha->categoria;
        $valoresCategorias[] = (int) $linha->total;
    }
} catch (PDOException $e) {
    $totalEquipamentos = 0;
    $ativos = 0;
    $emManutencao = 0;
    $inativos = 0;
    $garantiaExpirada = 0;
    $semDocumentacao = 0;
    $garantiasAExpirar = 0;
    $criticidadeElevada = 0;
    $labelsServicos = [];
    $valoresServicos = [];
    $labelsCategorias = [];
    $valoresCategorias = [];
}
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
            <div class="mb-4">
                <h3 class="fw-bold mb-1" style="color:#47a894;">Indicadores de Síntese</h3>
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-stethoscope" style="color: #86b0aa; font-size: 1.1rem;"></i>
                    <span class="text-muted" style="font-size: 0.95rem;">Equipamentos</span>
                </div>
            </div>

            <div class="row g-4 mb-5">

                <div class="col-6 col-md-4 col-lg-2 d-flex">
                    <div class="p-4 bg-white rounded shadow-sm text-center border card-indicador total"
                        style="border-color:#86b0aa!important;">

                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <h3 class="fw-bold" style="color:#1a826d;"><?= $totalEquipamentos ?></h3>
                            <i class="fa-solid fa-layer-group indicador-icon"></i>
                        </div>

                        <p class="text-muted small">Total</p>
                    </div>
                </div>


                <div class="col-6 col-md-4 col-lg-2 d-flex">
                    <div class="p-4 bg-white rounded shadow-sm text-center border card-indicador ativos"
                        style="border-color:#86b0aa!important;">

                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <h3 class="fw-bold"><?= $ativos ?></h3>
                            <i class="fa-solid fa-circle-check indicador-icon"></i>
                        </div>
                        <p class="text-muted small">Ativos</p>

                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2 d-flex">
                    <div class="p-4 bg-white rounded shadow-sm text-center border card-indicador manutencao"
                        style="border-color:#86b0aa!important;">

                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <h3 class="fw-bold"><?= $emManutencao ?></h3>
                            <i class="fa-solid fa-screwdriver-wrench indicador-icon"></i>
                        </div>
                        <p class="text-muted small">Em Manutenção</p>

                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2 d-flex">
                    <div class="p-4 bg-white rounded shadow-sm text-center border card-indicador inativos"
                        style="border-color:#86b0aa!important;">

                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <h3 class="fw-bold"><?= $inativos ?></h3>
                            <i class="fa-solid fa-circle-xmark indicador-icon"></i>
                        </div>
                        <p class="text-muted small">Inativos</p>

                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2 d-flex">
                    <div class="p-4 bg-white rounded shadow-sm text-center border card-indicador garantia"
                        style="border-color:#86b0aa!important;">

                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <h3 class="fw-bold"><?= $garantiaExpirada ?></h3>
                            <i class="fa-solid fa-hourglass-end indicador-icon"></i>
                        </div>
                        <p class="text-muted small">Garantia Expirada</p>

                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2 d-flex">
                    <div class="p-4 bg-white rounded shadow-sm text-center border card-indicador sem-doc"
                        style="border-color:#86b0aa!important;">

                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <h3 class="fw-bold"><?= $semDocumentacao ?></h3>
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
                                    <?= $garantiasAExpirar ?>
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
                                    <?= $criticidadeElevada ?>
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

        </main>

    </div>

</div>

<!-- CHART.JS -->
<script src="<?= BASE_URL ?>/assets/chartjs/chart.js"></script>

<script>
    new Chart(document.getElementById('graficoServicos'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($labelsServicos) ?>,
            datasets: [{
                label: 'Equipamentos',
                data: <?= json_encode($valoresServicos) ?>,
                backgroundColor: '#86b0aa'
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    ticks: {
                        autoSkip: false,
                        maxRotation: 0,
                        minRotation: 0,
                        font: {
                            size: 9
                        }
                    }
                },
                y: {
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    new Chart(document.getElementById('graficoCategorias'), {
        type: 'pie',
        data: {
            labels: <?= json_encode($labelsCategorias) ?>,
            datasets: [{
                data: <?= json_encode($valoresCategorias) ?>,
                backgroundColor: ['#acd6d0', '#86b0aa', '#1a826d', '#0f5a48', '#5fa59b', '#c3e0db', '#3d8a76']
            }]
        }
    });
</script>


<?php include 'includes/footer.php'; ?>