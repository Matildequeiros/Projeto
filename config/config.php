<?php
// URL base do projeto no browser
define('BASE_URL', '/PROJETO/Frontend');

// Configurações globais da aplicação
define('APP_NAME', 'HospitalGest');
define('APP_VERSION', '1.0.0');
define('APP_COPYRIGHT', '© 2026 HospitalGest');

// Base de dados
define('MYSQL_HOST', 'vsgate-s1.dei.isep.ipp.pt');
define('MYSQL_PORT', 10464);
define('MYSQL_DATABASE', 'db1241344');
define('MYSQL_USERNAME', '1241344');
define('MYSQL_PASSWORD', 'queirós_344');

// Segurança — Encriptação com OpenSSL
define('OPENSSL_METHOD', 'AES-256-CBC');
define('OPENSSL_KEY', 'H0SDRQzIGqclX2kbYBk9xspdn9U5f3Wa'); // 32 caracteres
define('OPENSSL_IV', 'BzKAbjuREsHgnw56'); // 16 caracteres