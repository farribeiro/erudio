<?php

namespace SME\CommonsBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="sme_periodo_dia")
 */
class PeriodoDia {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(unique=true, nullable=false) */
    private $nome;
    
    /** @ORM\Column(unique=true, nullable=false) */
    private $abreviacao;
    
    /** @ORM\Column(nullable=false) */
    private $matutino;
    
    /** @ORM\Column(nullable=false) */
    private $vespertino;
    
    /** @ORM\Column(nullable=false) */
    private $noturno;

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    public function getAbreviacao() {
        return $this->abreviacao;
    }

    public function setAbreviacao($abreviacao) {
        $this->abreviacao = $abreviacao;
    }

    public function getMatutino() {
        return $this->matutino;
    }

    public function setMatutino($matutino) {
        $this->matutino = $matutino;
    }

    public function getVespertino() {
        return $this->vespertino;
    }

    public function setVespertino($vespertino) {
        $this->vespertino = $vespertino;
    }

    public function getNoturno() {
        return $this->noturno;
    }

    public function setNoturno($noturno) {
        $this->noturno = $noturno;
    }
    
    public function estaContido(PeriodoDia $periodo) {
        $bool1 = $this->matutino ? $this->matutino && $periodo->getMatutino() : true;
        $bool2 = $this->vespertino ? $this->vespertino && $periodo->getVespertino() : true; 
        $bool3 = $this->noturno ? $this->noturno && $periodo->getNoturno() : true; 
        return $bool1 && $bool2 && $bool3;
    }
    
}
