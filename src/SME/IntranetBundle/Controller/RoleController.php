<?php

namespace SME\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\IntranetBundle\Entity\Role;
use Doctrine\Common\Collections\ArrayCollection;

class RoleController extends Controller {
    
    public function formPesquisaAction() {
        return $this->render('IntranetBundle:Role:formPesquisa.html.twig');
    }
    
    public function pesquisarAction() {
        $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
        $query = $qb->select('r')->from('IntranetBundle:Role','r')->where('r.role IS NOT NULL');
        if($this->getRequest()->request->get('nome')) {
            $query = $query->andWhere('r.nomeExibicao LIKE :nome')
                           ->setParameter('nome', '%' . $this->getRequest()->request->get('nome') . '%');
        }
        $roles = $query->orderBy('r.nomeExibicao', 'ASC')->getQuery()->getResult();
        return $this->render('IntranetBundle:Role:listaRoles.html.twig', array('roles' => $roles));
    }
    
    public function listarRolesHerdadasAction(Role $role) {
        return $this->render('IntranetBundle:Role:listaRolesHerdadas.html.twig', array(
            'role' => $role,
            'roles' => $this->getDoctrine()->getRepository('IntranetBundle:Role')->findBy(array(), array('nomeExibicao' => 'ASC'))
        ));
    }
    
    public function adicionarRoleHerdadaAction(Role $role) {
        $roleHerdada = $this->getDoctrine()->getRepository('IntranetBundle:Role')->find($this->getRequest()->request->get('roleHerdada'));
        $role->getRolesHerdadas()->add($roleHerdada);
        $this->getDoctrine()->getManager()->merge($role);
        $this->getDoctrine()->getManager()->flush();
        return $this->forward('IntranetBundle:Role:listarRolesHerdadas', array('role' => $role));
    }
    
    public function removerRoleHerdadaAction(Role $role, Role $roleHerdada) {
        $role->getRolesHerdadas()->removeElement($roleHerdada);
        $this->getDoctrine()->getManager()->merge($role);
        $this->getDoctrine()->getManager()->flush();
        return $this->forward('IntranetBundle:Role:listarRolesHerdadas', array('role' => $role));
    }
    
}

