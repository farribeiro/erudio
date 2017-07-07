<?php

namespace SME\CommonsBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use SME\CommonsBundle\Util\DocumentosUtil;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_pessoa_fisica")
 */
class PessoaFisica extends Pessoa {
    
    /** @ORM\Column(name="rg_numero") */
    private $numeroRg;
    
    /** @ORM\Column(name="rg_orgao_expedidor") */
    private $orgaoExpedidorRg;
    
    /** @ORM\Column(name="rg_data_expedicao", type="date") */
    private $dataExpedicaoRg;
    
    /** @ORM\Column(name="pis_pasep") */
    private $pisPasep;
    
    /** @ORM\Column(name="titulo_eleitor_numero") */
    private $numeroTituloEleitor;
    
    /** @ORM\Column(name="titulo_eleitor_zona") */
    private $zonaTituloEleitor;
    
    /** @ORM\Column(name="titulo_eleitor_secao") */
    private $secaoTituloEleitor;
    
    /** @ORM\Column() */
    private $naturalidade;
    
    /** @ORM\Column() */
    private $nacionalidade;
    
    /**
    * @ORM\OneToOne(targetEntity="EstadoCivil")
    * @ORM\JoinColumn(name="estado_civil_id", referencedColumnName="id") 
    */
    private $estadoCivil;
    
    /** @ORM\Column(name="mae_nome") */
    private $nomeMae;
    
    /** @ORM\Column(name="pai_nome") */
    private $nomePai;
    
    /**
    * @ORM\ManyToOne(targetEntity="Raca")
    */
    private $raca;
    
    /** 
        * @ORM\Column(name="certidao_nascimento_completa", length=32)
        * @Assert\NotBlank(groups={"menor_idade"})
        * @Assert\Length(min=32, max=32, groups={"menor_idade"})
        */
    private $certidaoNascimento;
    
    /** @ORM\OneToMany(targetEntity="Formacao", mappedBy="pessoaFisica", cascade={"all"})) */
    private $formacoes;
    
    /** @ORM\OneToMany(targetEntity="Relacionamento", mappedBy="pessoa", cascade={"all"})) */
    private $relacoes;
    
    /**
        * @ORM\Column(name="profissao_nome")
        */
    private $profissao;
    
    /**
        * @ORM\Column(name="carteira_trabalho_numero")
        */
    private $carteiraTrabalhoNumero;
    
    /**
        * @ORM\Column(name="carteira_trabalho_serie")
        */
    private $carteiraTrabalhoSerie;
    
    /**
        * @ORM\Column(name="carteira_trabalho_data_expedicao", type="date")
        */
    private $carteiraTrabalhoDataExepdicao;
    
    /**
        * @ORM\OneToOne(targetEntity="Estado")
        * @ORM\JoinColumn(name="carteira_trabalho_estado_id", referencedColumnName="id") 
        */
    private $carteiraTrabalhoEstado;
    
    /**
        * @ORM\OneToMany(targetEntity="SME\DGPBundle\Entity\Vinculo", mappedBy="servidor")
        */
    private $vinculosTrabalho;
    
    /**
        * @ORM\ManyToMany(targetEntity="Particularidade")
        * @ORM\JoinTable(name="sme_pessoa_fisica_particularidade",
        *      joinColumns={@ORM\JoinColumn(name="pessoa_fisica_id", referencedColumnName="id")},
        *      inverseJoinColumns={@ORM\JoinColumn(name="particularidade_id", referencedColumnName="id")}
        *   )
        */
    private $particularidades;
    
    public function __construct() {
        parent::__construct();
        $this->relacoes = new ArrayCollection();
        $this->formacoes = new ArrayCollection();
        $this->vinculosTrabalho = new ArrayCollection();
        $this->particularidades = new ArrayCollection();
    }
    
    public function getNumeroRg() {
        return $this->numeroRg;
    }

    public function setNumeroRg($numeroRg) {
        $this->numeroRg = $numeroRg;
    }

    public function getOrgaoExpedidorRg() {
        return $this->orgaoExpedidorRg;
    }

    public function setOrgaoExpedidorRg($orgaoExpedidorRg) {
        $this->orgaoExpedidorRg = $orgaoExpedidorRg;
    }

    public function getPisPasep() {
        return $this->pisPasep;
    }

    public function setPisPasep($pisPasep) {
        $this->pisPasep = $pisPasep;
    }

    public function getNumeroTituloEleitor() {
        return $this->numeroTituloEleitor;
    }

    public function setNumeroTituloEleitor($numeroTituloEleitor) {
        $this->numeroTituloEleitor = $numeroTituloEleitor;
    }

    public function getZonaTituloEleitor() {
        return $this->zonaTituloEleitor;
    }

