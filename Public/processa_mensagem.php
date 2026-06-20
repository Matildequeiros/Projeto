<?php
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$nome     = trim($_POST['nome']     ?? '');
$email    = trim($_POST['email']    ?? '');
$assunto  = trim($_POST['assunto']  ?? '');
$mensagem = trim($_POST['mensagem'] ?? '');

$erros = [];

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erros[] = "O email é obrigatório e deve ser válido.";
}

if (empty($mensagem)) {
    $erros[] = "A mensagem é obrigatória.";
}

if (!empty($erros)) {
    header('Location: index.php?erro_mensagem=1#contacto');
    exit;
}

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8mb4",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $ligacao->prepare("INSERT INTO mensagens_publico (nome, email, assunto, mensagem, data_envio, lida) VALUES (:nome, :email, :assunto, :mensagem, NOW(), 0)");
    $stmt->execute([
        ':nome'     => $nome ?: null,
        ':email'    => $email,
        ':assunto'  => $assunto ?: null,
        ':mensagem' => $mensagem,
    ]);

    $ligacao = null;
    header('Location: index.php?sucesso_mensagem=1#contacto');
    exit;
} catch (PDOException $e) {
    header('Location: index.php?erro_mensagem=1#contacto');
    exit;
}