<?php

namespace App\lib\Env;

use App\lib\File\File;

class Env {
    private static $completed = false;
    public static function getEnv() {
        if(self::$completed){
            return;
        }

        $envFile = '.env';
        $file = new File($envFile, 'r');

        if($file){
            while($line = $file->readLine()){
                $arr = explode('=',$line);
                $variableName = trim($arr[0]);
                $variableValue = str_replace('"',"", trim($arr[1]));

                $_ENV[$variableName] = $variableValue;
            }
            self::$completed = true;
        }
    }
}