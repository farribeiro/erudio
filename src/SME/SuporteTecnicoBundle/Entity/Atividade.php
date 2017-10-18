<?php

namespace SME\SuporteTecnicoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SME\SuporteTecnicoBundle\Entity\Chamado;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_suportetecnico_atividade")
 */
class Atividade {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column */
    private $descricao;
    
    /** @ORM\Column(type="datetime", nullable=false) */
    private $inicio;
    
    /** @ORM\Column(type="datetime", nullable=false) */
    private $termino;
    
    /**
    * @ORM\ManyToMany(targetEntity="SME\CommonsBundle\Entity\PessoaFisica")
    * @ORM\JoinTable(name="sme_suportetecnico_atividade_tecnicos",
    *      joinColumns={@ORM\JoinColumn(name="atividade_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="pessoa_fisica_id", referencedColumnName="id")}
    *   )
    */
    private $tecnicos;
    
    /** @ORM\Column(name="data_cadastro", type="datetime", nullable=false) */
    private $dataCadastro;
    
    /** 
     * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PessoaFisica")
     * @ORM\JoinColumn(name="pessoa_cadastrou_id", referencedColumnName="id")
     */
    private $pessoaCadastrou;
    
    /** @ORM\ManyToOne(targetEntity="Chamado", inversedBy="atividades") */
    private $chamado;
    
    public function __construct() {
        $this->tecnicos = new ArrayCollection();
    }


    public function getId() {
        return $this->id;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getInicio() {
        return $this->inicio;
    }

    public function getTermino() {
        return $this->termino;
    }

    public function getTecnicos() {
        return $this->tecnicos;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function getPessoaCadastrou() {
        return $this->pessoaCadastrou;
    }

    public function getChamado() {
        return $this->chamado;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function setInicio($inicio) {
        $this->inicio = $inicio;
    }

    public function setTermino($termino) {
        $this->termino = $termino;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    public function setPessoaCadastrou($pessoaCadastrou) {
        $this->pessoaCadastrou = $pessoaCadastrou;
    }

    public function setChamado($chamado) {
        $this->chamado = $chamado;
    }
    
}
