<?php
/**
 * Classe Helper
 * 
 * Cette classe contient des fonctions utilitaires pour l'application.
 */
class Helper {
    /**
     * Sanitize les données pour éviter les attaques XSS
     * 
     * @param mixed $data Les données à sanitizer
     * @return mixed Les données sanitizées
     */
    public static function sanitize($data) {
        if(is_array($data)) {
            foreach($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
        
        return $data;
    }
    
    /**
     * Vérifie si une chaîne est vide
     * 
     * @param string $string La chaîne à vérifier
     * @return boolean True si la chaîne est vide, false sinon
     */
    public static function isEmpty($string) {
        return empty(trim($string));
    }
    
    /**
     * Vérifie si une chaîne est un email valide
     * 
     * @param string $email L'email à vérifier
     * @return boolean True si l'email est valide, false sinon
     */
    public static function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * Génère un nom de fichier unique
     * 
     * @param string $extension L'extension du fichier
     * @return string Le nom de fichier unique
     */
    public static function generateUniqueFilename($extension) {
        return uniqid() . '.' . $extension;
    }
    
    /**
     * Formate une date
     * 
     * @param string $date La date à formater
     * @param string $format Le format de la date (par défaut: d/m/Y)
     * @return string La date formatée
     */
    public static function formatDate($date, $format = 'd/m/Y') {
        $dateObj = new DateTime($date);
        return $dateObj->format($format);
    }
    
    /**
     * Tronque une chaîne de caractères
     * 
     * @param string $string La chaîne à tronquer
     * @param int $length La longueur maximale
     * @param string $append La chaîne à ajouter à la fin (par défaut: ...)
     * @return string La chaîne tronquée
     */
    public static function truncate($string, $length, $append = '...') {
        if(strlen($string) > $length) {
            $string = substr($string, 0, $length) . $append;
        }
        
        return $string;
    }
    
    /**
     * Génère un token aléatoire
     * 
     * @param int $length La longueur du token (par défaut: 32)
     * @return string Le token
     */
    public static function generateToken($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * Vérifie si une requête est une requête AJAX
     * 
     * @return boolean True si c'est une requête AJAX, false sinon
     */
    public static function isAjaxRequest() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Redirige vers une URL
     * 
     * @param string $url L'URL de redirection
     * @return void
     */
    public static function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * Obtient l'extension d'un fichier
     * 
     * @param string $filename Le nom du fichier
     * @return string L'extension du fichier
     */
    public static function getFileExtension($filename) {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }
    
    /**
     * Génère un slug à partir d'une chaîne
     * 
     * @param string $string La chaîne à transformer en slug
     * @return string Le slug
     */
    public static function slugify($string) {
        // Remplacer les caractères non alphanumériques par des tirets
        $string = preg_replace('/[^\p{L}\p{N}]+/u', '-', $string);
        // Convertir en minuscules
        $string = mb_strtolower($string, 'UTF-8');
        // Supprimer les tirets en début et fin de chaîne
        $string = trim($string, '-');
        
        return $string;
    }
    
    /**
     * Formate un nombre
     * 
     * @param float $number Le nombre à formater
     * @param int $decimals Le nombre de décimales
     * @return string Le nombre formaté
     */
    public static function formatNumber($number, $decimals = 2) {
        return number_format($number, $decimals, ',', ' ');
    }
    
    /**
     * Vérifie si une date est valide
     * 
     * @param string $date La date à vérifier
     * @param string $format Le format de la date (par défaut: Y-m-d)
     * @return boolean True si la date est valide, false sinon
     */
    public static function isValidDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
} 