<?php

namespace App\Http\Interfaces;

interface IResponse {
    function getData();
    function getView();
    function send();
}