<?php
// IMPORTANT: Ce script est uniquement à des fins de test et de débogage
// Ne pas utiliser en production

// Définir les constantes de chemin
define('APPROOT', __DIR__ . '/app');
define('URLROOT', 'http://localhost/etud');
define('SITENAME', 'StageFinder');

// Définir les constantes de base de données (ces valeurs doivent correspondre à celles de votre fichier config.php)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'website');

// Démarrer la session
session_start();

// Afficher l'information de base
echo "<h1>Test de l'application StageFinder</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>APPROOT: " . APPROOT . "</p>";
echo "<p>DB_HOST: " . DB_HOST . "</p>";
echo "<p>DB_NAME: " . DB_NAME . "</p>";

// Fonction pour vérifier si un fichier existe et est lisible
function checkFile($path) {
    if(file_exists($path) && is_readable($path)) {
        echo "<span style='color:green'>✓</span> Le fichier existe et est lisible: $path<br>";
        return true;
    } else {
        echo "<span style='color:red'>✗</span> Le fichier n'existe pas ou n'est pas lisible: $path<br>";
        return false;
    }
}

// Vérifier les fichiers clés
echo "<h2>Vérification des fichiers clés</h2>";
$configFile = APPROOT . '/config/config.php';
$databaseFile = APPROOT . '/core/Database.php';
$modelFile = APPROOT . '/core/Model.php';
$controllerFile = APPROOT . '/core/Controller.php';
$routerFile = APPROOT . '/core/Router.php';
$stageModelFile = APPROOT . '/models/StageModel.php';
$dashboardControllerFile = APPROOT . '/controllers/DashboardController.php';

checkFile($configFile);
checkFile($databaseFile);
checkFile($modelFile);
checkFile($controllerFile);
checkFile($routerFile);
checkFile($stageModelFile);
checkFile($dashboardControllerFile);

// Charger les fichiers de base dans le bon ordre
echo "<h2>Chargement des fichiers principaux</h2>";
try {
    // Charger la configuration d'abord
    // require_once $configFile; // Nous avons déjà défini les constantes manuellement
    
    // Puis les classes de base
    require_once $databaseFile;
    echo "<span style='color:green'>✓</span> Database.php chargé avec succès<br>";
    
    require_once $modelFile;
    echo "<span style='color:green'>✓</span> Model.php chargé avec succès<br>";
    
    require_once $controllerFile;
    echo "<span style='color:green'>✓</span> Controller.php chargé avec succès<br>";
    
    require_once $routerFile;
    echo "<span style='color:green'>✓</span> Router.php chargé avec succès<br>";
} catch(Exception $e) {
    echo "<span style='color:red'>✗</span> Erreur lors du chargement des fichiers principaux: " . $e->getMessage() . "<br>";
}

// Vérifier les classes chargées
echo "<h2>Classes chargées</h2>";
echo "<pre>";
$classes = get_declared_classes();
$frameworkClasses = array_filter($classes, function($class) {
    return $class == 'Controller' || $class == 'Model' || $class == 'Database' || $class == 'Router';
});
print_r($frameworkClasses);
echo "</pre>";

// Tester la connexion à la base de données
echo "<h2>Test de connexion à la base de données</h2>";
try {
    $db = new Database();
    echo "<span style='color:green'>✓</span> Connexion à la base de données réussie<br>";
} catch(Exception $e) {
    echo "<span style='color:red'>✗</span> Erreur de connexion à la base de données: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// Tester le chargement de StageModel
echo "<h2>Test de chargement de StageModel</h2>";
try {
    require_once $stageModelFile;
    if(class_exists('StageModel')) {
        echo "<span style='color:green'>✓</span> StageModel chargé avec succès<br>";
    } else {
        echo "<span style='color:red'>✗</span> StageModel n'existe pas après chargement<br>";
    }
} catch(Exception $e) {
    echo "<span style='color:red'>✗</span> Erreur lors du chargement de StageModel: " . $e->getMessage() . "<br>";
}

// Tester le chargement de DashboardController
echo "<h2>Test de chargement de DashboardController</h2>";
try {
    require_once $dashboardControllerFile;
    if(class_exists('DashboardController')) {
        echo "<span style='color:green'>✓</span> DashboardController chargé avec succès<br>";
    } else {
        echo "<span style='color:red'>✗</span> DashboardController n'existe pas après chargement<br>";
    }
} catch(Exception $e) {
    echo "<span style='color:red'>✗</span> Erreur lors du chargement de DashboardController: " . $e->getMessage() . "<br>";
}

// Tester l'instanciation d'un StageModel
echo "<h2>Test d'instanciation de StageModel</h2>";
try {
    if(class_exists('StageModel')) {
        $stageModel = new StageModel();
        echo "<span style='color:green'>✓</span> StageModel instancié avec succès<br>";
    }
} catch(Exception $e) {
    echo "<span style='color:red'>✗</span> Erreur lors de l'instanciation de StageModel: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// Tester l'instanciation d'un contrôleur
echo "<h2>Test d'instanciation de DashboardController</h2>";
try {
    if(class_exists('DashboardController')) {
        $_SESSION['user_id'] = 1; // Simuler une connexion utilisateur
        $dashboardController = new DashboardController();
        echo "<span style='color:green'>✓</span> DashboardController instancié avec succès<br>";
    }
} catch(Exception $e) {
    echo "<span style='color:red'>✗</span> Erreur lors de l'instanciation de DashboardController: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>Conclusion</h2>";
echo "<p>Vérifiez les résultats ci-dessus pour identifier la source du problème.</p>";

// Ajouter des liens vers des tests spécifiques
echo "<h2>Tests supplémentaires disponibles</h2>";
echo "<div style='background-color: #f8f8f8; padding: 10px; border-left: 3px solid #007bff;'>";
echo "<p>Ces tests vous permettent de vérifier des composants spécifiques de l'application:</p>";
echo "<ul>";
echo "<li><a href='test_dashboard_controller.php'>Test du DashboardController</a> - Teste les méthodes du contrôleur avec la base de données réelle</li>";
echo "<li><a href='test_dashboard_controller_with_mocks.php'>Test du DashboardController avec Mocks</a> - Teste les méthodes du contrôleur avec des modèles simulés</li>";
echo "<li><a href='test_stage_model.php'>Test du StageModel</a> - Teste les méthodes de récupération des stages</li>";
echo "<li><a href='test_candidature_model.php'>Test du CandidatureModel</a> - Teste les méthodes de gestion des candidatures</li>";
echo "<li><a href='test_etudiant_model.php'>Test du EtudiantModel</a> - Teste les méthodes de gestion des étudiants</li>";
echo "</ul>";
echo "</div>";

echo "<p>Pour accéder à l'application, utilisez: <a href='" . URLROOT . "'>Accéder à l'application</a></p>"; 