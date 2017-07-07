<?php

namespace SME\DGPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SME\CommonsBundle\Entity\PessoaFisica;

class UsuarioController extends Controller {
    
    public function alterarSenhaAction(PessoaFisica $pessoa) {
        $request = $this->getRequest();
    	if (!is_null($request->get('password'))) {
            $password = $this->get('md5_encoder')->encodePassword($request->get('password'), null);
            $pessoa->getUsuario()->setPassword($password);
            try {
                $this->get('cadastro_unico')->retain($pessoa);
                
                $erudioUser = $this->getDoctrine()->getManager('erudio')->getRepository('ErudioBundle:ErudioUser')->findOneByUsername($pessoa->getCpfCnpj());
                if($erudioUser) {
                    $erudioUser->setPassword($password);
                    $emErudio = $this->getDoctrine()->getManager('erudio');
                    $emErudio->merge($erudioUser);
                    $emErudio->flush();
                }
                
                $moodleUser = $this->getDoctrine()->getManager('moodle')->getRepository('MoodleBundle:MoodleUser')->findOneByUsername($pessoa->getCpfCnpj());
                if($moodleUser) {
                    $passwordMoodle = $this->get('md5_encoder')->encodeMoodlePassword($request->get('password'));
                    $moodleUser->setPassword($passwordMoodle);
                    $emMoodle = $this->getDoctrine()->getManager('moodle');
                    $emMoodle->merge($moodleUser);
                    $emMoodle->flush();
                }
                
                $this->get('session')->getFlashBag()->set('message', 'Senha alterada com sucesso');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
    	}
    	return $this->render('DGPBundle:Usuario:formAlteracaoSenha.html.twig', array('pessoa' => $pessoa));
    }
    
}
