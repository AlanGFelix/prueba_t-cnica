<?php

namespace App\Http\Model;

use App\Http\Interfaces\IMenu;
use App\lib\Database\Database;
use PDOException;

class Menu implements IMenu {
    private $dbInstance;

    public function __construct(Database $dbInstance) {
        $this->dbInstance = $dbInstance;
    }

    public function get($id) {
        $menu = $this->dbInstance->execute("select * from menus WHERE id = ?", [$id]);

        if($menu) {
            $menu = $menu[0];
        }

        return $menu;
    }

    public function getAll() {
        $menus = $this->dbInstance->execute("SELECT * FROM menus WHERE id_parent is null");

        return $menus;
    }

    public function getAllWithChildren() {
        $menus = $this->dbInstance->execute("SELECT * FROM menus WHERE id_parent is null");
        $menus = $this->getChilds($menus);

        return $menus;
    }

    public function getAllWithParent() {
        $menusWithoutParent = $this->dbInstance->execute("SELECT * FROM menus WHERE id_parent is null ORDER BY id");
        $menusWithParent = $this->dbInstance->execute("SELECT * FROM menus WHERE id_parent is not null ORDER BY id");
        $menus = $this->getParentName($menusWithParent, $menusWithoutParent);

        return $menus;
    }

    public function create($name, $description, $id_parent = null) {
        try {
            $this->dbInstance->execute(
                "insert into menus (name, description, id_parent) values (?,?,?)",
                [$name, $description, $id_parent]
            );
        } catch (PDOException $e) {
            die("Error al generar el menú. Detalle: " . $e->getMessage());
        }
    }

    public function update($id, $name, $description, $id_parent = null) {
        try {
            $this->dbInstance->execute(
                "UPDATE menus SET name = ?, description = ?, id_parent = ? WHERE id = ?",
                [$name, $description, $id_parent, $id]
            );
        } catch (PDOException $e) {
            die("Error al actualizar el menú. Detalle: " . $e->getMessage());
        }
    }

    public function delete($id) {

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

    private function getParentName($menusWithParent, $menusWithoutParent) {
        $menusParents = $this->indexByParent($menusWithParent);
        $menus = [];

        foreach($menusWithoutParent as $menu) {
            array_push($menus, $menu);
            $idParent = $menu['id'];
            $children = $menusParents[$idParent];

            foreach ($children as $menuChild) {
                unset($menuChild['id_parent']);
                $menuChild['parent'] = ['id' => $menu['id'], 'name' => $menu['name']];
                array_push($menus, $menuChild);
            }
        }

        return $menus;
    }

    private function indexByParent($menusWithParent) {
        $arr = [];

        foreach ($menusWithParent as $menu) {
            $parent = $menu['id_parent'];
            if (!isset($arr[$parent])) {
                $arr[$parent] = [$menu];
            } else {
                array_push($arr[$parent], $menu);
            }
        }

        return $arr;
    }
}