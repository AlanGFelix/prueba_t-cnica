<?php

namespace App\lib\Database;

use PDOException;
Use App\lib\Env\Env;

class Database {
    private $connection = null;
    public function __construct(){
        Env::getEnv();
        $this->connect();
    }

    private function connect() {
        try {
            $connectionString = $_ENV['DB_DRIVER'].
            ":host=". $_ENV['DB_HOST'].
            ";port=". $_ENV['DB_PORT'].
            ";dbname=". $_ENV['DB_NAME'] .
            ";charset=". $_ENV['DB_CHARSET'];
            $username = $_ENV['DB_USERNAME'];
            $password = $_ENV['DB_PASSWORD'];

            $connection = new \PDO($connectionString, $username, $password);
            $this->connection = $connection;
        } catch (PDOException $e) {
            die("Error conectando a la base de datos: ". $e->getMessage());
        }
    }
    
    public function execute($query, $params = null) {
        try {
            $connection = $this->connection;
            $statement = $connection->prepare($query);
            $statement->execute($params);
            $result = $statement->fetchAll();

            return $result;
        } catch (PDOException $e) {
            die("Error consultando los nombres: ". $e->getMessage());
        }

    }
}