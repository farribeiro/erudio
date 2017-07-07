<?php

namespace SME\CommonsBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use SME\CommonsBundle\Entity\Endereco;
use SME\CommonsBundle\Entity\Telefone;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_pessoa")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="tipo_pessoa", type="string")
 * @ORM\DiscriminatorMap({"Pessoa" = "Pessoa", "PessoaFisica" = "PessoaFisica", "PessoaJuridica" = "PessoaJuridica", "UnidadeEnsino" = "PessoaJuridica", "Instituicao" = "PessoaJuridica"})
 */
class Pessoa implements \Serializable, \JsonSerializable {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /**
    * @ORM\Column(nullable=false)
    * @Assert\NotBlank(groups={"maior_idade", "menor_idade"})
    */
    private $nome;
    
    /** @ORM\Column() */
    private $apelido;
    
    /** 
    * @ORM\Column(name="cpf_cnpj", length=14)
    * @Assert\NotBlank(groups={"maior_idade"})
    * @Assert\Length(min=11, max=11, groups={"maior_idade"})
    */
    private $cpfCnpj;
    
    /** @ORM\Column(name="data_nascimento", type="date") */
    private $dataNascimento;
    
    /** @ORM\Column(length=200) */
    private $email;
    
    /** 
        * @ORM\OneToOne(targetEntity="SME\IntranetBundle\Entity\PortalUser", inversedBy="pessoa", cascade={"all"})
        * @ORM\JoinColumn(name="usuario_id")
        */
    private $usuario;
    
    /** @ORM\OneToOne(targetEntity="Endereco", cascade={"all"}) */
    private $endereco;
    
    /** @ORM\OneToMany(targetEntity="Telefone", mappedBy="pessoa", cascade={"all"}) */
    private $telefones;
    
    /** @ORM\Column() */
    private $ativo;

    public function __construct() {
        $this->ativo = true;
        $this->telefones = new ArrayCollection();
        $this->endereco = new Endereco();
    }    
    
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

    public function getApelido() {
        return $this->apelido;
    }

    public function setApelido($apelido) {
        $this->apelido = $apelido;
    }

    public function getCpfCnpj() {
        return $this->cpfCnpj;
    }

    public function setCpfCnpj($cpfCnpj) {
        $this->cpfCnpj = $cpfCnpj;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function setDataNascimento(\DateTime $dataNascimento = null) {
        $this->dataNascimento = $dataNascimento;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }
    
    public function getEndereco() {
        return $this->endereco;
    }

    public function setEndereco(Endereco $endereco) {
        $this->endereco = $endereco;
    }
    
    public function getTelefones() {
        return $this->telefones;
    }

    public function setTelefones($telefones) {
        $this->telefones = $telefones;
    }
    
    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    
    public function getTelefone() {
        foreach($this->telefones as $tel) {
            if($tel->getDescricao() == Telefone::PRINCIPAL) {
                return $tel;
            }
        }
        // O trecho de código abaixo serve para evitar problemas com referência nula
        $telefone = new Telefone();
        $telefone->setPessoa($this);
        $telefone->setDescricao(Telefone::PRINCIPAL);
        $this->telefones->add($telefone);
        return $telefone;
    }
    
    public function getCelular() {
        foreach($this->telefones as $tel) {
            if($tel->getDescricao() == Telefone::CELULAR) {
                return $tel;
            }
        }
        // O trecho de código abaixo serve para evitar problemas com referência nula
        $celular = new Telefone();
        $celular->setPessoa($this);
        $celular->setDescricao(Telefone::CELULAR);
        $this->telefones->add($celular);
        return $celular;
    }
    
    public function serialize()
    {
        return \json_encode(array($this->id, $this->nome, $this->cpfCnpj, $this->dataNascimento, $this->email));
    }

    public function unserialize($serialized)
    {
        list($this->id, $this->nome, $this->cpfCnpj, $this->dataNascimento, $this->email) = \json_decode($serialized);
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'nome' => $this->nome,
            'cpfCnpj' => $this->cpfCnpj,
            'dataNascimento' => $this->dataNascimento,
            'email' => $this->email
        );
    }
    
}
