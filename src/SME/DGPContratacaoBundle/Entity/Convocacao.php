<?php

namespace SME\DGPContratacaoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\DGPContratacaoBundle\Entity\Processo;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_dgp_vinculacao_convocacao")
 */
class Convocacao implements \JsonSerializable {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column(nullable=false) */
    private $nome;
    
    /**
        * @ORM\ManyToOne(targetEntity="Processo")
        */
    private $processo;
    
    /** @ORM\Column(name="edital_numero") */
    private $numeroEdital;
    
    /** @ORM\Column(name="edital_ano") */
    private $anoEdital;
    
    /** @ORM\Column(name="data_realizacao", type="date") */
    private $dataRealizacao;
    
    /** @ORM\Column */
    private $ativo;
    
    public function getEdital() {
        if($this->numeroEdital < 10) {
            return '00' . $this->numeroEdital . '/' . $this->anoEdital;
        } elseif($this->numeroEdital < 100) {
            return '0' . $this->numeroEdital . '/' . $this->anoEdital;
        } else {
            return $this->numeroEdital . '/' . $this->anoEdital;
        }
    }
    
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getProcesso() {
        return $this->processo;
    }

    public function getNumeroEdital() {
        return $this->numeroEdital;
    }

    public function getAnoEdital() {
        return $this->anoEdital;
    }

    public function getDataRealizacao() {
        return $this->dataRealizacao;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setProcesso($processo) {
        $this->processo = $processo;
    }

    public function setNumeroEdital($numeroEdital) {
        $this->numeroEdital = $numeroEdital;
    }

    public function setAnoEdital($anoEdital) {
        $this->anoEdital = $anoEdital;
    }

    public function setDataRealizacao($dataRealizacao) {
        $this->dataRealizacao = $dataRealizacao;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'nome' => $this->nome
        );
    }
    
}
