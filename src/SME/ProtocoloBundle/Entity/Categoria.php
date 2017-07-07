<?php

namespace SME\ProtocoloBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="jos_protocolo_categoria")
 */
class Categoria {
    
    const REQUERIMENTO_DGP = 1;
    const CHAMADO_TECNICO = 3;
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(nullable=false, unique=true) */
    private $nome;
    
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
