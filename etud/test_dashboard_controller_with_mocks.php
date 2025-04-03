<?php
// Script de test pour DashboardController avec des modèles mock

// Définir les constantes nécessaires
define('APPROOT', __DIR__ . '/app');
define('URLROOT', 'http://localhost/etud');
define('SITENAME', 'StageFinder');

// Démarrer la session
session_start();

// Simuler un utilisateur connecté
$_SESSION['user_id'] = 1;
$_SESSION['user_email'] = 'test@example.com';

// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test de DashboardController avec des Mocks</h1>";

// Charger les classes de base
try {
    require_once APPROOT . '/core/Controller.php';
    echo "<p style='color:green'>✓ Controller.php chargé</p>";
    
    // Charger les modèles mock
    require_once __DIR__ . '/app/models/mock/StageModel.php';
    echo "<p style='color:green'>✓ Mock StageModel.php chargé</p>";
    
    require_once __DIR__ . '/app/models/mock/CandidatureModel.php';
    echo "<p style='color:green'>✓ Mock CandidatureModel.php chargé</p>";
    
    require_once __DIR__ . '/app/models/mock/EtudiantModel.php';
    echo "<p style='color:green'>✓ Mock EtudiantModel.php chargé</p>";
    
    // Charger le contrôleur à tester
    require_once APPROOT . '/controllers/DashboardController.php';
    echo "<p style='color:green'>✓ DashboardController.php chargé</p>";
    
    // Surcharger la classe DashboardController pour utiliser les modèles mock
    class TestDashboardController extends DashboardController {
        public function __construct() {
            // Ne pas appeler parent::__construct() pour éviter la redirection
            // Initialiser manuellement les modèles avec les mocks
            $this->stageModel = new StageModel();
            $this->candidatureModel = new CandidatureModel();
            $this->etudiantModel = new EtudiantModel();
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
        
        // Surcharger la méthode model pour simulation
        public function model($model) {
            // Les modèles sont déjà créés dans le constructeur, cette méthode n'est pas utilisée
            return null;
        }
    }
    
    // Instancier notre contrôleur de test
    $dashboardController = new TestDashboardController();
    echo "<p style='color:green'>✓ TestDashboardController instancié avec succès</p>";
    
    // Tester les méthodes du contrôleur
    echo "<h2>Test des méthodes</h2>";
    
    // Tester la méthode index
    echo "<h3>Test de la méthode index()</h3>";
    $dashboardController->index();
    
    // Tester la méthode stages
    echo "<h3>Test de la méthode stages()</h3>";
    $dashboardController->stages();
    
    // Tester la méthode stage avec un ID
    echo "<h3>Test de la méthode stage(1)</h3>";
    $dashboardController->stage(1);
    
    // Tester la méthode candidatures
    echo "<h3>Test de la méthode candidatures()</h3>";
    $dashboardController->candidatures();
    
    // Tester la méthode postuler avec un ID
    echo "<h3>Test de la méthode postuler(2) - Vérification du formulaire</h3>";
    $dashboardController->postuler(2);
    
    // Tester la méthode delete_candidature
    echo "<h3>Test de la méthode delete_candidature(1)</h3>";
    $dashboardController->delete_candidature(1);
    
    // Tester la méthode profil
    echo "<h3>Test de la méthode profil()</h3>";
    $dashboardController->profil();
    
} catch (Exception $e) {
    echo "<p style='color:red'>✗ Erreur: " . $e->getMessage() . "</p>";
    echo "<pre>";
    echo $e->getTraceAsString();
    echo "</pre>";
}

echo "<p>Tests terminés avec succès!</p>";
echo "<p><a href='test.php'>Retour au script de test principal</a></p>"; 