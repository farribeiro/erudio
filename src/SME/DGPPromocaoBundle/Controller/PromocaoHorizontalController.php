<?php

namespace SME\DGPPromocaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\DGPBundle\Entity\Vinculo;
use SME\DGPPromocaoBundle\Entity\PromocaoHorizontal;
use SME\DGPPromocaoBundle\Entity\FormacaoExterna;
use SME\DGPPromocaoBundle\Entity\FormacaoInterna;
use SME\DGPPromocaoBundle\Entity\Status;
use SME\DGPPromocaoBundle\Form\PromocaoType;
use SME\DGPPromocaoBundle\Form\PromocaoHorizontalServidorType;
use SME\DGPPromocaoBundle\Report\PromocaoHorizontalReport;

class PromocaoHorizontalController extends Controller {
    
    public function listarAction(Vinculo $vinculo) {
    	$promocoes = $this->getDoctrine()->getRepository('DGPPromocaoBundle:PromocaoHorizontal')->findBy(array('vinculo' => $vinculo), array('dataCadastro' => 'DESC'));
        $anoAtual = date('Y');
        $ano_inicial = date('Y') - 2;
        $arrayAnos = array();
        for ($i = 0; $i < 5; $i++) {
            $arrayAnos[] = $ano_inicial;
            $ano_inicial++;
        }
    	return $this->render('DGPPromocaoBundle:PromocaoHorizontal:promocoes.html.twig', array(
            'vinculo' => $vinculo, 
            'promocoes' => $promocoes,
            'anos' => $arrayAnos,
            'anoAtual' => $anoAtual
        ));
    }
    
