<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\IController;
use App\Http\Interfaces\IMenu;
use Request;
use App\Http\Response;
use Exception;

class MenuController implements IController{
    private $menuInstance;
    public function __construct(IMenu $menuInstance) {
        $this->menuInstance = $menuInstance;
    }
    public function index() {
        $menus = $this->menuInstance->getAllWithParent();

        return new Response('menu', ['menus' => $menus]);
    }

    public function create() {
        $menus = $this->menuInstance->getAll();

        return new Response('create-menu', ['menus' => $menus]);
    }

    public function store() {
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            die("Método no permitio para la operación");
        }

        $body = $_POST;
        $this->validateInputs($body);

        $name = trim($body['name']);
        $description = trim($body['description']);
        $parent = $body['parent'] == "" ? null : $body['parent'];

        $this->menuInstance->create($name, $description, $parent);
        
        return new Response('redirect', ['path' => '../']);
    }

    public function edit($args) {
        $input = $args[0];

        try {
            $id = $this->validateInt($input);
        } catch (Exception $e) {
            die("Error con el Menú solicitado. Detalle: {$e->getMessage()}");
        }

        $menus = $this->menuInstance->getAll();
        $menuUpdate = $this->menuInstance->get($id);

        if(!$menuUpdate) {
            die("No existe un menú con ese id");
        }

        return new Response('create-menu',  ['menus' => $menus, 'menuUpdate' => [$menuUpdate]]);
    }

    public function update($args) {
        $input = $args[0];
        $id = null;

        try {
            $id = $this->validateInt($input);
        } catch (Exception $e) {
            die("Error con el Menú solicitado. Detalle: {$e->getMessage()}");
        }

        $body = $_POST;
        $this->validateInputs($body);
        $name = trim($body['name']);
        $description = trim($body['description']);
        $parent = $body['parent'] == "" ? null : $body['parent'];

        $this->menuInstance->update($id, $name, $description, $parent);

        return new Response('redirect', ['path' => '../../']);
    }

    private function validateInputs($request) {
        if (!isset($request['name'])) {
            die("El valor del nombre del menú debe ser enviado");
        }
        
        if (!isset($request['description'])) {
            die("El valor de la descripción debe ser enviado");
        } 
        
        $parent = $request['parent'];
        $name = trim($request['name']);
        $description = trim($request['description']);

        if (!is_string($name)) {
            die("El valor del nombre del menú debe ser string");
        }

        if (!is_string($description)) {
            die("El valor de la descripción debe ser string");
        }

        if ($name == "") {
            die("El valor del nombre del menú no puede estar vacío");
        }

        if ($description == "") {
            die("El valor de la descripción no puede estar vacío");
        }

        if(isset($parent) && $parent != "") {
            try {
                $parent = $this->validateInt($parent);
            } catch (Exception $e) {
                die("Error con el Menú Padre. Detalle: ".$e->getMessage());
            }

            $menu = $this->menuInstance->get($parent);
            if (!$menu) {
                die("Debe mandar un menú padre existente");
            }
        }
    }

    private function validateInt($value) {
        try {
            $value = (int) $value;
        } catch(Exception $e) {
            throw new Exception("Debe mandar un valor entero");
        }

        return $value;
    }
}