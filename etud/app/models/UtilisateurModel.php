<?php
/**
 * Modèle Utilisateur
 * 
 * Ce modèle gère les opérations liées aux utilisateurs.
 */
class UtilisateurModel extends Model {
    /**
     * Nom de la table associée
     * @var string
     */
    protected $table = 'user';
    
    /**
     * Clé primaire de la table
     * @var string
     */
    protected $primaryKey = 'id_user';
    
    /**
     * Constructeur
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Récupère un utilisateur par son email
     * 
     * @param string $email L'email de l'utilisateur
     * @return object|false L'utilisateur trouvé ou false
     */
    public function findByEmail($email) {
        return $this->findBy('email', $email);
    }
    
    /**
     * Authentifie un utilisateur
     * 
     * @param string $email L'email de l'utilisateur
     * @param string $password Le mot de passe en clair
     * @return object|false L'utilisateur authentifié ou false
     */
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return false;
        }
        
        if (password_verify($password, $user->password)) {
            return $user;
        }
        
        return false;
    }
    
    /**
     * Crée un nouvel utilisateur
     * 
     * @param array $data Les données de l'utilisateur
     * @return int|boolean L'ID de l'utilisateur créé ou false en cas d'échec
     */
    public function createUser($data) {
        // Hasher le mot de passe
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Ajouter la date de création
        $data['created_at'] = date('Y-m-d H:i:s');
        
        return $this->create($data);
    }
    
    /**
     * Met à jour un utilisateur
     * 
     * @param int $id L'ID de l'utilisateur
     * @param array $data Les données à mettre à jour
     * @return boolean True si la mise à jour a réussi, false sinon
     */
    public function updateUser($id, $data) {
        // Si le mot de passe est présent et non vide, le hasher
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            // Sinon, le supprimer pour ne pas l'écraser avec une chaîne vide
            unset($data['password']);
        }
        
        // Ajouter la date de mise à jour
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->update($id, $data);
    }
    
    /**
     * Récupère un étudiant avec ses détails
     * 
     * @param int $userId L'ID de l'utilisateur
     * @return object|false L'étudiant avec ses détails ou false
     */
    public function getEtudiantDetails($userId) {
        $sql = "SELECT u.*, s.*
                FROM {$this->table} u
                JOIN student s ON u.id_user = s.id_user
                WHERE u.id_user = :user_id";
        
        $result = $this->customQuery($sql, ['user_id' => $userId]);
        
        if (empty($result)) {
            return false;
        }
        
        return $result[0];
    }
    
    /**
     * Récupère un pilote avec ses détails
     * 
     * @param int $userId L'ID de l'utilisateur
     * @return object|false Le pilote avec ses détails ou false
     */
    public function getPiloteDetails($userId) {
        $sql = "SELECT u.*, p.*
                FROM {$this->table} u
                JOIN pilote p ON u.id_user = p.id_user
                WHERE u.id_user = :user_id";
        
        $result = $this->customQuery($sql, ['user_id' => $userId]);
        
        if (empty($result)) {
            return false;
        }
        
        return $result[0];
    }
} 