<?php

namespace SME\ErudioBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_pessoa")
 */
class ErudioPessoa {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column(name="nome", nullable=false) */
    private $nome;
    
    /** @ORM\Column(name="cpf_cnpj", nullable=false) */
    private $cpfCnpj;
    
    /** @ORM\Column(name="data_nascimento", type="date") */
    private $dataNascimento;
    
    /** @ORM\Column(name="usuario_id") */
    private $usuarioId;
    
    public function getId() {
        return $this->id;
    }
    
    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
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

    public function setDataNascimento($datanasc) {
        $this->dataNascimento = $datanasc;
    }

    public function getUsuarioId() {
        return $this->usuarioId;
    }

    public function setUsuarioId($usuarioId) {
        $this->usuarioId = $usuarioId;
    }
}
