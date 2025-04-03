<?php
class StageModel extends Model {
    private $table = 'stages';
    
    public function __construct() {
        parent::__construct();
    }
    
    // Récupérer tous les stages
    public function getAllStages() {
        return $this->findAll($this->table, 'created_at DESC');
    }
    
    // Récupérer les stages récents
    public function getRecentStages($limit = 5) {
        $sql = "SELECT s.*, e.nom as entreprise_nom 
                FROM {$this->table} s
                JOIN entreprises e ON s.entreprise_id = e.id
                ORDER BY s.created_at DESC
                LIMIT :limit";
        
        $this->db->query($sql);
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }
    
    // Récupérer un stage par son ID
    public function getStageById($id) {
        $sql = "SELECT s.*, e.nom as entreprise_nom, e.description as entreprise_description, 
                       e.adresse as entreprise_adresse, e.ville as entreprise_ville, 
                       e.code_postal as entreprise_code_postal, e.pays as entreprise_pays,
                       e.site_web as entreprise_site_web
                FROM {$this->table} s
                JOIN entreprises e ON s.entreprise_id = e.id
                WHERE s.id = :id";
        
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
    
    // Rechercher des stages par mots-clés
    public function searchStages($keywords) {
        $sql = "SELECT s.*, e.nom as entreprise_nom 
                FROM {$this->table} s
                JOIN entreprises e ON s.entreprise_id = e.id
                WHERE s.titre LIKE :keywords 
                OR s.description LIKE :keywords 
                OR s.competences_requises LIKE :keywords
                OR e.nom LIKE :keywords
                ORDER BY s.created_at DESC";
        
        $this->db->query($sql);
        $this->db->bind(':keywords', '%' . $keywords . '%');
        
        return $this->db->resultSet();
    }
    
    // Filtrer les stages par critères
    public function filterStages($filters) {
        $sql = "SELECT s.*, e.nom as entreprise_nom 
                FROM {$this->table} s
                JOIN entreprises e ON s.entreprise_id = e.id
                WHERE 1=1";
        
        // Filtrer par ville
        if (!empty($filters['ville'])) {
            $sql .= " AND e.ville = :ville";
        }
        
        // Filtrer par type de stage
        if (!empty($filters['type'])) {
            $sql .= " AND s.type = :type";
        }
        
        // Filtrer par durée
        if (!empty($filters['duree'])) {
            $sql .= " AND s.duree = :duree";
        }
        
        $sql .= " ORDER BY s.created_at DESC";
        
        $this->db->query($sql);
        
        if (!empty($filters['ville'])) {
            $this->db->bind(':ville', $filters['ville']);
        }
        
        if (!empty($filters['type'])) {
            $this->db->bind(':type', $filters['type']);
        }
        
        if (!empty($filters['duree'])) {
            $this->db->bind(':duree', $filters['duree']);
        }
        
        return $this->db->resultSet();
    }
} 