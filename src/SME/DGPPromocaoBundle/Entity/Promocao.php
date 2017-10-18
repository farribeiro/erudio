<?php

namespace SME\DGPPromocaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SME\DGPPromocaoBundle\Entity\Status;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_dgp_promocao")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="tipo_promocao", type="string")
 * @ORM\DiscriminatorMap({"HORIZONTAL" = "PromocaoHorizontal", "VERTICAL" = "PromocaoVertical"})
 */
abstract class Promocao {
    
    const TIPO_PROMOCAO_HORIZONTAL = 'HORIZONTAL';
    const TIPO_PROMOCAO_VERTICAL = 'VERTICAL';
    
    const STATUS_INICIAL = 1;
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /**
        * @ORM\Column()
        */
    private $protocolo;
    
    /**
        * @ORM\ManyToOne(targetEntity="SME\DGPBundle\Entity\Vinculo")
        * @ORM\JoinColumn(name="vinculo_id", referencedColumnName="id")
        */
    private $vinculo;
    
    /** @ORM\Column() */
    private $ano;
    
    /** @ORM\Column(name="nivel_anterior") */
    private $nivelAnterior;
    
    /** @ORM\Column(name="nivel_atual") */
    private $nivelAtual;
    
    /**
        * @ORM\ManyToOne(targetEntity="Status")
        * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
        */
    private $status;
    
    /** @ORM\Column(name="data_inicio", type="date", nullable=false) */
    private $dataInicio;
    
    /** @ORM\Column(name="data_cadastro", type="datetime", nullable=false) */
    private $dataCadastro;
    
    /** @ORM\Column(name="data_modificacao", type="datetime") */
    private $dataModificacao;
    
    /** @ORM\Column(name="data_encerramento", type="datetime") */
    private $dataEncerramento;
    
    /**  @ORM\Column() */
    private $resposta;
    
    /**  @ORM\Column() */
    private $observacao;
    
    /**
        * @ORM\ManyToOne(targetEntity="CIGeral", inversedBy="promocoes")
        * @ORM\JoinColumn(name="ci_geral_id", referencedColumnName="id")
        */
    private $ciGeral;
    
    public function getEncerrado() {
        return $this->dataEncerramento != null;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getVinculo() {
        return $this->vinculo;
    }

    public function getAno() {
        return $this->ano;
    }
        
    public function getStatus() {
        return $this->status;
    }

    public function getObservacao() {
        return $this->observacao;
    }

    public function setVinculo($vinculo) {
        $this->vinculo = $vinculo;
    }

    public function setAno($ano) {
        $this->ano = $ano;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setObservacao($observacao) {
        $this->observacao = $observacao;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function getDataModificacao() {
        return $this->dataModificacao;
    }

    public function getDataEncerramento() {
        return $this->dataEncerramento;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    public function setDataModificacao($dataModificacao) {
        $this->dataModificacao = $dataModificacao;
    }

    public function setDataEncerramento($dataEncerramento) {
        $this->dataEncerramento = $dataEncerramento;
    }
    
    public function getDataInicio() {
        return $this->dataInicio;
    }

    public function setDataInicio($dataInicio) {
        $this->dataInicio = $dataInicio;
    }
    
    public function getResposta() {
        return $this->resposta;
    }

    public function setResposta($resposta) {
        $this->resposta = $resposta;
    }
    
    public function getNivelAnterior() {
        return $this->nivelAnterior;
    }

    public function getNivelAtual() {
        return $this->nivelAtual;
    }

    public function setNivelAnterior($nivelAnterior) {
        $this->nivelAnterior = $nivelAnterior;
    }

    public function setNivelAtual($nivelAtual) {
        $this->nivelAtual = $nivelAtual;
    }
    
    public function getProtocolo() {
        return $this->protocolo;
    }

    public function setProtocolo($protocolo) {
        $this->protocolo = $protocolo;
    }
    
    public function getDeferido() {
        return $this->getStatus()->getId() === Status::DEFERIDO;
    }
    
    public function getIndeferido() {
        return $this->getStatus()->getId() === Status::INDEFERIDO;
    }
    
    public function getCancelado() {
        return $this->getStatus()->getId() === Status::CANCELADO;
    }
    
    public function getCiGeral() {
        return $this->ciGeral;
    }

    public function setCiGeral($ciGeral) {
        $this->ciGeral = $ciGeral;
    }

}
