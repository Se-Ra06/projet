<?php
/**
 * Classe Session
 * 
 * Cette classe gère les sessions utilisateur et les messages flash.
 */
class Session {
    /**
     * Constructeur
     */
    public function __construct() {
        // Démarrer la session si elle n'est pas déjà démarrée
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Définit un message flash
     * 
     * @param string $name Le nom du message
     * @param string $message Le contenu du message
     * @param string $class La classe CSS du message (success, danger, warning, info)
     * @return void
     */
    public function setFlash($name, $message, $class = 'success') {
        // Structure du message flash
        $_SESSION['flash'][$name] = [
            'message' => $message,
            'class' => $class
        ];
    }
    
    /**
     * Vérifie si un message flash existe
     * 
     * @param string $name Le nom du message
     * @return boolean True si le message existe, false sinon
     */
    public function hasFlash($name) {
        return isset($_SESSION['flash'][$name]);
    }
    
    /**
     * Obtient un message flash et le supprime de la session
     * 
     * @param string $name Le nom du message
     * @return mixed Le message flash ou null si le message n'existe pas
     */
    public function getFlash($name) {
        if($this->hasFlash($name)) {
            $flash = $_SESSION['flash'][$name];
            unset($_SESSION['flash'][$name]);
            return $flash;
        }
        
        return null;
    }
    
    /**
     * Définit une variable de session
     * 
     * @param string $key La clé
     * @param mixed $value La valeur
     * @return void
     */
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Obtient une variable de session
     * 
     * @param string $key La clé
     * @return mixed La valeur ou null si la clé n'existe pas
     */
    public function get($key) {
        if(isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        
        return null;
    }
    
    /**
     * Supprime une variable de session
     * 
     * @param string $key La clé
     * @return void
     */
    public function remove($key) {
        if(isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Vérifie si une variable de session existe
     * 
     * @param string $key La clé
     * @return boolean True si la variable existe, false sinon
     */
    public function has($key) {
        return isset($_SESSION[$key]);
    }
    
    /**
     * Détruit la session
     * 
     * @return void
     */
    public function destroy() {
        session_unset();
        session_destroy();
    }
    
    /**
     * Obtient l'utilisateur courant si connecté
     * 
     * @return mixed L'utilisateur courant ou null si non connecté
     */
    public function getCurrentUser() {
        if(isset($_SESSION['user_id'])) {
            // Charger l'utilisateur depuis la base de données
            $userModel = new UserModel();
            return $userModel->findById($_SESSION['user_id']);
        }
        
        return null;
    }
    
    /**
     * Vérifie si l'utilisateur est connecté
     * 
     * @return boolean True si l'utilisateur est connecté, false sinon
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Vérifie si l'utilisateur a un rôle spécifique
     * 
     * @param string|array $roles Le(s) rôle(s) à vérifier
     * @return boolean True si l'utilisateur a le rôle, false sinon
     */
    public function hasRole($roles) {
        if(!$this->isLoggedIn()) {
            return false;
        }
        
        // Convertir en tableau si ce n'est pas déjà le cas
        if(!is_array($roles)) {
            $roles = [$roles];
        }
        
        return in_array($_SESSION['user_role'], $roles);
    }
} 