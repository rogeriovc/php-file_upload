<?php

class Database {
    private $host = "localhost";
    private $port = "3306";
    private $username = "root";
    private $password = "";
    private $dbName = "arquivosdb";

    public function conectar() {
        try {
            $connUrl = "mysql:host=$this->host;port=$this->port;dbname=$this->dbName;charset=utf8mb4";
            $conn = new PDO(
                $connUrl, 
                $this->username, 
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            return $conn;
        } catch(PDOException $e) {
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }
}