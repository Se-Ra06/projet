<?php
/**
 * Classe Controller de base
 * 
 * Cette classe sert de classe de base pour tous les contrôleurs de l'application.
 * Elle fournit des méthodes communes pour charger les modèles et les vues.
 */
class Controller {
    /**
     * Charge un modèle
     * 
     * @param string $model Le nom du modèle à charger
     * @return object L'instance du modèle
     */
    protected function model($model) {
        // Vérifie si le fichier du modèle existe
        if(file_exists(APPROOT . '/models/' . $model . '.php')) {
            // Charge le fichier du modèle
            require_once APPROOT . '/models/' . $model . '.php';
            
            // Instancie le modèle
            return new $model();
        } else {
            // Si le modèle n'existe pas, lance une exception
            throw new Exception('Le modèle ' . $model . ' n\'existe pas');
        }
    }
    
    /**
     * Charge une vue
     * 
     * @param string $view Le chemin de la vue à charger
     * @param array $data Les données à passer à la vue (optional)
     * @return void
     */
    protected function view($view, $data = []) {
        // Vérifie si le fichier de vue existe
        if(file_exists(APPROOT . '/views/' . $view . '.php')) {
            // Charge le fichier de vue
            require_once APPROOT . '/views/' . $view . '.php';
        } else {
            // Si la vue n'existe pas, lance une exception
            throw new Exception('La vue ' . $view . ' n\'existe pas');
        }
    }
    
    /**
     * Redirige vers une URL spécifique
     * 
     * @param string $url L'URL vers laquelle rediriger
     * @return void
     */
    protected function redirect($url) {
        header('Location: ' . URLROOT . '/' . $url);
        exit;
    }
    
    /**
     * Vérifie si la requête est de type POST
     * 
     * @return boolean True si la requête est de type POST, false sinon
     */
    protected function isPostRequest() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Obtient les données POST
     * 
     * @return array Les données POST
     */
    protected function getPostData() {
        return $_POST;
    }
    
    /**
     * Obtient les données GET
     * 
     * @return array Les données GET
     */
    protected function getGetData() {
        return $_GET;
    }
    
    /**
     * Nettoie les données d'entrée
     * 
     * @param mixed $data Les données à nettoyer
     * @return mixed Les données nettoyées
     */
    protected function sanitizeInput($data) {
        if(is_array($data)) {
            foreach($data as $key => $value) {
                $data[$key] = $this->sanitizeInput($value);
            }
        } else {
            $data = htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
        }
        
        return $data;
    }
    
    // Vérifier si l'utilisateur est connecté
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
} 