<?php

namespace SME\FilaUnicaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="sme_fu_inscricao_evento")
*/
class Evento {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\ManyToOne(targetEntity="Inscricao", inversedBy="historico") */
    private $inscricao;
    
    /** @ORM\Column() */
    private $descricao;
    
    /** @ORM\Column(name="data_ocorrencia", type="date", nullable=false) */
    private $dataOcorrencia;
    
    /** @ORM\Column(name="data_cadastro", type="datetime", nullable=false) */
    private $dataCadastro;
    
    /** 
    * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\PessoaFisica")
    * @ORM\JoinColumn(name="pessoa_cadastrou_id", referencedColumnName="id")
    */
    private $pessoaCadastrou;
    
    public function getId() {
        return $this->id;
    }
    
    public function getInscricao() {
        return $this->inscricao;
    }

    public function setInscricao(Inscricao $inscricao) {
        $this->inscricao = $inscricao;
    }
    
    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getDataOcorrencia() {
        return $this->dataOcorrencia;
    }

    public function setDataOcorrencia(\DateTime $dataOcorrencia) {
        $this->dataOcorrencia = $dataOcorrencia;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function setDataCadastro(\DateTime $dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }
    
    public function getPessoaCadastrou() {
        return $this->pessoaCadastrou;
    }

    public function setPessoaCadastrou($pessoaCadastrou) {
        $this->pessoaCadastrou = $pessoaCadastrou;
    }
    
}
