<?php

namespace App\Http\Interfaces;

interface IMenu {
    function get($id);
    function getAll();
    function create($name, $description, $id_parent = null);
    function update($id, $name, $description, $id_parent = null);
    function delete($id);
}