<?php

namespace SME\DGPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\CommonsBundle\Entity\PessoaFisica;
use SME\DGPBundle\Entity\Vinculo;
use SME\DGPBundle\Forms\Servidor\VinculoForm;

class VinculoController extends Controller {
    
    public function listarPorPessoaAction(PessoaFisica $pessoa) {
        $vinculos = $this->getDoctrine()->getRepository('DGPBundle:Vinculo')->findBy(
            array('servidor' => $pessoa, 'ativo' => true)
        );
        return $this->render('DGPBundle:Vinculo:vinculosPorPessoa.html.twig', array('pessoa' => $pessoa, 'vinculos' => $vinculos));
    }

    public function cadastrarAction(PessoaFisica $pessoa) {
        $errors = "";
        $vinculo = new Vinculo();
        $vinculo->setServidor($pessoa);
        $vinculo->setAtivo(true);
        $form = $this->createForm(new VinculoForm(), $vinculo, array('pessoa' => $pessoa));
        $form->handleRequest($this->getRequest());
        $numeroControle = $this->definirNumeroControle($vinculo);
        if($form->isValid()) {    		
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($vinculo);
                $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Vínculo criado com sucesso');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
            return $this->redirect($this->generateUrl('dgp_pessoa_vinculo_listar', array('pessoa' => $pessoa->getId())));
        } else {
            $errors = $this->get('form_helper')->getFormErrors($form);
        }
        return $this->render('DGPBundle:Vinculo:formCadastro.html.twig', array(
            'pessoa' => $pessoa,
            'form' => $form->createView(),
            'erros' => $errors,
            'numeroControle' => $numeroControle
        ));
    }

    private function definirNumeroControle(Vinculo $vinculo) {
        if($vinculo->getProcessoAdmissao()) {
            $now = new \DateTime();
            $numeroControle = $this->getDoctrine()->getRepository('DGPBundle:Vinculo')
                ->createQueryBuilder('v')->select('count(v.id)')
                ->join('v.inscricaoVinculacao', 'i')->join('i.cargo', 'c')->join('c.processo', 'p')
                ->where('p.id = :processo')->andWhere('v.dataInicio BETWEEN :dataInicio AND :dataTermino')
                ->setParameter('processo', $vinculo->getProcessoAdmissao()->getId())
                ->setParameter('dataInicio', $now->format('Y') . '-01-01')
                ->setParameter('dataTermino', $now->format('Y') . '-12-31')
                ->getQuery()->getSingleScalarResult() + 1;
            return $numeroControle;
        }
        return null;
    }

    public function alterarAction(PessoaFisica $pessoa, Vinculo $vinculo) {
        $errors = "";
        $form = $this->createForm(new VinculoForm(), $vinculo, array('pessoa' => $pessoa));
        $form->handleRequest($this->getRequest());
        if($form->isValid()) {    		
            try {
                $em = $this->getDoctrine()->getManager();
                $em->merge($vinculo);
                $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Vínculo alterado com sucesso');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
            return $this->redirect($this->generateUrl('dgp_pessoa_vinculo_listar', array('pessoa' => $pessoa->getId())));
        } else {
            $errors = $this->get('form_helper')->getFormErrors($form);
        }
        return $this->render('DGPBundle:Vinculo:formCadastro.html.twig', array(
            'pessoa' => $pessoa,
            'vinculo' =>$vinculo,
            'form' => $form->createView(),
            'erros' => $errors
        ));
    }

    public function excluirAction(PessoaFisica $pessoa, Vinculo $vinculo) {
        try {
            if(!$vinculo->getAlocacoes()->isEmpty()) {
                throw new \Exception('O vínculo não pode estar alocado em nenhuma unidade para ser removido');
            }
            $vinculo->setAtivo(false);    
            $em = $this->getDoctrine()->getManager();
            $em->merge($vinculo);
            $em->flush();
            $this->get('session')->getFlashBag()->set('message', 'Vínculo excluído com sucesso');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('dgp_pessoa_vinculo_listar', array('pessoa' => $pessoa->getId())));
    }
    
    public function formAlteracaoRapidaAction(Vinculo $vinculo) {
        return $this->render('DGPBundle:Vinculo:modalFormAlteracao.html.twig', array(
            'vinculo' => $vinculo,
            'cargos' => $this->getDoctrine()->getRepository('DGPBundle:Cargo')->findBy(array(), array('nome' => 'ASC')),
            'tiposVinculo' => $this->getDoctrine()->getRepository('DGPBundle:TipoVinculo')->findAll()
        ));
    }
    
    public function alterarRapidoAction(Vinculo $vinculo) {
        $post = $this->getRequest()->request;
        try {
            $vinculo->setCargo($this->getDoctrine()->getRepository('DGPBundle:Cargo')->find($post->get('cargo')));
            $vinculo->setTipoVinculo($this->getDoctrine()->getRepository('DGPBundle:TipoVinculo')->find($post->get('tipoVinculo')));
            $vinculo->setCargaHoraria($post->getInt('cargaHoraria'));
            $vinculo->setQuadroEspecial($post->getInt('quadroEspecial'));
            $vinculo->setGratificacao($post->get('gratificacao'));
            $vinculo->setLotacaoSecretaria($post->get('lotacaoSecretaria'));
            $vinculo->setCodigoDepartamento($post->get('codigoDepartamento'));
            $vinculo->setCodigoSetor($post->get('codigoSetor'));
            $vinculo->setAtivo($post->get('ativo'));
            $this->getDoctrine()->getManager()->merge($vinculo);
            $this->getDoctrine()->getManager()->flush();
        } catch (Exception $ex) {
            
        }
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }
    
    public function imprimirDocumentosAction(Vinculo $vinculo) {
        return $this->render('DGPBundle:Vinculo:modalDocumentos.html.twig', array('vinculo' => $vinculo));
    }
}