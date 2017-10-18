<?php

namespace SME\IntranetBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SME\IntranetBundle\Entity\PortalUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_intranet_notificacao")
 */
class Notification {
    
    CONST INFO = 'info';
    CONST DANGER = 'danger';
    CONST WARNING = 'warning';
    CONST SUCCESS = 'success';
    CONST LIMIT_PAGES = 10;
	
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column() */
    private $texto;
    
    /**
        * @ORM\ManyToOne(targetEntity="PortalUser")
        */
    private $usuario;
    
    /** @ORM\Column() */
    private $lido;
    
    /** @ORM\Column() */
    private $tipo;
    
    /** @ORM\Column(name="criado_em",  type="datetime") */
    private $criadoEm;
    
    public function getId() {
        return $this->id;
    }
    
    public function getTexto() {
        return $this->texto;
    }

    public function setTexto($texto) {
        $this->texto = $texto;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario(PortalUser $usuario) {
        $this->usuario = $usuario;
    }

    public function getLido() {
        return $this->lido;
    }

    public function setLido($lido) {
        $this->lido = $lido;
    }

    public function getTipo() {
    	return $this->tipo;
    }
    
    public function setTipo($tipo) {
    	$this->tipo = $tipo;
    }
    
    public function getCriadoEm() {
    	return $this->criadoEm;
    }
    
    public function setCriadoEm($criadoEm) {
    	$this->criadoEm = $criadoEm;
    }
}
