<?php
/**
 * Modèle Candidature
 * 
 * Ce modèle gère les opérations liées aux candidatures de stages.
 */
class CandidatureModel extends Model {
    /**
     * Nom de la table associée
     * @var string
     */
    protected $table = 'application';
    
    /**
     * Clé primaire de la table
     * @var string
     */
    protected $primaryKey = 'id_app';
    
    /**
     * Constructeur
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Récupère toutes les candidatures d'un étudiant
     * 
     * @param int $etudiantId L'ID de l'étudiant
     * @return array Les candidatures de l'étudiant
     */
    public function getStudentCandidatures($etudiantId) {
        $sql = "SELECT a.*, i.title as stage_titre, i.id_company, 
                       c.name_company as entreprise_nom, i.offre_date as date_debut, '' as date_fin
                FROM {$this->table} a
                JOIN internship i ON a.id_internship = i.id_internship
                JOIN company c ON i.id_company = c.id_company
                WHERE a.id_student = :etudiant_id
                ORDER BY a.created_at DESC";

        // Pour débugger la requête SQL
        error_log("SQL Query: " . $sql);
        error_log("Student ID: " . $etudiantId);
        
        try {
            $result = $this->customQuery($sql, ['etudiant_id' => $etudiantId]);
            return $result ? $result : [];
        } catch (Exception $e) {
            error_log("Erreur dans getStudentCandidatures: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupère les statistiques des candidatures d'un étudiant
     * 
     * @param int $etudiantId L'ID de l'étudiant
     * @return object Les statistiques des candidatures
     */
    public function getStudentStats($etudiantId) {
        $sql = "SELECT 
                    COUNT(*) as total_candidatures,
                    0 as candidatures_en_attente,
                    0 as candidatures_acceptees,
                    0 as candidatures_refusees
                FROM {$this->table}
                WHERE id_student = :etudiant_id";
        
        try {
            $result = $this->customQuery($sql, ['etudiant_id' => $etudiantId]);
            return $result ? $result : (object)['total_candidatures' => 0, 'candidatures_en_attente' => 0, 'candidatures_acceptees' => 0, 'candidatures_refusees' => 0];
        } catch (Exception $e) {
            error_log("Erreur dans getStudentStats: " . $e->getMessage());
            return (object)['total_candidatures' => 0, 'candidatures_en_attente' => 0, 'candidatures_acceptees' => 0, 'candidatures_refusees' => 0];
        }
    }
    
    /**
     * Vérifie si un étudiant a déjà postulé à un stage
     * 
     * @param int $etudiantId L'ID de l'étudiant
     * @param int $stageId L'ID du stage
     * @return boolean True si l'étudiant a déjà postulé, false sinon
     */
    public function checkIfApplied($etudiantId, $stageId) {
        $sql = "SELECT COUNT(*) as count
                FROM {$this->table}
                WHERE id_student = :etudiant_id AND id_internship = :stage_id";
        
        try {
            $result = $this->customQuery($sql, [
                'etudiant_id' => $etudiantId,
                'stage_id' => $stageId
            ]);
            
            if (!$result || !isset($result[0]->count)) {
                return false;
            }
            
            return $result[0]->count > 0;
        } catch (Exception $e) {
            error_log("Erreur dans checkIfApplied: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Crée une nouvelle candidature
     * 
     * @param array $data Les données de la candidature
     * @return int|boolean L'ID de la candidature créée ou false en cas d'échec
     */
    public function createCandidature($data) {
        // Transformer les données pour la nouvelle structure
        $applicationData = [
            'id_student' => $data['etudiant_id'],
            'id_internship' => $data['stage_id'],
            'cv' => $data['cv_path'] ?? '',
            'cover_letter' => $data['lettre_motivation'],
            'application_date' => date('Y-m-d')
        ];
        
        return $this->create($applicationData);
    }
    
    /**
     * Met à jour le statut d'une candidature
     * 
     * @param int $candidatureId L'ID de la candidature
     * @param string $statut Le nouveau statut
     * @return boolean True si la mise à jour a réussi, false sinon
     */
    public function updateStatus($candidatureId, $statut) {
        // Remarque: la table application n'a pas de colonne statut
        // Cette méthode est conservée pour compatibilité mais ne fait rien
        return true;
    }
    
    /**
     * Supprime une candidature
     * 
     * @param int $id L'ID de la candidature
     * @return boolean True si la suppression a réussi, false sinon
     */
    public function deleteCandidature($id) {
        return $this->delete($id);
    }
} 