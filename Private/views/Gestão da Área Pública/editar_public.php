<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

// Processar o formulário quando submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $ligacao = new PDO(
            "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
            MYSQL_USERNAME,
            MYSQL_PASSWORD
        );
        $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Mapa: nome do campo no formulário => secção + coluna na BD
        $campos = [
            'hero_titulo'              => ['hero', 'titulo'],
            'hero_texto'                => ['hero', 'texto'],
            'sobre_nos_intro_texto'     => ['sobre_nos_intro', 'texto'],
            'sobre_nos_problema_texto'  => ['sobre_nos_problema', 'texto'],
            'sobre_nos_solucao_texto'   => ['sobre_nos_solucao', 'texto'],
            'sobre_nos_oferecemos_texto' => ['sobre_nos_oferecemos', 'texto'],
            'sobre_nos_objetivo_texto'  => ['sobre_nos_objetivo', 'texto'],
            'servico_equipamentos_texto' => ['servico_equipamentos', 'texto'],
            'servico_localizacoes_texto' => ['servico_localizacoes', 'texto'],
            'servico_fornecedores_texto' => ['servico_fornecedores', 'texto'],
            'servico_documentacao_texto' => ['servico_documentacao', 'texto'],
            'servico_garantias_texto'   => ['servico_garantias', 'texto'],
            'servico_dashboard_texto'   => ['servico_dashboard', 'texto'],
            'contactos_titulo'          => ['contactos', 'titulo'],
            'contactos_texto'           => ['contactos', 'texto'],
            'rodape_telefone_titulo'    => ['rodape_telefone', 'titulo'],
            'rodape_telefone_texto'     => ['rodape_telefone', 'texto'],
            'rodape_email_texto'        => ['rodape_email', 'texto'],
            'rodape_morada_titulo'      => ['rodape_morada', 'titulo'],
            'rodape_morada_texto'       => ['rodape_morada', 'texto'],
            'rodape_horario_titulo'     => ['rodape_horario', 'titulo'],
            'rodape_horario_texto'      => ['rodape_horario', 'texto'],
        ];

        foreach ($campos as $nome_campo => $info) {
            [$secao, $coluna] = $info;
            $valor = trim($_POST[$nome_campo] ?? '');

            $stmt = $ligacao->prepare("UPDATE gestao_area_publica SET {$coluna} = :valor WHERE secao = :secao");
            $stmt->execute([':valor' => $valor, ':secao' => $secao]);
        }

        $ligacao = null;
        header('Location: editar_public.php?sucesso=1');
        exit;
    } catch (PDOException $e) {
        $erro_sistema = "Erro ao guardar alterações: " . $e->getMessage();
    }
}

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $ligacao->query("SELECT * FROM gestao_area_publica");
    $linhas = $stmt->fetchAll(PDO::FETCH_OBJ);

    // Organizar os dados por secção, para acesso fácil tipo $conteudo['hero']->titulo
    $conteudo = [];
    foreach ($linhas as $linha) {
        $conteudo[$linha->secao] = $linha;
    }
} catch (PDOException $e) {
    $erro_sistema = "Erro ao carregar conteúdo: " . $e->getMessage();
    $conteudo = [];
}

$ligacao = null;
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?>

