<?php

namespace SME\ProtocoloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\ProtocoloBundle\Entity\Encaminhamento;
use SME\ProtocoloBundle\Entity\Categoria;

class EncaminhamentoController extends Controller {
    
    public function listarAction(Categoria $categoria) {
        $encaminhamentos = $this->getDoctrine()->getRepository('ProtocoloBundle:Encaminhamento')->findBy(
                array('pessoaRecebe' => $this->getUser()->getPessoa(), 'recebido' => false), 
                array('dataCadastro' => 'DESC')
        );
        return $this->render('ProtocoloBundle:Encaminhamento:listaEncaminhamentos.html.twig', 
                array('encaminhamentos' => $encaminhamentos, 'categoria' => $categoria));
    }
    
    public function aceitarAction(Encaminhamento $encaminhamento) {
        try {
            if($this->getUser()->getPessoa()->getId() != $encaminhamento->getPessoaRecebe()->getId()) {
                throw new \Exception('Somente a pessoa para a qual o protocolo foi encaminhado pode recebê-lo');
            }
            $encaminhamento->setRecebido(true);
            $encaminhamento->setDataRecebimento(new \DateTime());
            $encaminhamento->getProtocolo()->setResponsavelAtual($this->getUser()->getPessoa());
            $this->getDoctrine()->getManager()->merge($encaminhamento->getProtocolo());
            $this->getDoctrine()->getManager()->merge($encaminhamento);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message','Protocolo ' . $encaminhamento->getProtocolo()->getProtocolo() . ' recebido');
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error','Erro: ' . $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('protocolo_admin_listarEncaminhamentos', 
                array('categoria' => $encaminhamento->getProtocolo()->getSolicitacao()->getCategoria()->getId())));
    }
    
}
