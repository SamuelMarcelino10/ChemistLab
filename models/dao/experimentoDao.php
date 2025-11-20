<?php

namespace chemistLab\models\dao;

use PDO;
use chemistLab\models\entidades\experimento;

class experimentoDao {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function save(experimento $experimento) {
        $sql = "INSERT INTO experimentos (titulo, materiais, descricao, regente_id) 
                VALUES (:titulo, :materiais, :descricao, :regente_id)";
        
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':titulo' => $experimento->getTitulo(),
            ':materiais' => $experimento->getMateriais(),
            ':descricao' => $experimento->getDescricao(),
            ':regente_id' => $experimento->getRegenteId() 
        ]);
    }
    
    public function findAll() {
        $sql = "SELECT id, titulo, descricao 
                FROM experimentos 
                ORDER BY id DESC";
                
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM experimentos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}