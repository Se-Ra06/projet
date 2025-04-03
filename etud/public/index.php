<?php
/**
 * Point d'entrée de l'application StageFinder
 * 
 * Ce fichier est le point d'entrée de toutes les requêtes pour notre application.
 * Il charge les fichiers de configuration et d'initialisation, puis démarre l'application.
 */

// Charger le fichier de configuration
require_once '../app/config/config.php';

// Charger le fichier d'initialisation qui charge les classes et initialise l'application
require_once '../app/core/init.php';

// L'application est déjà initialisée dans le fichier init.php
// Il ne reste plus qu'à la démarrer
$app->run(); 