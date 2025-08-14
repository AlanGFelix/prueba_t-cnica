<?php

namespace App\lib\File;

class File {
    private $fileResource;
    public function __construct($file, $mode) {
        $rootPath = __DIR__. '/../../../';
        $path = $rootPath . $file;
        $this->fileResource = fopen($path, $mode);
    }

    public function readLine() {
        return fgets($this->fileResource);
    }
}