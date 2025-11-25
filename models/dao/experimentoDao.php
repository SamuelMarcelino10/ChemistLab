<?php

namespace chemistLab\models\dao;

use PDO;
use chemistLab\models\entidades\experimento;

class experimentoDao {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    //salva experimento
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
    
    //procura todos os experimentos salvos
    public function findAll() {
        $sql = "SELECT id, titulo, descricao 
                FROM experimentos 
                ORDER BY id DESC";
                
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    //busca por id
    public function findById($id) {
        $sql = "SELECT id, titulo, materiais, descricao, regente_id FROM experimentos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return new \chemistLab\models\entidades\experimento(
            $data['titulo'],
            $data['materiais'],
            $data['descricao'],
            $data['regente_id'], 
            $data['id']
        );
    }
    
    //atualiza experimento
    public function update(experimento $experimento) {
        $sql = "UPDATE experimentos SET 
                titulo = :titulo, 
                materiais = :materiais, 
                descricao = :descricao 
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':titulo' => $experimento->getTitulo(),
            ':materiais' => $experimento->getMateriais(),
            ':descricao' => $experimento->getDescricao(),
            ':id' => $experimento->getId()
        ]);
    }
    
    //deleta experimento
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM experimentos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}