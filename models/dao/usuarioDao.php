<?php

namespace chemistLab\models\dao;

use PDO;
use chemistLab\models\entidades\usuario;

class usuarioDao {
    private $pdo;

    //recebe conexao do db connect
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    //busca usuario por cpf
    public function findByCpf($cpf) {
        $stmt = $this->pdo->prepare("SELECT id, nome_completo, email, cpf, senha, tipo_conta 
                                    FROM usuarios 
                                    WHERE cpf = :cpf");
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return new usuario(
            $data['nome_completo'],
            $data['email'],
            $data['cpf'],
            $data['tipo_conta'],
            $data['senha'], 
            $data['id']
        );
    }
    
    //busca usuario pelo id
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT id, nome_completo, email, cpf, tipo_conta 
                                    FROM usuarios 
                                    WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return new usuario(
            $data['nome_completo'],
            $data['email'],
            $data['cpf'],
            $data['tipo_conta'],
            null, 
            $data['id']
        );
    }
    
    //atualiza usuarios
    public function update(usuario $usuario) {
        $sql = "UPDATE usuarios SET 
                nome_completo = :nome, 
                email = :email 
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':nome' => $usuario->getNomeCompleto(),
            ':email' => $usuario->getEmail(),
            ':id' => $usuario->getId()
        ]);
    }
}