<?php
/**
 * Classe Model
 * 
 * Cette classe sert de classe de base pour tous les modèles de l'application.
 * Elle fournit des méthodes communes pour interagir avec la base de données.
 */
class Model {
    /**
     * L'instance de la base de données
     * @var Database
     */
    protected $db;
    
    /**
     * Le nom de la table associée au modèle
     * @var string
     */
    protected $table;
    
    /**
     * La clé primaire de la table
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * Constructeur
     */
    public function __construct() {
        // Initialiser la connexion à la base de données
        $this->db = new Database();
    }
    
    /**
     * Trouve tous les enregistrements
     * 
     * @return array Les enregistrements
     */
    public function findAll() {
        $this->db->query("SELECT * FROM {$this->table}");
        return $this->db->resultSet();
    }
    
    /**
     * Trouve un enregistrement par sa clé primaire
     * 
     * @param mixed $id La valeur de la clé primaire
     * @return object L'enregistrement
     */
    public function findById($id) {
        $this->db->query("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    /**
     * Trouve des enregistrements par une condition
     * 
     * @param string $column La colonne
     * @param mixed $value La valeur
     * @return array Les enregistrements
     */
    public function findBy($column, $value) {
        $this->db->query("SELECT * FROM {$this->table} WHERE {$column} = :value");
        $this->db->bind(':value', $value);
        return $this->db->resultSet();
    }
    
    /**
     * Trouve un enregistrement par une condition
     * 
     * @param string $column La colonne
     * @param mixed $value La valeur
     * @return object L'enregistrement
     */
    public function findOneBy($column, $value) {
        $this->db->query("SELECT * FROM {$this->table} WHERE {$column} = :value");
        $this->db->bind(':value', $value);
        return $this->db->single();
    }
    
    /**
     * Crée un nouvel enregistrement
     * 
     * @param array $data Les données à insérer
     * @return boolean True si l'insertion a réussi, false sinon
     */
    public function create($data) {
        // Construire la requête SQL
        $fields = array_keys($data);
        $fieldsString = implode(', ', $fields);
        $placeholders = ':' . implode(', :', $fields);
        
        $this->db->query("INSERT INTO {$this->table} ({$fieldsString}) VALUES ({$placeholders})");
        
        // Lier les valeurs aux paramètres
        foreach($data as $key => $value) {
            $this->db->bind(':' . $key, $value);
        }
        
        // Exécuter la requête
        if($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    /**
     * Met à jour un enregistrement
     * 
     * @param mixed $id L'ID de l'enregistrement à mettre à jour
     * @param array $data Les données à mettre à jour
     * @return boolean True si la mise à jour a réussi, false sinon
     */
    public function update($id, $data) {
        // Construire la requête SQL
        $fields = array_keys($data);
        $setClause = '';
        
        foreach($fields as $field) {
            $setClause .= "{$field} = :{$field}, ";
        }
        
        // Supprimer la virgule et l'espace en trop
        $setClause = rtrim($setClause, ', ');
        
        $this->db->query("UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id");
        
        // Lier les valeurs aux paramètres
        $this->db->bind(':id', $id);
        
        foreach($data as $key => $value) {
            $this->db->bind(':' . $key, $value);
        }
        
        // Exécuter la requête
        return $this->db->execute();
    }
    
    /**
     * Supprime un enregistrement
     * 
     * @param mixed $id L'ID de l'enregistrement à supprimer
     * @return boolean True si la suppression a réussi, false sinon
     */
    public function delete($id) {
        $this->db->query("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    
    /**
     * Compte le nombre d'enregistrements
     * 
     * @return int Le nombre d'enregistrements
     */
    public function count() {
        $this->db->query("SELECT COUNT(*) as count FROM {$this->table}");
        $result = $this->db->single();
        return $result->count;
    }
    
    /**
     * Exécute une requête SQL personnalisée
     * 
     * @param string $sql La requête SQL
     * @param array $params Les paramètres de la requête
     * @return mixed Le résultat de la requête
     */
    public function customQuery($sql, $params = []) {
        $this->db->query($sql);
        
        // Lier les valeurs aux paramètres
        foreach($params as $key => $value) {
            $this->db->bind(':' . $key, $value);
        }
        
        // Exécuter la requête
        if($this->db->execute()) {
            if(stripos($sql, 'SELECT') === 0) {
                if(stripos($sql, 'LIMIT 1') !== false) {
                    return $this->db->single();
                } else {
                    return $this->db->resultSet();
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
} 