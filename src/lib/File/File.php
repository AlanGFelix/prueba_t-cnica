<?php

namespace App\lib\File;

class File {
    private $fileResource;
    public function __construct($file, $mode) {
        $this->fileResource = fopen($file, $mode);
    }

    public function readLine() {
        return fgets($this->fileResource);
    }
}