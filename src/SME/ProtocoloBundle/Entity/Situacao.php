<?php

namespace SME\ProtocoloBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="jos_protocolo_situacao")
 */
class Situacao {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(nullable=true) */
    private $nome;
    
    /** @ORM\Column() */
    private $descricao;
    
    /** @ORM\Column() */
    private $terminal;
    
    /** 
     * @ORM\ManyToOne(targetEntity="Categoria")
     * @ORM\JoinColumn(name="categoria_aplicavel_id", referencedColumnName="id")
     */
    private $categoriaAplicavel;
    
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getTerminal() {
        return $this->terminal;
    }

    public function setTerminal($terminal) {
        $this->terminal = $terminal;
    }

    public function getCategoriaAplicavel() {
        return $this->categoriaAplicavel;
    }

    public function setCategoriaAplicavel($categoriaAplicavel) {
        $this->categoriaAplicavel = $categoriaAplicavel;
    }
    
}
