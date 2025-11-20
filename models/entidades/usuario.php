<?php

namespace chemistLab\models\entidades;

class usuario {
    private $id;
    private $nomeCompleto;
    private $email;
    private $cpf;
    private $senha;
    private $tipoConta;

    public function __construct(
        $nomeCompleto, 
        $email, 
        $cpf, 
        $tipoConta,
        $senha = null, 
        $id = null
    ) {
        $this->nomeCompleto = $nomeCompleto;
        $this->email = $email;
        $this->cpf = $cpf;
        $this->tipoConta = $tipoConta;
        $this->senha = $senha;
        $this->id = $id;
    }

    public function getId() { return $this->id; }
    public function getNomeCompleto() { return $this->nomeCompleto; }
    public function getEmail() { return $this->email; }
    public function getCpf() { return $this->cpf; }
    public function getSenha() { return $this->senha; }
    public function getTipoConta() { return $this->tipoConta; }

    public function setId($id) { $this->id = $id; }
    public function setNomeCompleto($nomeCompleto) { $this->nomeCompleto = $nomeCompleto; }
    public function setEmail($email) { $this->email = $email; }
    public function setSenha($senha) { $this->senha = $senha; }
}