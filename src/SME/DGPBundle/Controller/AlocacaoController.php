<?php

namespace SME\DGPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\DGPBundle\Forms\Servidor\AlocacaoForm;
use Symfony\Component\HttpFoundation\Response;
use \SME\DGPBundle\Entity\Vinculo;
use SME\DGPBundle\Entity\Alocacao; 

class AlocacaoController extends Controller {
    
    public function listarPorVinculoAction(Vinculo $vinculo) {
    	$totalHorasVinculo = $vinculo->getCargaHoraria();
    	$totalHorasAlocacoes = 0;
        $alocacoes = $vinculo->getAlocacoes()->filter( function($a) { return $a->getAtivo(); } );
        foreach ($alocacoes as $alocacao) {
            $totalHorasAlocacoes += $alocacao->getCargaHoraria();
    	}
    	if ($totalHorasAlocacoes >= 40) {
            $totalHorasAlocacoes = 0;
    	}
    	return $this->render('DGPBundle:Alocacao:alocacoesPorVinculo.html.twig', array(
            'vinculo' => $vinculo, 
            'alocacoes'=> $alocacoes, 
            'error' => '', 
            'showCad' => $totalHorasAlocacoes)
        );
    }
    
    public function cadastrarAction(Vinculo $vinculo) {
        $request = $this->getRequest();
    	$errors = '';
    	$alocacao = new Alocacao();
    	$alocacao->setVinculoServidor($vinculo);
    	$form = $this->createForm(new AlocacaoForm(), $alocacao);
    	$form->handleRequest($request);
    	if ($form->isValid()) {    	
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($alocacao);
                $em->flush();
                return new Response('success');
            } catch (\Exception $ex) {
                return new Response('error');
            }
    	} else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	}
    	return $this->render('DGPBundle:Alocacao:formCadastro.html.twig', array(
            'form' => $form->createView(), 
            'error'=>'', 
            'vinculo' => $vinculo, 
            'erros' => $errors
        ));
    }
    
    public function alterarAction(Vinculo $vinculo, Alocacao $alocacao) {
        $request = $this->getRequest();
    	$errors = '';
    	$form = $this->createForm(new AlocacaoForm(), $alocacao);
    	$form->handleRequest($request);
    	if ($form->isValid()) {    	
            try {
                $em = $this->getDoctrine()->getManager();
                $em->merge($alocacao);
                $em->flush();
                return new Response('success');
            } catch (\Exception $ex) {
                return new Response('error');
            }
    	} else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	}
    	return $this->render('DGPBundle:Alocacao:formAlteracao.html.twig', array(
            'form' => $form->createView(), 
            'error'=>'', 
            'vinculo' => $vinculo,
            'alocacao' => $alocacao,
            'erros' => $errors
        ));
    }
    
    public function excluirAction(Vinculo $vinculo, Alocacao $alocacao) {
    	try {
            $this->get('dgp_alocacao')->remove($alocacao);
            return $this->redirect($this->generateUrl('dgp_vinculo_alocacao_listar', array('vinculo' => $vinculo->getId())));
    	} catch (\Exception $e) {
            return new Response($e->getMessage());
    	}
    }
    
}
