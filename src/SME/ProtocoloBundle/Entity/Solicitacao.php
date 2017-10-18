<?php

namespace SME\ProtocoloBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="jos_protocolo_solicitacao")
 */
class Solicitacao implements \JsonSerializable {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column(name="titulo", nullable=false) */
    private $nome;
    
    /** @ORM\Column(name="titulo_identificacao", nullable=false, unique=true) */
    private $nomeIdentificacao;
    
    /** @ORM\ManyToOne(targetEntity="Categoria") */
    private $categoria;
    
    /** 
     * @ORM\OneToOne(targetEntity="Situacao")
     * @ORM\JoinColumn(name="situacao_inicial_id", referencedColumnName="id")
     */
    private $situacaoInicial;
    
    /** @ORM\Column() */
    private $ativo;
    
    /** @ORM\Column() */
    private $impresso;
    
    /** @ORM\Column() */
    private $externo;
    
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getCategoria() {
        return $this->categoria;
    }

    public function setCategoria(Categoria $categoria) {
        $this->categoria = $categoria;
    }

    public function getSituacaoInicial() {
        return $this->situacaoInicial;
    }

    public function setSituacaoInicial(Situacao $situacaoInicial = null) {
        $this->situacaoInicial = $situacaoInicial;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
    function getImpresso() {
        return $this->impresso;
    }

    function setImpresso($impresso) {
        $this->impresso = $impresso;
    }

    public function getExterno() {
        return $this->externo;
    }

    public function setExterno($externo) {
        $this->externo = $externo;
    }
    
    public function getNomeIdentificacao() {
        return $this->nomeIdentificacao;
    }

    public function setNomeIdentificacao($nomeIdentificacao) {
        $this->nomeIdentificacao = $nomeIdentificacao;
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'nome' => $this->nome,
            'nomeIdentificacao' => $this->nomeIdentificacao
        );
    }

}
