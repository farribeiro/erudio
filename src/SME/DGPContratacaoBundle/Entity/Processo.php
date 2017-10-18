<?php

namespace SME\DGPContratacaoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SME\DGPContratacaoBundle\Entity\TipoProcesso;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="sme_dgp_vinculacao_processo")
 */
class Processo {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(nullable=false) */
    private $nome;
    
    /**
     * @ORM\ManyToOne(targetEntity="TipoProcesso", inversedBy="processos")
     * @ORM\JoinColumn(name="tipo_processo_id", referencedColumnName="id")
     */
    private $tipoProcesso;
    
    /** @ORM\Column(name="edital_numero") */
    private $numeroEdital;
    
    /** @ORM\Column(name="edital_ano") */
    private $anoEdital;
    
    /** @ORM\Column(name="homologacao_decreto") */
    private $decretoHomologacao;
    
    /** @ORM\Column(name="homologacao_data", type="date") */
    private $dataHomologacao;
    
    /** @ORM\Column(name="homologacao_edicao_jornal") */
    private $edicaoJornalHomologacao;
    
    /** @ORM\Column(name="homologacao_data_publicacao", type="date") */
    private $dataPublicacaoHomologacao;
    
    /** @ORM\Column(name="prorrogacao_decreto") */
    private $decretoProrrogacao;
    
    /** @ORM\Column(name="prorrogacao_data", type="date") */
    private $dataProrrogacao;
    
    /** @ORM\Column(name="prorrogacao_edicao_jornal") */
    private $edicaoJornalProrrogacao;
    
    /** @ORM\Column(name="prorrogacao_data_publicacao", type="date") */
    private $dataPublicacaoProrrogacao;
    
    /** @ORM\Column(name="data_encerramento", type="date") */
    private $dataEncerramento;
    
    /** @ORM\Column */
    private $ativo;
    
    /**
       * @ORM\OneToMany(targetEntity="Cargo", mappedBy="processo", cascade={"all"}, orphanRemoval=true)
       */
    private $cargos;
    
    /**
       * @ORM\OneToMany(targetEntity="Convocacao", mappedBy="processo", cascade={"all"}, orphanRemoval=true)
       */
    private $convocacoes;
    
    public function __construct() {
        $this->cargos = new ArrayCollection();
        $this->convocacoes = new ArrayCollection();
    }
    
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getTipoProcesso() {
        return $this->tipoProcesso;
    }

    public function getNumeroEdital() {
        return $this->numeroEdital;
    }

    public function getAnoEdital() {
        return $this->anoEdital;
    }

    public function getEdital() {
        if($this->numeroEdital < 10) {
            return '00' . $this->numeroEdital . '/' . $this->anoEdital;
        } elseif($this->numeroEdital < 100) {
            return '0' . $this->numeroEdital . '/' . $this->anoEdital;
        } else {
            return $this->numeroEdital . '/' . $this->anoEdital;
        }
    }

    public function getDataEncerramento() {
        return $this->dataEncerramento;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setTipoProcesso(TipoProcesso $tipoProcesso) {
        $this->tipoProcesso = $tipoProcesso;
    }

    public function setNumeroEdital($numeroEdital) {
        $this->numeroEdital = $numeroEdital;
    }

    public function setAnoEdital($anoEdital) {
        $this->anoEdital = $anoEdital;
    }

    public function setDataEncerramento(\DateTime $dataEncerramento) {
        $this->dataEncerramento = $dataEncerramento;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
    public function getCargos() {
        return $this->cargos;
    }
    
    function getConvocacoes() {
        return $this->convocacoes;
    }
    
    function getDecretoHomologacao() {
        return $this->decretoHomologacao;
    }

    function getDataHomologacao() {
        return $this->dataHomologacao;
    }

    function getEdicaoJornalHomologacao() {
        return $this->edicaoJornalHomologacao;
    }

    function getDataPublicacaoHomologacao() {
        return $this->dataPublicacaoHomologacao;
    }

    function getDecretoProrrogacao() {
        return $this->decretoProrrogacao;
    }

    function getDataProrrogacao() {
        return $this->dataProrrogacao;
    }

    function getEdicaoJornalProrrogacao() {
        return $this->edicaoJornalProrrogacao;
    }

    function getDataPublicacaoProrrogacao() {
        return $this->dataPublicacaoProrrogacao;
    }

    function setDecretoHomologacao($decretoHomologacao) {
        $this->decretoHomologacao = $decretoHomologacao;
    }

    function setDataHomologacao($dataHomologacao) {
        $this->dataHomologacao = $dataHomologacao;
    }

    function setEdicaoJornalHomologacao($edicaoJornalHomologacao) {
        $this->edicaoJornalHomologacao = $edicaoJornalHomologacao;
    }

    function setDataPublicacaoHomologacao($dataPublicacaoHomologacao) {
        $this->dataPublicacaoHomologacao = $dataPublicacaoHomologacao;
    }

    function setDecretoProrrogacao($decretoProrrogacao) {
        $this->decretoProrrogacao = $decretoProrrogacao;
    }

    function setDataProrrogacao($dataProrrogacao) {
        $this->dataProrrogacao = $dataProrrogacao;
    }

    function setEdicaoJornalProrrogacao($edicaoJornalProrrogacao) {
        $this->edicaoJornalProrrogacao = $edicaoJornalProrrogacao;
    }

    function setDataPublicacaoProrrogacao($dataPublicacaoProrrogacao) {
        $this->dataPublicacaoProrrogacao = $dataPublicacaoProrrogacao;
    }
    
}
