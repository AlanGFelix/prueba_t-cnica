<?php

namespace App\Http\Interfaces;

interface IController {
    function index();
    function create();
    function store($request);
    function update();
}