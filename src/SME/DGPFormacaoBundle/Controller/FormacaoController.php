<?php

namespace SME\DGPFormacaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SME\DGPFormacaoBundle\Form\Formacao\FormacaoType;
use SME\DGPFormacaoBundle\Form\Formacao\PesquisaFormacaoType;
use SME\DGPFormacaoBundle\Report\ListaChamadaReport;
use SME\DGPFormacaoBundle\Entity\Formacao;
use SME\DGPFormacaoBundle\Entity\Matricula;
use Symfony\Component\HttpFoundation\Response;

class FormacaoController extends Controller {

    function listarAction(Request $request) {
        $form = $this->createForm(new PesquisaFormacaoType());
        $form->handleRequest($request);
        if ($form->isValid()) {
           try {
                $params = $form->getData();
                $formacoes = $this->pesquisar($params);
                return $this->render('DGPFormacaoBundle:Formacao:formacoes.html.twig', array(
                    'formacoes' => $formacoes
                ));
            } catch(\Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', $ex->getMessage());
            }
        }
        return $this->render('DGPFormacaoBundle:Formacao:formPesquisa.html.twig', array('form' => $form->createView()));
    }
    
    private function pesquisar(array $params) {
        $qb = $this->getDoctrine()->getRepository('DGPFormacaoBundle:Formacao')->createQueryBuilder('f')->where('f.ativo = true');
        if($params['periodoConclusaoInicio'] && $params['periodoConclusaoFim']) {
            $params['periodoConclusao'] = array(
                0 => $params['periodoConclusaoInicio'], 
                1 => $params['periodoConclusaoFim']
            );
        }
        foreach($params as $k => $v) {
            if($v !== null && key_exists($k, $this->parameterMap())) {
                $f = $this->parameterMap()[$k];
                $f($qb, $v);
            }
        }
        return $qb->orderBy('f.dataInicioFormacao', 'DESC')->getQuery()->getResult();
    }
    
    private function parameterMap() {
        return array(
            'nome' => function($qb, $value) {
                $qb->andWhere('f.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
            'periodoConclusao' => function($qb, $value) {
                $dataInicio = \DateTime::createFromFormat('d/m/Y', $value[0]);
                $dataTermino = \DateTime::createFromFormat('d/m/Y', $value[1]);
                $qb->andWhere($qb->expr()->between('f.dataTerminoFormacao', ':inicio', ':termino'))
                    ->setParameter('inicio', $dataInicio->format('Y-m-d'))
                    ->setParameter('termino', $dataTermino->format('Y-m-d'));  
            }
        );
    }

    function cadastrarAction() {
        $errors = '';
        $formacao = new Formacao();
        $form = $this->createForm(new FormacaoType(), $formacao);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            try {
                $formacao->setAtivo(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($formacao);
                $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Formação criada com sucesso');
                return $this->redirect($this->generateUrl('dgp_formacao_atualizar', array('formacao' => $formacao->getId())));
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->set('error', $e->getMessage());
            }
        } else {
            $errors = $this->get('form_helper')->getFormErrors($form);
        }
        return $this->render('DGPFormacaoBundle:Formacao:novaFormacao.html.twig', array('form' => $form->createView(), 'errors' => $errors));
    }

    function atualizarAction(Formacao $formacao) {
        $errors = '';
        $form = $this->createForm(new FormacaoType(), $formacao);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            try {
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->set('message', 'Formação editada com sucesso');
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->set('error', $e->getMessage());
            }
        } else {
            $errors = $this->get('form_helper')->getFormErrors($form);
        }
        return $this->render('DGPFormacaoBundle:Formacao:editarFormacao.html.twig', array(
            'formacao' => $formacao, 
            'form' => $form->createView(), 
            'errors' => $errors
        ));
    }

    function excluirAction(Formacao $formacao) {
        try {
            $formacao->setAtivo(false);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message', 'Formação removida com sucesso');
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->set('error', $e->getMessage());
        }
        return $this->redirect($this->generateUrl('dgp_formacao_listar'));
    }

    function imprimirChamadaAction(Formacao $formacao) {
        $listaChamada = new ListaChamadaReport();
        $listaChamada->setFormacao($formacao);
        return $this->get('pdf')->render($listaChamada);
    }
    
    function listarNoLocalAction() {
        $termino = date('Y-m-d');
        $qb = $this->getDoctrine()->getRepository('DGPFormacaoBundle:Formacao')->createQueryBuilder('f')->where('f.ativo = true')->andWhere('f.dataTerminoFormacao >= :termino');
        $qb->setParameter('termino', $termino);
        $results = $qb->getQuery()->getResult();
        return $this->render('DGPFormacaoBundle:Formacao:formacoesNoLocal.html.twig', array('formacoes' => $results));
    }
    
    function listarInscritosAction(Formacao $formacao) {
        return $this->render('DGPFormacaoBundle:Formacao:formacoesInscritos.html.twig', array('formacao' => $formacao));
    }
    
    function buscarNomeAction() {
        $pessoaCpf = $this->getRequest()->request->get('pessoaCpf');
        if (!empty($pessoaCpf)) {
            $pessoa = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findByCpfCnpj($pessoaCpf);
            if (!empty($pessoa[0])) {
                return new Response($pessoa[0]->getNome());
            } else {
                return new Response('Pessoa não encontrada com este CPF');
            }
        } else {
            return new Response('CPF não enviado.');
        }
    }
    
    function inscreverNoLocalAction(Formacao $formacao) {
        $pessoaCpf = $this->getRequest()->request->get('pessoaCpf');
        if (!empty($pessoaCpf)) {
            try {
                $pessoa = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findByCpfCnpj($pessoaCpf);
                $pessoa = $pessoa[0];
                if($formacao->getVagasDisponiveis() <= 0) {
                    throw new \Exception('Infelizmente todas as vagas para esta formação já foram preenchidas');
                }
                if(!$formacao->getAberto() && (!$this->getUser() || $this->getUser()->getPessoa()->getVinculosTrabalho()->isEmpty())) {
                    throw new \Exception('Esta formação só está disponível para os servidores da Educação');
                }
                $matricula = $this->getDoctrine()->getRepository('DGPFormacaoBundle:Matricula')->findOneBy(array(
                    'formacao' => $formacao, 'pessoa' => $pessoa
                ));
                if ($matricula && $matricula->getAtivo()) {
                    $msg = 'Você já está inscrito nesta formação';
                    throw new \Exception($msg);
                } elseif($matricula) {
                    $matricula->setAtivo(true);
                    $this->getDoctrine()->getManager()->flush();
                    $this->get('session')->getFlashBag()->set('message', 'Inscrição reativada com sucesso');
                } else {
                    $matricula = new Matricula();
                    $matricula->setFormacao($formacao);
                    $matricula->setPessoa($pessoa);
                    $matricula->setAprovado(false);
                    $matricula->setDataCadastro(new \DateTime());
                    $this->getDoctrine()->getManager()->persist($matricula);
                    $this->getDoctrine()->getManager()->flush();
                    $this->get('session')->getFlashBag()->set('message', 'Inscrição realizada com sucesso');
                    return new Response('success');
                }
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->set('error', $e->getMessage());
                return new Response($e->getMessage());
            }
        } else {
            return new Response('CPF não enviado.');
        }
    }
}
