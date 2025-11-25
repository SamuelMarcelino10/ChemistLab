<?php

namespace chemistLab\models\entidades;

class experimento {
    //atributos
    private $id;
    private $titulo;
    private $materiais;
    private $descricao;
    private $regenteId; 

    //construtor
    public function __construct(
        $titulo, 
        $materiais, 
        $descricao, 
        $regenteId, 
        $id = null
    ) {
        $this->titulo = $titulo;
        $this->materiais = $materiais;
        $this->descricao = $descricao;
        $this->regenteId = $regenteId; 
        $this->id = $id;
    }

    //getters
    public function getId() { return $this->id; }
    public function getTitulo() { return $this->titulo; }
    public function getMateriais() { return $this->materiais; }
    public function getDescricao() { return $this->descricao; }
    public function getRegenteId() { return $this->regenteId; } 

    //setter
    public function setId($id) { $this->id = $id; }
}