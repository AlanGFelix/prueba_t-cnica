<?php

namespace App\Http\Interfaces;


interface IRequest {
    function setController();
    function getController();
    function setMethod();
    function getMethod();
    function send();
}