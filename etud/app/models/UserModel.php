<?php
/**
 * Modèle utilisateur
 * 
 * Ce modèle gère les opérations liées aux utilisateurs comme la connexion, l'inscription, etc.
 */
class UserModel extends Model {
    /**
     * Nom de la table associée
     * @var string
     */
    protected $table = 'utilisateurs';
    
    /**
     * Constructeur
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Vérifie les identifiants de connexion
     * 
     * @param string $email L'email de l'utilisateur
     * @param string $password Le mot de passe de l'utilisateur
     * @return mixed L'utilisateur ou false si les identifiants sont incorrects
     */
    public function login($email, $password) {
        $this->db->query("SELECT * FROM {$this->table} WHERE email = :email");
        $this->db->bind(':email', $email);
        
        $user = $this->db->single();
        
        // Vérifier si un utilisateur a été trouvé
        if($user) {
            // Vérifier le mot de passe
            if(password_verify($password, $user->mot_de_passe)) {
                return $user;
            }
        }
        
        return false;
    }
    
    /**
     * Crée un nouvel utilisateur
     * 
     * @param array $data Les données de l'utilisateur
     * @return int|boolean L'ID de l'utilisateur créé ou false en cas d'échec
     */
    public function register($data) {
        // Hacher le mot de passe
        $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        
        // Ajouter la date de création
        $data['date_creation'] = date('Y-m-d H:i:s');
        
        return $this->create($data);
    }
    
    /**
     * Vérifie si un email existe déjà
     * 
     * @param string $email L'email à vérifier
     * @return boolean True si l'email existe, false sinon
     */
    public function emailExists($email) {
        $this->db->query("SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email");
        $this->db->bind(':email', $email);
        
        $result = $this->db->single();
        
        return $result->count > 0;
    }
    
    /**
     * Récupère un utilisateur par son ID
     * 
     * @param int $id L'ID de l'utilisateur
     * @return object L'utilisateur
     */
    public function getUserById($id) {
        return $this->findById($id);
    }
    
    /**
     * Récupère un utilisateur par son email
     * 
     * @param string $email L'email de l'utilisateur
     * @return object L'utilisateur
     */
    public function getUserByEmail($email) {
        return $this->findOneBy('email', $email);
    }
    
    /**
     * Met à jour les informations d'un utilisateur
     * 
     * @param int $id L'ID de l'utilisateur
     * @param array $data Les données à mettre à jour
     * @return boolean True si la mise à jour a réussi, false sinon
     */
    public function updateUser($id, $data) {
        // Ajouter la date de mise à jour
        $data['date_mise_a_jour'] = date('Y-m-d H:i:s');
        
        return $this->update($id, $data);
    }
} 