<?php
require_once __DIR__ . '/../../config/config.php';

// Inicia a sessão se ainda não estiver iniciada
function start_session()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// Verifica se a sessão do utilizador está ativa
function check_session()
{
    return isset($_SESSION['utilizador']);
}

// Redireciona automaticamente se não houver sessão iniciada
function redirect_if_not_logged($redirect_to = '/public/login.php')
{
    start_session();
    if (!check_session()) {
        header("Location: " . BASE_URL . $redirect_to);
        exit;
    }
}
function logout_and_redirect($redirect_to = '/public/login.php')
{
    start_session();
    session_unset();
    session_destroy();

    // Redireciona para a página de login com caminho absoluto
    header("Location: " . BASE_URL . $redirect_to);
    exit;
}

// Devolve o perfil do utilizador autenticado (ou '' se não houver sessão)
function perfil_atual()
{
    start_session();
    return $_SESSION['perfil'] ?? '';
}

function is_administrador()
{
    return perfil_atual() === 'administrador';
}

// Só o administrador pode gerir a área pública e ver as mensagens
function pode_gerir_area_publica()
{
    return is_administrador();
}

// Administrador e técnico podem criar/editar/eliminar registos.
// O profissional de saúde só pode consultar (ver detalhes).
function pode_editar_dados()
{
    return in_array(perfil_atual(), ['administrador', 'tecnico'], true);
}

// Bloqueia o acesso à página atual se o perfil não estiver autorizado
function bloquear_se_nao_autorizado($autorizado)
{
    if (!$autorizado) {
        header("Location: " . BASE_URL . "/private/index.php?erro=sem_permissao");
        exit;
    }
}
// Encriptação e desencriptação de valores com OpenSSL
function aes_encrypt($value)
{
    return bin2hex(openssl_encrypt(
        $value,
        OPENSSL_METHOD,
        OPENSSL_KEY,
        OPENSSL_RAW_DATA,
        OPENSSL_IV
    ));
}

function aes_decrypt($value)
{
    if (!is_string($value) || strlen($value) % 2 !== 0) return false;
    return openssl_decrypt(
        hex2bin($value),
        OPENSSL_METHOD,
        OPENSSL_KEY,
        OPENSSL_RAW_DATA,
        OPENSSL_IV
    );
}


// REGISTO DE EVENTOS (LOG)
// Escreve uma linha no ficheiro de log, com data e hora
function registar_evento($mensagem)
{
    $pasta_logs = __DIR__ . '/../../logs';

    // Cria a pasta logs/ automaticamente se ainda não existir
    if (!is_dir($pasta_logs)) {
        mkdir($pasta_logs, 0777, true);
    }

    $ficheiro_log = $pasta_logs . '/eventos.log';
    $linha = '[' . date('Y-m-d H:i:s') . '] ' . $mensagem . PHP_EOL;

    file_put_contents($ficheiro_log, $linha, FILE_APPEND);
}


// UPLOAD DE DOCUMENTOS (PDF)
function guardar_documento_pdf($nome_campo, $contexto)
{
    if (!isset($_FILES[$nome_campo]) || $_FILES[$nome_campo]['error'] === UPLOAD_ERR_NO_FILE) {
        return '';
    }

    if ($_FILES[$nome_campo]['error'] !== UPLOAD_ERR_OK) {
        return '';
    }

    $ficheiro_tmp = $_FILES[$nome_campo]['tmp_name'];

    // Valida que o ficheiro é realmente um PDF (não confia apenas na extensão)
    if (mime_content_type($ficheiro_tmp) !== 'application/pdf') {
        return '';
    }

    $pasta_destino = __DIR__ . '/../../assets/uploads/' . $contexto;
    if (!is_dir($pasta_destino)) {
        mkdir($pasta_destino, 0777, true);
    }

    $nome_novo = $contexto . '_' . uniqid() . '.pdf';
    $caminho_destino = $pasta_destino . '/' . $nome_novo;

    if (move_uploaded_file($ficheiro_tmp, $caminho_destino)) {
        return $contexto . '/' . $nome_novo;
    }

    return '';
}

// UPLOAD DE DOCUMENTOS (PDF) — versão para campos de ficheiro múltiplos, ex. name="doc_ficheiro[]"
function guardar_documento_pdf_multiplo($nome_campo, $indice, $contexto)
{
    if (!isset($_FILES[$nome_campo]['tmp_name'][$indice]) || $_FILES[$nome_campo]['error'][$indice] === UPLOAD_ERR_NO_FILE) {
        return '';
    }

    if ($_FILES[$nome_campo]['error'][$indice] !== UPLOAD_ERR_OK) {
        return '';
    }

    $ficheiro_tmp = $_FILES[$nome_campo]['tmp_name'][$indice];

    if (mime_content_type($ficheiro_tmp) !== 'application/pdf') {
        return '';
    }

    $pasta_destino = __DIR__ . '/../../assets/uploads/' . $contexto;
    if (!is_dir($pasta_destino)) {
        mkdir($pasta_destino, 0777, true);
    }

    $nome_novo = $contexto . '_' . uniqid() . '.pdf';
    $caminho_destino = $pasta_destino . '/' . $nome_novo;

    if (move_uploaded_file($ficheiro_tmp, $caminho_destino)) {
        return $contexto . '/' . $nome_novo;
    }

    return '';
}