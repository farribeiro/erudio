<?php

namespace SME\DGPFormacaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\DGPFormacaoBundle\Form\Encontro\EncontroType;
use SME\DGPFormacaoBundle\Entity\Formacao;
use SME\DGPFormacaoBundle\Entity\Encontro;

class EncontroController extends Controller {
    
    public function listarAction(Formacao $formacao) {
        $errors = '';
        $encontro = new Encontro();
        $form = $this->createForm(new EncontroType(), $encontro);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            try {
                $encontro->setAtivo(true);
                $encontro->setFormacao($formacao);
                $this->getDoctrine()->getManager()->persist($encontro);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->set('message', 'Encontro cadastrado com sucesso');
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->set('error', $e->getMessage());
            }
        } else {
            $errors = $this->get('form_helper')->getFormErrors($form);
        }
        return $this->render('DGPFormacaoBundle:Encontro:encontros.html.twig', array(
            'formacao' => $formacao,
            'form' => $form->createView(), 
            'errors' => $errors
        ));
    }

    
    public function atualizarAction(Encontro $encontro) {
        
    }
    
    public function excluirAction(Formacao $formacao, Encontro $encontro) {
        try {
            $encontro->setAtivo(false);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message', 'Encontro excluído com sucesso');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('dgp_formacao_listarEncontros', array('formacao' => $formacao->getId())));
    }
    
}
