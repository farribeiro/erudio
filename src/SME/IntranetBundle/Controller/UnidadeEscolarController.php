<?php

namespace SME\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use SME\CommonsBundle\Entity\Entidade;
use SME\IntranetBundle\Entity\PortalUser;
use SME\IntranetBundle\Entity\Role;

class UnidadeEscolarController extends Controller {
    
    public function listarUnidadesEscolaresAction() {
        $unidades = array();
        foreach($this->getUser()->getRolesAtribuidas() as $role) {
            if(\strstr($role->getRole(),'ROLE_UNIDADE_')) {
                $unidades[] = $this->getDoctrine()->getRepository('CommonsBundle:Entidade')->find(\substr($role->getRole(), 13));
            }
        }
        
        foreach ($unidades as $x => $unidade) {
            if ($unidade == null) {
                unset($unidades[$x]);
            }
        }
        
        return $this->render('IntranetBundle:UnidadeEscolar:listaUnidadesEscolares.html.twig', array(
            'unidades' => $unidades
        ));
    }
    
    public function selecionarUnidadeEscolarAction(Entidade $unidade) {
        if($this->get('security.context')->isGranted('ROLE_UNIDADE_' . $unidade->getId()) === false) {
            throw new AccessDeniedException('Você não é administrador nesta unidade.');
        }
        $this->getRequest()->getSession()->set('unidade', $unidade);
        return $this->render('IntranetBundle:Index:unidadeEscolar.html.twig');
    }
    
    public function gerenciarUsuarioAction(PortalUser $usuario) {
        return $this->render('IntranetBundle:UnidadeEscolar:usuario.html.twig', array(
            'usuario' => $usuario,
            'roles' => $this->getDoctrine()->getRepository('IntranetBundle:Role')->findBy(array('role' => 'ROLE_SUPORTE_USER'), array('nomeExibicao' => 'ASC'))
        ));
    }
    
    public function atribuirRoleAction(Request $request, PortalUser $usuario) {
        $role = $this->getDoctrine()->getRepository('IntranetBundle:Role')->find($this->getRequest()->request->get('role'));
        try {
            if(!in_array($role->getRole(), $this->getRolesAcessiveis())) {
                throw new \Exception('Operação não permitida para unidades escolares');
            }
            $usuario->getRolesAtribuidas()->add($role);
            $this->getDoctrine()->getManager()->merge($usuario);
            $this->getDoctrine()->getManager()->flush();
            $request->getSession()->getFlashBag()->add('message', 'Permissão concedida');
        } catch(\Exception $ex) {
            $request->getSession()->getFlashBag()->add('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('intranet_unidade_usuario_gerenciar', array('usuario' => $usuario->getId())));
    }
    
    public function retirarRoleAction(Request $request, PortalUser $usuario, Role $role) {
        try {
            if(!in_array($role->getRole(), $this->getRolesAcessiveis())) {
                throw new \Exception('Operação não permitida para unidades escolares');
            }
            $usuario->getRolesAtribuidas()->removeElement($role);
            $this->getDoctrine()->getManager()->merge($usuario);
            $this->getDoctrine()->getManager()->flush();
        } catch(\Exception $ex) {
            $request->getSession()->getFlashBag()->add('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('intranet_unidade_usuario_gerenciar', array('usuario' => $usuario->getId())));
    }
    
    private function getRolesAcessiveis() {
        return array('ROLE_SUPORTE_USER');
    }
    
}
