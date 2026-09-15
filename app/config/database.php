<?php
/**
 * Configurações de conexão com o banco de dados
 * LarPerto - Portal de Imóveis
 */

return [
    'host'      => 'localhost',
    'dbname'    => 'larperto',
    'username'  => 'root',
    'password'  => '',          // No XAMPP normalmente fica vazio
    'charset'   => 'utf8mb4',
    'options'   => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]
];