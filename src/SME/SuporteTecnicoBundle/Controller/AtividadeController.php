<?php

namespace SME\SuporteTecnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\SuporteTecnicoBundle\Form\AtividadeType;
use SME\SuporteTecnicoBundle\Entity\Atividade;
use SME\SuporteTecnicoBundle\Entity\Chamado;

class AtividadeController extends Controller {
    
    public function cadastrarAction(Chamado $chamado) {
        $atividade = new Atividade();
        $atividade->setPessoaCadastrou($this->getUser()->getPessoa());
        $atividade->setDataCadastro(new \DateTime());
        $atividade->setChamado($chamado);
        $form = $this->createForm(new AtividadeType(), $atividade, array('chamado' => $chamado));
        $form->handleRequest($this->getRequest());
        if($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($atividade);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('suporte_chamado_gerenciar', array(
                'chamado' => $chamado->getId()
            )));
        }
        return $this->render('SuporteTecnicoBundle:Atividade:formCadastro.html.twig', array(
            'chamado' => $chamado,
            'form' => $form->createView(), 
            'errors' => $this->get('form_helper')->getFormErrors($form)
        ));
    }
    
    public function excluirAction(Atividade $atividade) {
        if($this->getUser()->getPessoa()->getId() === $atividade->getPessoaCadastrou()->getId()) {
            $this->getDoctrine()->getManager()->remove($atividade);
            $this->getDoctrine()->getManager()->flush();
            return '';
        } else {
            return '';
        }
    }
    
}
