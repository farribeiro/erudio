<?php

namespace SME\FilaUnicaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity(readOnly=true)
* @ORM\Table(name="sme_fu_zoneamento")
*/
class Zoneamento {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(unique=true, nullable=false) */
    private $nome;
    
    /** @ORM\Column() */
    private $descricao;
    
    public function getId() {
        return $this->id;
    }
    
    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }
        
}
