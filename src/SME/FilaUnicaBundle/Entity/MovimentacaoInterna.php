<?php

namespace SME\FilaUnicaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="sme_fu_movimentacao_interna")
*/
class MovimentacaoInterna {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** 
        * @ORM\OneToOne(targetEntity="Inscricao")
        */
    private $inscricao;
    
    /**
        * @ORM\ManyToOne(targetEntity="UnidadeEscolar")
        * @ORM\JoinColumn(name="unidade_original_id")
        */
    private $unidadeEscolarOriginal;
    
    /**
        * @ORM\ManyToOne(targetEntity="UnidadeEscolar")
        * @ORM\JoinColumn(name="unidade_alterada_id")
        */
    private $unidadeEscolarAlterada;
    
    /**
        * @ORM\ManyToOne(targetEntity="AnoEscolar")
        * @ORM\JoinColumn(name="ano_escolar_original_id")
        */
    private $anoEscolarOriginal;
    
    /**
        * @ORM\ManyToOne(targetEntity="AnoEscolar")
        * @ORM\JoinColumn(name="ano_escolar_alterado_id")
        */
    private $anoEscolarAlterado;
    
    /**
        * @ORM\Column(nullable=false)
        */
    private $justificativa;
    
    /** @ORM\Column(name="data_cadastro", type="datetime", nullable=false) */
    private $dataCadastro;
    
    /** 
        * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PessoaFisica")
        * @ORM\JoinColumn(name="pessoa_atendente_id", referencedColumnName="id")
        */
    private $atendente;
    
    /**
        * @ORM\Column(nullable=false)
        */
    private $ativo;
    
    public function getId() {
        return $this->id;
    }

    public function getInscricao() {
        return $this->inscricao;
    }

    public function getUnidadeEscolarOriginal() {
        return $this->unidadeEscolarOriginal;
    }

    public function getUnidadeEscolarAlterada() {
        return $this->unidadeEscolarAlterada;
    }

    public function getJustificativa() {
        return $this->justificativa;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function getAtendente() {
        return $this->atendente;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function setInscricao($inscricao) {
        $this->inscricao = $inscricao;
    }

    public function setUnidadeEscolarOriginal($unidadeEscolarOriginal) {
        $this->unidadeEscolarOriginal = $unidadeEscolarOriginal;
    }

    public function setUnidadeEscolarAlterada($unidadeEscolarAlterada) {
        $this->unidadeEscolarAlterada = $unidadeEscolarAlterada;
    }

    public function getAnoEscolarOriginal() {
        return $this->anoEscolarOriginal;
    }

    public function getAnoEscolarAlterado() {
        return $this->anoEscolarAlterado;
    }

    public function setAnoEscolarOriginal($anoEscolarOriginal) {
        $this->anoEscolarOriginal = $anoEscolarOriginal;
    }

    public function setAnoEscolarAlterado($anoEscolarAlterado) {
        $this->anoEscolarAlterado = $anoEscolarAlterado;
    }
   
    public function setJustificativa($justificativa) {
        $this->justificativa = $justificativa;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    public function setAtendente($atendente) {
        $this->atendente = $atendente;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
}
