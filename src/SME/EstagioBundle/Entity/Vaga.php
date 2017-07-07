<?php

namespace SME\EstagioBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_estagio_vaga")
 */

class Vaga {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column(nullable=false) */
    private $titulo;
    
    /** @ORM\Column(nullable=false) */
    private $descricao;
        
    /** @ORM\Column(name="totalVagas", nullable=false) */
    private $totalVagas;
    
    /** @ORM\Column(name="inscricao_id", type="integer", nullable=true) */
    private $inscricao = null;
    
    /** @ORM\Column(type="integer", nullable=false) */
    private $unidade;
    
    /** @ORM\Column(type="integer", nullable=false) */
    private $turno;
    
    /** @ORM\Column(nullable=false) */
    private $disciplina;
    
    /** @ORM\Column(nullable=false) */
    private $status = 0;
    
    /** @ORM\Column(nullable=false, type="boolean") */
    private $ativo = true;
    
    public function getId() {
        return $this->id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }
    
    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }
    
    public function getTotalVagas() {
        return $this->totalVagas;
    }

    public function setTotalVagas($totalVagas) {
        $this->totalVagas = $totalVagas;
    }
    
    public function getInscricao() {
        return $this->inscricao;
    }

    public function setInscricao($inscricao) {
        $this->inscricao = $inscricao;
    }
    
    public function getUnidade() {
        return $this->unidade;
    }

    public function setUnidade($unidade) {
        $this->unidade = $unidade;
    }
    
    public function getTurno() {
        return $this->turno;
    }

    public function setTurno($turno) {
        $this->turno = $turno;
    }
    
    public function getDisciplina() {
        return $this->disciplina;
    }

    public function setDisciplina($disciplina) {
        $this->disciplina = $disciplina;
    }
    
    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
    
    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
}