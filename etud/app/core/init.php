<?php
// Charge les classes principales du core
require_once APPROOT . '/core/Database.php';
require_once APPROOT . '/core/Controller.php';
require_once APPROOT . '/core/Model.php';
require_once APPROOT . '/core/Router.php';
require_once APPROOT . '/core/Session.php';
require_once APPROOT . '/core/App.php';
require_once APPROOT . '/core/Helper.php';
require_once APPROOT . '/core/Validator.php';

// Fonction d'autoload pour charger automatiquement les classes des contrôleurs et modèles
spl_autoload_register(function($className) {
    // Vérifier dans les controllers
    if (file_exists(APPROOT . '/controllers/' . $className . '.php')) {
        require_once APPROOT . '/controllers/' . $className . '.php';
    }
    
    // Vérifier dans les models
    elseif (file_exists(APPROOT . '/models/' . $className . '.php')) {
        require_once APPROOT . '/models/' . $className . '.php';
    }
});

// Initialiser l'application
$app = new App(); 