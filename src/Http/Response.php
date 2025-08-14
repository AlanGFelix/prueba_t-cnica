<?php
namespace App\Http;

use App\Http\Interfaces\IResponse;

class Response implements IResponse{
    private $view;
    private $data;
    public function __construct($view, $data = []) {
        $this->view = $view;
        $this->data = $data;
    }

    public function getView() {
        return $this->view;
    }

    public function getData(){
        return $this->data;
    }

    public function send(){
        $view = $this->getView();

        $data = $this->getData();

        $view = __DIR__ . "/../../Views/$view.php";

        require_once __DIR__ . '/../../Views/layout.php';
    }
}