<?php

namespace SME\DGPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\CommonsBundle\Entity\PessoaFisica;
use SME\CommonsBundle\Entity\Telefone;
use SME\DGPBundle\Forms\Servidor\TelefoneForm;

class TelefoneController extends Controller {
    
    public function cadastrarAction(PessoaFisica $pessoa) {
    	$telefone = new Telefone();
        $telefone->setPessoa($pessoa);
    	$form = $this->createForm(new TelefoneForm(), $telefone);
    	$form->handleRequest($this->getRequest());
    	if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($telefone);
                $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Telefone adicionado com sucesso');
            } catch(\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
    	}
    	return $this->render('DGPBundle:Telefone:formCadastro.html.twig', array(
            'form' => $form->createView(), 
            'pessoa' => $pessoa,
            'telefones' => $pessoa->getTelefones()
        ));
    }
    
    public function excluirAction(PessoaFisica $pessoa, Telefone $telefone) {
    	try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($telefone);
            $em->flush();
            $this->get('session')->getFlashBag()->set('message', 'Telefone excluído com sucesso');
    	} catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
    	}
        return $this->redirect($this->generateUrl('dgp_pessoa_telefone_cadastrar', array('pessoa' => $pessoa->getId())));
    }
    
}
