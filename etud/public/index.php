<?php
/**
 * Point d'entrée de l'application StageFinder
 * 
 * Ce fichier est le point d'entrée de toutes les requêtes pour notre application.
 * Il charge le fichier d'initialisation, qui va lui-même charger la configuration 
 * et initialiser l'application.
 */

// Charger directement le fichier d'initialisation
// (il charge déjà config.php et initialise l'application)
require_once '../app/core/init.php';

// L'application est déjà initialisée dans le fichier init.php
// Il ne reste plus qu'à la démarrer
$app->run(); 