<?php

namespace SME\CommonsBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_pessoa_telefone")
 */
class Telefone {
    
    const PRINCIPAL = "RESIDENCIAL";
    const CELULAR = "CELULAR";
    const COMERCIAL = "COMERCIAL";
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column() */
    private $numero;
    
    /** @ORM\Column() */
    private $descricao;
    
    /** @ORM\Column(name="falar_com") */
    private $falarCom;
    
    /** @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="telefones") */
    private $pessoa;

    public function getId() {
        return $this->id;
    }
    
    public function getNumero() {
        return $this->numero;
    }

    public function setNumero($numero) {
        $this->numero = \str_replace(array('(', ')', '-'), '', $numero);
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getFalarCom() {
        return $this->falarCom;
    }

    public function setFalarCom($falarCom) {
        $this->falarCom = $falarCom;
    }
    
    public function getPessoa() {
        return $this->pessoa;
    }

    public function setPessoa(Pessoa $pessoa) {
        $this->pessoa = $pessoa;
    }
    
    public function getNumeroFormatado() {
        if(!\is_numeric($this->numero)) { $this->setNumero($this->numero); }
        $size = \strlen($this->numero);
        if($size < 10) {
            return \substr($this->numero, 0, $size - 4) . '-' . \substr($this->numero, $size - 4);
        } elseif($size <= 11) {
            return '(' . \substr($this->numero, 0, 2) . ')' . 
                    \substr($this->numero, 2, $size - 6) . '-' . \substr($this->numero, $size - 4); 
        } else {
            return $this->numero;
        }
    }
    
}
