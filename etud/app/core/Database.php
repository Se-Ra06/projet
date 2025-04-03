<?php
/**
 * Classe Database
 * 
 * Cette classe gère la connexion à la base de données et les opérations CRUD.
 * Elle utilise PDO pour se connecter à la base de données.
 */
class Database {
    /**
     * L'instance de PDO
     * @var PDO
     */
    private $dbh;
    
    /**
     * L'objet statement
     * @var PDOStatement
     */
    private $stmt;
    
    /**
     * Les options de PDO
     * @var array
     */
    private $options = [
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES => false
    ];
    
    /**
     * Constructeur - Se connecte à la base de données
     */
    public function __construct() {
        // Construire le DSN
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
        
        try {
            // Créer une instance de PDO
            $this->dbh = new PDO($dsn, DB_USER, DB_PASS, $this->options);
        } catch(PDOException $e) {
            // Afficher l'erreur et arrêter le script
            error_log('Erreur de connexion à la base de données: ' . $e->getMessage());
            die('Erreur de connexion à la base de données: ' . $e->getMessage());
        }
    }
    
    /**
     * Prépare une requête SQL
     * 
     * @param string $sql La requête SQL à préparer
     * @return void
     */
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }
    
    /**
     * Lie une valeur à un paramètre
     * 
     * @param mixed $param Le paramètre à lier
     * @param mixed $value La valeur à lier
     * @param mixed $type Le type de la valeur (optionnel)
     * @return void
     */
    public function bind($param, $value, $type = null) {
        // Si le type n'est pas spécifié, déterminer le type
        if(is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        
        // Lier la valeur au paramètre
        $this->stmt->bindValue($param, $value, $type);
    }
    
    /**
     * Exécute la requête préparée
     * 
     * @return boolean True si la requête a réussi, false sinon
     */
    public function execute() {
        return $this->stmt->execute();
    }
    
    /**
     * Récupère un enregistrement
     * 
     * @return object L'enregistrement comme objet
     */
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }
    
    /**
     * Récupère tous les enregistrements
     * 
     * @return array Les enregistrements comme tableau d'objets
     */
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }
    
    /**
     * Récupère le nombre de lignes affectées par la dernière requête
     * 
     * @return int Le nombre de lignes
     */
    public function rowCount() {
        return $this->stmt->rowCount();
    }
    
    /**
     * Récupère le dernier ID inséré
     * 
     * @return string Le dernier ID inséré
     */
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
    
    /**
     * Démarre une transaction
     * 
     * @return boolean True si la transaction a démarré, false sinon
     */
    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }
    
    /**
     * Valide une transaction
     * 
     * @return boolean True si la transaction a été validée, false sinon
     */
    public function commit() {
        return $this->dbh->commit();
    }
    
    /**
     * Annule une transaction
     * 
     * @return boolean True si la transaction a été annulée, false sinon
     */
    public function rollBack() {
        return $this->dbh->rollBack();
    }
} 