<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//  Session en PREMIER avant tout
session_start();

// Autoload Composer
require_once __DIR__ . '/vendor/autoload.php';

// Charge le .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Lance le Router
$router = new Router();
$router->handleRequest($_GET);