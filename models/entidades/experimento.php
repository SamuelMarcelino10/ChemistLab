<?php

namespace chemistLab\models\entidades;

class experimento {
    private $id;
    private $titulo;
    private $materiais;
    private $descricao;
    private $regenteId; 

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

    public function getId() { return $this->id; }
    public function getTitulo() { return $this->titulo; }
    public function getMateriais() { return $this->materiais; }
    public function getDescricao() { return $this->descricao; }
    public function getRegenteId() { return $this->regenteId; } 

    public function setId($id) { $this->id = $id; }
}