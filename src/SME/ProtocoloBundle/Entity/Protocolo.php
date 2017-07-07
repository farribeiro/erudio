<?php

namespace SME\ProtocoloBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SME\CommonsBundle\Entity\PessoaFisica;

/**
 * @ORM\Entity()
 * @ORM\Table(name="jos_protocolo")
 */
class Protocolo {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\ManyToOne(targetEntity="Solicitacao") */
    private $solicitacao;
    
    /** 
     * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PessoaFisica")
     * @ORM\JoinColumn(name="pessoa_solicitante_id", referencedColumnName="id")
     */
    private $requerente;
    
    /** 
     * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PessoaFisica")
     * @ORM\JoinColumn(name="pessoa_responsavel_id", referencedColumnName="id")
     */
    private $responsavelAtual;
    
    /** @ORM\ManyToOne(targetEntity="Situacao") */
    private $situacao;
    
    /** @ORM\Column(name="data_cadastro", type="datetime", nullable=false) */
    private $dataCadastro;
    
    /** @ORM\Column(name="data_modificacao", type="datetime") */
    private $dataModificacao;
    
    /** @ORM\Column(name="data_encerramento", type="datetime") */
    private $dataEncerramento;
    
    /** @ORM\Column() */
    private $observacao;
    
    /** @ORM\Column(nullable=false) */
    private $ativo;
    
    /** @ORM\OneToMany(targetEntity="Encaminhamento", mappedBy="protocolo", cascade={"all"}, orphanRemoval=true) */
    private $encaminhamentos;
    
    /** @ORM\OneToMany(targetEntity="InformacaoDocumento", mappedBy="protocolo", cascade={"all"}, orphanRemoval=true) */
    private $informacoesDocumento;
    
    public function __construct() {
        $this->informacoesDocumento = new ArrayCollection();
        $this->encaminhamentos = new ArrayCollection();
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getProtocolo() {
        return $this->dataCadastro->format('Y') . 
                '.' . $this->solicitacao->getCategoria()->getId() . 
                '.' . $this->id;
    }
    
    public function getSolicitacao() {
        return $this->solicitacao;
    }

    public function setSolicitacao(Solicitacao $solicitacao) {
        $this->solicitacao = $solicitacao;
    }

    public function getRequerente() {
        return $this->requerente;
    }

    public function setRequerente(PessoaFisica $requerente=null) {
        $this->requerente = $requerente;
    }

    public function getResponsavelAtual() {
        return $this->responsavelAtual;
    }

    public function setResponsavelAtual(PessoaFisica $responsavelAtual) {
        $this->responsavelAtual = $responsavelAtual;
    }

    public function getSituacao() {
        return $this->situacao;
    }

    public function setSituacao(Situacao $situacao) {
        $this->situacao = $situacao;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function setDataCadastro(\DateTime $dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    public function getDataModificacao() {
        return $this->dataModificacao;
    }

    public function setDataModificacao(\DateTime $dataModificacao) {
        $this->dataModificacao = $dataModificacao;
    }

    public function getDataEncerramento() {
        return $this->dataEncerramento;
    }

    public function setDataEncerramento(\DateTime $dataEncerramento = null) {
        $this->dataEncerramento = $dataEncerramento;
    }

    public function getObservacao() {
        return $this->observacao;
    }

    public function setObservacao($observacao) {
        $this->observacao = $observacao;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

    public function getInformacoesDocumento() {
        return $this->informacoesDocumento;
    }

    public function setInformacoesDocumento($informacoesDocumento) {
        $this->informacoesDocumento = $informacoesDocumento;
    }
    
    public function getEncaminhamentos() {
        return $this->encaminhamentos;
    }

    public function setEncaminhamentos($encaminhamentos) {
        $this->encaminhamentos = $encaminhamentos;
    }
    
    public function getEncaminhado() {
        return $this->encaminhamentos->isEmpty() 
                ? false 
                : !$this->encaminhamentos->last()->getRecebido();
    }
    
}
