<?php

namespace SME\SuporteTecnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\SuporteTecnicoBundle\Form\AnotacaoType;
use SME\SuporteTecnicoBundle\Entity\Anotacao;
use SME\SuporteTecnicoBundle\Entity\Chamado;

class AnotacaoController extends Controller {
    
    public function cadastrarAction(Chamado $chamado) {
        $anotacao = new Anotacao();
        $anotacao->setPessoaCadastrou($this->getUser()->getPessoa());
        $anotacao->setDataCadastro(new \DateTime());
        $anotacao->setChamado($chamado);
        $form = $this->createForm(new AnotacaoType(), $anotacao, array('chamado' => $chamado));
        $form->handleRequest($this->getRequest());
        if($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($anotacao);
            $this->getDoctrine()->getManager()->flush();
            $this->get('notification')->send(
                'Seu chamado técnico de nº ' . $chamado->getId() . ' sofreu atualizações, <a href="' . $this->generateUrl('suporte_chamado_gerenciar', array('chamado' => $chamado->getId())) . '">clique aqui</a> para visualizar', 
                $chamado->getPessoaCadastrou()->getUsuario(), 'info'
            );
            return $this->redirect($this->generateUrl('suporte_chamado_gerenciar', array(
                'chamado' => $chamado->getId()
            )));
        }
        return $this->render('SuporteTecnicoBundle:Anotacao:formCadastro.html.twig', array(
            'chamado' => $chamado,
            'form' => $form->createView(), 
            'errors' => $this->get('form_helper')->getFormErrors($form)
        ));
    }
    
    public function excluirAction(Anotacao $anotacao) {
        if($this->getUser()->getPessoa()->getId() === $anotacao->getPessoaCadastrou()->getId()) {
            $this->getDoctrine()->getManager()->remove($anotacao);
            $this->getDoctrine()->getManager()->flush();
            return '';
        } else {
            return '';
        }
    }
    
}
