<?php

namespace SME\IntranetBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use SME\IntranetBundle\Entity\Role;

/**
 * @ORM\Entity
 * @ORM\Table(name="sme_intranet_usuario")
 */
class PortalUser implements UserInterface, \Serializable {
    
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
    
    /** @ORM\Column(name="senha_expirada") */
    private $passwordExpirado = false;
    
    /** @ORM\OneToOne(targetEntity="SME\CommonsBundle\Entity\Pessoa", mappedBy="usuario") */
    private $pessoa;
    
    /**
        * @ORM\ManyToMany(targetEntity="Role", inversedBy="usuarios")
        * @ORM\JoinTable(name="sme_intranet_enrol",
        *      joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")},
        *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
        *   )
        */
    private $rolesAtribuidas;
       
    public function __construct() {
        $this->rolesAtribuidas = new ArrayCollection();
    }
    
    public function eraseCredentials() {
    }

    public function getSalt() {
        $parts = explode(':', $this->password);
        return isset($parts[1]) ? $parts[1] : null;
    }

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
    
    public function getPasswordExpirado() {
        return $this->passwordExpirado;
    }

    public function setPasswordExpirado($passwordExpirado) {
        $this->passwordExpirado = $passwordExpirado;
    }
   
    public function getPessoa() {
        return $this->pessoa;
    }
    
    public function getRoles() {
        return $this->rolesAtribuidas->toArray();
    }

    public function getRolesAtribuidas() {
        return $this->rolesAtribuidas;
    }

    public function setRolesAtribuidas($rolesAtribuidas) {
        $this->rolesAtribuidas = $rolesAtribuidas;
    }
    
    public function equals(UserInterface $user) {
        return $user instanceof PortalUser && $this->username === $user->getUsername();
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
