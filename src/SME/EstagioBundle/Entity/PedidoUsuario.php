<?php

namespace SME\EstagioBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_estagio_pedido_usuario")
 */

class PedidoUsuario {
    
    /**
        * @ORM\Id
        * @ORM\Column(type="integer")
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;
    
    /** @ORM\Column(nullable=false) */
    private $nome;
    
    /** @ORM\Column(nullable=false) */
    private $cpf;
    
    /** @ORM\Column(nullable=false, name="dataNascimento") */
    private $dataNascimento;
    
    /** @ORM\Column(nullable=false) */
    private $email;
    
    /** @ORM\Column(nullable=false, name="email_supervisor") */
    private $emailSupervisor;
    
    /** @ORM\Column(type="integer", nullable=false) */
    private $instituicao;
    
    /** @ORM\Column(nullable=false) */
    private $curso;
    
    /** @ORM\Column(nullable=false) */
    private $status = 1;
    
    /** @ORM\Column(nullable=false, type="boolean") */
    private $ativo = true;
    
    public function getId() {
        return $this->id;
    }
    
    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    public function getCpf() {
        return $this->cpf;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }
    
    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function setDataNascimento($dataNascimento) {
        $this->dataNascimento = $dataNascimento;
    }
    
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function getEmailSupervisor() {
        return $this->emailSupervisor;
    }

    public function setEmailSupervisor($emailSupervisor) {
        $this->emailSupervisor = $emailSupervisor;
    }

    public function getInstituicao() {
        return $this->instituicao;
    }

    public function setInstituicao($instituicao) {
        $this->instituicao = $instituicao;
    }
    
    public function getCurso() {
        return $this->curso;
    }

    public function setCurso($curso) {
        $this->curso = $curso;
    }
        
    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
    
    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
}