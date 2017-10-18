<?php

namespace SME\DGPContratacaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\DGPContratacaoBundle\Entity\CIGeral;
use SME\DGPContratacaoBundle\Entity\TipoProcesso;
use SME\DGPContratacaoBundle\Report\CIPosseReport;
use SME\DGPContratacaoBundle\Report\CIContratacaoReport;

class CIGeralController extends Controller {
    
    public function formCadastroAction() {
        return $this->render('DGPContratacaoBundle:CIGeral:formCadastro.html.twig', 
            array('processos' => $this->getDoctrine()->getRepository('DGPContratacaoBundle:Processo')->findAll())
        );
    }
    
    public function cadastrarAction() {
        $post = $this->getRequest()->request;
        try {
            $ci = new CIGeral();
            $ci->setNumero($post->getInt('numero'));
            $ci->setAno($post->getInt('ano'));
            $ci->setProrrogacao($post->getInt('prorrogacao'));
            $processo = $this->getDoctrine()->getManager()->find('DGPContratacaoBundle:Processo', $post->getInt('processo'));
            $ci->setProcesso($processo);
            $this->getDoctrine()->getManager()->persist($ci);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message', 'CI Geral ' . $ci->getNumeroAno() . ' criada com sucesso');
            return $this->redirect($this->generateUrl('dgp_ciGeral_formAlteracao', array('ci' => $ci->getId())));
        } catch(Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', 'Ocorreu um erro no cadastro, verifique se já não existe uma CI com este número e ano');
            return $this->redirect($this->generateUrl('dgp_ciGeral_formCadastro'));
        }
    }
    
    public function formPesquisaAction() {
        return $this->render('DGPContratacaoBundle:CIGeral:formPesquisa.html.twig', 
            array('processos' => $this->getDoctrine()->getRepository('DGPContratacaoBundle:Processo')->findAll())
        );
    }
    
    public function pesquisarAction() {
        $post = $this->getRequest()->request;
        $processo = $this->getDoctrine()->getRepository('DGPContratacaoBundle:Processo')->find($post->getInt('processo'));
        $criteria = array('processo' => $processo);
        if($post->getInt('numero')) {
            $criteria['numero'] = $post->getInt('numero');
        }
        return $this->render('DGPContratacaoBundle:CIGeral:listaCIs.html.twig', 
            array('cis' => $this->getDoctrine()->getRepository('DGPContratacaoBundle:CIGeral')->findBy($criteria, array('numero' => 'DESC')))
        );
    }
    
    public function formAlteracaoAction(CIGeral $ci) {
        if($ci->getProcesso()->getTipoProcesso()->getId() != TipoProcesso::CONCURSO_PUBLICO) {
            foreach($ci->getVinculos() as $vinculo) {
                if(!$vinculo->getObservacao()) {
                    $justificativa = '';
                    foreach($vinculo->getAlocacoesOriginais() as $i=>$alocacao) {
                        $justificativa .= ($i+1) . ') ' . $alocacao->getMotivoEncaminhamento(); 
                    }
                    $vinculo->setObservacao($justificativa);
                }
            }
        }
        return $this->render('DGPContratacaoBundle:CIGeral:formAlteracao.html.twig', array('ci' => $ci)); 
    }
    
    public function atualizarAction(CIGeral $ci) {
        $post = $this->getRequest()->request;
        try {
            $ci->setNumero($post->getInt('numero'));
            $ci->setProrrogacao($post->getInt('prorrogacao'));
            $this->getDoctrine()->getManager()->merge($ci);
            foreach($ci->getVinculos() as $i=>$vinculo) {
                if($vinculo->getId() == $post->get('vinculos')[$i]) {
                    if($ci->getProcesso()->getTipoProcesso()->getId() === TipoProcesso::CONCURSO_PUBLICO) {
                        $vinculo->setPortaria($post->get('portaria')[$i]);
                        $vinculo->setDataNomeacao(\DateTime::createFromFormat('d/m/Y', trim($post->get('dataNomeacao')[$i])));
                        $vinculo->setDataPosse(\DateTime::createFromFormat('d/m/Y', trim($post->get('dataPosse')[$i])));
                    } else {
                        $vinculo->setObservacao($post->get('observacao')[$i]);
                    }
                    $this->getDoctrine()->getManager()->merge($vinculo);
                }
            }
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message', 'Alterações salvas com sucesso');
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('dgp_ciGeral_formAlteracao', array('ci' => $ci->getId())));
    }
    
