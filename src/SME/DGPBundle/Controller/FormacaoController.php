<?php

namespace SME\DGPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\CommonsBundle\Entity\PessoaFisica;
use SME\CommonsBundle\Entity\Formacao;
use SME\DGPBundle\Forms\Servidor\FormacaoForm;

class FormacaoController extends Controller {
    
    public function listarAction(PessoaFisica $pessoa) {
    	$formacoes = $this->getDoctrine()->getRepository('CommonsBundle:Formacao')->findBy(array('pessoaFisica' => $pessoa));
    	return $this->render('DGPBundle:Formacao:formacoes.html.twig', array('pessoa' => $pessoa, 'formacoes' => $formacoes));
    }
    
    public function cadastrarAction(PessoaFisica $pessoa) {
    	$formacao = new Formacao();
    	$formacao->setPessoaFisica($pessoa);
    	$errors = '';
    	$form = $this->createForm(new FormacaoForm(), $formacao);
    	$form->handleRequest($this->getRequest());
    	 
    	if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($formacao);
                $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Título adicionado com sucesso');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
            return $this->redirect($this->generateUrl('dgp_pessoa_formacao_listar', array('pessoa' => $pessoa->getId())));
    	} else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	} 
    	return $this->render('DGPBundle:Formacao:formCadastro.html.twig', array(
            'pessoa' => $pessoa, 
            'form' => $form->createView(), 
            'erros' => $errors
        ));
    }
    
    public function alterarAction(PessoaFisica $pessoa, Formacao $formacao) {
    	$errors = '';
    	$form = $this->createForm(new FormacaoForm(), $formacao);
    	$form->handleRequest($this->getRequest());
    
    	if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->merge($formacao);
                $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Título alterado com sucesso');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
            return $this->redirect($this->generateUrl('dgp_pessoa_formacao_listar', array('pessoa' => $pessoa->getId())));
    	} else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	}
    	return $this->render('DGPBundle:Formacao:formCadastro.html.twig', array(
            'pessoa' => $pessoa,
            'formacao' => $formacao,
            'form' => $form->createView(), 
            'erros' => $errors
        ));
    }
    
    public function excluirAction(PessoaFisica $pessoa, Formacao $formacao) { 
    	try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($formacao);
            $em->flush();
            $this->get('session')->getFlashBag()->set('message', 'Título excluído com sucesso');
    	} catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
    	}
        return $this->redirect($this->generateUrl('dgp_pessoa_formacao_listar', array('pessoa' => $pessoa->getId())));
    }
    
}
