<?php

namespace SME\DGPBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SME\CommonsBundle\Entity\PessoaFisica;
use SME\DGPContratacaoBundle\Entity\Inscricao;
use SME\DGPContratacaoBundle\Entity\Convocacao;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_dgp_vinculo")
 */
class Vinculo {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column(name="numero_controle") */
    private $numeroControle;
    
    /**
        * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PessoaFisica", cascade={"merge"}, fetch="LAZY", inversedBy="vinculosTrabalho")
        * @ORM\JoinColumn(name="pessoa_fisica_id")
        */
    private $servidor;
    
    /**
     * @ORM\Column(unique=true)
     */
    private $matricula;
    
    /** @ORM\Column(name="data_inicio", type="date") */
    private $dataInicio;
    
    /** @ORM\Column(name="data_termino", type="date") */
    private $dataTermino;
    
    /** @ORM\ManyToOne(targetEntity="Cargo") */
    private $cargo;
    
    /** 
     * @ORM\OneToOne(targetEntity="Vinculo") 
     * @ORM\JoinColumn(name="vinculo_original_id", referencedColumnName="id")
     */
    private $vinculoOriginal;
    
    /** 
     * @ORM\ManyToOne(targetEntity="TipoVinculo")
     * @ORM\JoinColumn(name="tipo_vinculo_id", referencedColumnName="id")
     */
    private $tipoVinculo;
    
    /** @ORM\Column(name="carga_horaria", type="integer") */
    private $cargaHoraria;

    /** @ORM\Column(name="conta_bancaria_numero") */
    private $numeroContaBancaria;
    
    /** @ORM\Column(name="conta_bancaria_agencia") */
    private $agenciaContaBancaria;
    
    /** 
     * @ORM\ManyToOne(targetEntity="Banco")
     * @ORM\JoinColumn(name="conta_bancaria_banco_id", referencedColumnName="id")
     */
    private $bancoContaBancaria;
    
    /** @ORM\Column(name="quadro_especial", type="boolean") */
    private $quadroEspecial = false;
    
    /** @ORM\Column() */
    private $gratificacao;
    
    /** @ORM\Column(name="lotacao_secretaria") */
    private $lotacaoSecretaria;
    
    /** @ORM\Column(name="codigo_departamento") */
    private $codigoDepartamento;
    
    /** @ORM\Column(name="codigo_setor") */
    private $codigoSetor;
    
    /** @ORM\Column(name="data_cadastro", type="datetime") */
    private $dataCadastro;
    
    /** @ORM\Column */
    private $ativo = true;
    
    /** @ORM\Column */
    private $portaria;
    
    /** @ORM\Column(name="edicao_jornal_nomeacao") */
    private $edicaoJornalNomeacao;
    
    /** 
        * @ORM\OneToOne(targetEntity="SME\DGPContratacaoBundle\Entity\Inscricao", inversedBy="vinculoAssociado")
        * @ORM\JoinColumn(name="vinculacao_inscricao_id", referencedColumnName="id") 
        */
    private $inscricaoVinculacao;
    
    /** 
        * @ORM\ManyToOne(targetEntity="SME\DGPContratacaoBundle\Entity\Convocacao")
        * @ORM\JoinColumn(name="vinculacao_convocacao_id", referencedColumnName="id") 
        */
    private $convocacaoVinculacao;
    
    /** @ORM\OneToMany(targetEntity="Alocacao", mappedBy="vinculoServidor", cascade={"all"}, orphanRemoval=true) */
    private $alocacoes;
    
    /**
        * @ORM\ManyToOne(targetEntity="SME\DGPContratacaoBundle\Entity\CIGeral", inversedBy="vinculos")
        * @ORM\JoinColumn(name="vinculacao_ci_geral_id", referencedColumnName="id")
        */
    private $ciGeral;
    
    /** @ORM\Column(nullable=false) */
    private $validado = true;
    
    /** @ORM\Column */
    private $observacao;
    
    public function __construct() {
        $this->alocacoes = new ArrayCollection();
    }
    
