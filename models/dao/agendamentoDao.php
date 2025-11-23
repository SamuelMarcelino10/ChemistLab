<?php

namespace chemistLab\models\dao;

use PDO;
use chemistLab\models\entidades\agendamento;

class agendamentoDao {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function save(agendamento $agendamento) {
        $sql = "INSERT INTO agendamentos (data_aula, turno, nome_professor, nome_experimento, regente_id) 
                VALUES (:data, :turno, :professor, :experimento, :regente_id)";
        
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':data' => $agendamento->getDataAula(),
            ':turno' => $agendamento->getTurno(),
            ':professor' => $agendamento->getNomeProfessor(),
            ':experimento' => $agendamento->getNomeExperimento(),
            ':regente_id' => $agendamento->getRegenteId()
        ]);
    }
    
    public function findFuture() {
        $sql = "SELECT id, data_aula, turno, nome_professor, nome_experimento 
                FROM agendamentos 
                WHERE data_aula >= CURRENT_DATE 
                ORDER BY data_aula ASC, turno ASC";
                
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findById($id) {
        $sql = "SELECT id, data_aula, turno, nome_professor, nome_experimento, regente_id FROM agendamentos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return new \chemistLab\models\entidades\agendamento(
            $data['data_aula'],
            $data['turno'],
            $data['nome_professor'],
            $data['nome_experimento'],
            $data['regente_id'], 
            $data['id']
        );
    }
    
    public function update(agendamento $agendamento) {
        $sql = "UPDATE agendamentos SET 
                data_aula = :data, 
                turno = :turno, 
                nome_professor = :professor, 
                nome_experimento = :experimento 
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':data' => $agendamento->getDataAula(),
            ':turno' => $agendamento->getTurno(),
            ':professor' => $agendamento->getNomeProfessor(),
            ':experimento' => $agendamento->getNomeExperimento(),
            ':id' => $agendamento->getId()
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM agendamentos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}