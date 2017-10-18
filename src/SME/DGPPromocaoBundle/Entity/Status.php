<?php

namespace SME\DGPPromocaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity(readOnly=true)
* @ORM\Table(name="sme_dgp_promocao_status")
*/
class Status {
    
    const AGUARDANDO_ENTREGA = 1;
    const EM_ANALISE = 2;
    const DEFERIDO = 3;
    const INDEFERIDO = 4;
    const CANCELADO = 5;
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column(unique=true, nullable=false) */
    private $nome;
    
    /** @ORM\Column(type="boolean", nullable=false) */
    private $terminal;
    
    public function getId() {
        return $this->id;
    }
    
    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    public function getTerminal() {
        return $this->terminal;
    }

    public function setTerminal($terminal) {
        $this->terminal = $terminal;
    }
    
}
