<?php

namespace SME\DGPContratacaoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="sme_dgp_vinculacao_processo_tipo")
 */
class TipoProcesso {
    
    const CONCURSO_PUBLICO = 1;
    const PROCESSO_SELETIVO = 2;
    const CHAMADA_PUBLICA = 3;
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(nullable=false, unique=true) */
    private $nome;
    
    /** @ORM\OneToMany(targetEntity="Processo", mappedBy="tipoProcesso") */
    private $processos;
    
    public function __construct() {
        $this->processos = new ArrayCollection();
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
    
    public function getProcessos() {
        return $this->processos;
    }
    
}
