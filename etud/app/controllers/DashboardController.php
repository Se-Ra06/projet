<?php
class DashboardController extends Controller {
    private $stageModel;
    private $candidatureModel;
    private $etudiantModel;
    
    public function __construct() {
        // Vérifier si l'utilisateur est connecté
        if(!isset($_SESSION['user_id'])) {
            $this->redirect('home/login');
        }
        
        // Charger les modèles
        $this->stageModel = $this->model('StageModel');
        $this->candidatureModel = $this->model('CandidatureModel');
        $this->etudiantModel = $this->model('EtudiantModel');
    }
    
    // Page d'accueil du dashboard
    public function index() {
        // Récupérer les stages récents
        $stages = $this->stageModel->getRecentStages();
        
        // Récupérer les statistiques de candidatures de l'étudiant
        // Utiliser l'ID d'étudiant (pas l'ID utilisateur) pour les statistiques
        // On doit donc d'abord récupérer l'étudiant associé à l'utilisateur connecté
        $etudiant = $this->etudiantModel->findByEmail($_SESSION['user_email'] ?? '');
        $etudiantId = $etudiant ? $etudiant->id_student : 0;
        
        if ($etudiantId) {
            $stats = $this->etudiantModel->getStats($etudiantId);
        } else {
            $stats = ['total_candidatures' => 0, 'total_wishlist' => 0];
            // Log pour le debug
            error_log("Impossible de trouver l'étudiant associé à l'utilisateur " . ($_SESSION['user_email'] ?? 'inconnu'));
        }
        
        $data = [
            'title' => 'Dashboard Étudiant',
            'stages' => $stages,
            'stats' => $stats
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
        
        // Récupérer l'ID étudiant associé à l'utilisateur
        $etudiant = $this->etudiantModel->findByEmail($_SESSION['user_email'] ?? '');
        $etudiantId = $etudiant ? $etudiant->id_student : 0;
        
        // Vérifier si l'étudiant a déjà postulé
        $dejaPostule = $etudiantId ? $this->candidatureModel->checkIfApplied($etudiantId, $id) : false;
        
        $data = [
            'title' => $stage->title,
            'stage' => $stage,
            'dejaPostule' => $dejaPostule
        ];
        
        $this->view('dashboard/stage-details', $data);
    }
    
    // Gestion des candidatures de l'étudiant
    public function candidatures() {
        // Récupérer l'ID étudiant associé à l'utilisateur
        $etudiant = $this->etudiantModel->findByEmail($_SESSION['user_email'] ?? '');
        $etudiantId = $etudiant ? $etudiant->id_student : 0;
        
        if (!$etudiantId) {
            $_SESSION['error_message'] = "Vous devez compléter votre profil étudiant avant de pouvoir voir vos candidatures.";
            $this->redirect('dashboard/profil');
        }
        
        // Récupérer toutes les candidatures de l'étudiant
        $candidatures = $this->candidatureModel->getStudentCandidatures($etudiantId);
        
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
        
        // Récupérer l'ID étudiant associé à l'utilisateur
        $etudiant = $this->etudiantModel->findByEmail($_SESSION['user_email'] ?? '');
        $etudiantId = $etudiant ? $etudiant->id_student : 0;
        
        if (!$etudiantId) {
            $_SESSION['error_message'] = "Vous devez compléter votre profil étudiant avant de pouvoir postuler.";
            $this->redirect('dashboard/profil');
        }
        
        // Vérifier si la méthode est POST
        if($this->isPostRequest()) {
            $postData = $this->getPostData();
            
            $candidatureData = [
                'etudiant_id' => $etudiantId,
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
            'title' => 'Postuler - ' . $stage->title,
            'stage' => $stage
        ];
        
        $this->view('dashboard/postuler', $data);
    }
    
    // Supprimer une candidature
    public function delete_candidature($id = null) {
        if($id === null) {
            $this->redirect('dashboard/candidatures');
        }
        
        // Récupérer l'ID étudiant associé à l'utilisateur
        $etudiant = $this->etudiantModel->findByEmail($_SESSION['user_email'] ?? '');
        $etudiantId = $etudiant ? $etudiant->id_student : 0;
        
        if (!$etudiantId) {
            $_SESSION['error_message'] = "Impossible de trouver votre profil étudiant.";
            $this->redirect('dashboard/candidatures');
        }
        
        // Vérifier que la candidature appartient bien à l'étudiant connecté
        $candidature = $this->candidatureModel->findById($id);
        
        if(!$candidature || $candidature->id_student != $etudiantId) {
            $_SESSION['error_message'] = "Vous n'êtes pas autorisé à supprimer cette candidature.";
            $this->redirect('dashboard/candidatures');
        }
        
        if($this->candidatureModel->deleteCandidature($id)) {
            $_SESSION['success_message'] = 'Votre candidature a été annulée avec succès';
        } else {
            $_SESSION['error_message'] = 'Une erreur est survenue lors de l\'annulation de votre candidature';
        }
        
        $this->redirect('dashboard/candidatures');
    }
    
    // Profil de l'étudiant
    public function profil() {
        // Récupérer les données de l'étudiant
        $etudiant = $this->etudiantModel->findByEmail($_SESSION['user_email'] ?? '');
        
        $data = [
            'title' => 'Mon Profil',
            'etudiant' => $etudiant
        ];
        
        $this->view('dashboard/profil', $data);
    }

    // Mise à jour des informations personnelles
    public function update_profile() {
        if(!$this->isPostRequest()) {
            $this->redirect('dashboard/profil');
        }
        
        $postData = $this->getPostData();
        
        // Récupérer l'étudiant
        $etudiant = $this->etudiantModel->findByEmail($_SESSION['user_email'] ?? '');
        $etudiantId = $etudiant ? $etudiant->id_student : 0;
        
        if (!$etudiantId) {
            $_SESSION['error_message'] = "Impossible de trouver votre profil étudiant.";
            $this->redirect('dashboard/profil');
        }
        
        $data = [
            'id' => $etudiantId,
            'name' => trim($postData['name'] ?? ''),
            'email' => trim($postData['email'] ?? ''),
            'phone_number' => trim($postData['phone_number'] ?? ''),
            'date_of_birth' => $postData['date_of_birth'] ?? null,
            'location' => trim($postData['location'] ?? '')
        ];
        
        if($this->etudiantModel->updateStudent($data)) {
            $_SESSION['success_message'] = 'Vos informations personnelles ont été mises à jour avec succès';
        } else {
            $_SESSION['error_message'] = 'Une erreur est survenue lors de la mise à jour de vos informations';
        }
        
        $this->redirect('dashboard/profil');
    }

    // Mise à jour des informations académiques
    public function update_academic() {
        if(!$this->isPostRequest()) {
            $this->redirect('dashboard/profil');
        }
        
        $postData = $this->getPostData();
        
        // Récupérer l'étudiant
        $etudiant = $this->etudiantModel->findByEmail($_SESSION['user_email'] ?? '');
        $etudiantId = $etudiant ? $etudiant->id_student : 0;
        
        if (!$etudiantId) {
            $_SESSION['error_message'] = "Impossible de trouver votre profil étudiant.";
            $this->redirect('dashboard/profil');
        }
        
        $data = [
            'id' => $etudiantId,
            'year' => $postData['year'] ?? '',
            'degree' => trim($postData['degree'] ?? ''),
            'school' => trim($postData['school'] ?? '')
        ];
        
        if($this->etudiantModel->updateStudent($data)) {
            $_SESSION['success_message'] = 'Vos informations académiques ont été mises à jour avec succès';
        } else {
            $_SESSION['error_message'] = 'Une erreur est survenue lors de la mise à jour de vos informations';
        }
        
        $this->redirect('dashboard/profil');
    }

    // Mise à jour de la description
    public function update_description() {
        if(!$this->isPostRequest()) {
            $this->redirect('dashboard/profil');
        }
        
        $postData = $this->getPostData();
        
        // Récupérer l'étudiant
        $etudiant = $this->etudiantModel->findByEmail($_SESSION['user_email'] ?? '');
        $etudiantId = $etudiant ? $etudiant->id_student : 0;
        
        if (!$etudiantId) {
            $_SESSION['error_message'] = "Impossible de trouver votre profil étudiant.";
            $this->redirect('dashboard/profil');
        }
        
        $data = [
            'id' => $etudiantId,
            'description' => trim($postData['description'] ?? '')
        ];
        
        if($this->etudiantModel->updateStudent($data)) {
            $_SESSION['success_message'] = 'Votre description a été mise à jour avec succès';
        } else {
            $_SESSION['error_message'] = 'Une erreur est survenue lors de la mise à jour de votre description';
        }
        
        $this->redirect('dashboard/profil');
    }

    // Mise à jour de l'image de profil
    public function update_profile_image() {
        if(!$this->isPostRequest()) {
            $this->redirect('dashboard/profil');
        }
        
        // Vérifier si un fichier a été téléchargé
        if(empty($_FILES['profile_image']['name'])) {
            $_SESSION['error_message'] = 'Aucune image sélectionnée';
            $this->redirect('dashboard/profil');
        }
        
        // Récupérer l'étudiant
        $etudiant = $this->etudiantModel->findByEmail($_SESSION['user_email'] ?? '');
        $etudiantId = $etudiant ? $etudiant->id_student : 0;
        
        if (!$etudiantId) {
            $_SESSION['error_message'] = "Impossible de trouver votre profil étudiant.";
            $this->redirect('dashboard/profil');
        }
        
        // Configurer les paramètres de téléchargement
        $uploadDir = 'public/uploads/profile/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        $file = $_FILES['profile_image'];
        
        // Vérifier le type de fichier
        if(!in_array($file['type'], $allowedTypes)) {
            $_SESSION['error_message'] = 'Type de fichier non autorisé. Utilisez JPG, PNG ou GIF.';
            $this->redirect('dashboard/profil');
        }
        
        // Vérifier la taille du fichier
        if($file['size'] > $maxSize) {
            $_SESSION['error_message'] = 'Le fichier est trop volumineux. Taille maximum: 2MB.';
            $this->redirect('dashboard/profil');
        }
        
        // Générer un nom de fichier unique
        $filename = uniqid() . '_' . $file['name'];
        $destination = $uploadDir . $filename;
        
        // Déplacer le fichier
        if(move_uploaded_file($file['tmp_name'], $destination)) {
            // Supprimer l'ancienne image si elle existe
            if($etudiant->image && file_exists($uploadDir . $etudiant->image)) {
                unlink($uploadDir . $etudiant->image);
            }
            
            // Mettre à jour l'image dans la base de données
            $data = [
                'id' => $etudiantId,
                'image' => $filename
            ];
            
            if($this->etudiantModel->updateStudent($data)) {
                $_SESSION['success_message'] = 'Votre image de profil a été mise à jour avec succès';
            } else {
                $_SESSION['error_message'] = 'Une erreur est survenue lors de la mise à jour de votre image';
            }
        } else {
            $_SESSION['error_message'] = 'Une erreur est survenue lors du téléchargement de l\'image';
        }
        
        $this->redirect('dashboard/profil');
    }

    // Mise à jour du CV
    public function update_cv() {
        if(!$this->isPostRequest()) {
            $this->redirect('dashboard/profil');
        }
        
        // Vérifier si un fichier a été téléchargé
        if(empty($_FILES['cv_file']['name'])) {
            $_SESSION['error_message'] = 'Aucun fichier sélectionné';
            $this->redirect('dashboard/profil');
        }
        
        // Récupérer l'étudiant
        $etudiant = $this->etudiantModel->findByEmail($_SESSION['user_email'] ?? '');
        $etudiantId = $etudiant ? $etudiant->id_student : 0;
        
        if (!$etudiantId) {
            $_SESSION['error_message'] = "Impossible de trouver votre profil étudiant.";
            $this->redirect('dashboard/profil');
        }
        
        // Configurer les paramètres de téléchargement
        $uploadDir = 'public/uploads/cv/';
        $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        $file = $_FILES['cv_file'];
        
        // Vérifier le type de fichier
        if(!in_array($file['type'], $allowedTypes)) {
            $_SESSION['error_message'] = 'Type de fichier non autorisé. Utilisez PDF, DOC ou DOCX.';
            $this->redirect('dashboard/profil');
        }
        
        // Vérifier la taille du fichier
        if($file['size'] > $maxSize) {
            $_SESSION['error_message'] = 'Le fichier est trop volumineux. Taille maximum: 5MB.';
            $this->redirect('dashboard/profil');
        }
        
        // Générer un nom de fichier unique
        $filename = uniqid() . '_' . $file['name'];
        $destination = $uploadDir . $filename;
        
        // Déplacer le fichier
        if(move_uploaded_file($file['tmp_name'], $destination)) {
            // Supprimer l'ancien CV s'il existe
            if($etudiant->cv && file_exists($uploadDir . $etudiant->cv)) {
                unlink($uploadDir . $etudiant->cv);
            }
            
            // Mettre à jour le CV dans la base de données
            if($this->etudiantModel->updateCV($etudiantId, $filename)) {
                $_SESSION['success_message'] = 'Votre CV a été mis à jour avec succès';
            } else {
                $_SESSION['error_message'] = 'Une erreur est survenue lors de la mise à jour de votre CV';
            }
        } else {
            $_SESSION['error_message'] = 'Une erreur est survenue lors du téléchargement du CV';
        }
        
        $this->redirect('dashboard/profil');
    }

    // Afficher la liste des stages favoris
    public function wishlist() {
        // Récupérer l'ID étudiant associé à l'utilisateur
        $etudiant = $this->etudiantModel->findByEmail($_SESSION['user_email'] ?? '');
        $etudiantId = $etudiant ? $etudiant->id_student : 0;
        
        if (!$etudiantId) {
            $_SESSION['error_message'] = "Vous devez compléter votre profil étudiant avant de pouvoir voir vos favoris.";
            $this->redirect('dashboard/profil');
        }
        
        // Récupérer tous les stages favoris de l'étudiant
        $wishlist = $this->stageModel->getWishlistStages($etudiantId);
        
        $data = [
            'title' => 'Mes stages favoris',
            'wishlist' => $wishlist
        ];
        
        $this->view('dashboard/wishlist', $data);
    }

    // Ajouter un stage aux favoris
    public function add_wishlist($stageId = null) {
        if($stageId === null) {
            $this->redirect('dashboard/stages');
        }
        
        // Vérifie si c'est une requête AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        // Récupérer l'ID étudiant associé à l'utilisateur
        $etudiant = $this->etudiantModel->findByEmail($_SESSION['user_email'] ?? '');
        $etudiantId = $etudiant ? $etudiant->id_student : 0;
        
        if (!$etudiantId) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => 'Profil étudiant incomplet']);
                exit;
            }
            $_SESSION['error_message'] = "Vous devez compléter votre profil étudiant.";
            $this->redirect('dashboard/profil');
        }
        
        // Ajouter le stage aux favoris
        $result = $this->stageModel->addToWishlist($etudiantId, $stageId);
        
        if ($isAjax) {
            echo json_encode(['success' => $result]);
            exit;
        }
        
        if($result) {
            $_SESSION['success_message'] = 'Le stage a été ajouté à vos favoris';
        } else {
            $_SESSION['error_message'] = 'Une erreur est survenue ou le stage est déjà dans vos favoris';
        }
        
        $this->redirect('dashboard/stage/' . $stageId);
    }

    // Retirer un stage des favoris
    public function remove_wishlist($stageId = null) {
        if($stageId === null) {
            $this->redirect('dashboard/wishlist');
        }
        
        // Vérifie si c'est une requête AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        // Récupérer l'ID étudiant associé à l'utilisateur
        $etudiant = $this->etudiantModel->findByEmail($_SESSION['user_email'] ?? '');
        $etudiantId = $etudiant ? $etudiant->id_student : 0;
        
        if (!$etudiantId) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => 'Profil étudiant introuvable']);
                exit;
            }
            $_SESSION['error_message'] = "Impossible de trouver votre profil étudiant.";
            $this->redirect('dashboard/wishlist');
        }
        
        // Retirer le stage des favoris
        $result = $this->stageModel->removeFromWishlist($etudiantId, $stageId);
        
        if ($isAjax) {
            echo json_encode(['success' => $result]);
            exit;
        }
        
        if($result) {
            $_SESSION['success_message'] = 'Le stage a été retiré de vos favoris';
        } else {
            $_SESSION['error_message'] = 'Une erreur est survenue';
        }
        
        $this->redirect('dashboard/wishlist');
    }
}