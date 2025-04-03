<?php
/**
 * Classe App
 * 
 * Cette classe sert de point d'entrée principal pour l'application.
 * Elle charge les composants nécessaires et gère le flux de l'application.
 */
class App {
    /**
     * L'instance du routeur
     * @var Router
     */
    private $router;
    
    /**
     * L'instance de session
     * @var Session
     */
    private $session;
    
    /**
     * Constructeur
     */
    public function __construct() {
        // Initialiser la session
        $this->session = new Session();
        
        // Initialiser le routeur
        $this->router = new Router();
    }
    
    /**
     * Démarre l'application
     */
    public function run() {
        // Résoudre la route
        $this->router->resolve();
    }
    
    /**
     * Charge un modèle
     * 
     * @param string $model Le nom du modèle à charger
     * @return object L'instance du modèle
     */
    public static function loadModel($model) {
        // Vérifier si le modèle existe
        $modelFile = APPROOT . '/models/' . $model . '.php';
        
        if(file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        } else {
            throw new Exception("Le modèle $model n'existe pas");
        }
    }
    
    /**
     * Charge une vue
     * 
     * @param string $view Le nom de la vue à charger
     * @param array $data Les données à passer à la vue
     * @return void
     */
    public static function loadView($view, $data = []) {
        // Extraire les données pour les rendre accessibles dans la vue
        extract($data);
        
        // Vérifier si la vue existe
        $viewFile = APPROOT . '/views/' . $view . '.php';
        
        if(file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            throw new Exception("La vue $view n'existe pas");
        }
    }
    
    /**
     * Redirige vers une URL
     * 
     * @param string $url L'URL de redirection
     * @return void
     */
    public static function redirect($url) {
        header('Location: ' . URLROOT . '/' . $url);
        exit;
    }
    
    /**
     * Vérifie si l'utilisateur est connecté
     * 
     * @return boolean True si l'utilisateur est connecté, false sinon
     */
    public static function isLoggedIn() {
        if(isset($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Génère un token CSRF
     * 
     * @return string Le token CSRF
     */
    public static function generateCsrfToken() {
        if(!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Vérifie le token CSRF
     * 
     * @param string $token Le token à vérifier
     * @return boolean True si le token est valide, false sinon
     */
    public static function checkCsrfToken($token) {
        if(isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token) {
            return true;
        } else {
            return false;
        }
    }
} 