<?php
// Настройки базы данных
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'valeria_admin');
define('DB_USER', 'root');
define('DB_PASS', 'aiwa30');

// Настройки сессии
session_start();

// Базовый URL
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/admin');

// Подключение к базе данных
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Функция для редиректа
function redirect($url) {
    header("Location: " . $url);
    exit;
}

// Проверка авторизации
function checkAuth() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        redirect('login.php');
    }
}
?>