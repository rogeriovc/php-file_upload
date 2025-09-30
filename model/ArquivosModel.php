<?php

require_once __DIR__ . '/../database/Database.php';

class ArquivosModel {
    private $tabela = "imagens";
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conectar();
    }

    public function listar() {
        $query = "SELECT * FROM {$this->tabela} ORDER BY data_envio DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function buscarPorId($id) {
        $query = "SELECT * FROM {$this->tabela} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function criar($dados) {
        $query = "INSERT INTO {$this->tabela} (nome, nome_original, caminho, tamanho)
                  VALUES (:nome, :nome_original, :caminho, :tamanho)";
       
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':nome_original', $dados['nome_original']);
        $stmt->bindParam(':caminho', $dados['caminho']);
        $stmt->bindParam(':tamanho', $dados['tamanho'], PDO::PARAM_INT);
       
        return $stmt->execute();
    }

    public function deletar($id) {
        $query = "DELETE FROM {$this->tabela} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}