<?php

namespace SME\FilaUnicaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity(readOnly=true)
* @ORM\Table(name="sme_fu_situacao_familiar")
*/
class SituacaoFamiliar {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(unique=true, nullable=false) */
    private $descricao;
    
    /** @ORM\Column(name="peso_renda", nullable=false) */
    private $pesoRenda;
    
    public function getId() {
        return $this->id;
    }
    
    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }
    
    public function getPesoRenda() {
        return $this->pesoRenda;
    }

    public function setPesoRenda($pesoRenda) {
        $this->pesoRenda = $pesoRenda;
    }
        
}
