<?php
// Configuration de l'application

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'stagefinder_db');

// Configuration des chemins
define('APPROOT', realpath(dirname(dirname(__FILE__)))); // Chemin absolu vers le répertoire 'app'
define('URLROOT', 'http://localhost/etud');
define('SITENAME', 'StageFinder');
define('APP_VERSION', '1.0.0');

// Configuration des sessions
session_start(); 