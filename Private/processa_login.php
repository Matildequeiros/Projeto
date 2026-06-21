<?php
require_once 'includes/funcoes.php';
start_session();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../public/login.php');
    return;
}

$username = isset($_POST['text_username']) ? $_POST['text_username'] : '';
$password = isset($_POST['text_password']) ? $_POST['text_password'] : '';

$validation_errors = [];

if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $validation_errors[] = 'O username tem que ser um email válido.';
}

if (strlen($username) < 5 || strlen($username) > 50) {
    $validation_errors[] = 'O username deve ter entre 5 e 50 caracteres.';
}

if (strlen($password) < 6 || strlen($password) > 12) {
    $validation_errors[] = 'A password deve ter entre 6 e 12 caracteres.';
}

if (!empty($validation_errors)) {
    $_SESSION['validation_errors'] = $validation_errors;
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

    $parametros = [
        ':u' => $username
    ];

    // Procuramos o utilizador só pelo email
    $comando = $ligacao->prepare("SELECT * FROM utilizadores WHERE email = :u");
    $comando->execute($parametros);
    $resultados = $comando->fetchAll(PDO::FETCH_OBJ);

    // Verificamos a password com password_verify()
    if (count($resultados) === 0 || !password_verify($password, $resultados[0]->password)) {
        $_SESSION['server_error'] = 'Login inválido';
        header('Location: ../public/login.php');
        return;
    }

    $utilizador = $resultados[0];

    $_SESSION['utilizador'] = $utilizador->nome;
    $_SESSION['perfil'] = $utilizador->perfil;

    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    $_SESSION['server_error'] = 'Erro ao ligar à base de dados.';
    header('Location: ../public/login.php');
    return;
}
