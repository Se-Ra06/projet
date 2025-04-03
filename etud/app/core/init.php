<?php
// Vérifier si APPROOT n'est pas déjà défini
if (!defined('APPROOT')) {
    // Charge d'abord le fichier de configuration qui définit les constantes
    require_once __DIR__ . '/../config/config.php';
}

// Vérifier à nouveau si APPROOT est défini, sinon le définir manuellement
if (!defined('APPROOT')) {
    define('APPROOT', realpath(dirname(__DIR__)));
}

// Charge les classes principales du core dans un ordre logique
// D'abord Database qui est utilisée par Model
require_once APPROOT . '/core/Database.php';

// Ensuite Model qui est utilisé par Controller
require_once APPROOT . '/core/Model.php';

// Puis Controller qui est utilisé par Router
require_once APPROOT . '/core/Controller.php';

// Les autres classes qui dépendent des précédentes
require_once APPROOT . '/core/Router.php';
require_once APPROOT . '/core/Session.php';
require_once APPROOT . '/core/Helper.php';
require_once APPROOT . '/core/Validator.php';
require_once APPROOT . '/core/App.php';

// Configurer l'autoloader
function autoload($className) {
    // Vérifier si le fichier existe dans le répertoire controllers
    if(file_exists(APPROOT . '/controllers/' . $className . '.php')){
        require_once APPROOT . '/controllers/' . $className . '.php';
    } 
    // Vérifier si le fichier existe dans le répertoire models
    elseif(file_exists(APPROOT . '/models/' . $className . '.php')){
        require_once APPROOT . '/models/' . $className . '.php';
    } 
    // Vérifier si le fichier existe dans le répertoire core
    elseif(file_exists(APPROOT . '/core/' . $className . '.php')){
        require_once APPROOT . '/core/' . $className . '.php';
    }
}

// Enregistrer l'autoloader
spl_autoload_register('autoload');

// Initialiser l'application
$app = new App();

// Démarrer l'application
$app->run(); 