<?php

namespace SME\DGPContratacaoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SME\DGPContratacaoBundle\Entity\Processo;

/**
 * @ORM\Entity(repositoryClass="SME\DGPContratacaoBundle\Entity\Repository\CIGeralRepository")
 * @ORM\Table(name="sme_dgp_vinculacao_ci_geral")
 */
class CIGeral {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(nullable=false) */
    private $numero;
    
    /** @ORM\Column(nullable=false) */
    private $ano;
    
    /** @ORM\Column(type="boolean", nullable=false) */
    private $prorrogacao;
    
    /** @ORM\ManyToOne(targetEntity="Processo") */
    private $processo;
    
    /**
    * @ORM\OneToMany(targetEntity="SME\DGPBundle\Entity\Vinculo", mappedBy="ciGeral", orphanRemoval=false)
    */
    private $vinculos;
    
    public function __construct() {
        $this->vinculos = new ArrayCollection();
    }
    
    public function getNumeroAno() {
        if($this->numero < 10) {
            return '00' . $this->numero . '/' . $this->ano;
        } elseif($this->numero < 100) {
            return '0' . $this->numero . '/' . $this->ano;
        } else {
            return $this->numero . '/' . $this->ano;
        }
    }
    
    public function getId() {
        return $this->id;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function getAno() {
        return $this->ano;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }

    public function setAno($ano) {
        $this->ano = $ano;
    }
    
    public function getProrrogacao() {
        return $this->prorrogacao;
    }

    public function setProrrogacao($prorrogacao) {
        $this->prorrogacao = $prorrogacao;
    }
    
    public function getProcesso() {
        return $this->processo;
    }

    public function setProcesso(Processo $processo) {
        $this->processo = $processo;
    }
    
    public function getVinculos() {
        return $this->vinculos;
    }

    public function setVinculos($vinculos) {
        $this->vinculos = $vinculos;
    }
    
}
