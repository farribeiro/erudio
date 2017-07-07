<?php

namespace SME\CommonsBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\CommonsBundle\Entity\Pessoa;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_pessoa_juridica")
 */
class PessoaJuridica extends Pessoa {
    
    /** @ORM\Column(name="inscricao_estadual_numero") */
    private $numeroInscricao;
    
    /** @ORM\Column(name="inscricao_estadual_uf") */
    private $estadoInscricao;
    
    public function getNumeroInscricao() {
        return $this->numeroInscricao;
    }

    public function setNumeroInscricao($numeroInscricao) {
        $this->numeroInscricao = $numeroInscricao;
    }

    public function getEstadoInscricao() {
        return $this->estadoInscricao;
    }

    public function setEstadoInscricao($estadoInscricao) {
        $this->estadoInscricao = $estadoInscricao;
    }
    
}
