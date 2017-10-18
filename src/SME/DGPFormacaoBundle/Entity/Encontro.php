<?php

namespace SME\DGPFormacaoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\DGPFormacaoBundle\Entity\Formacao;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_dgp_formacao_encontro")
 */
class Encontro {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column() */
    private $conteudo;
    
    /** @ORM\Column(name="data_realizacao", type="date") */
    private $dataRealizacao;
    
    /** @ORM\Column(name="carga_horaria") */
    private $cargaHoraria;
    
    /** @ORM\ManyToOne(targetEntity="Formacao", inversedBy="encontros") */
    private $formacao;
    
    /** @ORM\Column(type="boolean") */
    private $ativo = true;
    
    public function getId() {
        return $this->id;
    }

    public function getConteudo() {
        return $this->conteudo;
    }

    public function getCargaHoraria() {
        return $this->cargaHoraria;
    }

    public function getFormacao() {
        return $this->formacao;
    }

    public function setConteudo($conteudo) {
        $this->conteudo = $conteudo;
    }

    public function setCargaHoraria($cargaHoraria) {
        $this->cargaHoraria = $cargaHoraria;
    }

    public function setFormacao(Formacao $formacao) {
        $this->formacao = $formacao;
    }

    public function getDataRealizacao() {
        return $this->dataRealizacao;
    }

    public function setDataRealizacao($dataRealizacao) {
        $this->dataRealizacao = $dataRealizacao;
    }
    
    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
}
