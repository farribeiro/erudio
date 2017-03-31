<?php

namespace CalendarioBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_aula_observacao")
*/

class AulaObservacao extends AbstractEditableEntity {
    
    /** 
    *   @JMS\Groups({"LIST"})   
    *   @ORM\Column(nullable = true) 
    */
    private $observacao;
    
    /**        
    * @ORM\ManyToOne(targetEntity = "Aula")
    * @ORM\JoinColumn(name = "aula_id")
    */
    private $aula;
    
    function getObservacao() {
        return $this->observacao;
    }

    function getAula() {
        return $this->aula;
    }

    function setObservacao($observacao) {
        $this->observacao = $observacao;
    }
    
}
