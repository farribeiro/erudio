<?php

namespace SME\DGPPromocaoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="SME\DGPPromocaoBundle\Entity\Repository\CIGeralRepository")
 * @ORM\Table(name="sme_dgp_promocao_ci_geral")
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
    
    /** @ORM\Column(name="tipo_promocao", nullable=false) */
    private $tipoPromocao;
    
    /**
        * @ORM\OneToMany(targetEntity="Promocao", mappedBy="ciGeral", orphanRemoval=false)
        */
    private $promocoes;
    
    public function __construct() {
        $this->promocoes = new ArrayCollection();
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
    
    public function getPromocoes() {
        return $this->promocoes;
    }
    
    public function setPromocoes($promocoes) {
        $this->promocoes = $promocoes;
    }
    
    public function getTipoPromocao() {
        return $this->tipoPromocao;
    }

    public function setTipoPromocao($tipoPromocao) {
        $this->tipoPromocao = $tipoPromocao;
    }
    
}


