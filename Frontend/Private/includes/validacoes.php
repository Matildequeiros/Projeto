<?php

function validar_texto_obrigatorio(string $valor, string $campo): array {
    $erros = [];
    if (empty(trim($valor))) {
        $erros[] = " {$campo} é obrigatório.";
    } 
    return $erros;
}

function validar_codigo(string $codigo): array {
    $erros = [];
    if (empty(trim($codigo))) {
        $erros[] = "O código interno é obrigatório.";
    } elseif (preg_match('/\s/', $codigo)) {
        $erros[] = "O código interno não pode conter espaços.";
    } elseif (!preg_match('/^EQ-(\d{4})-\d+$/', $codigo, $matches) || $matches[1] < 1900 || $matches[1] > 2100) {
        $erros[] = "O código deve seguir o formato EQ-AAAA-NNN com um ano válido (ex: EQ-2025-001).";
    }
    return $erros;
}

function validar_ano_fabrico(string $ano): array {
    $erros = [];
    if (empty(trim($ano))) {
        $erros[] = "O ano de fabrico é obrigatório.";
    } elseif (!preg_match('/^\d{4}$/', $ano) || $ano < 1900 || $ano > 2100) {
        $erros[] = "O ano de fabrico é inválido.";
    }
    return $erros;
}

function validar_numero_serie(string $numero_serie): array {
    $erros = [];
    if (empty(trim($numero_serie))) {
        $erros[] = "O número de série é obrigatório.";
    }
    return $erros;
}

function validar_select(string $valor, string $campo): array {
    $erros = [];
    if (empty($valor)) {
        $erros[] = " {$campo} é obrigatório.";
    }
    return $erros;
}

function validar_componente(string $nome, string $tipo, string $referencia, string $quantidade, string $estado): array {
    $erros = [];

    $algumPreenchido = !empty($nome) || !empty($referencia) || !empty($quantidade);

    if ($algumPreenchido && empty(trim($nome))) {
        $erros[] = "O nome do componente é obrigatório.";
    }

    if ($algumPreenchido && empty(trim($tipo))) {
        $erros[] = "O tipo do componente é obrigatório.";
    }

    if ($algumPreenchido && empty(trim($quantidade))) {
        $erros[] = "A quantidade do componente é obrigatória.";
    }

    if ($tipo === 'componente' && $algumPreenchido && empty(trim($estado))) {
        $erros[] = "O estado é obrigatório para componentes.";
    }

    return $erros;
}

function validar_data_obrigatoria(string $data, string $campo): array {
    $erros = [];
    if (empty($data)) {
        $erros[] = "{$campo} é obrigatória.";
    } elseif ($data > date('Y-m-d')) {
        $erros[] = "{$campo} não pode ser no futuro.";
    }
    return $erros;
}

function validar_data_posterior(string $data, string $data_limite, string $campo, string $campo_limite): array {
    $erros = [];
    if (!empty($data) && !empty($data_limite) && $data > $data_limite) {
        $erros[] = "{$campo} não pode ser posterior a {$campo_limite}.";
    }
    return $erros;
}

function validar_custo(string $custo): array {
    $erros = [];
    if (empty($custo)) {
        $erros[] = "O custo de aquisição é obrigatório.";
    } elseif (!is_numeric($custo) || $custo < 0) {
        $erros[] = "O custo de aquisição é inválido.";
    }
    return $erros;
}

function validar_telefone(string $telefone): array {
    $erros = [];
    if (!empty($telefone) && !preg_match('/^[0-9]{9}$/', $telefone)) {
        $erros[] = "O telefone de contacto deve ter 9 dígitos.";
    }
    return $erros;
}

function validar_data_anterior(string $data, string $data_limite, string $campo, string $campo_limite): array {
    $erros = [];
    if (!empty($data) && !empty($data_limite) && $data <= $data_limite) {
        $erros[] = "{$campo} tem de ser posterior a {$campo_limite}.";
    }
    return $erros;
}

function validar_documento(string $nome, string $data): array {
    $erros = [];
    $algumPreenchido = !empty($nome) || !empty($data);
    if ($algumPreenchido && empty(trim($nome))) {
        $erros[] = "O nome do documento é obrigatório.";
    }
    return $erros;
}