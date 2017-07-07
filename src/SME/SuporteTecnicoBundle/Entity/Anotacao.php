<?php

namespace SME\SuporteTecnicoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\SuporteTecnicoBundle\Entity\Chamado;
use SME\CommonsBundle\Entity\PessoaFisica;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_suportetecnico_anotacao")
 */
class Anotacao {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column */
    private $descricao;
    
    /** @ORM\Column(name="data_cadastro", type="datetime", nullable=false) */
    private $dataCadastro;
    
    /** 
     * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PessoaFisica")
     * @ORM\JoinColumn(name="pessoa_cadastrou_id", referencedColumnName="id")
     */
    private $pessoaCadastrou;
    
    /** @ORM\ManyToOne(targetEntity="Chamado", inversedBy="anotacoes") */
    private $chamado;
    
    public function getId() {
        return $this->id;
    }

    public function getDescricao() {
        return $this->descricao;
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

    public function setDataCadastro(\DateTime $dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    public function setPessoaCadastrou(PessoaFisica $pessoaCadastrou) {
        $this->pessoaCadastrou = $pessoaCadastrou;
    }

    public function setChamado(Chamado $chamado) {
        $this->chamado = $chamado;
    }


    
}
