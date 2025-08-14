<?php
namespace App\Http;

use App\Http\Interfaces\IRequest;
use App\Http\Model\Menu;
use App\lib\Database\Database;
use Exception;

class Request implements IRequest{
    private $segments = [];
    private $controller;
    private $method;

    public function __construct(){
        $segments = explode('/',$_SERVER['REQUEST_URI']);
        $this->segments = array_splice($segments, 2, 2);
        
        $this->setController();
        $this->setMethod();
    }

    public function setController() {
        $this->controller = empty($this->segments[0]) ?
            'menu' :
            $this->segments[0];
    }

    public function setMethod() {
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
        $databaseInstance = new Database();
        $menuInstance = new Menu($databaseInstance);

        $response = call_user_func([
            new $controller($menuInstance),
            $method
        ]);
        
        try {
            if ($response instanceof Response) {
                $response->send();
            } else {
                throw new Exception('Error al procesar la solicitud');
            }
        } catch(Exception $e) {
            echo "Error al gestionar el controller. Detalle: {$e->getMessage()}";
        }
    }
}