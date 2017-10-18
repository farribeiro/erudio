<?php

namespace SME\DGPContratacaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProcessoController extends Controller {
    
    public function pesquisarAction() {
        $form = $this->createFormBuilder()
            ->add('tipoProcesso', 'entity', array(
                'label' => 'Tipo de Processo:', 
                'class' => 'DGPContratacaoBundle:TipoProcesso',
                'property' => 'nome',
                'required' => true
            ))->getForm();
        $form->handleRequest($this->getRequest());
        if($form->isValid()) {
            $processos = $this->getDoctrine()->getRepository('DGPContratacaoBundle:Processo')->createQueryBuilder('p')
                ->join('p.tipoProcesso', 'tipo')
                ->where('tipo.id = :tipo')
                ->setParameter('tipo', $form->getData()['tipoProcesso'])
                ->orderBy('p.id', 'DESC')
                ->getQuery()->getResult();        
            return $this->render('DGPContratacaoBundle:Processo:listaProcessos.html.twig', array(
                'processos' => $processos
            ));
        }
        return $this->render('DGPContratacaoBundle:Processo:formPesquisa.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
}
