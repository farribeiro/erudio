<?php

namespace SME\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use SME\IntranetBundle\Entity\PortalUser;
use SME\MoodleBundle\Entity\MoodleUser;
use Symfony\Component\HttpFoundation\Response;
use SME\IntranetBundle\Entity\PasswordRecovery;
use Symfony\Component\HttpFoundation\Request;

class AcessoController extends Controller
{
    
    public function indexAction() {
        return $this->render('IntranetBundle:Acesso:index.html.twig');
    }
    
    public function loginAction(Request $request) {
        $session = $request->getSession();
        $email = '';
        
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        if($error instanceof BadCredentialsException) {
            $error = new BadCredentialsException($this->get('translator')->trans($error->getMessage()));
            $lastUsername = $session->get(SecurityContext::LAST_USERNAME);
            if(!empty($lastUsername)){
                $pessoa = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findOneByCpfCnpj($lastUsername);
                $usuario = $pessoa->getUsuario();
                $email = $pessoa->getEmail();
                $error = $usuario->getPasswordExpirado() ? null : $error;
            }
        }
        
        return $this->render('IntranetBundle:Acesso:login.html.twig', array(
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error' => $error,
                'ano' => date('Y'),
                'senhaExpirada' => isset($usuario) ? $usuario->getPasswordExpirado() : false,
                'username' => isset($usuario) ? $usuario->getUsername() : false,
                'nomeUsuario' => isset($pessoa) ? $pessoa->getNome() : false,
                'email' => $email
            )
        );
    }
    
