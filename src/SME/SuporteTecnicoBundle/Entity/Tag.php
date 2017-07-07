<?php

namespace SME\SuporteTecnicoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\SuporteTecnicoBundle\Entity\Categoria;
use SME\SuporteTecnicoBundle\Entity\Prioridade;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_suportetecnico_tag")
 */
class Tag {
    
    /**
        *  @ORM\Id
        *  @ORM\Column(type="integer")
        *  @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /**  @ORM\Column(nullable=false) */
    private $nome;
    
    /** 
        * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="tags")
        * @ORM\JoinColumn(name="categoria_id")
        */
    private $categoria;
    
    /** 
        * @ORM\ManyToOne(targetEntity="Prioridade")
        * @ORM\JoinColumn(name="prioridade_id")
        */
    private $prioridade;
    
    /**  @ORM\Column(nullable=false) */
    private $ativo = true;
    
    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function getCategoria() {
        return $this->categoria;
    }

    function getAtivo() {
        return $this->ativo;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setCategoria(Categoria $categoria = null) {
        $this->categoria = $categoria;
    }

    function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
    function getPrioridade() {
        return $this->prioridade;
    }

    function setPrioridade(Prioridade $prioridade = null) {
        $this->prioridade = $prioridade;
    }
    
}
