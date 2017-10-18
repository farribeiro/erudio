<?php

namespace SME\QuestionarioBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_perguntas")
 */

class Pergunta {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column(nullable=false) */
    private $pergunta;
    
    /** @ORM\Column(nullable=false, type="boolean", name="tem_resposta") */
    private $temResposta = false;
    
    /** @ORM\Column(nullable=true) */
    private $respostas;
    
    /** @ORM\Column(nullable=false, type="boolean", name="multi_resposta") */
    private $multiResposta = false;
    
    /** @ORM\Column(nullable=false, type="boolean") */
    private $ativo = true;
    
    /** @ORM\Column(nullable=false, name="categoria_id") */
    private $categoriaId;
    
    public function getId() {
        return $this->id;
    }

    public function getPergunta() {
        return $this->pergunta;
    }

    public function setPergunta($pergunta) {
        $this->pergunta = $pergunta;
    }
    
    public function getTemResposta() {
        return $this->temResposta;
    }

    public function setTemResposta($temResposta) {
        $this->temResposta = $temResposta;
    }

    public function getRespostas() {
        return $this->respostas;
    }

    public function setRespostas($respostas) {
        $this->respostas = $respostas;
    }
    
    public function getMultiResposta() {
        return $this->multiResposta;
    }

    public function setMultiResposta($multiResposta) {
        $this->multiResposta = $multiResposta;
    }
    
    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
    public function getCategoriaId() {
        return $this->categoriaId;
    }

    public function setCategoriaId($categoriaId) {
        $this->categoriaId = $categoriaId;
    }
}