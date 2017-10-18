<?php

namespace SME\CommonsBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\CommonsBundle\Entity\Pais;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="sme_estado")
 */
class Estado {
    
    const SC = 24;
    
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
    
    /** @ORM\ManyToOne(targetEntity="Pais") */
    private $pais;
    
    /** @ORM\Column(nullable=false) */
    private $ativo;
       
    function __construct() {
        
    }
    
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

    public function getPais() {
        return $this->pais;
    }

    public function setPais(Pais $pais) {
        $this->pais = $pais;
    }
    
    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
}
