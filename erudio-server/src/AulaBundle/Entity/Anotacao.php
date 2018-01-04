<?php

namespace AulaBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_aula_anotacao")
*/
class Anotacao extends AbstractEditableEntity {
    
    /** 
    * @JMS\Groups({"LIST"})   
    * @ORM\Column(name = "observacao") 
    */
    private $conteudo;
    
    /**
    * @JMS\Groups({"DETAILS"})      
    * @ORM\ManyToOne(targetEntity = "Aula")
    * @ORM\JoinColumn(nullable = false)
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
