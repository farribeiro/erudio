<?php

namespace SME\DGPContratacaoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\DGPContratacaoBundle\Entity\Cargo;
use SME\CommonsBundle\Entity\PessoaFisica;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_dgp_vinculacao_inscricao")
 */
class Inscricao {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(nullable=false) */
    private $numero;
    
    /**
     * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PessoaFisica", cascade={"merge"}, fetch="LAZY")
     * @ORM\JoinCOlumn(name="pessoa_candidato_id")
     */
    private $candidato;
    
    /**
        * @ORM\ManyToOne(targetEntity="Cargo", inversedBy="inscricoes")
        * @ORM\JoinCOlumn(name="processo_cargo_id")
        */
    private $cargo;
    
    /** @ORM\Column */
    private $classificacao;
    
    /** @ORM\Column(name="avaliacao_media_final") */
    private $avaliacao;
    
    /** @ORM\Column */
    private $ativo;
    
    /** 
        * @ORM\OneToOne(targetEntity="SME\DGPBundle\Entity\Vinculo", mappedBy="inscricaoVinculacao")
        */
    private $vinculoAssociado;
    
    public function getDescricao() {
        return $this->getProcesso()->getNome() . ' | ' . $this->cargo->getNome();
    }
    
    public function getId() {
        return $this->id;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function getCandidato() {
        return $this->candidato;
    }

    public function getCargo() {
        return $this->cargo;
    }

    public function getProcesso() {
        return $this->cargo->getProcesso();
    }
    
    public function getClassificacao() {
        return $this->classificacao;
    }

    public function getAvaliacao() {
        return $this->avaliacao;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }

    public function setCandidato(PessoaFisica $candidato) {
        $this->candidato = $candidato;
    }

    public function setCargo(Cargo $cargo) {
        $this->cargo = $cargo;
    }

    public function setClassificacao($classificacao) {
        $this->classificacao = $classificacao;
    }

    public function setAvaliacao($avaliacao) {
        $this->avaliacao = $avaliacao;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
    function getVinculoAssociado() {
        return $this->vinculoAssociado;
    }

    function setVinculoAssociado($vinculoAssociado) {
        $this->vinculoAssociado = $vinculoAssociado;
    }

}
