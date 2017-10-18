<?php

namespace SME\DGPBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="sme_dgp_vinculo_tipo")
 */
class TipoVinculo {
    
    const EFETIVO = 1;
    const ACT = 2;
    const COMISSIONADO = 3;
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(nullable=false, unique=true) */
    private $nome;
    
    function __construct($id = null, $nome = null) {
        $this->id = $id;
        $this->nome = $nome;
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
    
}
