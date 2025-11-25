<?php

namespace chemistLab\models\entidades;

class equipamento {
    //atributos
    private $id;
    private $nome;
    private $tipo;
    private $descricao;
    private $quantidade;
    private $unidadeMedida;
    private $statusEquipamento;

    //construtor
    public function __construct(
        $nome, 
        $tipo, 
        $descricao, 
        $quantidade, 
        $unidadeMedida, 
        $statusEquipamento, 
        $id = null
    ) {
        $this->nome = $nome;
        $this->tipo = $tipo;
        $this->descricao = $descricao;
        $this->quantidade = $quantidade;
        $this->unidadeMedida = $unidadeMedida;
        $this->statusEquipamento = $statusEquipamento;
        $this->id = $id;
    }

    //getters
    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getTipo() { return $this->tipo; }
    public function getDescricao() { return $this->descricao; }
    public function getQuantidade() { return $this->quantidade; }
    public function getUnidadeMedida() { return $this->unidadeMedida; }
    public function getStatusEquipamento() { return $this->statusEquipamento; }

    //setters
    public function setId($id) { $this->id = $id; }
    public function setDescricao($descricao) { $this->descricao = $descricao; }
}