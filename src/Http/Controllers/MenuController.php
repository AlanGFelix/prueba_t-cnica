<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\lib\Database\Database;
use PDOException;

class MenuController {
    private $dbInstance;
    public function __construct($dbInstance) {
        $this->dbInstance = $dbInstance;
    }
    public function index() {
        $menus = $this->dbInstance->execute("SELECT * FROM menus WHERE id_parent is null");

        $menus = $this->getChilds($menus);

        return new Response('menu', ['menus' => $menus]);
    }

    public function create() {
        return new Response('create-menu');
    }

    public function store($request) {
        $this->validateInputs($request);

        $parent = $request['parent'] ?? null;
        $name = $request['name'];
        $description = $request['description'];

        try {
            $this->dbInstance->execute(
                "insert into menus (name, description, id_parent) values (?,?,?)",
                [$name, $description, $parent]
            );
        } catch (PDOException $e) {
            die("Error al generar el menú. Detalle: " . $e->getMessage());
        }
        
        header("Location: ../");
        exit(303);
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

        $menu = $this->dbInstance->execute("select * from menus WHERE id = ?", $parent);
        if (count($menu) < 1) {
            die("Debe mandar un menú padre existente");
        }
    }

    private function getChilds($menus) {
        foreach ($menus as &$menu) {
            $submenus = null;
            $idMenu = $menu['id'];
            
            try {
                $submenus = $this->dbInstance->execute("SELECT * FROM menus WHERE id_parent = ?", [$idMenu]);
            } catch (PDOException $e) {
                die("Error al buscar los submenús. Detalle: " . $e->getMessage());
            }

            if($submenus) {
                $submenus = $this->getChilds($submenus);
            }
            $menu['submenus'] = $submenus;
        }
        
        return $menus;
    }
}