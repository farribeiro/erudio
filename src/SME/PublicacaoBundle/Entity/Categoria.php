<?php

namespace SME\PublicacaoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_publicacao_categorias")
 */

class Categoria implements \JsonSerializable {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column(nullable=false) */
    private $nome;
    
    /** @ORM\Column(name="nome_exibicao", nullable=false) */
    private $nomeExibicao;
    
    /** 
     * @ORM\ManyToOne(targetEntity="Grupo")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id", nullable=true)
     */
    private $grupo = null;
    
    /** 
        * @ORM\ManyToOne(targetEntity="Categoria")
        * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id", nullable=true)
        */
    private $categoria = null;
    
    /** @ORM\Column(nullable=true, type="boolean") */
    private $ativo = true;
    
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
    
    public function getGrupo() {
        return $this->grupo;
    }

    public function setGrupo(Grupo $grupo) {
        $this->grupo = $grupo;
    }
    
    public function getCategoria() {
        return $this->categoria;
    }

    public function setCategoria(Categoria $categoria) {
        $this->categoria = $categoria;
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