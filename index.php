<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// ── Affichage des erreurs selon l'environnement ──
if (($_ENV['APP_ENV'] ?? 'production') === 'development') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL);
    ini_set('log_errors', '1');
}

// ── CSRF généré avant tout ──
$csrfTokenManager = new CSRFTokenManager();
$csrfTokenManager->generateCSRFToken();

// ── Router ──
$router = new Router();
$router->handleRequest($_GET);