<div class="container-fluid">
    <div class="row">

        <?php include '../../includes/sidebar.php'; ?>


        <!-- CONTEÚDO PRINCIPAL -->
        <main class="col-md-9 col-lg-10 p-4">

            <div class="card shadow p-4" style="border-radius: 12px; max-width: 1000px; margin: 0 auto;">

                <h2 class="mb-4" style="color: #1a826d;">
                    <i class="fa-solid fa-globe me-2"></i> Gestão da Área Pública
                </h2>

                <form method="post" action="editar_public.php">

                    <div class="accordion" id="accordionPublico">

                        <!-- SECÇÃO HERO -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heroHeading">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#hero" aria-expanded="true" aria-controls="hero">
                                    Secção Hero
                                </button>
                            </h2>

                            <div id="hero" class="accordion-collapse collapse show" aria-labelledby="heroHeading"
                                data-bs-parent="#accordionPublico">
                                <div class="accordion-body">

                                    <div class="mb-3">
                                        <label class="form-label">Título</label>
                                        <input type="text" class="form-control" name="hero_titulo"
                                            value="<?= htmlspecialchars($conteudo['hero']->titulo ?? '') ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Subtítulo</label>
                                        <textarea class="form-control" name="hero_texto"
                                            rows="2"><?= htmlspecialchars($conteudo['hero']->texto ?? '') ?></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Texto do Botão</label>
                                        <input type="text" class="form-control" value="Fale Connosco" disabled>
                                        <small class="text-muted">Este texto está fixo no código por agora.</small>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- SOBRE NÓS -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="sobreHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#sobre" aria-expanded="false" aria-controls="sobre">
                                    Sobre Nós
                                </button>
                            </h2>

                            <div id="sobre" class="accordion-collapse collapse" aria-labelledby="sobreHeading"
                                data-bs-parent="#accordionPublico">
                                <div class="accordion-body">

                                    <div class="mb-3">
                                        <label class="form-label">Texto Principal</label>
                                        <textarea class="form-control" name="sobre_nos_intro_texto"
                                            rows="3"><?= htmlspecialchars($conteudo['sobre_nos_intro']->texto ?? '') ?></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">O Problema</label>
                                            <textarea class="form-control" name="sobre_nos_problema_texto"
                                                rows="2"><?= htmlspecialchars($conteudo['sobre_nos_problema']->texto ?? '') ?></textarea>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">A Nossa Solução</label>
                                            <textarea class="form-control" name="sobre_nos_solucao_texto"
                                                rows="2"><?= htmlspecialchars($conteudo['sobre_nos_solucao']->texto ?? '') ?></textarea>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">O Que Oferecemos</label>
                                            <textarea class="form-control" name="sobre_nos_oferecemos_texto"
                                                rows="3"><?= htmlspecialchars($conteudo['sobre_nos_oferecemos']->texto ?? '') ?></textarea>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Objetivo</label>
                                            <textarea class="form-control" name="sobre_nos_objetivo_texto"
                                                rows="2"><?= htmlspecialchars($conteudo['sobre_nos_objetivo']->texto ?? '') ?></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- SERVIÇOS -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="servicosHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#servicos" aria-expanded="false" aria-controls="servicos">
                                    Serviços
                                </button>
                            </h2>

                            <div id="servicos" class="accordion-collapse collapse" aria-labelledby="servicosHeading"
                                data-bs-parent="#accordionPublico">
                                <div class="accordion-body">

                                    <div class="row">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Gestão de Equipamentos</label>
                                                <textarea class="form-control" name="servico_equipamentos_texto"
                                                    rows="2"><?= htmlspecialchars($conteudo['servico_equipamentos']->texto ?? '') ?></textarea>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Localizações</label>
                                                <textarea class="form-control" name="servico_localizacoes_texto"
                                                    rows="2"><?= htmlspecialchars($conteudo['servico_localizacoes']->texto ?? '') ?></textarea>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Fornecedores</label>
                                                <textarea class="form-control" name="servico_fornecedores_texto"
                                                    rows="2"><?= htmlspecialchars($conteudo['servico_fornecedores']->texto ?? '') ?></textarea>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Documentação</label>
                                                <textarea class="form-control" name="servico_documentacao_texto"
                                                    rows="2"><?= htmlspecialchars($conteudo['servico_documentacao']->texto ?? '') ?></textarea>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Garantias e Contratos</label>
                                                <textarea class="form-control" name="servico_garantias_texto"
                                                    rows="2"><?= htmlspecialchars($conteudo['servico_garantias']->texto ?? '') ?></textarea>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Dashboard</label>
                                                <textarea class="form-control" name="servico_dashboard_texto"
                                                    rows="2"><?= htmlspecialchars($conteudo['servico_dashboard']->texto ?? '') ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- SECÇÃO CONTACTO -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="contactoHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#contacto" aria-expanded="false" aria-controls="contacto">
                                    Secção Contacto
                                </button>
                            </h2>

                            <div id="contacto" class="accordion-collapse collapse" aria-labelledby="contactoHeading"
                                data-bs-parent="#accordionPublico">
                                <div class="accordion-body">

                                    <div class="mb-3">
                                        <label class="form-label">Título da Barra</label>
                                        <input type="text" class="form-control" name="contactos_titulo"
                                            value="<?= htmlspecialchars($conteudo['contactos']->titulo ?? '') ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Subtítulo da Barra</label>
                                        <textarea class="form-control" name="contactos_texto"
                                            rows="2"><?= htmlspecialchars($conteudo['contactos']->texto ?? '') ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Texto do Botão "Fale Connosco"</label>
                                        <input type="text" class="form-control" value="Fale Connosco" disabled>
                                        <small class="text-muted">Este texto está fixo no código por agora.</small>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- POPUP -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="popupHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#popup" aria-expanded="false" aria-controls="popup">
                                    Popup "Fale Connosco"
                                </button>
                            </h2>

                            <div id="popup" class="accordion-collapse collapse" aria-labelledby="popupHeading"
                                data-bs-parent="#accordionPublico">
                                <div class="accordion-body">

                                    <div class="mb-3">
                                        <label class="form-label">Título do Popup</label>
                                        <input type="text" class="form-control" value="Como Podemos Ajudar?">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Label - Nome</label>
                                            <input type="text" class="form-control" value="Nome:">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Label - Email</label>
                                            <input type="text" class="form-control" value="Email:">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Label - Assunto</label>
                                            <input type="text" class="form-control" value="Assunto:">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Label - Mensagem</label>
                                            <input type="text" class="form-control" value="Mensagem:">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Texto do Botão "Enviar"</label>
                                        <input type="text" class="form-control" value="Enviar">
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- CONTACTOS -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="contactosHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#contactos" aria-expanded="false" aria-controls="contactos">
                                    Contactos
                                </button>
                            </h2>

                            <div id="contactos" class="accordion-collapse collapse"
                                aria-labelledby="contactosHeading" data-bs-parent="#accordionPublico">
                                <div class="accordion-body">

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Telefone Fixo</label>
                                            <input type="text" class="form-control" name="rodape_telefone_titulo"
                                                value="<?= htmlspecialchars($conteudo['rodape_telefone']->titulo ?? '') ?>">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Telemóvel</label>
                                            <input type="text" class="form-control" name="rodape_telefone_texto"
                                                value="<?= htmlspecialchars($conteudo['rodape_telefone']->texto ?? '') ?>">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="rodape_email_texto"
                                                value="<?= htmlspecialchars($conteudo['rodape_email']->texto ?? '') ?>">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Morada</label>
                                            <input type="text" class="form-control" name="rodape_morada_titulo"
                                                value="<?= htmlspecialchars($conteudo['rodape_morada']->titulo ?? '') ?>">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Localidade</label>
                                            <input type="text" class="form-control" name="rodape_morada_texto"
                                                value="<?= htmlspecialchars($conteudo['rodape_morada']->texto ?? '') ?>">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Texto do Horário</label>
                                            <input type="text" class="form-control" name="rodape_horario_titulo"
                                                value="<?= htmlspecialchars($conteudo['rodape_horario']->titulo ?? '') ?>">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Horário</label>
                                            <input type="text" class="form-control" name="rodape_horario_texto"
                                                value="<?= htmlspecialchars($conteudo['rodape_horario']->texto ?? '') ?>">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- BOTÕES -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="../../index.php" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left me-2"></i> Voltar
                        </a>

                        <button type="submit" class="btn" style="background-color: #1a826d; color: white;">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Guardar Alterações
                        </button>
                    </div>

                </form>

            </div>

        </main>

    </div>
</div>


<?php include '../../includes/footer.php'; ?>