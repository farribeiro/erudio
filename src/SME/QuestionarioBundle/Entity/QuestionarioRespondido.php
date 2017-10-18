<?php

namespace SME\QuestionarioBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_questionario_respondido")
 */

class QuestionarioRespondido {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column(nullable=false, type="integer", name="pergunta_id") */
    private $perguntaId;
    
    /** @ORM\Column(nullable=false, type="integer", name="questionario_id") */
    private $questionarioId;
    
    /** @ORM\Column(nullable=true) */
    private $resposta = false;
    
    /** @ORM\Column(nullable=true, name="tem_respostas") */
    private $temRespostas = false;
    
    /** @ORM\Column(nullable=true) */
    private $respostas;
    
    /** @ORM\Column(nullable=true, name="respondido_por") */
    private $respondidoPor;
    
    /** @ORM\Column(nullable=true, name="vinculo_pessoa_juridica") */
    private $vinculoPessoa;
    
    /** @ORM\Column(nullable=true, name="respondido_em") */
    private $respondidoEm;
    
    public function getId() {
        return $this->id;
    }

    public function getPerguntaId() {
        return $this->perguntaId;
    }

    public function setPerguntaId($perguntaId) {
        $this->perguntaId = $perguntaId;
    }
    
    public function getQuestionarioId() {
        return $this->questionarioId;
    }

    public function setQuestionarioId($questionarioId) {
        $this->questionarioId = $questionarioId;
    }

    public function getResposta() {
        return $this->resposta;
    }
    
    public function setResposta($resposta) {
        $this->resposta = $resposta;
    }

    public function setTemResposta($temResposta) {
        $this->temResposta = $temResposta;
    }
    
    public function getTemRespostas() {
        return $this->temResposta;
    }

    public function getRespostas() {
        return $this->respostas;
    }

    public function setRespostas($respostas) {
        $this->respostas = $respostas;
    }
    
    public function getRespondidoPor() {
        return $this->respondidoPor;
    }

    public function setRespondidoPor($respondidoPor) {
        $this->respondidoPor = $respondidoPor;
    }
    
    public function getVinculoPessoa() {
        return $this->vinculoPessoa;
    }

    public function setVinculoPessoa($vinculoPessoa) {
        $this->vinculoPessoa = $vinculoPessoa;
    }
    
    public function getRespondidoEm() {
        return $this->respondidoEm;
    }

    public function setRespondidoEm($respondidoEm) {
        $this->respondidoEm = $respondidoEm;
    }
}