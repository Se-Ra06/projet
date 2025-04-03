<?php
// Définir les constantes nécessaires
define('APPROOT', realpath(__DIR__ . '/app'));
define('URLROOT', 'http://localhost/etud');
define('SITENAME', 'StageFinder');

// Définir les constantes de base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'website');

// Charger la classe Database d'abord
require_once APPROOT . '/core/Database.php';

// Puis la classe Model qui dépend de Database
require_once APPROOT . '/core/Model.php';

// Ensuite la classe Controller qui peut dépendre de Model
require_once APPROOT . '/core/Controller.php';

// Vérifier si les classes sont chargées
if (class_exists('Controller') && class_exists('Model') && class_exists('Database')) {
    echo "Les classes de base (Controller, Model, Database) existent et sont chargées.<br>";
    
    // Charger le StageModel
    require_once APPROOT . '/models/StageModel.php';
    
    // Charger le CandidatureModel
    require_once APPROOT . '/models/CandidatureModel.php';
    
    // Charger le DashboardController
    require_once APPROOT . '/controllers/DashboardController.php';
    
    // Vérifier si le DashboardController existe
    if (class_exists('DashboardController')) {
        echo "La classe DashboardController existe et est chargée.<br>";
        
        // Créer une instance du DashboardController
        try {
            session_start();
            $_SESSION['user_id'] = 1; // Simuler une connexion
            $dashboardController = new DashboardController();
            echo "Le DashboardController a été instancié avec succès.<br>";
        } catch (Exception $e) {
            echo "Erreur lors de l'instanciation du DashboardController: " . $e->getMessage() . "<br>";
            echo "Trace : <pre>" . $e->getTraceAsString() . "</pre>";
        }
    } else {
        echo "La classe DashboardController n'existe pas.<br>";
    }
} else {
    if (!class_exists('Controller')) {
        echo "La classe Controller n'existe pas.<br>";
    }
    if (!class_exists('Model')) {
        echo "La classe Model n'existe pas.<br>";
    }
    if (!class_exists('Database')) {
        echo "La classe Database n'existe pas.<br>";
    }
}

// Afficher des informations supplémentaires
echo "<h2>Informations supplémentaires :</h2>";
echo "APPROOT: " . APPROOT . "<br>";
echo "DB_HOST: " . DB_HOST . "<br>";
echo "DB_NAME: " . DB_NAME . "<br>";

// Contenu des fichiers
echo "<h3>Contenu du fichier Controller.php :</h3>";
echo "<pre>";
echo htmlspecialchars(file_get_contents(APPROOT . '/core/Controller.php'));
echo "</pre>";

echo "<h3>Contenu du fichier Model.php :</h3>";
echo "<pre>";
echo htmlspecialchars(file_get_contents(APPROOT . '/core/Model.php'));
echo "</pre>";

echo "<h3>Contenu du fichier Database.php :</h3>";
echo "<pre>";
echo htmlspecialchars(file_get_contents(APPROOT . '/core/Database.php'));
echo "</pre>";

// Lister les classes disponibles
echo "<h2>Classes disponibles :</h2>";
echo "<pre>";
print_r(get_declared_classes());
echo "</pre>"; 