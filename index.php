<?php

use App\Http\Request;

require_once realpath('vendor/autoload.php');

$request = new Request();
$request->send();