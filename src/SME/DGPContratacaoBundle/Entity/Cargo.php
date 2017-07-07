<?php

namespace SME\DGPContratacaoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\DGPContratacaoBundle\Entity\Processo;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="sme_dgp_vinculacao_processo_cargo")
 */
class Cargo {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(nullable=false) */
    private $nome;
    
    /**
     * @ORM\ManyToOne(targetEntity="Processo", inversedBy="cargos")
     */
    private $processo;
    
    /**
     * @ORM\OneToMany(targetEntity="Inscricao", mappedBy="cargo")
     */
    private $inscricoes;
    
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getProcesso() {
        return $this->processo;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setProcesso(Processo $processo) {
        $this->processo = $processo;
    }
    
    public function getInscricoes() {
        return $this->inscricoes;
    }
    
}
