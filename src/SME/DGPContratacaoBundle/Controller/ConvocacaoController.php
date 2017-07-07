<?php

namespace SME\DGPContratacaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use SME\DGPContratacaoBundle\Entity\Processo;
use SME\DGPContratacaoBundle\Entity\Convocacao;
use SME\DGPContratacaoBundle\Entity\TipoProcesso;
use SME\DGPContratacaoBundle\Form\ConvocacaoType;

class ConvocacaoController extends Controller {
    
    public function pesquisarAction(Processo $processo) {
        $convocacao = new Convocacao();
        $convocacao->setProcesso($processo);
        switch($processo->getTipoProcesso()->getId()) {
            case TipoProcesso::CONCURSO_PUBLICO:
                $convocacao->setNome('Edital de Convocação'); break;
            case TipoProcesso::PROCESSO_SELETIVO:
                $convocacao->setNome('ª Chamada Pública de ACTs'); break;
            case TipoProcesso::CHAMADA_PUBLICA:
                $convocacao->setNome('ª Chamada Pública por Nível de Escolaridade'); break;
        }
        $form = $this->createForm(new ConvocacaoType(), $convocacao);
        $form->handleRequest($this->getRequest());
        if($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($convocacao);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message', 'Convocação cadastrada');
            $form = $this->createForm(new ConvocacaoType());
        }
        $convocacoes = $this->getDoctrine()->getRepository('DGPContratacaoBundle:Convocacao')->findBy(
            array('processo' => $processo), array('dataRealizacao' => 'DESC')
        );
        return $this->render('DGPContratacaoBundle:Convocacao:listaConvocacoes.html.twig', array(
            'convocacoes' => $convocacoes, 
            'processo' => $processo,
            'form' => $form->createView()
        ));
    }
    
    public function getJsonAction() {
        $inscricao = $this->getDoctrine()->getRepository('DGPContratacaoBundle:Inscricao')->find($this->getRequest()->request->get('processo'));
        $convocacoes = $inscricao 
            ? $this->getDoctrine()->getRepository('DGPContratacaoBundle:Convocacao')->findBy(
                array('processo' => $inscricao->getProcesso()), array('dataRealizacao' => 'DESC')
            )
            : array();
        return new JsonResponse($convocacoes);
    }
    
}
