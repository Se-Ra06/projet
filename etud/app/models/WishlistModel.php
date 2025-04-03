<?php
/**
 * Modèle Wishlist
 * 
 * Ce modèle gère les opérations liées à la liste de favoris des étudiants.
 */
class WishlistModel extends Model {
    /**
     * Nom de la table associée
     * @var string
     */
    protected $table = 'wishlist';
    
    /**
     * Clé primaire de la table
     * @var string
     */
    protected $primaryKey = 'id_wishlist';
    
    /**
     * Constructeur
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Récupère tous les stages favoris d'un étudiant
     * 
     * @param int $etudiantId L'ID de l'étudiant
     * @return array Les stages favoris de l'étudiant
     */
    public function getStudentWishlist($etudiantId) {
        $sql = "SELECT w.*, i.*, c.name_company
                FROM {$this->table} w
                JOIN internship i ON w.id_internship = i.id_internship
                JOIN company c ON i.id_company = c.id_company
                WHERE w.id_student = :etudiant_id
                ORDER BY w.created_at DESC";
        
        return $this->customQuery($sql, ['etudiant_id' => $etudiantId]);
    }
    
    /**
     * Vérifie si un stage est dans la liste de favoris d'un étudiant
     * 
     * @param int $etudiantId L'ID de l'étudiant
     * @param int $stageId L'ID du stage
     * @return boolean True si le stage est dans la liste de favoris, false sinon
     */
    public function isInWishlist($etudiantId, $stageId) {
        $sql = "SELECT COUNT(*) as count
                FROM {$this->table}
                WHERE id_student = :etudiant_id AND id_internship = :stage_id";
        
        $result = $this->customQuery($sql, [
            'etudiant_id' => $etudiantId,
            'stage_id' => $stageId
        ]);
        
        return $result[0]->count > 0;
    }
    
    /**
     * Ajoute un stage à la liste de favoris d'un étudiant
     * 
     * @param int $etudiantId L'ID de l'étudiant
     * @param int $stageId L'ID du stage
     * @return int|boolean L'ID de l'entrée créée ou false en cas d'échec
     */
    public function addToWishlist($etudiantId, $stageId) {
        // Vérifier si le stage est déjà dans la liste de favoris
        if ($this->isInWishlist($etudiantId, $stageId)) {
            return true;
        }
        
        $data = [
            'id_student' => $etudiantId,
            'id_internship' => $stageId,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->create($data);
    }
    
    /**
     * Supprime un stage de la liste de favoris d'un étudiant
     * 
     * @param int $etudiantId L'ID de l'étudiant
     * @param int $stageId L'ID du stage
     * @return boolean True si la suppression a réussi, false sinon
     */
    public function removeFromWishlist($etudiantId, $stageId) {
        $sql = "DELETE FROM {$this->table}
                WHERE id_student = :etudiant_id AND id_internship = :stage_id";
        
        $this->db->query($sql);
        $this->db->bind(':etudiant_id', $etudiantId);
        $this->db->bind(':stage_id', $stageId);
        
        return $this->db->execute();
    }
} 