<?php
// Charger le fichier de configuration
require_once __DIR__ . '/config/config.php';

// Afficher les informations de debug
echo '<h1>Test de configuration</h1>';
echo '<p>APPROOT: ' . APPROOT . '</p>';
echo '<p>URLROOT: ' . URLROOT . '</p>';
echo '<p>SITENAME: ' . SITENAME . '</p>';
echo '<p>APP_VERSION: ' . APP_VERSION . '</p>';
echo '<p>PHP Version: ' . phpversion() . '</p>';
echo '<p>PHP_SELF: ' . $_SERVER['PHP_SELF'] . '</p>';
echo '<p>SCRIPT_FILENAME: ' . $_SERVER['SCRIPT_FILENAME'] . '</p>';
echo '<p>DOCUMENT_ROOT: ' . $_SERVER['DOCUMENT_ROOT'] . '</p>';

// Vérifier si les fonctionnalités essentielles sont disponibles
echo '<h2>Tests de fonctionnalités</h2>';
echo '<ul>';
echo '<li>dirname(__DIR__) = ' . dirname(__DIR__) . '</li>';
echo '<li>realpath(dirname(__DIR__)) = ' . realpath(dirname(__DIR__)) . '</li>';
echo '<li>realpath(__DIR__) = ' . realpath(__DIR__) . '</li>';
echo '</ul>'; 