<?php

/*
 * Um objeto desta classe representa basicamente o mesmo que uma alocação, todavia
 * não é garantido que o funcionário permanecerá neste mesmo local durante todo sua
 * carreira ou contrato. Enquanto uma alocação existente sempre representa um local
 * onde o servidor trabalha atualmente, a alocação original é imutável e informa a vaga
 * para a qual o servidor foi originalmente efetivado/contratado
 */

namespace SME\DGPBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\DGPBundle\Entity\Vinculo;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_dgp_vinculo_atuacao")
 */
class AlocacaoOriginal {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Vinculo", inversedBy="alocacoesOriginais")
     * @ORM\JoinColumn(name="vinculo_id")
     */
    private $vinculoServidor;
    
    /**
     * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\Entidade")
     * @ORM\JoinColumn(name="entidade_local_id", referencedColumnName="id")
     */
    private $localTrabalho;
    
    /**
        * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\Entidade")
        * @ORM\JoinColumn(name="entidade_lotacao_id", referencedColumnName="id")
        */
    private $localLotacao;
    
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
      * @ORM\Column(name="motivo_encaminhamento", nullable=true)
      */
    private $motivoEncaminhamento;
    
    /**
       * @ORM\Column(name="observacao", nullable=true)
       */
    private $observacao;
    
    function __construct() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getVinculoServidor() {
        return $this->vinculoServidor;
    }

    public function getLocalTrabalho() {
        return $this->localTrabalho;
    }

    public function getLocalLotacao() {
        return $this->localLotacao;
    }

    public function getCargaHoraria() {
        return $this->cargaHoraria;
    }

    public function getPeriodo() {
        return $this->periodo;
    }

    public function getMotivoEncaminhamento() {
        return $this->motivoEncaminhamento;
    }

    public function setVinculoServidor($vinculoServidor) {
        $this->vinculoServidor = $vinculoServidor;
    }

    public function setLocalTrabalho($localTrabalho) {
        $this->localTrabalho = $localTrabalho;
    }

    public function setLocalLotacao($localLotacao) {
        $this->localLotacao = $localLotacao;
    }

    public function setCargaHoraria($cargaHoraria) {
        $this->cargaHoraria = $cargaHoraria;
    }

    public function setPeriodo($periodo) {
        $this->periodo = $periodo;
    }

    public function setMotivoEncaminhamento($motivoEncaminhamento) {
        $this->motivoEncaminhamento = $motivoEncaminhamento;
    }

    function getObservacao() {
        return $this->observacao;
    }

    function setObservacao($observacao) {
        $this->observacao = $observacao;
    }
    
}