    public function getDescricao() {
        return $this->getCargo()->getNome() . ' - ' . 
            $this->getTipoVinculo()->getNome() . ' - ' . 
            $this->getCargaHoraria() . ' horas';
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getServidor() {
        return $this->servidor;
    }

    public function setServidor(PessoaFisica $servidor) {
        $this->servidor = $servidor;
    }

    public function getMatricula() {
        return $this->matricula;
    }

    public function setMatricula($matricula) {
        $this->matricula = $matricula;
    }

    public function getDataInicio() {
        return $this->dataInicio;
    }

    public function setDataInicio(\DateTime $dataInicio = null) {
        $this->dataInicio = $dataInicio;
    }
    
    public function getDataNomeacao() {
        /* A data de nomeação é salva no banco no mesmo campo do início de contrato */
        return $this->dataInicio;
    }
    
    public function setDataNomeacao(\DateTime $dataNomeacao) {
        $this->dataInicio = $dataNomeacao;
    }

    public function getDataTermino() {
        return $this->dataTermino;
    }
  
    public function setDataTermino($dataTermino) {
        $this->dataTermino = $dataTermino;
    }
    
    public function getDataPosse() {
        /* A data de posse é salva no banco no mesmo campo do término de contrato */
        return $this->dataTermino;
    }
    
    public function setDataPosse(\DateTime $dataPosse) {
        $this->dataTermino = $dataPosse;
    }

    public function getCargo() {
        return $this->cargo;
    }

    public function setCargo(Cargo $cargo) {
        $this->cargo = $cargo;
    }

    public function getVinculoOriginal() {
        return $this->vinculoOriginal;
    }

    public function getCargoOrigem() {
        return $this->vinculoOriginal instanceof Vinculo ? $this->vinculoOriginal->getCargo() : $this->cargo;
    }
    
    public function setVinculoOriginal(Vinculo $vinculoOriginal = null) {
        $this->vinculoOriginal = $vinculoOriginal;
    }
    
    public function getTipoVinculo() {
        return $this->tipoVinculo;
    }

    public function setTipoVinculo(TipoVinculo $tipoVinculo) {
        $this->tipoVinculo = $tipoVinculo;
    }

    public function getCargaHoraria() {
        return $this->cargaHoraria;
    }

    public function setCargaHoraria($cargaHoraria) {
        $this->cargaHoraria = $cargaHoraria;
    }

    public function getNumeroContaBancaria() {
        return $this->numeroContaBancaria;
    }

    public function setNumeroContaBancaria($numeroContaBancaria) {
        $this->numeroContaBancaria = $numeroContaBancaria;
    }

    public function getAgenciaContaBancaria() { 
       return $this->agenciaContaBancaria;
    }

    public function setAgenciaContaBancaria($agenciaContaBancaria) {
        $this->agenciaContaBancaria = $agenciaContaBancaria;
    }
    
    public function getBancoContaBancaria() {
        return $this->bancoContaBancaria;
    }

    public function setBancoContaBancaria(Banco $bancoContaBancaria = null) {
        $this->bancoContaBancaria = $bancoContaBancaria;
    }

    public function getQuadroEspecial() {
        return $this->quadroEspecial;
    }

    public function setQuadroEspecial($quadroEspecial) {
        $this->quadroEspecial = $quadroEspecial;
    }

    public function getGratificacao() {
        return $this->gratificacao;
    }

    public function setGratificacao($gratificacao) {
        $this->gratificacao = $gratificacao;
    }

    public function getLotacaoSecretaria() {
        return $this->lotacaoSecretaria;
    }

    public function setLotacaoSecretaria($lotacaoSecretaria) {
        $this->lotacaoSecretaria = $lotacaoSecretaria;
    }

    public function getCodigoDepartamento() {
        return $this->codigoDepartamento;
    }

    public function setCodigoDepartamento($codigoDepartamento) {
        $this->codigoDepartamento = $codigoDepartamento;
    }

    public function getCodigoSetor() {
        return $this->codigoSetor;
    }

    public function setCodigoSetor($codigoSetor) {
        $this->codigoSetor = $codigoSetor;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function setDataCadastro(\DateTime $dataCadastro = null) {
        $this->dataCadastro = $dataCadastro;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

    public function getPortaria() {
        return $this->portaria;
    }

    public function setPortaria($portaria) {
        $this->portaria = $portaria;
    }
    
    function getEdicaoJornalNomeacao() {
        return $this->edicaoJornalNomeacao;
    }

    function setEdicaoJornalNomeacao($edicaoJornalNomeacao) {
        $this->edicaoJornalNomeacao = $edicaoJornalNomeacao;
    }
  
    public function getAlocacoes() {
        return $this->alocacoes->filter(function($a) {
            return $a->getAtivo();
        });
    }

    public function setAlocacoes($alocacoes) {
        $this->alocacoes = $alocacoes;
    }
    
    public function getAlocacoesOriginais() {
        return $this->alocacoes->filter(function($a) {
            return $a->getOriginal();
        });
    }
    
    public function getValidado() {
        return $this->validado;
    }

    public function setValidado($validado) {
        $this->validado = $validado;
    }
    
    public function getProcessoAdmissao() {
        return $this->inscricaoVinculacao ? $this->inscricaoVinculacao->getCargo()->getProcesso() : null;
    }
    
    public function getInscricaoVinculacao() {
        return $this->inscricaoVinculacao;
    }

    public function setInscricaoVinculacao(Inscricao $inscricaoVinculacao = null) {
        $this->inscricaoVinculacao = $inscricaoVinculacao;
    }
    
    public function getConvocacaoVinculacao() {
        return $this->convocacaoVinculacao;
    }

    public function setConvocacaoVinculacao(Convocacao $convocacaoVinculacao = null) {
        $this->convocacaoVinculacao = $convocacaoVinculacao;
    }
    
    public function getCiGeral() {
        return $this->ciGeral;
    }

    public function setCiGeral($ciGeral) {
        $this->ciGeral = $ciGeral;
    }
    
    function getNumeroControle() {
        return $this->numeroControle;
    }

    function setNumeroControle($numeroControle) {
        $this->numeroControle = $numeroControle;
    }
  
    public function getObservacao() {
        return $this->observacao;
    }

    public function setObservacao($observacao) {
        $this->observacao = $observacao;
    }
    
    public function getOpcaoLei() {
        return $this->observacao;
    }
    
}