    public function excluirAction(CIGeral $ci) {
        try {
            if($ci->getVinculos()->isEmpty()) {
                $this->getDoctrine()->getManager()->remove($ci);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->set('message', 'CI ' . $ci->getNumeroAno() . ' excluída');
            } else {
                throw new \Exception('É obrigatório remover todos os vínculos da CI antes de excluí-la');
            }
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->forward('DGPContratacaoBundle:CIGeral:pesquisar');
    }
    
    public function formPesquisaVinculoAction(CIGeral $ci) {
        $criteria = array('processo' => $ci->getProcesso());
        return $this->render('DGPContratacaoBundle:CIGeral:modalFormPesquisaVinculo.html.twig', array(
            'ci' => $ci,
            'cargos' => $this->getDoctrine()->getRepository('DGPContratacaoBundle:Cargo')->findBy($criteria),
            'convocacoes' => $this->getDoctrine()->getRepository('DGPContratacaoBundle:Convocacao')->findBy($criteria, array('id' => 'DESC'))
        ));
    }
    
    public function pesquisarVinculosAction(CIGeral $ci) {
        $post = $this->getRequest()->request;
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder() 
                ->select('v')
                ->from('DGPBundle:Vinculo','v')
                ->join('v.servidor', 's')
                ->join('v.inscricaoVinculacao','i')
                ->join('i.cargo','cargo')
                ->leftJoin('v.convocacaoVinculacao','convocacao')
                ->where('v.ciGeral IS NULL');
        if($post->get('convocacao')) {
            $qb = $qb->andWhere('convocacao.id = :convocacao')->setParameter('convocacao', $post->getInt('convocacao'));
        }
        if($post->get('cargo')) {
            $qb = $qb->andWhere('cargo.id = :cargo')->setParameter('cargo', $post->getInt('cargo'));
        }
        return $this->render('DGPContratacaoBundle:CIGeral:listaVinculos.html.twig', array(
            'ci' => $ci,
            'vinculos' => $qb->getQuery()->getResult()
        ));
    }
    
    public function incluirVinculosAction(CIGeral $ci) {
        $post = $this->getRequest()->request;
        foreach($post->get('vinculos') as $vinculoId) {
            $vinculo = $this->getDoctrine()->getRepository('DGPBundle:Vinculo')->find($vinculoId);
            $vinculo->setCIGeral($ci);
            $ci->getVinculos()->add($vinculo);
            $this->getDoctrine()->getManager()->merge($vinculo);
        }
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl('dgp_ciGeral_formAlteracao', array('ci' => $ci->getId())));
    }
    
    public function excluirVinculosAction(CIGeral $ci) {
        $post = $this->getRequest()->request;
        foreach($post->get('selecionados') as $vinculoId) {
            $vinculo = $this->getDoctrine()->getRepository('DGPBundle:Vinculo')->find($vinculoId);
            $vinculo->setCiGeral(null);
            $this->getDoctrine()->getManager()->merge($vinculo);
        }
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl('dgp_ciGeral_formAlteracao', array('ci' => $ci->getId())));
    }
    
    public function imprimirAction(CIGeral $ci) {
        if($ci->getProcesso()->getTipoProcesso()->getId() === TipoProcesso::CONCURSO_PUBLICO) {
            $doc = new CIPosseReport();
        } else {
            $doc = new CIContratacaoReport();
        }
        $doc->setCiGeral($ci);
        return $this->get('pdf')->render($doc);
    }
    
}
