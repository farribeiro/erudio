<?php

namespace SME\FilaUnicaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity(readOnly=true)
* @ORM\Table(name="sme_fu_ano_escolar")
*/
class AnoEscolar {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(unique=true, nullable=false) */
    private $nome;
    
    /** @ORM\Column(name="data_limite_inferior", nullable=false, type="date") */
    private $dataLimiteInferior;
    
    /** @ORM\Column(name="data_limite_superior", nullable=false, type="date") */
    private $dataLimiteSuperior;
    
    public function getId() {
        return $this->id;
    }
  
    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    public function getDataLimiteInferior() {
        return $this->dataLimiteInferior;
    }

    public function getDataLimiteSuperior() {
        return $this->dataLimiteSuperior;
    }

    public function setDataLimiteInferior(\DateTime $dataLimiteInferior) {
        $this->dataLimiteInferior = $dataLimiteInferior;
    }

    public function setDataLimiteSuperior(\DateTime $dataLimiteSuperior) {
        $this->dataLimiteSuperior = $dataLimiteSuperior;
    }
    
}
