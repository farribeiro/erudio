<?php

namespace SME\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\CommonsBundle\Entity\Estado;

class CadastroController extends Controller {
   
    public function formCadastroAction() {
        return $this->render('IntranetBundle:Cadastro:formCadastro.html.twig');
    }
    
    public function criarCadastro() {
        
    }
    
    public function consultarCadastroAction() {
        $estado = $this->getDoctrine()->getRepository('CommonsBundle:Estado')->find(Estado::SC);
        return $this->render('IntranetBundle:Cadastro:consultaCadastro.html.twig', array(
            'pessoa' => $this->getUser()->getPessoa(), 
            'cidades' => $this->getDoctrine()->getRepository('CommonsBundle:Cidade')->findBy(
                    array('estado' => $estado), array('nome' => 'ASC')
             )
        ));
    }
    
    public function atualizarCadastroAction() {
        $pessoa = $this->getUser()->getPessoa();
        if (trim($this->getRequest()->request->get('password'))) {
        	$password = $this->get('md5_encoder')->encodePassword(trim($this->getRequest()->request->get('password')), null);
        	$pessoa->getUsuario()->setPassword($password);
        	
        	$moodleUser = $this->getDoctrine()->getManager('moodle')->getRepository('MoodleBundle:MoodleUser')->findOneByUsername($pessoa->getCpfCnpj());
        	if ($moodleUser) {
        		$passwordMoodle = $this->get('md5_encoder')->encodeMoodlePassword(trim($this->getRequest()->request->get('password')));
        		$moodleUser->setPassword($passwordMoodle);
        		$emMoodle = $this->getDoctrine()->getManager('moodle');
        		$emMoodle->flush();
        	}
        }
        $pessoa->setEmail($this->getRequest()->request->get('email'));
        $pessoa->getTelefone()->setNumero($this->getRequest()->request->get('telefone'));
        $pessoa->getCelular()->setNumero($this->getRequest()->request->get('celular'));
        $pessoa->getEndereco()->setLogradouro($this->getRequest()->request->get('logradouro'));
        $pessoa->getEndereco()->setNumero($this->getRequest()->request->get('numero'));
        $pessoa->getEndereco()->setComplemento($this->getRequest()->request->get('complemento'));
        $pessoa->getEndereco()->setBairro($this->getRequest()->request->get('bairro'));
        $pessoa->getEndereco()->setCep($this->getRequest()->request->get('cep'));
        $cidade = $this->getDoctrine()->getRepository('CommonsBundle:Cidade')->find($this->getRequest()->request->get('cidade'));
        $pessoa->getEndereco()->setCidade($cidade);
        try {
            $this->get('cadastro_unico')->retain($pessoa);
            $this->get('session')->getFlashBag()->set('message','Seu cadastro foi atualizado');
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error','Erro: ' . $ex->getMessage());
        }
        return $this->forward('IntranetBundle:Cadastro:consultarCadastro');
    }
    
}
