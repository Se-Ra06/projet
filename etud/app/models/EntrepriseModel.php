<?php
/**
 * Modèle Entreprise
 * 
 * Ce modèle gère les opérations liées aux entreprises.
 */
class EntrepriseModel extends Model {
    /**
     * Nom de la table associée
     * @var string
     */
    protected $table = 'company';
    
    /**
     * Clé primaire de la table
     * @var string
     */
    protected $primaryKey = 'id_company';
    
    /**
     * Constructeur
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Récupère toutes les entreprises
     * 
     * @return array Les entreprises
     */
    public function getAllEntreprises() {
        return $this->findAll();
    }
    
    /**
     * Récupère une entreprise par son ID
     * 
     * @param int $id L'ID de l'entreprise
     * @return object|false L'entreprise trouvée ou false
     */
    public function getEntrepriseById($id) {
        return $this->findById($id);
    }
    
    /**
     * Récupère les entreprises avec comptage des stages
     * 
     * @return array Les entreprises avec leurs statistiques
     */
    public function getEntreprisesWithStats() {
        $sql = "SELECT c.*, COUNT(i.id_internship) as nombre_stages
                FROM {$this->table} c
                LEFT JOIN internship i ON c.id_company = i.id_company
                GROUP BY c.id_company
                ORDER BY c.name_company";
        
        return $this->customQuery($sql);
    }
    
    /**
     * Recherche des entreprises par nom
     * 
     * @param string $query Le terme de recherche
     * @return array Les entreprises correspondantes
     */
    public function searchEntreprises($query) {
        $sql = "SELECT *
                FROM {$this->table}
                WHERE name_company LIKE :query
                ORDER BY name_company";
        
        return $this->customQuery($sql, ['query' => "%{$query}%"]);
    }
    
    /**
     * Crée une nouvelle entreprise
     * 
     * @param array $data Les données de l'entreprise
     * @return int|boolean L'ID de l'entreprise créée ou false en cas d'échec
     */
    public function createEntreprise($data) {
        return $this->create($data);
    }
    
    /**
     * Met à jour une entreprise
     * 
     * @param int $id L'ID de l'entreprise
     * @param array $data Les données à mettre à jour
     * @return boolean True si la mise à jour a réussi, false sinon
     */
    public function updateEntreprise($id, $data) {
        return $this->update($id, $data);
    }
    
    /**
     * Supprime une entreprise
     * 
     * @param int $id L'ID de l'entreprise
     * @return boolean True si la suppression a réussi, false sinon
     */
    public function deleteEntreprise($id) {
        return $this->delete($id);
    }
} 