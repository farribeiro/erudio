<?php

namespace SME\PublicacaoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="sme_publicacao_documentos_visibilidade")
 */

class Visibilidade {
    
    const PRIVADO = 1;
    const PUBLICO = 2;
    
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
}