<?php

namespace SME\QuestionarioBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_questionario_perguntas")
 */

class QuestionarioPergunta {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column(nullable=false, type="integer", name="questionario_id") */
    private $questionarioId;
    
    /** @ORM\Column(nullable=false, type="integer", name="pergunta_id") */
    private $perguntaId;
    
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
}