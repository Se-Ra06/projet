<?php
class Application {
    protected $router;
    
    public function __construct(Router $router) {
        $this->router = $router;
    }
    
    public function run() {
        // Récupérer les infos du routeur
        $controller = $this->router->getController();
        $method = $this->router->getMethod();
        $params = $this->router->getParams();
        
        // Appeler la méthode du contrôleur avec les paramètres
        call_user_func_array([$controller, $method], $params);
    }
} 