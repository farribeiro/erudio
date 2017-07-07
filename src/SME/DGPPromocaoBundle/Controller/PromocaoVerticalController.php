<?php

namespace SME\DGPPromocaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\DGPBundle\Entity\Vinculo;
use SME\DGPPromocaoBundle\Entity\PromocaoVertical;
use SME\DGPPromocaoBundle\Entity\Status;
use SME\DGPPromocaoBundle\Form\PromocaoVerticalType;
use SME\DGPPromocaoBundle\Form\PromocaoVerticalServidorType;
use SME\DGPPromocaoBundle\Report\PromocaoVerticalReport;
use SME\CommonsBundle\Entity\Formacao;

class PromocaoVerticalController extends Controller {
    
    public function listarAction(Vinculo $vinculo) {
    	$promocoes = $this->getDoctrine()->getRepository('DGPPromocaoBundle:PromocaoVertical')->findBy(array('vinculo' => $vinculo), array('dataCadastro' => 'DESC'));
    	return $this->render('DGPPromocaoBundle:PromocaoVertical:promocoes.html.twig', array(
            'vinculo' => $vinculo, 
            'promocoes' => $promocoes
        ));
    }
    
    public function cadastrarAction(Vinculo $vinculo) {
        $errors = '';
        $promocao = new PromocaoVertical();
        $promocao->setVinculo($vinculo);
    	$form = $this->createForm(new PromocaoVerticalType(), $promocao);
    	$form->handleRequest($this->getRequest());
    	if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $this->get('dgp_promocao')->definirProtocolo($promocao);
                $em->persist($promocao);
                $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Promoção cadastrada');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
            return $this->redirect($this->generateUrl('dgp_promocaoVertical_listar', array('vinculo' => $vinculo->getId())));
    	} else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	} 
    	return $this->render('DGPPromocaoBundle:PromocaoVertical:formCadastro.html.twig', array(
            'vinculo' => $vinculo,
            'form' => $form->createView(), 
            'erros' => $errors
        ));
    }
    
    public function alterarAction(Vinculo $vinculo, PromocaoVertical $promocao) {
    	$errors = '';
    	$form = $this->createForm(new PromocaoVerticalType(), $promocao);
    	$form->handleRequest($this->getRequest());
    	if ($form->isValid()) {
            try {
                if($promocao->getEncerrado()) {
                    $this->get('session')->getFlashBag()->set('message', 'Aviso: Você está alterando uma promoção já deferida/indeferida');
                }
                if($promocao->getStatus()->getTerminal() && !$promocao->getDataEncerramento()) {
                    $promocao->setDataEncerramento(new \DateTime());
                    $this->cadastrarFormacaoPessoa($promocao);
                }
                $em = $this->getDoctrine()->getManager();
                $em->merge($promocao);
                $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Promoção atualizada');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
    	} else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	} 
    	return $this->render('DGPPromocaoBundle:PromocaoVertical:formCadastro.html.twig', array(
            'vinculo' => $vinculo,
            'form' => $form->createView(), 
            'erros' => $errors
        ));
    }
    
    private function cadastrarFormacaoPessoa(PromocaoVertical $promocao) {
        $formacao = new Formacao();
        $formacao->setNome($promocao->getNomeCurso());
        $formacao->setCargaHoraria($promocao->getCargaHorariaCurso());
        $formacao->setDataConclusao($promocao->getDataConclusaoCurso());
        $formacao->setInstituicao($promocao->getInstituicaoCurso());
        $formacao->setGrauFormacao($promocao->getGrauCurso());
        $this->getDoctrine()->getManager()->persist($formacao);
        $this->getDoctrine()->getManager()->flush();
    }
    
    public function solicitarAction(Vinculo $vinculo) {
        if($vinculo->getServidor()->getId() != $this->getUser()->getPessoa()->getId()) {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Você não pode solicitar promoções para outro servidor');
        }
        $errors = '';
        $promocao = new PromocaoVertical();
        $promocao->setVinculo($vinculo);
        $statusInicial = $this->getDoctrine()->getRepository('DGPPromocaoBundle:Status')->find(PromocaoVertical::STATUS_INICIAL);
        $promocao->setStatus($statusInicial);
        $now = new \DateTime();
        $promocao->setAno($now->format('Y'));
    	$form = $this->createForm(new PromocaoVerticalServidorType(), $promocao);
    	$form->handleRequest($this->getRequest());
    	if ($form->isValid()) {
            try {
                if($form->get('matricula')->getData()) {
                    $vinculo->setMatricula($form->get('matricula')->getData());
                    $this->getDoctrine()->getManager()->merge($vinculo);
                } else {
                    throw new \Exception('O campo matrícula deve ser preenchido');
                }
                if($promocao->getCargaHorariaCurso() < PromocaoVertical::CARGA_HORARIA_MINIMA) {
                    throw new \Exception('Carga horária insuficiente. Carga mínima: ' . PromocaoVertical::CARGA_HORARIA_MINIMA . ' horas');
                }
                $em = $this->getDoctrine()->getManager();
                $this->get('dgp_promocao')->definirProtocolo($promocao);
                $em->persist($promocao);
                $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Requerimento Realizado');
                return $this->redirect($this->generateUrl('dgp_servidor_promocao_listar', array('vinculo' => $vinculo->getId())));
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
    	} else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	} 
    	return $this->render('DGPPromocaoBundle:PromocaoVertical:formServidor.html.twig', array(
            'vinculo' => $vinculo,
            'form' => $form->createView(), 
            'erros' => $errors
        ));
    }
    
    public function visualizarAction(PromocaoVertical $promocao) {
        return $this->render('DGPPromocaoBundle:PromocaoVertical:detalhes.html.twig', array(
            'promocao' => $promocao
        ));
    }
    
    public function cancelarAction(PromocaoVertical $promocao) {
        try {
            if($promocao->getStatus()->getId() != Status::AGUARDANDO_ENTREGA) {
                throw new \Exception('O requerimento já foi entregue e só pode ser cancelado por um administrador');
            }
            $statusCancelado = $this->getDoctrine()->getRepository('DGPPromocaoBundle:Status')->find(Status::CANCELADO);
            $promocao->setStatus($statusCancelado);
            $promocao->setDataEncerramento(new \DateTime());
            $promocao->setObservacao('Cancelado pelo próprio servidor');
            $this->getDoctrine()->getManager()->merge($promocao);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message', 'Requerimento Cancelado');
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('dgp_servidor_promocao_listar', array('vinculo' => $promocao->getVinculo()->getId())));
    }
    
    public function imprimirAction(PromocaoVertical $promocao) {
        $requerimento = new PromocaoVerticalReport();
        $requerimento->setPromocaoVertical($promocao);
        if($promocao->getVinculo()->getAlocacoes()->isEmpty()) {
            $alocacoes = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('a')->from('DGPBundle:Alocacao', 'a')->join('a.vinculoServidor', 'v')->join('v.servidor', 'p')
                ->where('p.id = :pessoa')->setParameter('pessoa', $promocao->getVinculo()->getServidor()->getId())
                ->getQuery()->getResult();
        } else {
            $alocacoes = $promocao->getVinculo()->getAlocacoes()->getValues();
        }
        $requerimento->setAlocacoes($alocacoes);
        return $this->get('pdf')->render($requerimento);
    }
    
}
