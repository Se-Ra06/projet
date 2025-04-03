<?php
/**
 * Modèle Etudiant
 * 
 * Ce modèle gère les opérations liées aux étudiants.
 */
class EtudiantModel extends Model {
    /**
     * Nom de la table associée
     * @var string
     */
    protected $table = 'student';
    
    /**
     * Clé primaire de la table
     * @var string
     */
    protected $primaryKey = 'id_student';
    
    /**
     * Constructeur
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Récupère un étudiant par son ID utilisateur
     * 
     * @param int $userId L'ID de l'utilisateur
     * @return object|false L'étudiant trouvé ou false
     */
    public function findByUserId($userId) {
        return $this->findBy('id_user', $userId);
    }
    
    /**
     * Crée un nouvel étudiant
     * 
     * @param array $data Les données de l'étudiant
     * @return int|boolean L'ID de l'étudiant créé ou false en cas d'échec
     */
    public function createStudent($data) {
        return $this->create($data);
    }
    
    /**
     * Met à jour un étudiant
     * 
     * @param int $id L'ID de l'étudiant
     * @param array $data Les données à mettre à jour
     * @return boolean True si la mise à jour a réussi, false sinon
     */
    public function updateStudent($id, $data) {
        return $this->update($id, $data);
    }
    
    /**
     * Met à jour le CV d'un étudiant
     * 
     * @param int $id L'ID de l'étudiant
     * @param string $cvPath Le chemin du CV
     * @return boolean True si la mise à jour a réussi, false sinon
     */
    public function updateCV($id, $cvPath) {
        return $this->update($id, ['cv' => $cvPath]);
    }
    
    /**
     * Récupère les statistiques d'un étudiant
     * 
     * @param int $etudiantId L'ID de l'étudiant
     * @return array Les statistiques de l'étudiant
     */
    public function getStats($etudiantId) {
        try {
            // Vérifier la structure de la table application
            $sqlCheckApplication = "SHOW COLUMNS FROM application";
            $colsApplication = $this->customQuery($sqlCheckApplication);
            $hasIdStudent = false;
            foreach ($colsApplication as $col) {
                if ($col->Field === 'id_student') {
                    $hasIdStudent = true;
                    break;
                }
            }
            
            // Nombre total de candidatures
            if ($hasIdStudent) {
                $sqlCandidatures = "SELECT COUNT(*) as total FROM application WHERE id_student = :etudiant_id";
            } else {
                // Fallback si la colonne n'existe pas (pour debug)
                error_log("Colonne id_student non trouvée dans la table application");
                $sqlCandidatures = "SELECT 0 as total";
            }
            
            $candidatures = $this->customQuery($sqlCandidatures, $hasIdStudent ? ['etudiant_id' => $etudiantId] : []);
            
            // Vérifier la structure de la table wishlist
            $sqlCheckWishlist = "SHOW COLUMNS FROM wishlist";
            $colsWishlist = $this->customQuery($sqlCheckWishlist);
            $hasIdStudentWishlist = false;
            foreach ($colsWishlist as $col) {
                if ($col->Field === 'id_student') {
                    $hasIdStudentWishlist = true;
                    break;
                }
            }
            
            // Nombre de stages dans la wishlist
            if ($hasIdStudentWishlist) {
                $sqlWishlist = "SELECT COUNT(*) as total FROM wishlist WHERE id_student = :etudiant_id";
            } else {
                // Fallback si la colonne n'existe pas (pour debug)
                error_log("Colonne id_student non trouvée dans la table wishlist");
                $sqlWishlist = "SELECT 0 as total";
            }
            
            $wishlist = $this->customQuery($sqlWishlist, $hasIdStudentWishlist ? ['etudiant_id' => $etudiantId] : []);
            
            return [
                'total_candidatures' => isset($candidatures[0]->total) ? $candidatures[0]->total : 0,
                'total_wishlist' => isset($wishlist[0]->total) ? $wishlist[0]->total : 0
            ];
        } catch (Exception $e) {
            error_log("Erreur dans EtudiantModel::getStats: " . $e->getMessage());
            return [
                'total_candidatures' => 0,
                'total_wishlist' => 0
            ];
        }
    }
    
    /**
     * Récupère un étudiant par son email
     * 
     * @param string $email L'email de l'étudiant
     * @return object|false L'étudiant trouvé ou false
     */
    public function findByEmail($email) {
        if (empty($email)) {
            error_log("Email vide fourni à findByEmail");
            return false;
        }
        
        try {
            $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
            $result = $this->customQuery($sql, ['email' => $email]);
            
            if ($result && is_array($result) && count($result) > 0) {
                return $result[0];
            }
            
            error_log("Aucun étudiant trouvé avec l'email: " . $email);
            return false;
        } catch (Exception $e) {
            error_log("Erreur dans findByEmail: " . $e->getMessage());
            return false;
        }
    }
} 