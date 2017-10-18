<?php

namespace SME\FilaUnicaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="sme_fu_inscricao_renda")
*/
class ComponenteRenda {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\ManyToOne(targetEntity="Inscricao", inversedBy="rendaDetalhada") */
    private $inscricao;
    
    /** @ORM\Column() */
    private $nome;
    
    /** @ORM\Column() */
    private $parentesco;
    
    /** @ORM\Column(name="rendimento_mensal") */
    private $rendimentoMensal;
    
    /** @ORM\Column() */
    private $atividade;

    /** @ORM\Column(name="local_trabalho") */
    private $localTrabalho;
    
    /** 
     * @ORM\Column(name="responsavel_id", nullable=true)
     */
    private $responsavel = NULL;
    
    
    public function getId() {
        return $this->id;
    }

    public function getInscricao() {
        return $this->inscricao;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getParentesco() {
        return $this->parentesco;
    }

    public function getRendimentoMensal() {
        return $this->rendimentoMensal;
    }

    public function setInscricao(Inscricao $inscricao) {
        $this->inscricao = $inscricao;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setParentesco($parentesco) {
        $this->parentesco = $parentesco;
    }

    public function setRendimentoMensal($rendimentoMensal) {
        $this->rendimentoMensal = $rendimentoMensal;
    }
    
    public function getAtividade() {
        return $this->atividade;
    }

    public function getLocalTrabalho() {
        return $this->localTrabalho;
    }

    public function setAtividade($atividade) {
        $this->atividade = $atividade;
    }

    public function setLocalTrabalho($localTrabalho) {
        $this->localTrabalho = $localTrabalho;
    }

    public function getResponsavel() {
        return $this->responsavel;
    }

    public function setResponsavel($responsavel) {
        $this->responsavel = $responsavel;
    }

}
