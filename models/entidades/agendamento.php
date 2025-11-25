<?php

namespace chemistLab\models\entidades;

class agendamento {
    //atributos
    private $id;
    private $dataAula;
    private $turno;
    private $nomeProfessor;
    private $nomeExperimento;
    private $regenteId; 

    //construtor
    public function __construct(
        $dataAula, 
        $turno, 
        $nomeProfessor, 
        $nomeExperimento, 
        $regenteId, 
        $id = null
    ) {
        $this->dataAula = $dataAula;
        $this->turno = $turno;
        $this->nomeProfessor = $nomeProfessor;
        $this->nomeExperimento = $nomeExperimento;
        $this->regenteId = $regenteId; 
        $this->id = $id;
    }

    //getters
    public function getId() { return $this->id; }
    public function getDataAula() { return $this->dataAula; }
    public function getTurno() { return $this->turno; }
    public function getNomeProfessor() { return $this->nomeProfessor; }
    public function getNomeExperimento() { return $this->nomeExperimento; }
    public function getRegenteId() { return $this->regenteId; } 

    //setter
    public function setId($id) { $this->id = $id; }
}