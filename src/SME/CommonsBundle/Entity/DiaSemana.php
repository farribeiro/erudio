<?php

namespace SME\CommonsBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="sme_dia_semana")
 */
class DiaSemana {
    
    const DOM = 1;
    const SEG = 2;
    const TER = 3;
    const QUA = 4;
    const QUI = 5;
    const SEX = 6;
    const SAB = 7;
    
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
    
    /** @ORM\Column(type="boolean", name="dia_util", nullable=false) */
    private $diaUtil;

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

    public function getDiaUtil() {
        return $this->diaUtil;
    }

    public function setDiaUtil($diaUtil) {
        $this->diaUtil = $diaUtil;
    }
    
}
