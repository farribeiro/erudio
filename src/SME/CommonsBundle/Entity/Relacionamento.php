<?php

namespace SME\CommonsBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_pessoa_fisica_relacionamento")
 */
class Relacionamento {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** 
     * @ORM\ManyToOne(targetEntity="PessoaFisica", inversedBy="relacoes")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;
    
    /** 
     * @ORM\OneToOne(targetEntity="PessoaFisica")
     * @ORM\JoinColumn(name="pessoa_parente_id", referencedColumnName="id") 
     */
    private $parente;
    
    /** @ORM\OneToOne(targetEntity="Parentesco") */
    private $parentesco;
    
    /** @ORM\Column(name="responsavel_legal", nullable=false) */
    private $responsavel;
    
    public function getId() {
        return $this->id;
    }
    
    public function getPessoa() {
        return $this->pessoa;
    }

    public function setPessoa(PessoaFisica $pessoa) {
        $this->pessoa = $pessoa;
    }

    public function getParentesco() {
        return $this->parentesco;
    }

    public function setParentesco(Parentesco $parentesco) {
        $this->parentesco = $parentesco;
    }

    public function getResponsavel() {
        return $this->responsavel;
    }

    public function setResponsavel($responsavel) {
        $this->responsavel = $responsavel;
    }
    
    public function getParente() {
        return $this->parente;
    }

    public function setParente(PessoaFisica $parente) {
        $this->parente = $parente;
    }
    
}

?>
