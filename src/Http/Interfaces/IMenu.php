<?php

namespace App\Http\Interfaces;

interface IMenu {
    function get($id);
    function getAll();
    function getAllWithChildren();
    function getAllWithParent();
    function create($name, $description, $id_parent = null);
    function update($id, $name, $description, $id_parent = null);
    function delete($id);
    // static function flatMenu($menu, $parent = null);
}