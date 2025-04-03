<?php
class CandidatureModel extends Model {
    private $table = 'candidatures';
    
    public function __construct() {
        parent::__construct();
    }
    
    // Récupérer toutes les candidatures d'un étudiant
    public function getStudentCandidatures($etudiantId) {
        $sql = "SELECT c.*, s.titre as stage_titre, s.entreprise_id, 
                       e.nom as entreprise_nom, s.date_debut, s.date_fin
                FROM {$this->table} c
                JOIN stages s ON c.stage_id = s.id
                JOIN entreprises e ON s.entreprise_id = e.id
                WHERE c.etudiant_id = :etudiant_id
                ORDER BY c.created_at DESC";
        
        $this->db->query($sql);
        $this->db->bind(':etudiant_id', $etudiantId);
        
        return $this->db->resultSet();
    }
    
    // Récupérer les statistiques des candidatures d'un étudiant
    public function getStudentStats($etudiantId) {
        $sql = "SELECT 
                    COUNT(*) as total_candidatures,
                    SUM(CASE WHEN statut = 'en_attente' THEN 1 ELSE 0 END) as candidatures_en_attente,
                    SUM(CASE WHEN statut = 'acceptee' THEN 1 ELSE 0 END) as candidatures_acceptees,
                    SUM(CASE WHEN statut = 'refusee' THEN 1 ELSE 0 END) as candidatures_refusees
                FROM {$this->table}
                WHERE etudiant_id = :etudiant_id";
        
        $this->db->query($sql);
        $this->db->bind(':etudiant_id', $etudiantId);
        
        return $this->db->single();
    }
    
    // Vérifier si un étudiant a déjà postulé à un stage
    public function checkIfApplied($etudiantId, $stageId) {
        $sql = "SELECT COUNT(*) as count
                FROM {$this->table}
                WHERE etudiant_id = :etudiant_id AND stage_id = :stage_id";
        
        $this->db->query($sql);
        $this->db->bind(':etudiant_id', $etudiantId);
        $this->db->bind(':stage_id', $stageId);
        
        $result = $this->db->single();
        
        return $result->count > 0;
    }
    
    // Créer une nouvelle candidature
    public function createCandidature($data) {
        // Ajouter la date de création et le statut par défaut
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['statut'] = 'en_attente';
        
        return $this->create($this->table, $data);
    }
    
    // Mettre à jour le statut d'une candidature
    public function updateStatus($candidatureId, $statut) {
        $data = [
            'statut' => $statut,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($this->table, $data, $candidatureId);
    }
    
    // Supprimer une candidature
    public function deleteCandidature($id) {
        return $this->delete($this->table, $id);
    }
} 