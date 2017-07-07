<?php

namespace SME\PresencaBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\CommonsBundle\Entity\Entidade;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_presenca")
 */

class Presenca {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column(nullable=false) */
    private $turma;
    
    /** @ORM\Column(nullable=false) */
    private $turno;
    
    /** @ORM\Column(name="quantidade_alunos", nullable=false) */
    private $qtdeAlunos;
    
    /** @ORM\Column(name="data", nullable=false) */
    private $dataCadastro;
    
    /** 
     * @ORM\ManyToOne(targetEntity="SME\CommonsBundle\Entity\Entidade")
     * @ORM\JoinColumn(name="entidade_id", referencedColumnName="id", nullable=true)
     */
    private $entidade;
        
    public function getId() {
        return $this->id;
    }

    public function getTurma() {
        return $this->turma;
    }

    public function setTurma($turma) {
        $this->turma = $turma;
    }
    
    public function getTurno() {
        return $this->turno;
    }

    public function setTurno($turno) {
        $this->turno = $turno;
    }
    
    public function getQtdeAlunos() {
        return $this->qtdeAlunos;
    }

    public function setQtdeAlunos($qtde) {
        $this->qtdeAlunos = $qtde;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }
    
    public function getEntidade() {
        return $this->entidade;
    }

    public function setEntidade(Entidade $entidade) {
        $this->entidade = $entidade;
    }

    public function getTurmas() {
        return array('Maternal I' => 'Maternal I', 'Maternal II' => 'Maternal II', 'Berçario I' => 'Berçario I', 'Berçario II' => 'Berçario II', 'Jardim I' => 'Jardim I', 'Jardim II' => 'Jardim II', 'Pré' => 'Pré');
    }
    
    public function getPeriodos() {
        return array('Matutino' => 'Matutino', 'Vespertino' => 'Vespertino', 'Integral' => 'Integral');
    }
}