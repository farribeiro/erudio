<?php

namespace SME\DGPPromocaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\DGPPromocaoBundle\Form\FormacaoExternaType;
use SME\DGPPromocaoBundle\Entity\PromocaoHorizontal;
use SME\DGPPromocaoBundle\Entity\FormacaoExterna;
use Symfony\Component\HttpFoundation\JsonResponse;

class FormacaoExternaController extends Controller {
    
    public function cadastrarAction(PromocaoHorizontal $promocao) {
        $formacao = new FormacaoExterna();
    	$formacao->setPromocaoHorizontal($promocao);
    	$errors = '';
    	$form = $this->createForm(new FormacaoExternaType(), $formacao);
    	$form->handleRequest($this->getRequest());
    	if ($form->isValid()) {
            try {
                if($promocao->getEncerrado()) {
                    $this->get('session')->getFlashBag()->set('message', 'Aviso: Você está alterando uma promoção já deferida/indeferida');
                }
                $this->getDoctrine()->getManager()->persist($formacao);
                $this->getDoctrine()->getManager()->flush();
                $result = array('result' => 'success');
                $this->get('session')->getFlashBag()->set('message', 'Formação externa adicionada');
            } catch (\Exception $ex) {
                $result = array('result' => 'error', 'message' => $ex->getMessage());
            }
            return new JsonResponse($result);
    	} else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	} 
    	return $this->render('DGPPromocaoBundle:FormacaoExterna:formCadastro.html.twig', array(
            'promocao' => $promocao, 
            'form' => $form->createView(), 
            'erros' => $errors
        ));
    }
    
    public function excluirAction(PromocaoHorizontal $promocao, FormacaoExterna $formacao) {
        try {
            if($promocao->getEncerrado()) {
                $this->get('session')->getFlashBag()->set('message', 'Aviso: Você está alterando uma promoção já deferida/indeferida');
            }
            $this->getDoctrine()->getManager()->remove($formacao);
            $this->getDoctrine()->getManager()->flush();
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('dgp_promocaoHorizontal_alterar', array('vinculo' => $promocao->getVinculo()->getId())));
    }
    
}
