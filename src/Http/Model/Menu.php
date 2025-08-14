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

    public static function printMenu($menus, $parent = null){
        $menuRows = "";
        $parentName = $parent['name'] ?? '';
        
        foreach ($menus as $menu){
            $menuRows .= 
                "<tr>
                    <td>
                        {$menu['id']}
                    </td>
                    <td>
                        {$menu['name']}
                    </td>
                    <td>
                        $parentName
                    </td>
                    <td>
                        {$menu['description']}
                    </td>
                    <td>
                        Acciones...
                    </td>
                </tr>";

            $submenus = $menu['submenus'];
            if($submenus) {
                $menuRows .= self::printMenu($submenus, $menu);  
            }
        }

        return $menuRows;
    }
}