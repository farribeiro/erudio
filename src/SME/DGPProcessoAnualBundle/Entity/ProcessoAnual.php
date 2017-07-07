<?php

namespace SME\DGPProcessoAnualBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity(readOnly=true)
* @ORM\Table(name="sme_dgp_processo_anual")
*/
class ProcessoAnual {
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(unique=true, nullable=false) */
    private $nome;
    
    /** @ORM\Column */
    private $disponivel;
    
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getDisponivel() {
        return $this->disponivel;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setDisponivel($disponivel) {
        $this->disponivel = $disponivel;
    }

}
