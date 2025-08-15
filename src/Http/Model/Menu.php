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
        $menu = $this->dbInstance->execute("select * from menus WHERE id = ? AND status = 1", [$id]);

        if($menu) {
            $menu = $menu[0];
        }

        return $menu;
    }

    public function getAll() {
        $menus = $this->dbInstance->execute("SELECT * FROM menus WHERE status = 1 ORDER BY id");

        return $menus;
    }

    public function getAllWithChildren() {
        $menus = $this->dbInstance->execute("SELECT * FROM menus WHERE id_parent is null AND status = 1 ORDER BY id");
        $menus = $this->getChilds($menus);

        return $menus;
    }

    public function getAllWithParent() {
        $allMenus = $this->dbInstance->execute("SELECT * FROM menus WHERE status = 1 AND id_parent is null  ORDER BY id");
        $menus = $this->getChilds($allMenus);
        $menus = $this->orderMenus($menus);

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
        try {
            $this->dbInstance->execute(
                "UPDATE menus SET status = 2 WHERE id = :id OR id_parent = :id",
                ['id' => $id]
            );
        } catch (PDOException $e) {
            die("Error al eliminar el menú. Detalle: " . $e->getMessage());
        }
    }

    private function getChilds($menus) {
        foreach ($menus as &$menu) {
            $submenus = null;
            $idMenu = $menu['id'];
            
            try {
                $submenus = $this->dbInstance->execute("SELECT * FROM menus WHERE id_parent = ? AND status = 1", [$idMenu]);
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

    private function orderMenus($menus, $parent = null) {
        $menusOrdered = [];

        foreach ($menus as &$menu) {
            $submenus = isset($menu['submenus']) ? $menu['submenus'] : null;

            if ($parent) {
                $menu['parent'] = $parent;
            }

            unset($menu['submenus']);
            unset($menu['id_parent']);

            array_push($menusOrdered, $menu);
           
            if ($submenus) {
                $menuParent = ['id' => $menu['id'], 'name' => $menu['name']];
                $submenus = $this->orderMenus($submenus, $menuParent);

                $menusOrdered = array_merge($menusOrdered, $submenus);
            }
        }

        return $menusOrdered;
    }
}