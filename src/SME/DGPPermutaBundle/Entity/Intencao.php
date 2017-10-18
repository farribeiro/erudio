<?php

namespace SME\DGPPermutaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="sme_dgp_permuta_intencao")
*/
class Intencao {
    /**
        * @ORM\Id
        * @ORM\Column(type = "integer")
        * @ORM\GeneratedValue(strategy = "AUTO")
        */
    private $id;
    
    /**
        * @ORM\ManyToOne(targetEntity = "SME\DGPBundle\Entity\Vinculo")
        * @ORM\JoinColumn(name = "vinculo_id", referencedColumnName = "id")
        */
    private $vinculo;
    
    /**
        * @ORM\ManyToOne(targetEntity = "SME\CommonsBundle\Entity\Entidade")
        * @ORM\JoinColumn(name = "entidade_lotacao_id", referencedColumnName = "id")
        */
    private $lotacao;
    
    /** @ORM\Column(name = "carga_horaria", nullable = false) */
    private $cargaHoraria;
    
    /** @ORM\Column(name = "bairro_interesse", nullable = false) */
    private $bairrosDeInteresse;
    
    /** @ORM\Column(name = "data_cadastro", type = "datetime", nullable = false) */
    private $dataCadastro;
    
    /** @ORM\Column(type = "boolean") */
    private $ativo = true;
    
    public function getId() {
        return $this->id;
    }

    public function getVinculo() {
        return $this->vinculo;
    }

    public function getLotacao() {
        return $this->lotacao;
    }

    public function getCargaHoraria() {
        return $this->cargaHoraria;
    }
    
    public function getBairrosDeInteresse() {
        return $this->bairrosDeInteresse;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function setVinculo($vinculo) {
        $this->vinculo = $vinculo;
    }

    public function setLotacao($lotacao) {
        $this->lotacao = $lotacao;
    }

    public function setCargaHoraria($cargaHoraria) {
        $this->cargaHoraria = $cargaHoraria;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }
    
    public function setBairrosDeInteresse($bairrosDeInteresse) {
        $this->bairrosDeInteresse = $bairrosDeInteresse;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

}
