<?php

namespace SME\FilaUnicaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity(readOnly=true)
* @ORM\Table(name="sme_fu_status")
*/
class Status {
    
    const EM_ESPERA = 1;
    const EM_CHAMADA = 2;
    const MATRICULADO = 3;
    const DESISTENTE_VAGA = 4;
    const ELIMINADO = 5;
    const EM_RESERVA = 6;
    const DESISTENTE_INSCRICAO = 7;
    const REMOVIDO_NA_VIRADA = 8;
    
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
