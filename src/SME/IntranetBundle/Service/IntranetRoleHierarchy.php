<?php

namespace SME\IntranetBundle\Service;

use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Doctrine\Bundle\DoctrineBundle\Registry;

class IntranetRoleHierarchy extends RoleHierarchy
{
    
    private $orm;

    public function __construct(array $hierarchy, Registry $orm)
    {
        $this->orm = $orm;
        parent::__construct($this->buildRolesTree());
    }

    private function buildRolesTree() {
        $hierarchy = array();
        $roles = $this->orm->getManager()->createQuery('select r, h from IntranetBundle:Role r JOIN r.rolesHerdadas h')->execute();
        foreach ($roles as $role) {
            if (count($role->getRolesHerdadas() ) > 0) {
                $rolesHerdadas = array();
                foreach ($role->getRolesHerdadas() as $r) {
                    $rolesHerdadas[] = $r->getRole();
                }
                $hierarchy[$role->getRole()] = $rolesHerdadas;
            }
        }
        return $hierarchy;
    }
}

