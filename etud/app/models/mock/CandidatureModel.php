<?php
/**
 * Modèle Mock pour Candidature - Utilisé pour les tests
 * 
 * Ce modèle simule le CandidatureModel sans dépendre de la base de données
 */
class CandidatureModel {
    /**
     * Récupère toutes les candidatures d'un étudiant (simulé)
     * 
     * @param int $etudiantId L'ID de l'étudiant
     * @return array Les candidatures de l'étudiant
     */
    public function getStudentCandidatures($etudiantId) {
        $candidatures = $this->getMockCandidatures();
        $results = [];
        
        foreach ($candidatures as $candidature) {
            if ($candidature->id_student == $etudiantId) {
                $results[] = $candidature;
            }
        }
        
        return $results;
    }
    
    /**
     * Récupère les statistiques des candidatures d'un étudiant (simulé)
     * 
     * @param int $etudiantId L'ID de l'étudiant
     * @return object Les statistiques des candidatures
     */
    public function getStudentStats($etudiantId) {
        $candidatures = $this->getStudentCandidatures($etudiantId);
        $stats = new stdClass();
        $stats->total_candidatures = count($candidatures);
        $stats->candidatures_en_attente = 0;
        $stats->candidatures_acceptees = 0;
        $stats->candidatures_refusees = 0;
        
        return $stats;
    }
    
    /**
     * Vérifie si un étudiant a déjà postulé à un stage (simulé)
     * 
     * @param int $etudiantId L'ID de l'étudiant
     * @param int $stageId L'ID du stage
     * @return boolean True si l'étudiant a déjà postulé, false sinon
     */
    public function checkIfApplied($etudiantId, $stageId) {
        $candidatures = $this->getMockCandidatures();
        
        foreach ($candidatures as $candidature) {
            if ($candidature->id_student == $etudiantId && $candidature->id_internship == $stageId) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Crée une nouvelle candidature (simulé)
     * 
     * @param array $data Les données de la candidature
     * @return int|boolean L'ID de la candidature créée ou false en cas d'échec
     */
    public function createCandidature($data) {
        // Simuler un ID pour la nouvelle candidature
        return 4; // ID de la nouvelle candidature simulée
    }
    
    /**
     * Met à jour le statut d'une candidature (simulé)
     * 
     * @param int $candidatureId L'ID de la candidature
     * @param string $statut Le nouveau statut
     * @return boolean True si la mise à jour a réussi, false sinon
     */
    public function updateStatus($candidatureId, $statut) {
        return true; // Simuler une mise à jour réussie
    }
    
    /**
     * Supprime une candidature (simulé)
     * 
     * @param int $id L'ID de la candidature
     * @return boolean True si la suppression a réussi, false sinon
     */
    public function deleteCandidature($id) {
        return true; // Simuler une suppression réussie
    }
    
    /**
     * Récupère une candidature par son ID (simulé)
     * 
     * @param int $id L'ID de la candidature
     * @return object|false La candidature trouvée ou false
     */
    public function findById($id) {
        $candidatures = $this->getMockCandidatures();
        
        foreach ($candidatures as $candidature) {
            if ($candidature->id_app == $id) {
                return $candidature;
            }
        }
        
        return false;
    }
    
    /**
     * Génère des données de test pour les candidatures
     * 
     * @return array Un tableau d'objets candidature
     */
    private function getMockCandidatures() {
        $candidatures = [];
        
        // Candidature 1
        $candidature1 = new stdClass();
        $candidature1->id_app = 1;
        $candidature1->id_student = 1;
        $candidature1->id_internship = 1;
        $candidature1->cv = "cv_etudiant1.pdf";
        $candidature1->cover_letter = "Lettre de motivation pour le stage de développeur PHP";
        $candidature1->application_date = "2023-02-15";
        $candidature1->created_at = "2023-02-15 14:30:00";
        $candidature1->updated_at = "2023-02-15 14:30:00";
        $candidature1->stage_titre = "Développeur PHP/Symfony";
        $candidature1->entreprise_nom = "WebTech Solutions";
        $candidature1->date_debut = "2023-03-15";
        $candidature1->date_fin = "";
        $candidatures[] = $candidature1;
        
        // Candidature 2
        $candidature2 = new stdClass();
        $candidature2->id_app = 2;
        $candidature2->id_student = 1;
        $candidature2->id_internship = 2;
        $candidature2->cv = "cv_etudiant1.pdf";
        $candidature2->cover_letter = "Lettre de motivation pour le stage de Data Analyst";
        $candidature2->application_date = "2023-03-05";
        $candidature2->created_at = "2023-03-05 09:45:00";
        $candidature2->updated_at = "2023-03-05 09:45:00";
        $candidature2->stage_titre = "Data Analyst";
        $candidature2->entreprise_nom = "DataViz Corp";
        $candidature2->date_debut = "2023-04-01";
        $candidature2->date_fin = "";
        $candidatures[] = $candidature2;
        
        // Candidature 3
        $candidature3 = new stdClass();
        $candidature3->id_app = 3;
        $candidature3->id_student = 2;
        $candidature3->id_internship = 3;
        $candidature3->cv = "cv_etudiant2.pdf";
        $candidature3->cover_letter = "Lettre de motivation pour le stage de développeur mobile";
        $candidature3->application_date = "2023-03-10";
        $candidature3->created_at = "2023-03-10 16:15:00";
        $candidature3->updated_at = "2023-03-10 16:15:00";
        $candidature3->stage_titre = "Développeur Mobile React Native";
        $candidature3->entreprise_nom = "MobileFirst";
        $candidature3->date_debut = "2023-03-20";
        $candidature3->date_fin = "";
        $candidatures[] = $candidature3;
        
        return $candidatures;
    }
} 