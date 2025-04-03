<?php
/**
 * Modèle Stage
 * 
 * Ce modèle gère les opérations liées aux stages.
 */
class StageModel extends Model {
    /**
     * Nom de la table associée
     * @var string
     */
    protected $table = 'internship';
    
    /**
     * Clé primaire de la table
     * @var string
     */
    protected $primaryKey = 'id_internship';
    
    /**
     * Constructeur
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Récupère tous les stages
     * 
     * @return array Les stages
     */
    public function getAllStages() {
        $sql = "SELECT s.*, c.name_company as entreprise_nom 
                FROM {$this->table} s
                JOIN company c ON s.id_company = c.id_company
                ORDER BY s.created_at DESC";
        
        return $this->customQuery($sql);
    }
    
    /**
     * Récupère les stages récents
     * 
     * @param int $limit Nombre de stages à récupérer
     * @return array Les stages récents
     */
    public function getRecentStages($limit = 5) {
        $sql = "SELECT i.*, c.name_company
                FROM {$this->table} i
                JOIN company c ON i.id_company = c.id_company
                ORDER BY i.created_at DESC
                LIMIT :limit";
        
        try {
            return $this->customQuery($sql, ['limit' => $limit]);
        } catch (Exception $e) {
            error_log("Erreur dans getRecentStages: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupère un stage par son ID
     * 
     * @param int $id L'ID du stage
     * @return object Le stage
     */
    public function getStageById($id) {
        $sql = "SELECT s.*, c.name_company as entreprise_nom, c.description as entreprise_description, 
                       c.location as entreprise_adresse, '' as entreprise_ville, 
                       '' as entreprise_code_postal, '' as entreprise_pays,
                       '' as entreprise_site_web
                FROM {$this->table} s
                JOIN company c ON s.id_company = c.id_company
                WHERE s.{$this->primaryKey} = :id";
        
        return $this->customQuery($sql, ['id' => $id]);
    }
    
    /**
     * Recherche des stages par mots-clés
     * 
     * @param string $keywords Les mots-clés
     * @return array Les stages correspondants
     */
    public function searchStages($keywords) {
        $keywords = '%' . $keywords . '%';
        
        $sql = "SELECT s.*, c.name_company as entreprise_nom 
                FROM {$this->table} s
                JOIN company c ON s.id_company = c.id_company
                WHERE s.title LIKE :keywords 
                OR s.description LIKE :keywords 
                OR c.name_company LIKE :keywords
                ORDER BY s.created_at DESC";
        
        return $this->customQuery($sql, ['keywords' => $keywords]);
    }
    
    /**
     * Filtre les stages par critères
     * 
     * @param array $filters Les critères de filtrage
     * @return array Les stages filtrés
     */
    public function filterStages($filters) {
        $sql = "SELECT s.*, c.name_company as entreprise_nom 
                FROM {$this->table} s
                JOIN company c ON s.id_company = c.id_company
                WHERE 1=1";
        
        $params = [];
        
        // Filtrer par location
        if (!empty($filters['location'])) {
            $sql .= " AND c.location LIKE :location";
            $params['location'] = '%' . $filters['location'] . '%';
        }
        
        $sql .= " ORDER BY s.created_at DESC";
        
        return $this->customQuery($sql, $params);
    }
} 