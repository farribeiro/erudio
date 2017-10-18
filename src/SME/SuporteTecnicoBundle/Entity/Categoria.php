<?php

namespace SME\SuporteTecnicoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SME\SuporteTecnicoBundle\Entity\Prioridade;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_suportetecnico_categoria")
 */
class Categoria implements \JsonSerializable {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(nullable=false) */
    private $nome;
    
    /** 
        * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="subcategorias")
        * @ORM\JoinColumn(name="categoria_pai_id", nullable=true)
        */
    private $categoriaPai;
    
    /** 
        * @ORM\OneToMany(targetEntity="Categoria", mappedBy="categoriaPai") 
       * @ORM\OrderBy({"nome" = "ASC"})
        */
    private $subcategorias;
    
    /** 
        * @ORM\ManyToOne(targetEntity="Equipe", inversedBy="categorias")
        * @ORM\JoinColumn(name="equipe_vinculada_id")
        */
    private $equipe;
    
    /** 
        * @ORM\ManyToOne(targetEntity="Prioridade")
        * @ORM\JoinColumn(name="prioridade_id")
        */
    private $prioridade;
    
    /** @ORM\OneToMany(targetEntity="Chamado", mappedBy="categoria") */
    private $chamados;
    
    /** 
        * @ORM\OneToMany(targetEntity="Tag", mappedBy="categoria", cascade={"all"}, orphanRemoval=true) 
        * @ORM\OrderBy({"nome" = "ASC"})     
        */
    private $tags;
    
    /** @ORM\Column(nullable=false) */
    private $ativo;
    
    public function __construct() {
        $this->subcategorias = new ArrayCollection();
        $this->chamados = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }
    
    public function getNomeHierarquico() {
        return $this->categoriaPai instanceof Categoria ? $this->getCategoriaPai()->getNomeHierarquico() . ' > ' . $this->getNome() : $this->getNome();
    }
    
    public function getAllSubcategorias() {
        $subcategorias = new ArrayCollection($this->getSubcategorias()->getValues());
        foreach($this->subcategorias as $categoria) {
            foreach($categoria->getAllSubCategorias() as $c) {
                $subcategorias->add($c);
            }
        }
        return $subcategorias;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    public function getCategoriaPai() {
        return $this->categoriaPai;
    }

    public function setCategoriaPai(Categoria $categoriaPai = null) {
        $this->categoriaPai = $categoriaPai;
    }
    
    public function getEquipe() {
        return $this->equipe;
    }

    public function setEquipe($equipe) {
        $this->equipe = $equipe;
    }
    
    function getPrioridade() {
        return $this->prioridade;
    }

    function setPrioridade(Prioridade $prioridade = null) {
        $this->prioridade = $prioridade;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
    public function getSubcategorias() {
        return $this->subcategorias->filter(function($c) { return $c->getAtivo(); });
    }
    
    public function getChamados() {
        foreach($this->subcategorias as $categoria) {
            foreach($categoria->getChamados() as $chamado) {
                $this->chamados->add($chamado);
            }
        }
        return $this->chamados;
    }
    
    function getTags() {
        if($this->categoriaPai) {
            $tagsPai = $this->categoriaPai->getTags();
            foreach($tagsPai as $t) {
                $this->tags->add($t);
            }
        }
        return $this->tags->filter(function($t) { return $t->getAtivo(); });
    }

    function setTags($tags) {
        $this->tags = $tags;
    }
 
    public function jsonSerialize() {
        return array('id' => $this->id, 'nome' => $this->nome);
    }
    
}
