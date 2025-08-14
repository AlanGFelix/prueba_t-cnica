<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\IController;
use App\Http\Interfaces\IMenu;
use App\Http\Response;

class MenuController implements IController{
    private $menuInstance;
    public function __construct(IMenu $menuInstance) {
        $this->menuInstance = $menuInstance;
    }
    public function index() {
        $menus = $this->menuInstance->getAll();

        return new Response('menu', ['menus' => $menus]);
    }

    public function create() {
        return new Response('create-menu');
    }

    public function store($request) {
        $this->validateInputs($request);

        $name = $request['name'];
        $description = $request['description'];
        $parent = $request['parent'] ?? null;

        $this->menuInstance->create($name, $description, $parent);
        
        header("Location: ../");
        exit(303);
    }

    public function update() {
        
    }

    private function validateInputs($request) {
        $parent = $request['parent'] ?? null;
        $name = $request['name'];
        $description = $request['description'];

       if (!$name) {
            die("El valor del nombre del menú debe ser enviado");
        }

        if (!$description) {
            die("El valor de la descripción debe ser enviado");
        } 

        if (!is_int($parent)) {
            die("El valor del menú padre debe ser un id de otro menú");
        }
        
        if (!is_string($name)) {
            die("El valor del nombre del menú debe ser string");
        }

        if (!is_string($description)) {
            die("El valor de la descripción debe ser string");
        }

        if ($name = "") {
            die("El valor del nombre del menú no puede estar vacío");
        }

        if ($description = "") {
            die("El valor de la descripción no puede estar vacío");
        }

        $menu = $this->menuInstance->get($parent);
        if (count($menu) < 1) {
            die("Debe mandar un menú padre existente");
        }
    }
}