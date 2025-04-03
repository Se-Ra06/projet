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
        // Routes pour la page d'accueil
        $this->addRoute('home', 'HomeController', 'index');
        $this->addRoute('home/login', 'HomeController', 'login');
        $this->addRoute('home/logout', 'HomeController', 'logout');
        
        // Routes pour le dashboard
        $this->addRoute('dashboard', 'DashboardController', 'index');
        $this->addRoute('dashboard/stages', 'DashboardController', 'stages');
        $this->addRoute('dashboard/stage/{id}', 'DashboardController', 'stage');
        $this->addRoute('dashboard/candidatures', 'DashboardController', 'candidatures');
        $this->addRoute('dashboard/postuler/{id}', 'DashboardController', 'postuler');
        $this->addRoute('dashboard/profil', 'DashboardController', 'profil');
        $this->addRoute('dashboard/updateProfile', 'DashboardController', 'updateProfile');
        $this->addRoute('dashboard/uploadCV', 'DashboardController', 'uploadCV');
        $this->addRoute('dashboard/downloadCV', 'DashboardController', 'downloadCV');
        $this->addRoute('dashboard/annulerCandidature/{id}', 'DashboardController', 'annulerCandidature');
    }
    
    /**
     * Ajouter une route
     * 
     * @param string $url L'URL de la route
     * @param string $controller Le contrôleur à appeler
     * @param string $method La méthode à appeler
     * @return void
     */
    public function addRoute($url, $controller, $method) {
        $this->routes[$url] = [
            'controller' => $controller,
            'method' => $method
        ];
    }
    
    /**
     * Résoudre l'URL et appeler le contrôleur et la méthode appropriés
     * 
     * @param string $url L'URL à résoudre
     * @return void
     */
    public function resolve($url) {
        // Supprimer le trailing slash si présent
        $url = rtrim($url, '/');
        
        // Si l'URL est vide, utiliser la route par défaut
        if(empty($url)) {
            $url = 'home';
        }
        
        // Vérifier si la route existe directement
        if(isset($this->routes[$url])) {
            $controller = $this->routes[$url]['controller'];
            $method = $this->routes[$url]['method'];
            $params = [];
        } else {
            // Essayer de faire correspondre une route avec des paramètres
            $matched = false;
            $params = [];
            
            foreach($this->routes as $route => $handler) {
                // Convertir les segments de route en expressions régulières
                $pattern = preg_replace('/{([a-z]+)}/', '([^/]+)', $route);
                $pattern = '#^' . $pattern . '$#i';
                
                // Vérifier si l'URL correspond au pattern
                if(preg_match($pattern, $url, $matches)) {
                    $matched = true;
                    $controller = $handler['controller'];
                    $method = $handler['method'];
                    
                    // Extraire les paramètres
                    array_shift($matches); // Supprimer le premier élément (l'URL complète)
                    $params = $matches;
                    
                    break;
                }
            }
            
            // Si aucune route ne correspond, utiliser la route par défaut
            if(!$matched) {
                $urlParts = explode('/', $url);
                
                // Vérifier si le premier segment peut être un contrôleur
                if(isset($urlParts[0]) && !empty($urlParts[0])) {
                    $controllerName = ucfirst($urlParts[0]) . 'Controller';
                    
                    // Vérifier si le contrôleur existe
                    if(file_exists(APPROOT . '/controllers/' . $controllerName . '.php')) {
                        $controller = $controllerName;
                        
                        // Vérifier si le deuxième segment peut être une méthode
                        if(isset($urlParts[1]) && !empty($urlParts[1])) {
                            $method = $urlParts[1];
                            
                            // Les segments restants sont des paramètres
                            $params = array_slice($urlParts, 2);
                        } else {
                            $method = $this->defaultMethod;
                            $params = [];
                        }
                    } else {
                        $controller = $this->defaultController;
                        $method = $this->defaultMethod;
                        $params = [];
                    }
                } else {
                    $controller = $this->defaultController;
                    $method = $this->defaultMethod;
                    $params = [];
                }
            }
        }
        
        // Vérifier si le contrôleur existe
        if(!file_exists(APPROOT . '/controllers/' . $controller . '.php')) {
            // Si le contrôleur n'existe pas, afficher une page 404
            http_response_code(404);
            require_once APPROOT . '/views/errors/404.php';
            return;
        }
        
        // Charger le contrôleur
        require_once APPROOT . '/controllers/' . $controller . '.php';
        
        // Instancier le contrôleur
        $controllerInstance = new $controller();
        
        // Vérifier si la méthode existe
        if(!method_exists($controllerInstance, $method)) {
            // Si la méthode n'existe pas, afficher une page 404
            http_response_code(404);
            require_once APPROOT . '/views/errors/404.php';
            return;
        }
        
        // Appeler la méthode avec les paramètres
        call_user_func_array([$controllerInstance, $method], $params);
    }
} 