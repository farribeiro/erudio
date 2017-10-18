<?php

namespace SME\PublicacaoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_publicacao_grupos")
 */

class Grupo implements \JsonSerializable {
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(nullable=false, unique=true) */
    private $nome;
    
    /** @ORM\Column(name="nome_exibicao", nullable=false) */
    private $nomeExibicao;
    
    /** @ORM\Column(nullable=true, type="boolean") */
    private $ativo;
    
    public function __construct()
    {
        $this->ativo = true;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getNomeExibicao() {
        return $this->nomeExibicao;
    }

    public function setNomeExibicao($nomeExibicao) {
        $this->nomeExibicao = $nomeExibicao;
    }
    
    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'nomeExibicao' => $this->nomeExibicao
        );
    }
    
}