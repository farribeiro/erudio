<?php

namespace SME\CommonsBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="sme_grau_formacao")
 */
class GrauFormacao {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @ORM\Column */
	private $nome;
	
	public function getId() {
 		return $this->id;
 	}

	public function setId($id) {
 		$this->id = $id;
 	}

	public function getNome() {
 		return $this->nome;
 	}

	public function setNome($nome) {
 		$this->nome = $nome;
 	}
        
}