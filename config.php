<?php
session_start();

$pdo = new PDO(
    'mysql:host=localhost;dbname=voting_system;charset=utf8mb4',
    'root',   // change to your MySQL username
    '',       // change to your MySQL password
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
);

function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

function require_login() {
    if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }
}

function require_admin() {
    require_login();
    if ($_SESSION['role'] !== 'admin') { http_response_code(403); exit('Admins only.'); }
}

function csrf_token() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}

function csrf_check() {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(400); exit('Invalid request token.');
    }
}
