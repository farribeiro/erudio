<?php

namespace SME\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection; 
use SME\IntranetBundle\Entity\PortalUser;
use SME\IntranetBundle\Entity\Role;
use SME\IntranetBundle\Entity\Notification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsuarioController extends Controller {
    
    public function formPesquisaUsuarioAction() {
        return $this->render('IntranetBundle:Usuario:formPesquisaUsuario.html.twig', array(
            'roles' => $this->getDoctrine()->getRepository('IntranetBundle:Role')->findBy(array(), array('nomeExibicao' => 'ASC'))
        ));
    }
    
    public function pesquisarUsuarioAction() {
        $nome = $this->getRequest()->request->get('nome');
        $username = $this->getRequest()->request->get('username');
        $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
        if($this->getRequest()->request->get('permissao')) {
            $permissaoId = $this->getRequest()->request->get('permissao');
            $role = $this->getDoctrine()->getRepository('IntranetBundle:Role')->find($permissaoId);
            $usuarios = $role->getUsuarios();
        } else {
            $query = $qb->select('user')->from('IntranetBundle:PortalUser','user')->where('user.username IS NOT NULL');
            if($nome) {
                $query = $query->andWhere('user.nomeExibicao LIKE :nome')->setParameter('nome', '%' . $nome . '%');
            }
            if($username) {
                $query = $query->andWhere('user.username LIKE :username')->setParameter('username', '%' . $username . '%');
            }
            $usuarios = $query->orderBy('user.nomeExibicao', 'ASC')->getQuery()->getResult();
        }
        return $this->render('IntranetBundle:Usuario:listaUsuarios.html.twig', array(
            'usuarios' => $usuarios
        ));
    }
    
    public function listarRolesAction(PortalUser $usuario) {
        return $this->render('IntranetBundle:Usuario:listaRoles.html.twig', array(
            'usuario' => $usuario,
            'roles' => $this->getDoctrine()->getRepository('IntranetBundle:Role')->findBy(array(), array('nomeExibicao' => 'ASC'))
        ));
    }
    
    public function atribuirRoleAction(PortalUser $usuario) {
        $role = $this->getDoctrine()->getRepository('IntranetBundle:Role')->find($this->getRequest()->request->get('role'));
        $usuario->getRolesAtribuidas()->add($role);
        $this->getDoctrine()->getManager()->merge($usuario);
        $this->getDoctrine()->getManager()->flush();
        return $this->forward('IntranetBundle:Usuario:listarRoles', array('usuario' => $usuario));
    }
    
    public function retirarRoleAction(PortalUser $usuario, Role $role) {
        $usuario->getRolesAtribuidas()->removeElement($role);
        $this->getDoctrine()->getManager()->merge($usuario);
        $this->getDoctrine()->getManager()->flush();
        return $this->forward('IntranetBundle:Usuario:listarRoles', array('usuario' => $usuario));
    }
    
    public function buscarNotificacoesAction(Request $request) {
    	$user = $this->getDoctrine()->getRepository('IntranetBundle:PortalUser')->findOneById($request->get('id'));
    	$error = false;
    	$showNext = true;
    	$page = 1;
    	
    	if (!$user) {
    		$error = true;
    	}
    	
    	if ($request->get('page')) {
    		$page = $request->get('page');
    	}
    	//$this->get('notification')->sendNotification('ha',$user);
    	
    	$total = $this->get('notification')->getTotalNotificationsNotRead($user);
    	
    	if (!$total) {
    		$total = '0';
    	}
    	
    	$pages = ceil($total/Notification::LIMIT_PAGES);
    	
    	if ($page >= $pages) {
    		$showNext = false;
    	}
    	
    	$notifications = $this->get('notification')->getNotificationsByPage($user, $page);
    	
    	if (!$notifications) {
    		$error = true;
    	}
    	
    	return $this->render('IntranetBundle:Usuario:listaNotificacoes.html.twig', array('notificacoes'=>$notifications, 'error' => $error, 'pagina' => $page, 'mostraProximo' => $showNext));
    }
    
    public function deletarNotificacaoAction(Request $request) {
    	$result = $this->get('notification')->deleteNotification($request->get('id'));
    	 
    	return $this->redirect($this->generateUrl('intranet_buscar_notificacoes',array('id'=>$request->get('user_id'))));
    }
    
    public function deletarNotificacoesAction(Request $request) {
    	$result = $this->get('notification')->deleteNotifications(json_decode($request->get('ids')));
    
    	return $this->redirect($this->generateUrl('intranet_buscar_notificacoes',array('id'=>$request->get('user_id'))));
    }
    
    public function totalNotificacoesAction(Request $request) {
    	$user = $this->getDoctrine()->getRepository('IntranetBundle:PortalUser')->findOneById($request->get('user_id'));
    	
    	if (!$user) {
    		return false;
    	}
    	
    	$result = $this->get('notification')->getTotalNotificationsNotRead($user);
    	
    	if (!$result) {
    		return new Response('');
    	}
    	return new Response($result);
    }
    
    public function buscarFotoAction(PortalUser $user) {
        //$moodleUser = $this->get('doctrine')->getRepository('MoodleBundle:MoodleUser', 'moodle')->findOneBy(array('username' => $user->getUsername()));
        //if (!$moodleUser) {
         //   return new Response('error');
        //}
        //$file = $this->get('doctrine')->getRepository('MoodleBundle:FileUser', 'moodle')->findOneBy(array('userid' => $moodleUser->getId()));
        //$photoUrl = 'http://voldemort/moodle4/pluginfile.php/' . $file->getContextId() . '/user/icon/aardvark_makeover/f1';
        //return new Response($photoUrl);
		return new Response('error');
    }
}
