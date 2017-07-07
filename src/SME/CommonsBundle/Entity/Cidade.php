<?php

namespace SME\CommonsBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\CommonsBundle\Entity\Estado;

/**
* @ORM\Entity(readOnly=true)
* @ORM\Table(name="sme_cidade")
*/
class Cidade {
    
    const ITAJAI = 4481;
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /** @ORM\Column(nullable=false) */
    private $nome;

    /** @ORM\Column(nullable=false) */
    private $sigla;
    
    /** @ORM\Column(nullable=true) */
    private $ibge;

    /** @ORM\ManyToOne(targetEntity="Estado") */
    private $estado;

    /** @ORM\Column(nullable=false) */
    private $ativo;
    
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getSigla() {
        return $this->sigla;
    }

    public function setSigla($sigla) {
        $this->sigla = $sigla;
    }

    public function getIbge() {
        return $this->ibge;
    }

    public function setIbge($ibge) {
        $this->ibge = $ibge;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado(Estado $estado) {
        $this->estado = $estado;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
}
