<?php

namespace SME\FilaUnicaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SME\CommonsBundle\Entity\Entidade;
use SME\FilaUnicaBundle\Entity\Zoneamento;

/**
* @ORM\Entity
* @ORM\Table(name="sme_fu_unidade")
*/
class UnidadeEscolar {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\Entidade", fetch="EAGER") */
    private $entidade;
    
    /** @ORM\ManyToOne(targetEntity="Zoneamento", fetch="EAGER") */
    private $zoneamento;
    
    /** @ORM\Column(nullable = false) */
    private $ativo;
    
    public function getId() {
        return $this->id;
    }
    
    public function getAtivo() {
        return $this->ativo;
    }

    public function getEntidade() {
        return $this->entidade;
    }

    public function setEntidade(Entidade $entidade) {
        $this->entidade = $entidade;
    }

    public function getZoneamento() {
        return $this->zoneamento;
    }

    public function setZoneamento(Zoneamento $zoneamento) {
        $this->zoneamento = $zoneamento;
    }
    
    public function getNome() {
        return $this->getEntidade()->getPessoaJuridica()->getNome();
    }
    
}
