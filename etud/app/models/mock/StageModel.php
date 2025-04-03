<?php
/**
 * Modèle Mock pour Stage - Utilisé pour les tests
 * 
 * Ce modèle simule le StageModel sans dépendre de la base de données
 */
class StageModel {
    /**
     * Récupère tous les stages (simulé)
     * 
     * @return array Les stages
     */
    public function getAllStages() {
        return $this->getMockStages();
    }
    
    /**
     * Récupère les stages récents (simulé)
     * 
     * @param int $limit Le nombre de stages à récupérer
     * @return array Les stages récents
     */
    public function getRecentStages($limit = 5) {
        $stages = $this->getMockStages();
        return array_slice($stages, 0, $limit);
    }
    
    /**
     * Récupère un stage par son ID (simulé)
     * 
     * @param int $id L'ID du stage
     * @return object Le stage
     */
    public function getStageById($id) {
        $stages = $this->getMockStages();
        foreach ($stages as $stage) {
            if ($stage->id_internship == $id) {
                return $stage;
            }
        }
        return null;
    }
    
    /**
     * Recherche des stages par mots-clés (simulé)
     * 
     * @param string $keywords Les mots-clés
     * @return array Les stages correspondants
     */
    public function searchStages($keywords) {
        $stages = $this->getMockStages();
        $results = [];
        
        foreach ($stages as $stage) {
            if (stripos($stage->title, $keywords) !== false || 
                stripos($stage->description, $keywords) !== false || 
                stripos($stage->name_company, $keywords) !== false) {
                $results[] = $stage;
            }
        }
        
        return $results;
    }
    
    /**
     * Filtre les stages par critères (simulé)
     * 
     * @param array $filters Les critères de filtrage
     * @return array Les stages filtrés
     */
    public function filterStages($filters) {
        $stages = $this->getMockStages();
        $results = [];
        
        foreach ($stages as $stage) {
            $match = true;
            
            if (!empty($filters['location']) && stripos($stage->location, $filters['location']) === false) {
                $match = false;
            }
            
            if ($match) {
                $results[] = $stage;
            }
        }
        
        return $results;
    }
    
    /**
     * Génère des données de test pour les stages
     * 
     * @return array Un tableau d'objets stage
     */
    private function getMockStages() {
        $stages = [];
        
        // Stage 1
        $stage1 = new stdClass();
        $stage1->id_internship = 1;
        $stage1->title = "Développeur PHP/Symfony";
        $stage1->description = "Stage de développement d'applications web avec PHP et Symfony";
        $stage1->id_company = 1;
        $stage1->name_company = "WebTech Solutions";
        $stage1->location = "Paris";
        $stage1->type = "Stage_fin_detudes";
        $stage1->duration = 6;
        $stage1->remuneration = 800;
        $stage1->offre_date = "2023-03-15";
        $stage1->created_at = "2023-02-01";
        $stage1->updated_at = "2023-02-01";
        $stage1->required_skills = "PHP, MySQL, JavaScript, Symfony";
        $stage1->additional_info = "Possibilité d'embauche à la fin du stage";
        $stage1->website = "https://webtech-solutions.com";
        $stage1->sector = "Développement web";
        $stage1->address = "10 Rue de la Paix, 75002 Paris";
        $stages[] = $stage1;
        
        // Stage 2
        $stage2 = new stdClass();
        $stage2->id_internship = 2;
        $stage2->title = "Data Analyst";
        $stage2->description = "Stage d'analyse de données et création de tableaux de bord";
        $stage2->id_company = 2;
        $stage2->name_company = "DataViz Corp";
        $stage2->location = "Lyon";
        $stage2->type = "Stage_annee";
        $stage2->duration = 4;
        $stage2->remuneration = 700;
        $stage2->offre_date = "2023-04-01";
        $stage2->created_at = "2023-03-01";
        $stage2->updated_at = "2023-03-01";
        $stage2->required_skills = "Python, R, SQL, Tableau";
        $stage2->additional_info = "Télétravail possible 2 jours par semaine";
        $stage2->website = "https://dataviz-corp.com";
        $stage2->sector = "Analyse de données";
        $stage2->address = "25 Avenue Jean Jaurès, 69007 Lyon";
        $stages[] = $stage2;
        
        // Stage 3
        $stage3 = new stdClass();
        $stage3->id_internship = 3;
        $stage3->title = "Développeur Mobile React Native";
        $stage3->description = "Stage de développement d'applications mobiles avec React Native";
        $stage3->id_company = 3;
        $stage3->name_company = "MobileFirst";
        $stage3->location = "Bordeaux";
        $stage3->type = "Stage_fin_detudes";
        $stage3->duration = 5;
        $stage3->remuneration = 750;
        $stage3->offre_date = "2023-03-20";
        $stage3->created_at = "2023-02-15";
        $stage3->updated_at = "2023-02-15";
        $stage3->required_skills = "JavaScript, React Native, Redux";
        $stage3->additional_info = "Entreprise en forte croissance";
        $stage3->website = "https://mobile-first.fr";
        $stage3->sector = "Développement mobile";
        $stage3->address = "45 Cours de la Marne, 33800 Bordeaux";
        $stages[] = $stage3;
        
        return $stages;
    }
} 