<?php

namespace SME\DGPPermutaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SME\DGPPermutaBundle\Entity\Intencao;
use SME\DGPPermutaBundle\Form\IntencaoType;

class IntencaoController extends Controller {
    
    public function listarAction() {
        $qb = $this->getDoctrine()->getRepository('DGPPermutaBundle:Intencao')->createQueryBuilder('i');
        $intencoes = $qb->join('i.vinculo', 'v')->join('v.cargo', 'c')
                ->where('i.ativo = true')->orderBy('c.nome')->getQuery()->getResult();
        return $this->render('DGPPermutaBundle:Intencao:intencoes.html.twig', 
            array('intencoes' => $intencoes)
        );
    }
    
    public function cadastrarAction(Request $request) {
        $errors = '';
        $intencao = new Intencao();
    	$form = $this->createForm(new IntencaoType(), $intencao, array('pessoa' => $this->getUser()->getPessoa()));
    	$form->handleRequest($request);
    	if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($intencao);
                $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Intenção cadastrada');
                return $this->redirect($this->generateUrl('dgp_servidor_permuta_listarIntencoes'));
            } catch (\Exception $ex) {
                die($ex->getMessage());
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
    	} else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	} 
    	return $this->render('DGPPermutaBundle:Intencao:formCadastro.html.twig', array(
            'form' => $form->createView(), 
            'erros' => $errors
        ));
    }
    
    public function excluirAction(Intencao $intencao, Request $request) {
        $pessoa = $intencao->getVinculo()->getServidor();
        if($pessoa->getId() === $this->getUser()->getPessoa()->getId()) {
            $intencao->setAtivo(false);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message', 'Intenção excluída');
        }
        return $this->redirect($this->generateUrl('dgp_servidor_permuta_listarIntencoes'));

    }
    
}
