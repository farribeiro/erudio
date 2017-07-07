<?php

namespace SME\CommonsBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name = "sme_particularidade")
 */
class Particularidade {
    
    const DEFICIENCIA = 'DEFICIENCIA', 
          TRANSTORNO = 'TRANSTORNO', 
          SUPERDOTACAO = 'SUPERDOTACAO';
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** 
        *   @ORM\Column(nullable = false) 
        */
    private $nome;
    
    /** 
        *   @ORM\Column(nullable = false) 
        */
    private $tipo;
    
    public function getId() {
        return $this->id;
    }
   
    public function getNome() {
        return $this->nome;
    }
         
    function getTipo() {
        return $this->tipo;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }
    
}
