<?php

namespace SME\SuporteTecnicoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_suportetecnico_equipe")
 */
class Equipe {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(nullable=false, unique=true) */
    private $nome;
    
    /** @ORM\Column */
    private $departamento;
    
    /**
        * @ORM\ManyToMany(targetEntity="SME\CommonsBundle\Entity\PessoaFisica")
        * @ORM\JoinTable(name="sme_suportetecnico_equipe_integrantes",
        *      joinColumns={@ORM\JoinColumn(name="equipe_id", referencedColumnName="id")},
        *      inverseJoinColumns={@ORM\JoinColumn(name="pessoa_fisica_id", referencedColumnName="id")}
        *   )
        */
    private $integrantes;
    
    /** @ORM\Column */
    private $ativo = true;
    
    /** @ORM\OneToMany(targetEntity="Categoria", mappedBy="equipe") */
    private $categorias;
    
    public function __construct() {
        $this->integrantes = new ArrayCollection();
        $this->categorias = new ArrayCollection();
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
    
    public function getDepartamento() {
        return $this->departamento;
    }

    public function setDepartamento($departamento) {
        $this->departamento = $departamento;
    }
    
    public function getIntegrantes() {
        return $this->integrantes;
    }
    
    public function getAtivo() {
    	return $this->ativo;
    }
    
    public function setAtivo($ativo) {
    	$this->ativo = $ativo;
    }

    public function getCategorias() {
        return $this->categorias;
    }
    
}
