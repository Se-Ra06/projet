<?php
/**
 * Classe Router
 * 
 * Cette classe gère le routage des requêtes HTTP vers les contrôleurs et méthodes appropriés.
 */
class Router {
    /**
     * Tableau contenant les routes de l'application
     * @var array
     */
    private $routes = [];
    
    /**
     * Contrôleur par défaut
     * @var string
     */
    private $defaultController = 'HomeController';
    
    /**
     * Méthode par défaut
     * @var string
     */
    private $defaultMethod = 'index';
    
    /**
     * Constructeur
     */
    public function __construct() {
        // Initialiser les routes
        $this->initRoutes();
    }
    
    /**
     * Initialiser les routes de l'application
     * 
     * @return void
     */
    private function initRoutes() {
        // Routes pour la page d'accueil et utilisateur
        $this->addRoute('', 'HomeController', 'index');
        $this->addRoute('home', 'HomeController', 'index');
        $this->addRoute('home/index', 'HomeController', 'index');
        $this->addRoute('home/login', 'HomeController', 'login');
        $this->addRoute('home/register', 'HomeController', 'register');
        $this->addRoute('home/logout', 'HomeController', 'logout');
        $this->addRoute('home/about', 'HomeController', 'about');
        $this->addRoute('home/contact', 'HomeController', 'contact');
        
        // Dashboard principal
        $this->addRoute('dashboard', 'DashboardController', 'index');
        
        // Routes pour les stages
        $this->addRoute('dashboard/stages', 'DashboardController', 'stages');
        $this->addRoute('dashboard/stage/{id}', 'DashboardController', 'stage');
        $this->addRoute('dashboard/postuler/{id}', 'DashboardController', 'postuler');
        
        // Routes pour les candidatures
        $this->addRoute('dashboard/candidatures', 'DashboardController', 'candidatures');
        $this->addRoute('dashboard/delete_candidature/{id}', 'DashboardController', 'delete_candidature');
        
        // Routes pour le profil
        $this->addRoute('dashboard/profil', 'DashboardController', 'profil');
        $this->addRoute('dashboard/update_profile', 'DashboardController', 'update_profile');
        $this->addRoute('dashboard/update_academic', 'DashboardController', 'update_academic');
        $this->addRoute('dashboard/update_description', 'DashboardController', 'update_description');
        $this->addRoute('dashboard/update_profile_image', 'DashboardController', 'update_profile_image');
        $this->addRoute('dashboard/update_cv', 'DashboardController', 'update_cv');
        
        // Routes pour la wishlist (favoris)
        $this->addRoute('dashboard/wishlist', 'DashboardController', 'wishlist');
        $this->addRoute('dashboard/add_wishlist/{id}', 'DashboardController', 'add_wishlist');
        $this->addRoute('dashboard/remove_wishlist/{id}', 'DashboardController', 'remove_wishlist');
    }
    
    /**
     * Ajoute une route à la liste des routes
     *
     * @param string $route L'URL de la route (peut contenir des paramètres entre accolades)
     * @param string $controller Le contrôleur à appeler
     * @param string $method La méthode à appeler
     * @return void
     */
    private function addRoute($route, $controller, $method) {
        $this->routes[] = [
            'route' => $route,
            'controller' => $controller,
            'method' => $method
        ];
    }
    
    /**
     * Résout l'URL actuelle et appelle le contrôleur et la méthode correspondants
     *
     * @return void
     */
    public function resolve() {
        // Obtenir l'URL actuelle
        $url = $this->getCurrentUrl();
        
        // Parcourir toutes les routes
        foreach ($this->routes as $route) {
            // Convertir les paramètres de route en pattern regex
            $pattern = $this->convertRouteToRegex($route['route']);
            
            // Vérifier si l'URL correspond à la route
            if (preg_match($pattern, $url, $matches)) {
                // Supprimer la première correspondance (la correspondance complète)
                array_shift($matches);
                
                // Créer le contrôleur
                $controllerName = $route['controller'];
                $controllerFile = '../app/controllers/' . $controllerName . '.php';
                
                // Vérifier si le fichier du contrôleur existe
                if (!file_exists($controllerFile)) {
                    throw new Exception("Controller file {$controllerFile} not found");
                }
                
                // Inclure le fichier du contrôleur
                require_once $controllerFile;
                
                // Vérifier si la classe du contrôleur existe
                if (!class_exists($controllerName)) {
                    throw new Exception("Controller class {$controllerName} not found");
                }
                
                // Instancier le contrôleur
                $controller = new $controllerName();
                
                // Obtenir la méthode à appeler
                $method = $route['method'];
                
                // Vérifier si la méthode existe
                if (!method_exists($controller, $method)) {
                    throw new Exception("Method {$method} not found in controller {$controllerName}");
                }
                
                // Appeler la méthode avec les paramètres
                call_user_func_array([$controller, $method], $matches);
                return;
            }
        }
        
        // Si aucune route ne correspond, afficher une erreur 404
        header("HTTP/1.0 404 Not Found");
        $this->loadView('errors/404');
    }
    
    /**
     * Convertit une route en pattern regex
     *
     * @param string $route La route à convertir
     * @return string Le pattern regex
     */
    private function convertRouteToRegex($route) {
        // Échapper les caractères spéciaux
        $pattern = preg_quote($route, '/');
        
        // Remplacer les paramètres {param} par des groupes de capture
        $pattern = preg_replace('/\\\{([a-zA-Z0-9_]+)\\\}/', '([^\/]+)', $pattern);
        
        // Ajouter les délimiteurs et limiter la correspondance à l'URL complète
        return '/^' . $pattern . '$/';
    }
    
    /**
     * Obtient l'URL actuelle sans le chemin de base
     *
     * @return string L'URL actuelle
     */
    private function getCurrentUrl() {
        // Obtenir l'URL complète
        $url = $_SERVER['REQUEST_URI'];
        
        // Supprimer les paramètres de requête
        $url = strtok($url, '?');
        
        // Supprimer le chemin de base
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        if ($basePath !== '/' && $basePath !== '\\') {
            $url = str_replace($basePath, '', $url);
        }
        
        // Supprimer les slashes au début et à la fin
        $url = trim($url, '/');
        
        return $url;
    }
    
    /**
     * Charge une vue
     *
     * @param string $view Le nom de la vue
     * @return void
     */
    private function loadView($view) {
        // Vérifier si la vue existe
        $viewFile = APPROOT . '/views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            echo "<h1>404 - Page Not Found</h1>";
            echo "<p>The requested page '{$view}' could not be found.</p>";
        }
    }
} 