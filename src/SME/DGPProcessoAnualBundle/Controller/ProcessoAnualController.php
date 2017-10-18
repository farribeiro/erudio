<?php

namespace SME\DGPProcessoAnualBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use SME\DGPProcessoAnualBundle\Entity\ProcessoAnual;
use SME\DGPProcessoAnualBundle\Entity\Inscricao;

class ProcessoAnualController extends Controller {
    
    public function listarDisponiveisAction() {
        $now = new \DateTime();
        $processosDisponiveis = $this->getDoctrine()->getRepository('DGPProcessoAnualBundle:ProcessoAnual')->findBy(
            array('disponivel' => true), array('nome' => 'ASC')
        );
        $inscricoes = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('i')->from('DGPProcessoAnualBundle:Inscricao', 'i')
            ->join('i.vinculoServidor', 'v')->join('v.servidor', 'p')
            ->where('p.id = :pessoa AND i.ano = :ano')
            ->setParameter('ano', $now->format('Y'))->setParameter('pessoa', $this->getUser()->getPessoa()->getId())
            ->orderBy('i.dataCadastro','DESC')->getQuery()->getResult();
        $vinculos = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('v')->from('DGPBundle:Vinculo', 'v')
            ->join('v.tipoVinculo', 't')->join('v.servidor', 's')
            ->where('v.ativo = true')->andWhere('s.id = :servidor')
            ->setParameter('servidor', $this->getUser()->getPessoa()->getId())
            ->getQuery()->getResult();
        return $this->render('DGPProcessoAnualBundle:ProcessoAnual:processosDisponiveis.html.twig', 
            array(
                'vinculos' => $vinculos,
                'processos' => $processosDisponiveis,
                'inscricoes' => $inscricoes
            )
        );
    }
    
    public function cadastrarInscricaoAction(ProcessoAnual $processo) {
        $post = $this->getRequest()->request;
        $vinculo = $this->getDoctrine()->getRepository('DGPBundle:Vinculo')->find($post->get('vinculo'));
        $result = array();
        try {
            $inscricao = new Inscricao();
            $now = new \DateTime();
            $inscricao->setVinculoServidor($vinculo);
            $inscricao->setProcessoAnual($processo);
            $inscricao->setAno($now->format('Y'));
            $inscricao->setDataCadastro($now);
            $this->getDoctrine()->getManager()->persist($inscricao);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message', 'Inscrição realizada');
            $result['result'] = $inscricao->getId();
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', 'Não foi possível fazer o cadastro. Verifique na lista acima se você já não está inscrito neste processo.');
            $result['result'] = 'error';
        }
        return new JsonResponse($result);
    }
    
    public function excluirInscricaoAction(ProcessoAnual $processo, Inscricao $inscricao) {
        try {
            $this->getDoctrine()->getManager()->remove($inscricao);
            $this->getDoctrine()->getManager()->flush();
        } catch (\Exception $ex) {
            
        }
        return '';
    }
    
}
