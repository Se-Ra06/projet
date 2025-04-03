<?php
class HomeController extends Controller {
    public function __construct() {
        // Initialiser les modèles dont on a besoin
    }
    
    // Méthode pour la page d'accueil
    public function index() {
        $data = [
            'title' => 'Bienvenue sur ' . SITENAME,
            'description' => 'Trouvez facilement votre stage idéal'
        ];
        
        // Charger la vue d'accueil
        $this->view('home/index', $data);
    }
    
    // Méthode pour la page de connexion
    public function login() {
        // Si l'utilisateur est déjà connecté, rediriger vers le dashboard
        if($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        $data = [
            'title' => 'Connexion',
            'email' => '',
            'password' => '',
            'email_err' => '',
            'password_err' => ''
        ];
        
        // Vérifier si la méthode est POST
        if($this->isPostRequest()) {
            // Traiter le formulaire
            $postData = $this->getPostData();
            
            // Valider l'email
            $data['email'] = trim($postData['email']);
            if(empty($data['email'])) {
                $data['email_err'] = 'Veuillez entrer votre email';
            }
            
            // Valider le mot de passe
            $data['password'] = trim($postData['password']);
            if(empty($data['password'])) {
                $data['password_err'] = 'Veuillez entrer votre mot de passe';
            }
            
            // Vérifier si les erreurs sont vides
            if(empty($data['email_err']) && empty($data['password_err'])) {
                // TODO: Implémenter la logique de connexion avec le modèle User
                
                // Rediriger temporairement vers le dashboard étudiant
                $this->redirect('dashboard');
            }
        }
        
        // Charger la vue de connexion
        $this->view('home/login', $data);
    }
    
    // Méthode pour la déconnexion
    public function logout() {
        // Détruire la session
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        
        session_destroy();
        
        // Rediriger vers la page de connexion
        $this->redirect('home/login');
    }
} 