<?php

namespace SME\EstagioBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_estagio_inscricao")
 */

class Inscricao {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column(type="integer", nullable=false) */
    //private $instituicao;
    
    /** @ORM\Column(type="integer", nullable=false) */
    //private $curso;
    
    /** @ORM\Column(nullable=false) */
    private $estagiario;
    
    /** @ORM\Column(type="integer", nullable=false) */
    private $orientador;
    
    /** @ORM\Column(nullable=true) */
    private $inicio;
    
    /** @ORM\Column(name="inicio_observacao", nullable=true) */
    private $inicioObservacao;
    
    /** @ORM\Column(name="fim_observacao", nullable=true) */
    private $fimObservacao;
    
    /** @ORM\Column(nullable=true) */
    private $fim;
    
    /** @ORM\Column(nullable=false) */
    private $aprovado = 0;
    
    /** @ORM\Column(nullable=false, type="boolean") */
    private $ativo = true;
    
    /** @ORM\Column(name="vaga_id", nullable=false) */
    private $vaga;
    
    /** @ORM\Column(nullable=true) */
    private $telefone;
    
    public function getId() {
        return $this->id;
    }

    /*public function getInstituicao() {
        return $this->instituicao;
    }

    public function setInstituicao($instituicao) {
        $this->instituicao = $instituicao;
    }
    
    public function getCurso() {
        return $this->curso;
    }

    public function setCurso($curso) {
        $this->curso = $curso;
    }*/
    
    public function getEstagiario() {
        return $this->estagiario;
    }

    public function setEstagiario($estagiario) {
        $this->estagiario = $estagiario;
    }
    
    public function getOrientador() {
        return $this->orientador;
    }

    public function setOrientador($orientador) {
        $this->orientador = $orientador;
    }
    
    public function getInicio() {
        return $this->inicio;
    }

    public function setInicio($inicio) {
        $this->inicio = $inicio;
    }

    public function getFim() {
        return $this->fim;
    }

    public function setFim($fim) {
        $this->fim = $fim;
    }
    
    public function getInicioObservacao() {
        return $this->inicioObservacao;
    }

    public function setInicioObservacao($inicioObservacao) {
        $this->inicioObservacao = $inicioObservacao;
    }

    public function getFimObservacao() {
        return $this->fimObservacao;
    }

    public function setFimObservacao($fimObservacao) {
        $this->fimObservacao = $fimObservacao;
    }
    
    public function getAprovado() {
        return $this->aprovado;
    }

    public function setAprovado($aprovado) {
        $this->aprovado = $aprovado;
    }
    
    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
    public function getVaga() {
        return $this->vaga;
    }

    public function setVaga($vaga) {
        $this->vaga = $vaga;
    }
    
    public function getTelefone() {
        return $this->telefone;
    }

    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }
}