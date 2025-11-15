<?php
declare(strict_types=1);

if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_NAME')) define('DB_NAME', 'lekario');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');

function db_connect(): PDO {
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_NAME);
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    return new PDO($dsn, DB_USER, DB_PASS, $options);
}

function testConnection() {
    try {
        $pdo = db_connect();
        return true;
    } catch (PDOException $e) {
        error_log('Błąd połączenia z BD: ' . $e->getMessage());
        return false;
    }
}

if (isset($_GET['test']) && $_GET['test'] === 'db') {
    if (testConnection()) {
        echo 'Połączenie z bazą danych powiodło się.';
    } else {
        http_response_code(500);
        echo 'Błąd połączenia z bazą danych.';
    }
}