    public function cadastrarAction(Vinculo $vinculo) {
        $promocao = new PromocaoHorizontal();
    	$promocao->setVinculo($vinculo);
        $promocao->setAno($this->getRequest()->request->getInt('ano'));
        $statusInicial = $this->getDoctrine()->getRepository('DGPPromocaoBundle:Status')->find(PromocaoHorizontal::STATUS_INICIAL);
        $promocao->setStatus($statusInicial);
        try {
            $em = $this->getDoctrine()->getManager();
            $this->get('dgp_promocao')->definirProtocolo($promocao);
            $em->persist($promocao);
            $em->flush();
            $this->get('session')->getFlashBag()->set('message', 'Promoção cadastrada');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
    	return $this->redirect($this->generateUrl('dgp_promocaoHorizontal_listar', array('vinculo' => $vinculo->getId())));
    }
    
    public function alterarAction(Vinculo $vinculo, PromocaoHorizontal $promocao) {
    	$errors = '';
    	$form = $this->createForm(new PromocaoType(), $promocao);
    	$form->handleRequest($this->getRequest());
    	if ($form->isValid()) {
            try {
                if($promocao->getEncerrado()) {
                    $this->get('session')->getFlashBag()->set('message', 'Aviso: Você está alterando uma promoção já deferida/indeferida');
                }
                else if($promocao->getStatus()->getId() === Status::DEFERIDO && $promocao->getCargaHorariaAcumulada() < PromocaoHorizontal::CARGA_HORARIA_MINIMA) {
                    throw new \Exception('Carga horária insuficiente para deferimento');
                }
                else if($promocao->getStatus()->getTerminal() && !$promocao->getDataEncerramento()) {
                    $promocao->setDataEncerramento(new \DateTime());
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
    	return $this->render('DGPPromocaoBundle:PromocaoHorizontal:formCadastro.html.twig', array(
            'vinculo' => $vinculo,
            'promocao' => $promocao,
            'formacoesInternas' => $this->getFormacoesInternasDisponiveis($vinculo),
            'form' => $form->createView(), 
            'erros' => $errors
        ));
    }
    
    public function incluirFormacaoAction(PromocaoHorizontal $promocao) {
        try {
            if($promocao->getEncerrado()) {
                $this->get('session')->getFlashBag()->set('message', 'Aviso: Você está alterando uma promoção já deferida/indeferida');
            }
            $matricula = $this->getDoctrine()->getRepository('DGPFormacaoBundle:Matricula')->find($this->getRequest()->request->getInt('matricula'));
            $formacaoInterna = new FormacaoInterna();
            $formacaoInterna->setPromocaoHorizontal($promocao);
            $formacaoInterna->setMatricula($matricula);
            $this->getDoctrine()->getManager()->persist($formacaoInterna);
            $this->getDoctrine()->getManager()->flush();
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('dgp_promocaoHorizontal_alterar', array('vinculo' => $promocao->getVinculo()->getId(), 'promocao' => $promocao->getId())));
    }
    
    public function excluirFormacaoAction(PromocaoHorizontal $promocao, FormacaoInterna $formacao) {
        try {
            if($promocao->getEncerrado()) {
                $this->get('session')->getFlashBag()->set('message', 'Aviso: Você está alterando uma promoção já deferida/indeferida');
            }
            $this->getDoctrine()->getManager()->remove($formacao);
            $this->getDoctrine()->getManager()->flush();
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('dgp_promocaoHorizontal_alterar', array('vinculo' => $promocao->getVinculo()->getId(), 'promocao' => $promocao->getId())));
    }
    
    private function getFormacoesInternasDisponiveis(Vinculo $vinculo) {
        $utilizadas = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('m.id')->from('DGPPromocaoBundle:FormacaoInterna', 'f')
            ->join('f.matricula', 'm')->join('f.promocaoHorizontal', 'p')->join('p.vinculo', 'v')->join('p.status', 's')
            ->where('v.id = :vinculo')->setParameter('vinculo', $vinculo->getId())
            ->andWhere('m.aprovado = true')
            ->andWhere('s.id NOT IN (:indeferidos)')->setParameter('indeferidos', array(Status::INDEFERIDO, Status::CANCELADO))
            ->getQuery()->getResult();
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('m')->from('DGPFormacaoBundle:Matricula', 'm')->join('m.pessoa', 'p')
            ->where('m.aprovado = true')->andWhere('p.id = :pessoa')
            ->setParameter('pessoa', $vinculo->getServidor()->getId());
        if(count($utilizadas)) {
            $qb = $qb->andWhere('m.id NOT IN (:utilizadas)')->setParameter('utilizadas', $utilizadas);
        } 
        $formacoes = $qb->getQuery()->getResult();
        return $formacoes;
    }
    
    public function solicitarAction(Vinculo $vinculo) {
        if($vinculo->getServidor()->getId() != $this->getUser()->getPessoa()->getId()) {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Você não pode solicitar promoções para outro servidor');
        }
        $errors = '';
        $promocao = new PromocaoHorizontal();
        $promocao->setVinculo($vinculo);
        $statusInicial = $this->getDoctrine()->getRepository('DGPPromocaoBundle:Status')->find(PromocaoHorizontal::STATUS_INICIAL);
        $promocao->setStatus($statusInicial);
        $now = new \DateTime();
        $promocao->setAno($now->format('Y'));
        $formacoesInternas = $this->getFormacoesInternasDisponiveis($vinculo);
        $options = array('formacoesInternas' => $formacoesInternas);
    	$form = $this->createForm(new PromocaoHorizontalServidorType(), null, $options);
    	$form->handleRequest($this->getRequest());
    	if ($form->isValid()) {
            try {
                $dados = $form->getData();
                if($form->get('matricula')->getData()) {
                    $vinculo->setMatricula($form->get('matricula')->getData());
                    $this->getDoctrine()->getManager()->merge($vinculo);
                } else {
                    throw new \Exception('O campo matrícula deve ser preenchido');
                }
                foreach($dados['formacoesInternas'] as $matricula) {
                    $formacaoInterna = new FormacaoInterna();
                    $formacaoInterna->setMatricula($matricula);
                    $formacaoInterna->setPromocaoHorizontal($promocao);
                    $promocao->getFormacoesInternas()->add($formacaoInterna);
                }
                for($i = 1; $i <= 8; $i++) {
                    if($dados['formacaoExterna' . $i]) {
                        $formacaoExterna = new FormacaoExterna();
                        $formacaoExterna->setNome($dados['formacaoExterna' . $i . '_nome']);
                        $formacaoExterna->setCargaHoraria($dados['formacaoExterna' . $i . '_cargaHoraria']);
                        $formacaoExterna->setInstituicao($dados['formacaoExterna' . $i . '_instituicao']);
                        $formacaoExterna->setDataConclusao($dados['formacaoExterna' . $i . '_dataConclusao']);
                        $formacaoExterna->setPromocaoHorizontal($promocao);
                        $promocao->getFormacoesExternas()->add($formacaoExterna);
                    }
                }
                if($promocao->getCargaHorariaAcumulada() < PromocaoHorizontal::CARGA_HORARIA_MINIMA) {
                    throw new \Exception('Carga horária insuficiente. Carga mínima: ' . PromocaoHorizontal::CARGA_HORARIA_MINIMA . ' horas');
                }
                $this->get('dgp_promocao')->definirProtocolo($promocao);
                $this->getDoctrine()->getManager()->persist($promocao);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->set('message', 'Requerimento Realizado');
                return $this->redirect($this->generateUrl('dgp_servidor_promocao_listar', array('vinculo' => $vinculo->getId())));
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
    	} else {
           $errors = $this->get('form_helper')->getFormErrors($form);
    	} 
    	return $this->render('DGPPromocaoBundle:PromocaoHorizontal:formServidor.html.twig', array(
            'vinculo' => $vinculo,
            'formacoesInternas' => $formacoesInternas,
            'form' => $form->createView(),
            'erros' => $errors
        ));
    }
    
    public function visualizarAction(PromocaoHorizontal $promocao) {
        return $this->render('DGPPromocaoBundle:PromocaoHorizontal:detalhes.html.twig', array(
            'promocao' => $promocao
        ));
    }
    
    public function cancelarAction(PromocaoHorizontal $promocao) {
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
    
    public function imprimirAction(PromocaoHorizontal $promocao) {
        $requerimento = new PromocaoHorizontalReport();
        $requerimento->setPromocaoHorizontal($promocao);
        //var_dump($promocao->getVinculo()->getAlocacoes()->isEmpty()); die;
        if($promocao->getVinculo()->getAlocacoes()->isEmpty()) {
            $alocacoes = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('a')->from('DGPBundle:Alocacao', 'a')->join('a.vinculoServidor', 'v')->join('v.servidor', 'p')
                ->where('p.id = :pessoa')->andWhere('a.ativo = :ativo')->setParameter('pessoa', $promocao->getVinculo()->getServidor()->getId())->setParameter('ativo',true)
                ->getQuery()->getResult();
        } else {
            $alocacoes = $promocao->getVinculo()->getAlocacoes()->getValues();
        }
        $requerimento->setAlocacoes($alocacoes);
        return $this->get('pdf')->render($requerimento);
    }
    
}
