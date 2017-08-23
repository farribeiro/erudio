<?php

namespace AulaBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_aula_observacao")
*/

class Anotacao extends AbstractEditableEntity {
    
    /** 
    * @JMS\Groups({"LIST"})   
    * @ORM\Column(name = "observacao", nullable = false) 
    */
    private $conteudo;
    
    /**
    * @JMS\Groups({"DETAILS"})      
    * @ORM\ManyToOne(targetEntity = "Aula")
    * @ORM\JoinColumn(name = "aula_id")
    */
    private $aula;
    
    function getConteudo() {
        return $this->conteudo;
    }

    function setConteudo($conteudo) {
        $this->conteudo = $conteudo;
    }

    function getAula() {
        return $this->aula;
    }
    
}
