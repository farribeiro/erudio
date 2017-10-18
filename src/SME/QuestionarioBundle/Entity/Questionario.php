<?php

namespace SME\QuestionarioBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_questionario")
 */

class Questionario {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column(nullable=false) */
    private $nome = null;
    
    /** @ORM\Column(nullable=true) */
    private $perguntas;
    
    /** @ORM\Column(nullable=false, type="boolean") */
    private $ativo = true;
    
    /** @ORM\Column(nullable=false, name="data_cadastro") */
    private $dataCadastro;
    
    public function getId() {
        return $this->id;
    }
    
    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getPerguntas() {
        return $this->perguntas;
    }

    public function setPerguntas($perguntas) {
        $this->perguntas = $perguntas;
    }
    
    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }
}