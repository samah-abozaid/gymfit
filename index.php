<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// ── CSRF généré avant tout ──
$csrfTokenManager = new CSRFTokenManager();
$csrfTokenManager->generateCSRFToken();

// ── Router ──
$router = new Router();
$router->handleRequest($_GET);

// Email : admin@gymfit.com
// Password : password 