<?php

namespace SME\SuporteTecnicoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SME\SuporteTecnicoBundle\Entity\Categoria;
use SME\SuporteTecnicoBundle\Entity\Prioridade;
use SME\SuporteTecnicoBundle\Entity\Status;
use SME\CommonsBundle\Entity\PessoaFisica;
use SME\CommonsBundle\Entity\Entidade;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_suportetecnico_chamado")
 */
class Chamado {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column */
    private $descricao;
    
    /** @ORM\ManyToOne(targetEntity="Categoria", inversedBy="chamados") */
    private $categoria;
    
    /** @ORM\ManyToOne(targetEntity="Prioridade") */
    private $prioridade;
    
    /** @ORM\ManyToOne(targetEntity="Status") */
    private $status;
    
    /** @ORM\Column */
    private $solucao;
    
    /** 
     * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\Entidade")
     * @ORM\JoinColumn(name="entidade_local_id", referencedColumnName="id")
     */
    private $local;
    
    /** 
     * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PessoaFisica")
     * @ORM\JoinColumn(name="pessoa_cadastrou_id", referencedColumnName="id")
     */
    private $pessoaCadastrou;
    
    /** @ORM\Column(name="data_cadastro", type="datetime", nullable=false) */
    private $dataCadastro;
    
    /** @ORM\Column(name="data_encerramento", type="datetime") */
    private $dataEncerramento;
    
    /** @ORM\Column(nullable=false) */
    private $ativo;
    
    /** @ORM\OneToMany(targetEntity="Atividade", mappedBy="chamado", cascade={"all"}, orphanRemoval=true) */
    private $atividades;
    
    /** @ORM\OneToMany(targetEntity="Anotacao", mappedBy="chamado", cascade={"all"}, orphanRemoval=true) */
    private $anotacoes;
    
    /**
        *  @ORM\ManyToMany(targetEntity="Tag")
        *  @ORM\JoinTable(name="sme_suportetecnico_chamado_tag",
        *      joinColumns={@ORM\JoinColumn(name="chamado_id", referencedColumnName="id")},
        *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
        *   )
        */
    private $tags;
    
    public function __construct() {
        $this->atividades = new ArrayCollection();
        $this->anotacoes = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }
    
    public function getId() {
        return $this->id;
    }
    
    function getTags() {
        return $this->tags;
    }
 
    public function getTitulo() {
        return $this->titulo;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getCategoria() {
        return $this->categoria;
    }

    public function getPrioridade() {
        return $this->prioridade;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getSolucao() {
        return $this->solucao;
    }

    public function getLocal() {
        return $this->local;
    }

    public function getPessoaCadastrou() {
        return $this->pessoaCadastrou;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function getDataEncerramento() {
        return $this->dataEncerramento;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function getAtividades() {
        return $this->atividades;
    }
    
    public function getAnotacoes() {
        return $this->anotacoes;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function setCategoria(Categoria $categoria) {
        $this->categoria = $categoria;
    }

    public function setPrioridade(Prioridade $prioridade) {
        $this->prioridade = $prioridade;
    }

    public function setStatus(Status $status) {
        $this->status = $status;
    }

    public function setSolucao($solucao) {
        $this->solucao = $solucao;
    }

    public function setLocal(Entidade $local) {
        $this->local = $local;
    }

    public function setPessoaCadastrou(PessoaFisica $pessoaCadastrou) {
        $this->pessoaCadastrou = $pessoaCadastrou;
    }

    public function setDataCadastro(\DateTime $dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    public function setDataEncerramento(\DateTime $dataEncerramento) {
        $this->dataEncerramento = $dataEncerramento;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
    public function getEndereco() {
        $endereco = $this->getLocal()->getPessoaJuridica()->getEndereco();
        return $endereco 
            ? $endereco->getLogradouro() . ' - ' . $endereco->getNumero() . ' / ' . $endereco->getBairro()
            : '';
    }
    
    public function getTelefones() {
        $telefones = $this->getLocal()->getPessoaJuridica()->getTelefones();
        $tel = '';
        foreach($telefones as $t) {
            $tel = $tel . $t->getNumeroFormatado() . ' ';
        }
        return $tel;
    }
    
    public function getEncerrado() {
        return $this->status->getTerminal() && $this->dataEncerramento != null;
    }

}
