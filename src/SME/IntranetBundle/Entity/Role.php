<?php

namespace SME\IntranetBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="sme_intranet_role")
 */
class Role implements RoleInterface {
    
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    
    /**
    * @ORM\Column(name="nome_exibicao", nullable=false)
    */
    private $nomeExibicao;
    
    /**
    * @ORM\Column(name="nome_role", nullable=false)
    */
    private $role;
    
    /**
    * @ORM\Column()
    */
    private $descricao;
    
    /**
        * @ORM\ManyToMany(targetEntity="Role", inversedBy="rolesFilhas")
        * @ORM\JoinTable(name="sme_intranet_role_hierarchy",
        *      joinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")},
        *      inverseJoinColumns={@ORM\JoinColumn(name="parent_role_id", referencedColumnName="id")}
        *   )
        */
    private $rolesHerdadas;
    
    /**
        * @ORM\ManyToMany(targetEntity="Role", mappedBy="rolesHerdadas")
        */
    private $rolesFilhas;
    
    /**
     * @ORM\ManyToMany(targetEntity="PortalUser", mappedBy="rolesAtribuidas", fetch="LAZY")
     */
    private $usuarios;
    
    public function __construct() {
        $this->rolesHerdadas = new ArrayCollection();
        $this->rolesFilhas = new ArrayCollection();
    }
    
    public function getId() {
        return $this->id;
    }

    public function getNomeExibicao() {
        return $this->nomeExibicao;
    }

    public function setNomeExibicao($nomeExibicao) {
        $this->nomeExibicao = $nomeExibicao;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }
    
    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }
    
    public function getUsuarios() {
        foreach($this->rolesFilhas as $roleFilha) {
            foreach($roleFilha->getUsuarios() as $usuario) {
                if(!$this->usuarios->contains($usuario)) {
                    $this->usuarios->add($usuario);
                }
            }
        }
        return $this->usuarios;
    }
    
    public function getRolesHerdadas() {
        return $this->rolesHerdadas;
    }
      
    public function setRolesHerdadas(ArrayCollection $rolesHerdadas) {
        $this->rolesHerdadas = $rolesHerdadas;
    }
    
    public function getRolesFilhas() {
        return $this->rolesFilhas;
    }

    public function setRolesFilhas(ArrayCollection $rolesFilhas) {
        $this->rolesFilhas = $rolesFilhas;
    }
    
    public function equals(Role $role) {
        return $this->getRole() === $role->getRole();
    }
    
}
