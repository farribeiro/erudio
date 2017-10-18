<?php

namespace SME\CommonsBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="sme_entidade_classe")
 */
class ClasseEntidade implements \Serializable {
    
    const UNIDADE_ESCOLAR = 3;
    const CEI = 12;
    const CEDIN = 13;
    const ESCOLA = 14;
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /** @ORM\Column(unique=true, nullable=false) */
    private $nome;
    
    /** @ORM\Column(nullable=false) */
    private $sigla;
    
    /**
     * @ORM\OneToOne(targetEntity="ClasseEntidade")
     * @ORM\JoinColumn(name="classe_pai_id", referencedColumnName="id") 
     */
    private $classePai;
    
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

    public function getSigla() {
        return $this->sigla;
    }

    public function setSigla($sigla) {
        $this->sigla = $sigla;
    }

    public function getClassePai() {
        return $this->classePai;
    }

    public function setClassePai($classePai) {
        $this->classePai = $classePai;
    }

    public function serialize()
    {
        return \json_encode(array($this->id, $this->nome, $this->sigla));
    }

    public function unserialize($serialized)
    {
        list($this->id, $this->nome, $this->sigla) = \json_decode($serialized);
    }
    
    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'nome' => $this->nome,
            'sigla' => $this->sigla
        );
    }
    
}
