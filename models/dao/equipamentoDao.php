<?php

namespace chemistLab\models\dao;

use PDO;
use chemistLab\models\entidades\equipamento;

class equipamentoDao {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function save(equipamento $equipamento) {
        $sql = "INSERT INTO estoque (nome, tipo, descricao, quantidade, unidade_medida, status_equipamento) 
                VALUES (:nome, :tipo, :descricao, :quantidade, :unidade_medida, :status)";
        
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':nome' => $equipamento->getNome(),
            ':tipo' => $equipamento->getTipo(),
            ':descricao' => $equipamento->getDescricao(),
            ':quantidade' => $equipamento->getQuantidade(),
            ':unidade_medida' => $equipamento->getUnidadeMedida(),
            ':status' => $equipamento->getStatusEquipamento()
        ]);
    }
    
    public function findAll() {
        $stmt = $this->pdo->query("SELECT id, nome, tipo, quantidade, status_equipamento 
                                    FROM estoque 
                                    ORDER BY nome ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT id, nome, tipo, descricao, quantidade, unidade_medida, status_equipamento FROM estoque WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return new equipamento(
            $data['nome'],
            $data['tipo'],
            $data['descricao'], 
            $data['quantidade'],
            $data['unidade_medida'],
            $data['status_equipamento'],
            $data['id'] 
        );
    }
    
    public function update(equipamento $equipamento) {
        $sql = "UPDATE estoque SET 
                nome = :nome, 
                tipo = :tipo, 
                descricao = :descricao, 
                quantidade = :quantidade, 
                unidade_medida = :unidade_medida, 
                status_equipamento = :status 
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':nome' => $equipamento->getNome(),
            ':tipo' => $equipamento->getTipo(),
            ':descricao' => $equipamento->getDescricao(), 
            ':quantidade' => $equipamento->getQuantidade(),
            ':unidade_medida' => $equipamento->getUnidadeMedida(),
            ':status' => $equipamento->getStatusEquipamento(),
            ':id' => $equipamento->getId()
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM estoque WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}