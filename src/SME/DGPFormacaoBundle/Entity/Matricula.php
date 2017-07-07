<?php

namespace SME\DGPFormacaoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\DGPFormacaoBundle\Entity\Formacao;

/**
* @ORM\Entity
* @ORM\Table(name="sme_dgp_formacao_matricula")
*/
class Matricula {
	
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
    * @ORM\ManyToOne(targetEntity="Formacao", inversedBy="matriculas")
    */
    private $formacao;

    /**
    * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PessoaFisica")
    * @ORM\JoinColumn(name="pessoa_fisica_id")
    */
    private $pessoa;

    /**
    * @ORM\Column(type="boolean")
    */
    private $aprovado;

    /**
    * @ORM\Column(name="data_cadastro", type="datetime")
    */
    private $dataCadastro;
    
    /**
    * @ORM\Column(type="boolean")
    */
    private $ativo = true;

    public function getId() {
        return $this->id;
    }

    public function getFormacao() {
        return $this->formacao;
    }

    public function setFormacao(Formacao $formacao) {
        $this->formacao = $formacao;
    }

    public function getPessoa() {
        return $this->pessoa;
    }

    public function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    public function getAprovado() {
        return $this->aprovado;
    }

    public function setAprovado($aprovado) {
        $this->aprovado = $aprovado;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

}