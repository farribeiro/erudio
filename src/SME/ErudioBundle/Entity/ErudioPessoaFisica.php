<?php

namespace SME\ErudioBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\ErudioBundle\Entity\ErudioPessoa;
/**
 * @ORM\Entity
 * @ORM\Table(name="sme_pessoa_fisica")
 */
class ErudioPessoaFisica {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        */
    private $id;
    
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        return $this->id = $id;
    }
}
