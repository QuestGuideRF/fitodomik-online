<?php
if (!isset($GLOBALS['env_config'])) {
    $GLOBALS['env_config'] = require __DIR__ . '/env.php';
}
$envConfig = $GLOBALS['env_config'];
$host = $envConfig['DB_HOST'];
$dbname = $envConfig['DB_NAME'];
$username = $envConfig['DB_USERNAME'];
$password = $envConfig['DB_PASSWORD'];
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET SESSION SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO'");
} catch(PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Ошибка подключения к базе данных. Пожалуйста, попробуйте позже.");
}