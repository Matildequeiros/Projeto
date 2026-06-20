<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['utilizador'])) {
    header('Location: ../public/login.php');
    exit;
}

$nome = $_SESSION['utilizador'];

// Buscar mensagens do público
try {
    $ligacao_nav = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao_nav->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $totalNaoLidas = $ligacao_nav->query("SELECT COUNT(*) FROM mensagens_publico WHERE lida = 0")->fetchColumn();

    $stmt = $ligacao_nav->query("SELECT * FROM mensagens_publico ORDER BY data_envio DESC LIMIT 20");
    $mensagens_publico = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $totalNaoLidas = 0;
    $mensagens_publico = [];
}

$ligacao_nav = null;
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
        <div class="col-6 text-end p-3 d-flex align-items-center justify-content-end gap-3">

            <!-- ÍCONE MENSAGENS -->
            <button type="button" class="btn position-relative" data-bs-toggle="modal" data-bs-target="#modalMensagens"
                style="background-color: #86B0AA; color: white; border: none;">
                <i class="fa-regular fa-envelope"></i>
                <?php if ($totalNaoLidas > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= $totalNaoLidas ?>
                    </span>
                <?php endif; ?>
            </button>

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

<!-- MODAL MENSAGENS DO PÚBLICO -->
<div class="modal fade" id="modalMensagens" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 12px;">

            <div class="modal-header" style="background-color: #1a826d; color: white;">
                <h5 class="modal-title">
                    <i class="fa-regular fa-envelope me-2"></i> Mensagens do Público
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">

                <?php if (empty($mensagens_publico)): ?>
                    <p class="text-muted text-center">Não existem mensagens.</p>
                <?php else: ?>

                    <div class="accordion" id="accordionMensagens">
                        <?php foreach ($mensagens_publico as $i => $msg): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#msg<?= $msg->id ?>">
                                        <?php if (!$msg->lida): ?>
                                            <span class="badge bg-danger me-2">Nova</span>
                                        <?php endif; ?>
                                        <?= htmlspecialchars($msg->nome ?: $msg->email) ?> —
                                        <?= htmlspecialchars($msg->assunto ?: 'Sem assunto') ?>
                                    </button>
                                </h2>

                                <div id="msg<?= $msg->id ?>" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionMensagens">
                                    <div class="accordion-body">

                                        <p><strong>Nome:</strong> <?= htmlspecialchars($msg->nome ?: '—') ?></p>
                                        <p><strong>Email:</strong> <?= htmlspecialchars($msg->email) ?></p>
                                        <p><strong>Assunto:</strong> <?= htmlspecialchars($msg->assunto ?: '—') ?></p>
                                        <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($msg->data_envio)) ?></p>
                                        <p><strong>Mensagem:</strong><br><?= nl2br(htmlspecialchars($msg->mensagem)) ?></p>

                                        <?php if (!$msg->lida): ?>
                                            <a href="<?= BASE_URL ?>/Private/marcar_mensagem_lida.php?id=<?= $msg->id ?>"
                                                class="btn btn-sm" style="background-color: #1a826d; color: white;">
                                                <i class="fa-solid fa-check me-1"></i> Marcar como lida
                                            </a>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php endif; ?>

            </div>

        </div>
    </div>
</div>