<?php

namespace SME\DGPBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\CommonsBundle\Entity\Entidade;
use SME\CommonsBundle\Entity\PeriodoDia;
use SME\DGPBundle\Entity\Vinculo;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_dgp_alocacao")
 */
class Alocacao {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Vinculo", inversedBy="alocacoes")
     * @ORM\JoinColumn(name="vinculo_servidor_id")
     */
    private $vinculoServidor;
    
    /**
     * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\Entidade")
     * @ORM\JoinColumn(name="entidade_local_id", referencedColumnName="id")
     */
    private $localTrabalho;
    
    /**
        * @ORM\Column(name="carga_horaria")
        */
    private $cargaHoraria;
    
    /**
        * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PeriodoDia")
        * @ORM\JoinColumn(name="periodo_dia_id")
        */
    private $periodo;
    
    /**
        * @ORM\Column(name="funcao_atual", nullable=true)
        */
    private $funcaoAtual;
    
    /**
        * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\Entidade")
        * @ORM\JoinColumn(name="entidade_lotacao_id", referencedColumnName="id")
        */
    private $localLotacao;
    
    /**
        * @ORM\Column(name="motivo_encaminhamento", nullable=true)
        */
    private $motivoEncaminhamento;
    
    /**
        * @ORM\Column(name="observacao", nullable=true)
        */
    private $observacao;
    
    /**
        * @ORM\Column(name="original")
        */
    private $original = false;
    
    /**
        * @ORM\Column(name="ativo")
        */
    private $ativo = true;
    
    function __construct() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getVinculoServidor() {
        return $this->vinculoServidor;
    }

    public function setVinculoServidor(Vinculo $vinculoServidor) {
        $this->vinculoServidor = $vinculoServidor;
    }

    public function getLocalTrabalho() {
        return $this->localTrabalho;
    }

    public function setLocalTrabalho(Entidade $localTrabalho) {
        $this->localTrabalho = $localTrabalho;
    }

    public function getCargaHoraria() {
        return $this->cargaHoraria;
    }

    public function setCargaHoraria($cargaHoraria) {
        $this->cargaHoraria = $cargaHoraria;
    }
    
    public function getFuncaoAtual() {
        return $this->funcaoAtual ? $this->funcaoAtual : $this->vinculoServidor->getCargo()->getNome();
    }

    public function setFuncaoAtual($funcaoAtual) {
        $this->funcaoAtual = $funcaoAtual;
    }
    
    function getPeriodo() {
        return $this->periodo;
    }

    function getLocalLotacao() {
        return $this->localLotacao;
    }

    function getMotivoEncaminhamento() {
        return $this->motivoEncaminhamento;
    }

    function getObservacao() {
        return $this->observacao;
    }

    function getAtivo() {
        return $this->ativo;
    }

    function setPeriodo(PeriodoDia $periodo) {
        $this->periodo = $periodo;
    }

    function setLocalLotacao(Entidade $localLotacao) {
        $this->localLotacao = $localLotacao;
    }

    function setMotivoEncaminhamento($motivoEncaminhamento) {
        $this->motivoEncaminhamento = $motivoEncaminhamento;
    }

    function setObservacao($observacao) {
        $this->observacao = $observacao;
    }

    function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
    function getOriginal() {
        return $this->original;
    }

    function setOriginal($original) {
        $this->original = $original;
    }
    
}