    public function sendRecoveryEmailAction(Request $request) {
    	$cpf = $request->request->get('username');
    	$email = $request->request->get('email');
    	    	
    	$pessoa = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findOneByCpfCnpj($cpf);
    	
    	if (!$pessoa->getUsuario()) {
            $request->getSession()->getFlashBag()->add('error','Você ainda não possui um usuário, utilize a opção "Novo por aqui?" para criá-lo');
            return $this->redirect($this->generateUrl('login'));
    	}
    	 
    	if ($email != $pessoa->getEmail()) {
            $request->getSession()->getFlashBag()->add('error','O e-mail informado não corresponde ao usuário cadastrado, por favor, verifique.');
            return $this->redirect($this->generateUrl('login'));
    	}
    	
    	$tokens = $this->getDoctrine()->getRepository('IntranetBundle:PasswordRecovery')->findByUserId($pessoa->getUsuario()->getId());
    	
    	if (!empty($tokens)) {
            foreach ($tokens as $x => $token)
            {
                $qd = $this->getDoctrine()->getManager();
                $qd->remove($token);
                $qd->flush();
            }
    	}
    	
    	$token = base64_encode($email . date('Y-m-d H:i:s'));
    	$expire = new \DateTime();
    	$expire->modify('+1 day');
    	$formatted_expire = date('d/m/Y - H:i', strtotime('+1 day'));
    	
    	$password_recovery = new PasswordRecovery();
    	$password_recovery->setUserId($pessoa->getUsuario()->getId());
    	$password_recovery->setToken($token);
    	$password_recovery->setData($expire);
    	
    	$em = $this->getDoctrine()->getManager();
    	$em->persist($password_recovery);
    	$em->flush();
    	    	
    	if (!$password_recovery->getId())
    	{
            $this->getRequest()->getSession()->getFlashBag()->add('error','Houve um erro ao requisitar nova senha, aguarde e tente novamente em alguns instantes, persistindo o erro, contate o administrador do sistema.');
            return $this->redirect($this->generateUrl('login'));
    	}
    	
    	$message = \Swift_Message::newInstance()
            ->setContentType('text/html')
            ->setSubject('Recuperação de Senha')
            ->setFrom('naoresponda@itajai.sc.gov.br')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'IntranetBundle:Acesso:email.html.twig',
                    array('token' => $token, 'expire' => $formatted_expire, 'name' => $pessoa->getNome() )
                )
            )
    	;
    	
    	$this->get('mailer')->send($message);
    	$request->getSession()->getFlashBag()->add('message','Um e-mail com instruções para recuperação da senha foi enviado para o endereço fornecido, por favor, verifique.');
    	return $this->redirect($this->generateUrl('login'));
    }
     
    public function setNewPasswordAction()
    {
    	$token = $this->getRequest()->query->get('token');
    	
    	$em = $this->getDoctrine()->getManager();
    	$query = $em->createQuery('SELECT rec FROM IntranetBundle:PasswordRecovery rec WHERE rec.token = :token AND rec.data > CURRENT_TIMESTAMP()')->setParameter('token', $token);
    	
    	try {
    		
    		$recovery = $query->getSingleResult();
    		return $this->render('IntranetBundle:Acesso:new_password.html.twig', array('token' => $recovery));
    		
    	} catch (\Doctrine\Orm\NoResultException $e) {
    		
    		$this->getRequest()->getSession()->getFlashBag()->add('error','Este link para alteração de senha não existe mais, para gerar um novo, clique em "Esqueci minha senha".');
    		return $this->redirect($this->generateUrl('login'));
    		
    	}
    }
    
    public function saveNewPasswordAction(Request $request)
    {
    	$token = $this->getDoctrine()->getRepository('IntranetBundle:PasswordRecovery')->findOneById($request->get('token'));
    	$data_atual = new \DateTime();
    	
    	if ($data_atual->format('Y-m-d H:i:s') < $token->getData())
    	{    		
    		$user = $this->getDoctrine()->getRepository('IntranetBundle:PortalUser')->findOneById($token->getUserId());
    		$password = $this->get('md5_encoder')->encodePassword($this->getRequest()->request->get('password'), null);
    		$user->setPassword($password);
                $user->setPasswordExpirado(false);
                
    		try {
    			$em = $this->getDoctrine()->getManager();
    			$em->merge($user);
    			$em->flush();
    			
    			$qd = $this->getDoctrine()->getManager();
    			$qd->remove($token);
    			$qd->flush();
                        
                        $erudioUser = $this->getDoctrine()->getManager('erudio')->getRepository('ErudioBundle:ErudioUser')->findOneByUsername($user->getUsername());
                        if($erudioUser) {
                            $erudioUser->setPassword($password);
                            $emErudio = $this->getDoctrine()->getManager('erudio');
                            $emErudio->merge($erudioUser);
                            $emErudio->flush();
                        }
    			
    			$this->getRequest()->getSession()->getFlashBag()->add('success','Senha alterada com sucesso.');
    			return $this->redirect($this->generateUrl('login'));
    			
    		} catch(\Exception $ex) {
    			//$this->get('session')->getFlashBag()->set('error','Erro: ' . $ex->getMessage());
    			$this->get('session')->getFlashBag()->set('error','Ocorreu um erro na alteração de senha, por favor, tente novamente em alguns instantes.');
    		}
    	}
    	
    	$this->getRequest()->getSession()->getFlashBag()->add('error','Este link para alteração de senha não existe mais, para gerar um novo, clique em "Esqueci minha senha".');
    	return $this->redirect($this->generateUrl('login'));
    }
    
    public function firstAccessAction()
    {
    	return $this->render('IntranetBundle:Acesso:firstAccess.html.twig');
    }
    
    public function getEmailAction(Request $request)
    {
    	$pessoa = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findOneByCpfCnpj($request->get('cpf'));

    	if (empty($pessoa))
    	{
    		return new Response('no_person');
    	}
    	
    	if (!$pessoa->getEmail())
    	{
    		return new Response('no_email');
    	}
    	
    	return new Response($pessoa->getEmail());
    }
    
    public function createUserAction(Request $request) {
    	$portalUser = new PortalUser();
    	$moodleUser = new MoodleUser();
  
    	$pessoa = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findOneByCpfCnpj($request->get('newUsername'));
    	
    	if (!$pessoa) {
    		return new Response('no_person');
    	}
    	
    	if ($pessoa->getUsuario()) {
            if ($pessoa->getUsuario()->getUsername() != $pessoa->getCpfCnpj()) {
                    $user = $pessoa->getUsuario();
                    $user->setUsername($pessoa->getCpfCnpj());
                    $this->get('cadastro_unico')->retain($pessoa);

                    return new Response('success_username');
            }

            return new Response('exist');
    	}
    	
    	$portalUser->setUsername($request->get('newUsername'));
    	$password = $this->get('md5_encoder')->encodePassword($request->get('newPassword'), null);
    	$portalUser->setPassword($password);
    	$portalUser->setNomeExibicao($pessoa->getNome());
    	
    	$name = explode(' ',$pessoa->getNome());
    	$firstname = $name[0];
    	unset($name[0]);
    	$lastname = '';
    	
    	foreach ($name as $namePart) {
    		$lastname .= $namePart . ' ';
    	}
    	
    	$lastname = trim($lastname);
    	
    	$moodleUser->setUsername($request->get('newUsername'));
    	$moodleUser->setAuth('manual');
    	$moodleUser->setFirstname($firstname);
    	$moodleUser->setLastname($lastname);
    	$moodleUser->setEmail($pessoa->getEmail());
    	$moodleUser->setPassword($this->get('md5_encoder')->encodeMoodlePassword($request->get('newPassword')));
    	
  	try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($portalUser);
            $pessoa->setUsuario($portalUser);
            $em->flush();

            $emMoodle = $this->getDoctrine()->getManager('moodle');
            $emMoodle->persist($moodleUser);
            $emMoodle->flush();
            return new Response('success');
    	} catch(\Exception $ex) {
            return new Response($ex->getMessage());
    	}
    }
    
    public function solicitarAtendimentoAction() {
        return $this->render('IntranetBundle:Acesso:atendimentoOnline.html.twig');
    }
    
}
