<?php
/**
 * Modèle Mock pour Etudiant - Utilisé pour les tests
 * 
 * Ce modèle simule le EtudiantModel sans dépendre de la base de données
 */
class EtudiantModel {
    /**
     * Récupère un étudiant par son ID utilisateur (simulé)
     * 
     * @param int $userId L'ID de l'utilisateur
     * @return object|false L'étudiant trouvé ou false
     */
    public function findByUserId($userId) {
        $etudiants = $this->getMockEtudiants();
        
        foreach ($etudiants as $etudiant) {
            if ($etudiant->id_user == $userId) {
                return $etudiant;
            }
        }
        
        return false;
    }
    
    /**
     * Récupère un étudiant par son email (simulé)
     * 
     * @param string $email L'email de l'étudiant
     * @return object|false L'étudiant trouvé ou false
     */
    public function findByEmail($email) {
        $etudiants = $this->getMockEtudiants();
        
        foreach ($etudiants as $etudiant) {
            if ($etudiant->email == $email) {
                return $etudiant;
            }
        }
        
        return false;
    }
    
    /**
     * Récupère tous les étudiants (simulé)
     * 
     * @return array Les étudiants
     */
    public function findAll() {
        return $this->getMockEtudiants();
    }
    
    /**
     * Crée un nouvel étudiant (simulé)
     * 
     * @param array $data Les données de l'étudiant
     * @return int|boolean L'ID de l'étudiant créé ou false en cas d'échec
     */
    public function createStudent($data) {
        // Simuler un ID pour le nouvel étudiant
        return 3; // ID du nouvel étudiant simulé
    }
    
    /**
     * Met à jour un étudiant (simulé)
     * 
     * @param int $id L'ID de l'étudiant
     * @param array $data Les données à mettre à jour
     * @return boolean True si la mise à jour a réussi, false sinon
     */
    public function updateStudent($id, $data) {
        return true; // Simuler une mise à jour réussie
    }
    
    /**
     * Met à jour le CV d'un étudiant (simulé)
     * 
     * @param int $id L'ID de l'étudiant
     * @param string $cvPath Le chemin du CV
     * @return boolean True si la mise à jour a réussi, false sinon
     */
    public function updateCV($id, $cvPath) {
        return true; // Simuler une mise à jour réussie
    }
    
    /**
     * Récupère les statistiques d'un étudiant (simulé)
     * 
     * @param int $etudiantId L'ID de l'étudiant
     * @return array Les statistiques de l'étudiant
     */
    public function getStats($etudiantId) {
        // Simuler des statistiques
        return [
            'total_candidatures' => ($etudiantId == 1) ? 2 : 1, // 2 candidatures pour étudiant 1, 1 pour les autres
            'total_wishlist' => ($etudiantId == 1) ? 3 : 1 // 3 stages en wishlist pour étudiant 1, 1 pour les autres
        ];
    }
    
    /**
     * Génère des données de test pour les étudiants
     * 
     * @return array Un tableau d'objets étudiant
     */
    private function getMockEtudiants() {
        $etudiants = [];
        
        // Etudiant 1
        $etudiant1 = new stdClass();
        $etudiant1->id_student = 1;
        $etudiant1->name = "Jean Dupont";
        $etudiant1->email = "test@example.com"; // Cet email correspond à celui dans les tests
        $etudiant1->password = "$2y$10$abcdefghijklmnopqrstuv"; // Hash bidon
        $etudiant1->location = "Paris";
        $etudiant1->phone_number = "0123456789";
        $etudiant1->date_of_birth = "1998-05-12";
        $etudiant1->year = "4th";
        $etudiant1->description = "Étudiant en informatique passionné par le développement web";
        $etudiant1->created_at = "2023-01-15 10:30:00";
        $etudiant1->updated_at = "2023-01-15 10:30:00";
        $etudiant1->id_user = 1;
        $etudiants[] = $etudiant1;
        
        // Etudiant 2
        $etudiant2 = new stdClass();
        $etudiant2->id_student = 2;
        $etudiant2->name = "Marie Martin";
        $etudiant2->email = "marie.martin@example.com";
        $etudiant2->password = "$2y$10$wxyzabcdefghijklmnopqr"; // Hash bidon
        $etudiant2->location = "Lyon";
        $etudiant2->phone_number = "0712345678";
        $etudiant2->date_of_birth = "1999-07-23";
        $etudiant2->year = "3rd";
        $etudiant2->description = "Étudiante en marketing digital cherchant un stage dans le domaine";
        $etudiant2->created_at = "2023-01-20 14:45:00";
        $etudiant2->updated_at = "2023-01-20 14:45:00";
        $etudiant2->id_user = 2;
        $etudiants[] = $etudiant2;
        
        return $etudiants;
    }
} 