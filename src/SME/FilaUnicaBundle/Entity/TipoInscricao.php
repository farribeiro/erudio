<?php

namespace SME\FilaUnicaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity(readOnly=true)
* @ORM\Table(name="sme_fu_inscricao_tipo")
*/
class TipoInscricao {
    
    const REGULAR = 1;
    const TRANSFERENCIA = 2;
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(unique=true, nullable=false) */
    private $nome;
    
    /** @ORM\Column(nullable=false) */
    private $prioridade;
    
    public function getId() {
        return $this->id;
    }
    
    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    public function getPrioridade() {
        return $this->prioridade;
    }

    public function setPrioridade($prioridade) {
        $this->prioridade = $prioridade;
    }

}
