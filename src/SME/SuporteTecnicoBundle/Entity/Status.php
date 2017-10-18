<?php

namespace SME\SuporteTecnicoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="sme_suportetecnico_status")
 */
class Status {
    
    const NOVO = 1;
    const RECEBIDO = 2;
    const EM_ANDAMENTO = 4;
    const PENDENTE = 5;
    const ENCERRADO = 6;
    const CANCELADO = 7;
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column(nullable=false, unique=true) */
    private $nome;
    
    /** @ORM\Column(nullable=false) */
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
