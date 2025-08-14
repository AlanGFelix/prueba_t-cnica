<?php

namespace App\Http\Interfaces;

interface IController {
    function index();
    function create();
    function store();
    function update();
}