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