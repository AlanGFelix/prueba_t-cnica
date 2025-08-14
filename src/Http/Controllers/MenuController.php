<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\lib\Database\Database;

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

    private function getChilds($menus) {
        foreach ($menus as &$menu) {
            $submenus = null;
            $idMenu = $menu['id'];
            $submenus = $this->dbInstance->execute("SELECT * FROM menus WHERE id_parent = ?", [$idMenu]);

            if($submenus) {
                $submenus = $this->getChilds($submenus);
            }
            $menu['submenus'] = $submenus;
        }
        
        return $menus;
    }
}