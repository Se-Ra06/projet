<?php
// Script de test pour DashboardController

// Définir les constantes nécessaires
define('APPROOT', __DIR__ . '/app');
define('URLROOT', 'http://localhost/etud');
define('SITENAME', 'StageFinder');

// Définir les constantes de base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'website');

// Démarrer la session
session_start();

// Simuler un utilisateur connecté
$_SESSION['user_id'] = 1;
$_SESSION['user_email'] = 'test@example.com';

// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test de DashboardController</h1>";

// Charger les classes dans le bon ordre
try {
    // D'abord les classes de base
    require_once APPROOT . '/core/Database.php';
    echo "<p style='color:green'>✓ Database.php chargé</p>";
    
    require_once APPROOT . '/core/Model.php';
    echo "<p style='color:green'>✓ Model.php chargé</p>";
    
    require_once APPROOT . '/core/Controller.php';
    echo "<p style='color:green'>✓ Controller.php chargé</p>";
    
    // Puis les modèles nécessaires
    require_once APPROOT . '/models/StageModel.php';
    echo "<p style='color:green'>✓ StageModel.php chargé</p>";
    
    require_once APPROOT . '/models/CandidatureModel.php';
    echo "<p style='color:green'>✓ CandidatureModel.php chargé</p>";
    
    require_once APPROOT . '/models/EtudiantModel.php';
    echo "<p style='color:green'>✓ EtudiantModel.php chargé</p>";
    
    // Enfin, charger DashboardController
    require_once APPROOT . '/controllers/DashboardController.php';
    echo "<p style='color:green'>✓ DashboardController.php chargé</p>";
    
    // Créer une classe pour capturer la sortie au lieu de l'afficher directement
    class OutputCapture extends Controller {
        public function view($view, $data = []) {
            echo "<div style='background-color: #f8f9fa; padding: 15px; border: 1px solid #ddd; margin-bottom: 20px;'>";
            echo "<h3>Vue chargée: $view</h3>";
            echo "<h4>Données passées à la vue:</h4>";
            echo "<pre>";
            print_r($data);
            echo "</pre>";
            echo "</div>";
        }
    }
    
    // Surcharger la classe DashboardController pour utiliser notre capture de sortie
    class TestDashboardController extends DashboardController {
        public function __construct() {
            // Ne pas appeler parent::__construct() pour éviter la redirection
            // car parent::__construct() vérifie si l'utilisateur est connecté
            
            // Simuler la connexion de l'utilisateur
            $_SESSION['user_id'] = 1;
            $_SESSION['user_email'] = 'test@example.com'; // Ajouter un email pour les tests
            
            // Initialiser manuellement les modèles
            $this->stageModel = $this->model('StageModel');
            $this->candidatureModel = $this->model('CandidatureModel');
            $this->etudiantModel = $this->model('EtudiantModel');
        }
        
        // Implémenter la méthode model() pour charger les modèles
        public function model($model) {
            // Créer une instance du modèle
            $modelClass = $model;
            if (class_exists($modelClass)) {
                return new $modelClass();
            } else {
                die("Modèle '$model' non trouvé");
            }
        }
        
        // Surcharger la méthode view pour capturer la sortie
        public function view($view, $data = []) {
            echo "<div style='background-color: #f8f9fa; padding: 15px; border: 1px solid #ddd; margin-bottom: 20px;'>";
            echo "<h3>Vue chargée: $view</h3>";
            echo "<h4>Données passées à la vue:</h4>";
            echo "<pre>";
            print_r($data);
            echo "</pre>";
            echo "</div>";
        }
        
        // Surcharger la méthode redirect pour éviter la redirection
        public function redirect($url) {
            echo "<p style='color:blue'>Redirection vers: " . URLROOT . "/$url</p>";
        }
    }
    
    // Instancier notre contrôleur de test
    $dashboardController = new TestDashboardController();
    echo "<p style='color:green'>✓ DashboardController instancié avec succès</p>";
    
    // Tester les méthodes du contrôleur
    echo "<h2>Test des méthodes</h2>";
    
    // Tester la méthode index
    echo "<h3>Test de la méthode index()</h3>";
    $dashboardController->index();
    
    // Tester la méthode stages
    echo "<h3>Test de la méthode stages()</h3>";
    $dashboardController->stages();
    
    // Tester la méthode candidatures
    echo "<h3>Test de la méthode candidatures()</h3>";
    $dashboardController->candidatures();
    
    // Tester la méthode profil
    echo "<h3>Test de la méthode profil()</h3>";
    $dashboardController->profil();
    
} catch (Exception $e) {
    echo "<p style='color:red'>✗ Erreur: " . $e->getMessage() . "</p>";
    echo "<pre>";
    echo $e->getTraceAsString();
    echo "</pre>";
}

echo "<p><a href='test.php'>Retour au script de test principal</a></p>";
?> 