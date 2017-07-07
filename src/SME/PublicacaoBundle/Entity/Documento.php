<?php

namespace SME\PublicacaoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="sme_publicacao_documentos")
 */

class Documento implements \JsonSerializable {
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(nullable=false) */
    private $arquivo;
    
    /** @ORM\Column(name="nome_exibicao", nullable=false) */
    private $nomeExibicao;
    
    /** @ORM\Column(name="mime_type", nullable=false) */
    private $mimeType;
    
    /** 
        * @ORM\ManyToOne(targetEntity="Grupo")
        * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
        */
    private $grupo;
    
    /** 
        * @ORM\ManyToOne(targetEntity="Categoria")
        * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
        */
    private $categoria;
    
    /** @ORM\Column(type="datetime", nullable=false) */
    private $publicacao;
    
    /** 
     * @ORM\ManyToOne(targetEntity="Visibilidade")
     * @ORM\JoinColumn(name="visibilidade_id", referencedColumnName="id")
     */
    private $visibilidade;
    
    /** @ORM\Column(nullable=true, type="boolean") */
    private $ativo;
    
    /** @ORM\Column(nullable=true, type="datetime", name="deleted_at") */
    private $deletedAt;
    
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        return $this->id = $id;
    }

    public function getArquivo() {
        return $this->arquivo;
    }

    public function setArquivo($arquivo) {
        $this->arquivo = $arquivo;
    }

    public function getNomeExibicao() {
        return $this->nomeExibicao;
    }
    
    public function setNomeExibicao($nomeExibicao) {
        $this->nomeExibicao = $nomeExibicao;
    }
    
    public function getMimeType() {
        return $this->mimeType;
    }
    
    public function setMimeType($mimeType) {
        $this->mimeType = $mimeType;
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
    
    public function getVisibilidade() {
        return $this->visibilidade;
    }
    
    public function getPublicacao() {
        return $this->publicacao;
    }

    public function setPublicacao($publicacao) {
        $this->publicacao = $publicacao;
    }

    public function setVisibilidade(Visibilidade $visibilidade) {
        $this->visibilidade = $visibilidade;
    }
    
    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
    public function getDeletedAt() {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt) {
        $this->deletedAt = $deletedAt;
    }
    
    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'nomeExibicao' => $this->nomeExibicao,
            'dataPublicacao' => $this->publicacao,
            'mimeType' => $this->mimeType
        );
    }
}