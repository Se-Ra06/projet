<?php
/**
 * Classe Validator
 * 
 * Cette classe gère la validation des données de formulaires.
 */
class Validator {
    /**
     * Les erreurs de validation
     * @var array
     */
    private $errors = [];
    
    /**
     * Les règles de validation
     * @var array
     */
    private $rules = [];
    
    /**
     * Les données à valider
     * @var array
     */
    private $data = [];
    
    /**
     * Constructeur
     * 
     * @param array $data Les données à valider
     */
    public function __construct($data = []) {
        $this->data = $data;
    }
    
    /**
     * Définit les données à valider
     * 
     * @param array $data Les données à valider
     * @return Validator
     */
    public function setData($data) {
        $this->data = $data;
        return $this;
    }
    
    /**
     * Ajoute une règle de validation
     * 
     * @param string $field Le champ à valider
     * @param string $rule La règle de validation
     * @param string $message Le message d'erreur
     * @param mixed $param Le paramètre de la règle (optionnel)
     * @return Validator
     */
    public function addRule($field, $rule, $message, $param = null) {
        $this->rules[] = [
            'field' => $field,
            'rule' => $rule,
            'message' => $message,
            'param' => $param
        ];
        
        return $this;
    }
    
    /**
     * Vérifie si les données sont valides
     * 
     * @return boolean True si les données sont valides, false sinon
     */
    public function validate() {
        $this->errors = [];
        
        foreach($this->rules as $rule) {
            $field = $rule['field'];
            $ruleMethod = $rule['rule'];
            $message = $rule['message'];
            $param = $rule['param'];
            
            // Si le champ n'existe pas dans les données
            if(!isset($this->data[$field]) && $ruleMethod !== 'required') {
                continue;
            }
            
            // Récupérer la valeur du champ
            $value = isset($this->data[$field]) ? $this->data[$field] : '';
            
            // Appliquer la règle de validation
            if(method_exists($this, $ruleMethod)) {
                $result = $this->$ruleMethod($value, $param);
                
                if(!$result) {
                    $this->errors[$field] = $message;
                }
            }
        }
        
        return empty($this->errors);
    }
    
    /**
     * Retourne les erreurs de validation
     * 
     * @return array Les erreurs de validation
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Retourne l'erreur d'un champ spécifique
     * 
     * @param string $field Le champ
     * @return string|null L'erreur ou null si aucune erreur
     */
    public function getError($field) {
        return isset($this->errors[$field]) ? $this->errors[$field] : null;
    }
    
    /**
     * Vérifie si un champ a une erreur
     * 
     * @param string $field Le champ
     * @return boolean True si le champ a une erreur, false sinon
     */
    public function hasError($field) {
        return isset($this->errors[$field]);
    }
    
    /**
     * Vérifie si un champ est requis
     * 
     * @param mixed $value La valeur du champ
     * @return boolean True si la valeur n'est pas vide, false sinon
     */
    private function required($value) {
        if(is_array($value)) {
            return !empty($value);
        }
        
        return !empty(trim($value));
    }
    
    /**
     * Vérifie si un champ est un email valide
     * 
     * @param string $value La valeur du champ
     * @return boolean True si la valeur est un email valide, false sinon
     */
    private function email($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * Vérifie si un champ est une URL valide
     * 
     * @param string $value La valeur du champ
     * @return boolean True si la valeur est une URL valide, false sinon
     */
    private function url($value) {
        return filter_var($value, FILTER_VALIDATE_URL);
    }
    
    /**
     * Vérifie si un champ a une longueur minimale
     * 
     * @param string $value La valeur du champ
     * @param int $length La longueur minimale
     * @return boolean True si la valeur a la longueur minimale, false sinon
     */
    private function minLength($value, $length) {
        return strlen(trim($value)) >= $length;
    }
    
    /**
     * Vérifie si un champ a une longueur maximale
     * 
     * @param string $value La valeur du champ
     * @param int $length La longueur maximale
     * @return boolean True si la valeur a la longueur maximale, false sinon
     */
    private function maxLength($value, $length) {
        return strlen(trim($value)) <= $length;
    }
    
    /**
     * Vérifie si un champ est égal à une autre valeur
     * 
     * @param string $value La valeur du champ
     * @param string $fieldName Le nom du champ à comparer
     * @return boolean True si les valeurs sont égales, false sinon
     */
    private function matches($value, $fieldName) {
        return isset($this->data[$fieldName]) && $value === $this->data[$fieldName];
    }
    
    /**
     * Vérifie si un champ est unique dans la base de données
     * 
     * @param string $value La valeur du champ
     * @param array $params Les paramètres (table, column, except)
     * @return boolean True si la valeur est unique, false sinon
     */
    private function unique($value, $params) {
        $table = $params['table'];
        $column = $params['column'];
        $except = isset($params['except']) ? $params['except'] : null;
        
        $db = new Database();
        
        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = :value";
        $bindings = [':value' => $value];
        
        if($except) {
            $sql .= " AND id != :except";
            $bindings[':except'] = $except;
        }
        
        $db->query($sql);
        
        foreach($bindings as $key => $value) {
            $db->bind($key, $value);
        }
        
        $result = $db->single();
        
        return $result->count === 0;
    }
    
    /**
     * Vérifie si un champ est numérique
     * 
     * @param mixed $value La valeur du champ
     * @return boolean True si la valeur est numérique, false sinon
     */
    private function numeric($value) {
        return is_numeric($value);
    }
    
    /**
     * Vérifie si un champ est un entier
     * 
     * @param mixed $value La valeur du champ
     * @return boolean True si la valeur est un entier, false sinon
     */
    private function integer($value) {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }
    
    /**
     * Vérifie si un champ est un flottant
     * 
     * @param mixed $value La valeur du champ
     * @return boolean True si la valeur est un flottant, false sinon
     */
    private function float($value) {
        return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
    }
    
    /**
     * Vérifie si un champ est inclus dans une liste de valeurs
     * 
     * @param mixed $value La valeur du champ
     * @param array $values Les valeurs autorisées
     * @return boolean True si la valeur est incluse, false sinon
     */
    private function inList($value, $values) {
        return in_array($value, $values);
    }
    
    /**
     * Vérifie si un champ correspond à un motif regex
     * 
     * @param string $value La valeur du champ
     * @param string $pattern Le motif regex
     * @return boolean True si la valeur correspond au motif, false sinon
     */
    private function pattern($value, $pattern) {
        return preg_match($pattern, $value);
    }
    
    /**
     * Vérifie si un champ est une date valide
     * 
     * @param string $value La valeur du champ
     * @param string $format Le format de la date
     * @return boolean True si la valeur est une date valide, false sinon
     */
    private function date($value, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $value);
        return $d && $d->format($format) === $value;
    }
} 