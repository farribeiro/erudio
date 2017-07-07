<?php

namespace SME\CommonsBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use SME\CommonsBundle\Entity\PessoaJuridica;
use SME\CommonsBundle\Entity\ClasseEntidade;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_entidade")
 */
class Entidade implements \Serializable {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /**
     * @ORM\OneToOne(targetEntity="PessoaJuridica", fetch="EAGER")
     * @ORM\JoinColumn(name="pessoa_juridica_id", referencedColumnName="id") 
     */
    private $pessoaJuridica;
    
    /**
     * @ORM\OneToOne(targetEntity="Entidade")
     * @ORM\JoinColumn(name="entidade_pai_id", referencedColumnName="id") 
     */
    private $entidadePai;
    
    /** @ORM\ManyToOne(targetEntity="ClasseEntidade") */
    private $classe;
    
    public function getPessoaJuridica() {
        return $this->pessoaJuridica;
    }

    public function setPessoaJuridica(PessoaJuridica $pessoaJuridica) {
        $this->pessoaJuridica = $pessoaJuridica;
    }

    public function getId() {
        return $this->id;
    }

    public function getClasse() {
        return $this->classe;
    }

    public function setClasse(ClasseEntidade $classe) {
        $this->classe = $classe;
    }
    
    public function getEntidadePai() {
        return $this->entidadePai;
    }

    public function setEntidadePai(Entidade $entidadePai) {
        $this->entidadePai = $entidadePai;
    }
    
    public function getNome() {
        return $this->pessoaJuridica->getNome();
    }
    
    public function setNome($nome) {
        return $this->pessoaJuridica->setNome($nome);
    }
    
    public function getCpfCnpj() {
        return $this->pessoaJuridica->getCpfCnpj();
    }

    public function setCpfCnpj($cpfCnpj) {
        $this->pessoaJuridica->setCpfCnpj($cpfCnpj);
    }

    public function getDataNascimento() {
        return $this->pessoaJuridica->getDataNascimento();
    }

    public function setDataNascimento(\DateTime $dataNascimento = null) {
        $this->pessoaJuridica->setDataNascimento($dataNascimento);
    }

    public function getEmail() {
        return $this->pessoaJuridica->getEmail();
    }

    public function setEmail($email) {
        $this->pessoaJuridica->setEmail($email);
    }
    
    public function getCodigoInep() {
        return $this->pessoaJuridica->getNumeroInscricao();
    }
    
    public function setCodigoInep($inep) {
        $this->pessoaJuridica->setNumeroInscricao($inep);
    }
    
    public function serialize()
    {
        return \json_encode(array(
            'id' => $this->id, 
            'pessoaJuridica' => $this->pessoaJuridica->jsonSerialize(),
            'classe' => $this->classe->jsonSerialize()));
    }

    public function unserialize($serialized)
    {
        $obj = \json_decode($serialized);
        $this->id = $obj->id;
        $this->pessoaJuridica = new PessoaJuridica();
        $this->pessoaJuridica->setId($obj->pessoaJuridica->id);
        $this->pessoaJuridica->setNome($obj->pessoaJuridica->nome);
        $this->pessoaJuridica->setEmail($obj->pessoaJuridica->email);
        $this->classe = new ClasseEntidade();
        $this->classe->setId($obj->classe->id);
        $this->classe->setNome($obj->classe->nome);
    }
    
}
