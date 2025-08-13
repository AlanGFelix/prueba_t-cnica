<?php
namespace App\Http;

class Response{
    private $view;
    public function __construct($view) {
        $this->view = $view;
    }

    public function getView() {
        return $this->view;
    }

    public function send(){
        $view = $this->getView();

        $content = file_get_contents(__DIR__ . "/../../Views/$view.php");

        require_once __DIR__ . '/../../Views/layout.php';
    }
}