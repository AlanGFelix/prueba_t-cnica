<?php
namespace App\Http;

use Exception;

class Request{
    private $segments = [];
    private $controller;
    private $method;

    public function __construct(){
        $segments = explode('/',$_SERVER['REQUEST_URI']);
        $this->segments = array_splice($segments, 3, 2);
        
        $this->setController();
        $this->setMethod();
    }

    private function setController() {
        $this->controller = empty($this->segments[0]) ?
            'home' :
            $this->segments[0];
    }

    private function setMethod() {
        $this->method = empty($this->segments[1]) ?
            'index' :
            $this->segments[1];
    }

    public function getController() {
        $controller = ucfirst($this->controller);

        return "App\Http\Controllers\\{$controller}Controller";
    }

    public function getMethod() {
        return $this->method;
    }

    public function send() {
        $controller = $this->getController();
        $method = $this->getMethod();

        $response = call_user_func([
            new $controller,
            $method
        ]);
        
        try {
            if ($response instanceof Response) {
                $response->send();
            } else {
                throw new Exception('Error al procesar la solicitud');
            }
        } catch(Exception $e) {
            echo "Detalle: {$e->getMessage()}";
        }

        
    }
}