    public function setZonaTituloEleitor($zonaTituloEleitor) {
        $this->zonaTituloEleitor = $zonaTituloEleitor;
    }

    public function getSecaoTituloEleitor() {
        return $this->secaoTituloEleitor;
    }

    public function setSecaoTituloEleitor($secaoTituloEleitor) {
        $this->secaoTituloEleitor = $secaoTituloEleitor;
    }

    public function getNaturalidade() {
        return $this->naturalidade;
    }

    public function setNaturalidade($naturalidade) {
        $this->naturalidade = $naturalidade;
    }

    public function getEstadoCivil() {
        return $this->estadoCivil;
    }

    public function setEstadoCivil(EstadoCivil $estadoCivil) {
        $this->estadoCivil = $estadoCivil;
    }

    public function getNomeMae() {
        return $this->nomeMae;
    }

    public function setNomeMae($nomeMae) {
        $this->nomeMae = $nomeMae;
    }

    public function getNomePai() {
        return $this->nomePai;
    }

    public function setNomePai($nomePai) {
        $this->nomePai = $nomePai;
    }
    
    public function getRaca() {
        return $this->raca;
    }

    public function setRaca(Raca $raca) {
        $this->raca = $raca;
    }
    
    public function getNacionalidade() {
        return $this->nacionalidade;
    }

    public function setNacionalidade($nacionalidade) {
        $this->nacionalidade = $nacionalidade;
    }
    
    public function getCertidaoNascimento() {
        return $this->certidaoNascimento;
    }

    public function getTermoCertidaoNascimento() {
        return \substr($this->certidaoNascimento, 
                DocumentosUtil::CERTIDAO_NASCIMENTO_TERMO_START, 
                DocumentosUtil::CERTIDAO_NASCIMENTO_TERMO_SIZE);
    }
    
    public function getLivroCertidaoNascimento() {
        return \substr($this->certidaoNascimento,
                DocumentosUtil::CERTIDAO_NASCIMENTO_LIVRO_START, 
                DocumentosUtil::CERTIDAO_NASCIMENTO_LIVRO_SIZE);
    }
    
    public function getFolhaCertidaoNascimento() {
        return \substr($this->certidaoNascimento,
                DocumentosUtil::CERTIDAO_NASCIMENTO_FOLHA_START, 
                DocumentosUtil::CERTIDAO_NASCIMENTO_FOLHA_SIZE);
    }
    
    public function setCertidaoNascimento($certidaoNascimento) {
        $this->certidaoNascimento = $certidaoNascimento;
    }
    
    public function getDataExpedicaoRg() {
        return $this->dataExpedicaoRg;
    }

    public function setDataExpedicaoRg($dataExpedicaoRg) {
        $this->dataExpedicaoRg = $dataExpedicaoRg;
    }
    
    public function getRelacoes() {
        return $this->relacoes;
    }

    function getFormacoes() {
        return $this->formacoes;
    }
   
    public function getProfissao() {
        return $this->profissao;
    }

    public function getCarteiraTrabalhoDataExepdicao() {
        return $this->carteiraTrabalhoDataExepdicao;
    }

    public function setProfissao($profissao) {
        $this->profissao = $profissao;
    }

    public function setCarteiraTrabalhoDataExepdicao($carteiraTrabalhoDataExepdicao) {
        $this->carteiraTrabalhoDataExepdicao = $carteiraTrabalhoDataExepdicao;
    }
    
    public function getCarteiraTrabalhoNumero() {
    	return $this->carteiraTrabalhoNumero;
    }
    
    public function setCarteiraTrabalhoNumero($ctps_num) {
    	$this->carteiraTrabalhoNumero = $ctps_num;
    }
    
    public function getCarteiraTrabalhoSerie() {
    	return $this->carteiraTrabalhoSerie;
    }
    
    public function setCarteiraTrabalhoSerie($ctps_serie) {
    	$this->carteiraTrabalhoSerie = $ctps_serie;
    }
    
    public function getCarteiraTrabalhoDataExpedicao() {
    	return $this->carteiraTrabalhoDataExepdicao;
    }
    
    public function setCarteiraTrabalhoDataExpedicao($ctps_data) {
    	$this->carteiraTrabalhoDataExepdicao = $ctps_data;
    }
    
    public function getCarteiraTrabalhoEstado() {
    	return $this->carteiraTrabalhoEstado;
    }
    
    public function setCarteiraTrabalhoEstado(Estado $ctps_estado) {
    	$this->carteiraTrabalhoEstado = $ctps_estado;
    }

    public function getVinculosTrabalho() {
        return $this->vinculosTrabalho->filter(function($v) { return $v->getAtivo(); });
    }
    
    public function getParticularidades() {
        return $this->particularidades;
    }
    
}
