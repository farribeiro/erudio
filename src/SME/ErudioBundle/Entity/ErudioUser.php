<?php

namespace SME\ErudioBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="edu_acesso_usuario")
 */
class ErudioUser {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column(name="nome_usuario", unique=true, nullable=false) */
    private $username;
    
    /** @ORM\Column(name="senha") */
    private $password;
    
    /** @ORM\Column(name="nome_exibicao") */
    private $nomeExibicao;
    
    public function getId() {
        return $this->id;
    }
    
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        $parts = explode(':', $this->password);
        return $parts[0];
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getNomeExibicao() {
        return $this->nomeExibicao;
    }

    public function setNomeExibicao($nomeExibicao) {
        $this->nomeExibicao = $nomeExibicao;
    }

    public function serialize()
    {
        return \json_encode(array($this->id, $this->username, $this->password, $this->rolesAtribuidas));
    }

    public function unserialize($serialized)
    {
        list($this->id, $this->username, $this->password, $this->rolesAtribuidas) = \json_decode($serialized);
    }

}
