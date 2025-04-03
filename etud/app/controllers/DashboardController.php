<?php
class DashboardController extends Controller {
    private $stageModel;
    private $candidatureModel;
    
    public function __construct() {
        // Vérifier si l'utilisateur est connecté
        if(!isset($_SESSION['user_id'])) {
            $this->redirect('home/login');
        }
        
        // Charger les modèles
        $this->stageModel = $this->model('StageModel');
        $this->candidatureModel = $this->model('CandidatureModel');
    }
    
    // Page d'accueil du dashboard
    public function index() {
        // Récupérer les stages récents
        $stages = $this->stageModel->getRecentStages();
        
        // Récupérer les statistiques de candidatures de l'étudiant
        $candidatures = $this->candidatureModel->getStudentStats($_SESSION['user_id']);
        
        $data = [
            'title' => 'Dashboard Étudiant',
            'stages' => $stages,
            'candidatures' => $candidatures
        ];
        
        $this->view('dashboard/index', $data);
    }
    
    // Liste des stages disponibles
    public function stages() {
        // Récupérer tous les stages disponibles
        $stages = $this->stageModel->getAllStages();
        
        $data = [
            'title' => 'Offres de stages',
            'stages' => $stages
        ];
        
        $this->view('dashboard/stages', $data);
    }
    
    // Détails d'un stage spécifique
    public function stage($id = null) {
        if($id === null) {
            $this->redirect('dashboard/stages');
        }
        
        // Récupérer les détails du stage
        $stage = $this->stageModel->getStageById($id);
        
        // Vérifier si le stage existe
        if(!$stage) {
            $this->redirect('dashboard/stages');
        }
        
        // Vérifier si l'étudiant a déjà postulé
        $dejaPostule = $this->candidatureModel->checkIfApplied($_SESSION['user_id'], $id);
        
        $data = [
            'title' => $stage->titre,
            'stage' => $stage,
            'dejaPostule' => $dejaPostule
        ];
        
        $this->view('dashboard/stage-details', $data);
    }
    
    // Gestion des candidatures de l'étudiant
    public function candidatures() {
        // Récupérer toutes les candidatures de l'étudiant
        $candidatures = $this->candidatureModel->getStudentCandidatures($_SESSION['user_id']);
        
        $data = [
            'title' => 'Mes Candidatures',
            'candidatures' => $candidatures
        ];
        
        $this->view('dashboard/candidatures', $data);
    }
    
    // Postuler à un stage
    public function postuler($stageId = null) {
        if($stageId === null) {
            $this->redirect('dashboard/stages');
        }
        
        // Vérifier si la méthode est POST
        if($this->isPostRequest()) {
            $postData = $this->getPostData();
            
            $candidatureData = [
                'etudiant_id' => $_SESSION['user_id'],
                'stage_id' => $stageId,
                'lettre_motivation' => trim($postData['lettre_motivation']),
                'cv_path' => '' // À implémenter plus tard: téléchargement de CV
            ];
            
            // Valider les données
            $errors = [];
            
            if(empty($candidatureData['lettre_motivation'])) {
                $errors[] = 'La lettre de motivation est requise';
            }
            
            // S'il n'y a pas d'erreurs, enregistrer la candidature
            if(empty($errors)) {
                if($this->candidatureModel->createCandidature($candidatureData)) {
                    // Rediriger avec un message de succès
                    $_SESSION['success_message'] = 'Votre candidature a été envoyée avec succès';
                    $this->redirect('dashboard/candidatures');
                } else {
                    // Erreur lors de l'enregistrement
                    $data['error'] = 'Une erreur est survenue lors de l\'envoi de votre candidature';
                }
            } else {
                $data['errors'] = $errors;
            }
        }
        
        // Récupérer les détails du stage
        $stage = $this->stageModel->getStageById($stageId);
        
        $data = [
            'title' => 'Postuler - ' . $stage->titre,
            'stage' => $stage
        ];
        
        $this->view('dashboard/postuler', $data);
    }
    
    // Profil de l'étudiant
    public function profil() {
        $data = [
            'title' => 'Mon Profil'
        ];
        
        $this->view('dashboard/profil', $data);
    }
} 