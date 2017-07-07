<?php

namespace SME\DGPPromocaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\DGPPromocaoBundle\Form\CIGeralType;
use SME\DGPPromocaoBundle\Entity\CIGeral;
use SME\DGPPromocaoBundle\Entity\Promocao;
use SME\DGPPromocaoBundle\Entity\Status;
use SME\DGPPromocaoBundle\Report\CIGeralReport;

class CIGeralController extends Controller {
    
    public function cadastrarAction() {
        $errors = '';
        $ci = new CIGeral();
    	$form = $this->createForm(new CIGeralType(), $ci);
    	$form->handleRequest($this->getRequest());
    	if ($form->isValid()) {
            try {
                $this->getDoctrine()->getManager()->persist($ci);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->set('message', 'CI cadastrada com sucesso');
                return $this->redirect($this->generateUrl('dgp_promocao_ciGeral_alterar', array('ci' => $ci->getId())));
            } catch(\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
        } else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	} 
        return $this->render('DGPPromocaoBundle:CIGeral:formCadastro.html.twig', array(
            'form' => $form->createView(),
            'erros' => $errors
        ));
    }
    
    public function formPesquisaAction() {
        return $this->render('DGPPromocaoBundle:CIGeral:formPesquisa.html.twig');
    }
    
    public function pesquisarAction() {
        $post = $this->getRequest()->request;
        $criteria = array('tipoPromocao' => $post->get('tipoPromocao'));
        if($post->getInt('ano')) {
            $criteria['ano'] = $post->getInt('ano');
        }
        if($post->getInt('numero')) {
            $criteria['numero'] = $post->getInt('numero');
        }
        return $this->render('DGPPromocaoBundle:CIGeral:listaCIs.html.twig', 
            array('cis' => $this->getDoctrine()->getRepository('DGPPromocaoBundle:CIGeral')->findBy($criteria, array('numero' => 'DESC')))
        );
    }
    
    public function alterarAction(CIGeral $ci) {
        $errors = '';
    	$form = $this->createForm(new CIGeralType(), $ci);
    	$form->handleRequest($this->getRequest());
    	if ($form->isValid()) {
            try {
                $this->getDoctrine()->getManager()->merge($ci);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->set('message', 'Alterações salvas com sucesso');
            } catch(\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
        } else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	} 
        return $this->render('DGPPromocaoBundle:CIGeral:formAlteracao.html.twig', array(
            'ci' => $ci,
            'form' => $form->createView(), 
            'erros' => $errors
        ));
    }
    
    public function excluirAction(CIGeral $ci) {
        try {
            if($ci->getPromocoes()->isEmpty()) {
                $this->getDoctrine()->getManager()->remove($ci);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->set('message', 'CI ' . $ci->getNumeroAno() . ' excluída');
            } else {
                throw new \Exception('É obrigatório remover todos as promoções da CI antes de excluí-la');
            }
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->forward('DGPPromocaoBundle:CIGeral:pesquisar');
    }
    
    public function formPesquisaPromocoesAction(CIGeral $ci) {
        return $this->render('DGPPromocaoBundle:CIGeral:modalFormPesquisaPromocao.html.twig', array(
            'ci' => $ci,
            'cargos' => $this->getDoctrine()->getRepository('DGPBundle:Cargo')->findBy(
                array(), array('nome' => 'ASC')
            )
        ));
    }
    
    public function pesquisarPromocoesAction(CIGeral $ci) {
        $post = $this->getRequest()->request;
        $rep = $ci->getTipoPromocao() === Promocao::TIPO_PROMOCAO_HORIZONTAL ? 'PromocaoHorizontal' : 'PromocaoVertical';
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder() 
                ->select('p')
                ->from('DGPPromocaoBundle:' . $rep, 'p')
                ->join('p.vinculo', 'v')->join('p.status', 's')->join('v.cargo','c')
                ->where('p.ciGeral IS NULL')->andWhere('s.id = :status')
                ->setParameter('status', Status::DEFERIDO);
        if($post->get('ano')) {
            $qb = $qb->andWhere('p.ano = :ano')->setParameter('ano', $post->getInt('ano'));
        }
        if($post->get('cargo')) {
            $qb = $qb->andWhere('c.id = :cargo')->setParameter('cargo', $post->getInt('cargo'));
        }
        return $this->render('DGPPromocaoBundle:CIGeral:listaPromocoes.html.twig', array(
            'ci' => $ci,
            'promocoes' => $qb->getQuery()->getResult()
        ));
    }
    
    public function adicionarPromocoesAction(CIGeral $ci) {
        $post = $this->getRequest()->request;
        foreach($post->get('promocoes') as $promocaoId) {
            $promocao = $this->getDoctrine()->getRepository('DGPPromocaoBundle:Promocao')->find($promocaoId);
            $promocao->setCIGeral($ci);
            $ci->getPromocoes()->add($promocao);
            $this->getDoctrine()->getManager()->merge($promocao);
        }
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl('dgp_promocao_ciGeral_alterar', array('ci' => $ci->getId())));
    }
    
    public function removerPromocaoAction(CIGeral $ci, Promocao $promocao) {
        try {
            $promocao->setCiGeral(null);
            $this->getDoctrine()->getManager()->merge($promocao);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message', 'Promoção removida da CI');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('dgp_promocao_ciGeral_alterar', array('ci' => $ci->getId())));
    }
    
    public function imprimirAction(CIGeral $ci) {
        $doc = new CIGeralReport();
        $doc->setCiGeral($ci);
        return $this->get('pdf')->render($doc);
    }
    
    
}
