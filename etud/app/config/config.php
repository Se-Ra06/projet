<?php
// Configuration de l'application

// Vérifier si les constantes sont déjà définies
if (!defined('DB_HOST')) {
    // Configuration de la base de données
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'website');
}

// Vérifier si APPROOT est déjà défini
if (!defined('APPROOT')) {
    // Constantes de l'application
    define('APPROOT', dirname(dirname(__FILE__)));
    define('URLROOT', 'http://localhost/etud');
    define('SITENAME', 'StageFinder');
    define('APP_VERSION', '1.0.0');

    // Charger les fichiers de base si c'est le premier chargement
    if (!class_exists('Database') && file_exists(APPROOT . '/core/Database.php')) {
        require_once APPROOT . '/core/Database.php';
    }
}

// Activer l'affichage des erreurs en mode développement
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ENVIRONMENT', 'development'); // Options: development, production 