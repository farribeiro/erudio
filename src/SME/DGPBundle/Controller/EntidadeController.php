<?php

namespace SME\DGPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\CommonsBundle\Entity\Entidade;
use SME\CommonsBundle\Entity\Endereco;
use SME\CommonsBundle\Entity\Telefone;
use SME\CommonsBundle\Entity\Estado;
use SME\DGPBundle\Entity\Alocacao;
use SME\DGPBundle\Forms\EntidadeType;
use SME\DGPBundle\Forms\Servidor\EnderecoForm;
use SME\DGPBundle\Forms\Servidor\TelefoneForm;

class EntidadeController extends Controller {
    
    function listarAction() {
        $qb = $this->getDoctrine()->getRepository('CommonsBundle:Entidade')->createQueryBuilder('e');
        $entidades = $qb->join('e.pessoaJuridica', 'p')->where('p.ativo = true')->orderBy('p.nome')->getQuery()->getResult();
        return $this->render('DGPBundle:Entidade:listaEntidades.html.twig', array('entidades' => $entidades));
    }
    
    function cadastrarAction() {
        $errors = '';
        $entidade = new Entidade();
    	$form = $this->createForm(new EntidadeType(), $entidade);
    	$form->handleRequest($this->getRequest());
    	if ($form->isValid()) {
            try {
                $this->getDoctrine()->getManager()->persist($entidade);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->set('message', 'Entidade criada com sucesso');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
            return $this->redirect($this->generateUrl('dgp_pessoa_formacao_listar', array('pessoa' => $pessoa->getId())));
    	} else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	}
    	return $this->render('DGPBundle:Entidade:formCadastro.html.twig', array(
            'form' => $form->createView(), 
            'erros' => $errors
        ));
    }
    
    function alterarAction(Entidade $entidade) {
        $errors = '';
    	$form = $this->createForm(new EntidadeType(), $entidade);
    	$form->handleRequest($this->getRequest());
    	if ($form->isValid()) {
            try {
                $this->getDoctrine()->getManager()->merge($entidade);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->set('message', 'Entidade alterada com sucesso');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
    	} else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	}
    	return $this->render('DGPBundle:Entidade:formCadastro.html.twig', array(
            'entidade' => $entidade,
            'form' => $form->createView(), 
            'erros' => $errors
        ));
    }
    
    function alterarEnderecoAction(Entidade $entidade) {
        $endereco = $entidade->getPessoaJuridica()->getEndereco();	
        if (!$endereco) {
            $endereco = new Endereco();
        }
    	$form = $this->createForm(new EnderecoForm(), $endereco);
    	$form->handleRequest($this->getRequest());  	
    	if ($form->isValid()) {
            $endereco = $form->getData();
            $entidade->getPessoaJuridica()->setEndereco($endereco);
            try {
                $this->getDoctrine()->getManager()->merge($entidade->getPessoaJuridica());
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->set('message', 'Endereço atualizado com sucesso');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
    	}
    	$cidades = $this->getDoctrine()->getRepository('CommonsBundle:Cidade')->findByEstado(Estado::SC);
    	return $this->render('DGPBundle:Entidade:formEndereco.html.twig', array(
            'form' => $form->createView(), 
            'entidade' => $entidade, 
            'cidades' => $cidades
        ));
    }
    
    public function adicionarTelefoneAction(Entidade $entidade) {
    	$telefone = new Telefone();
        $telefone->setPessoa($entidade->getPessoaJuridica());
    	$form = $this->createForm(new TelefoneForm(), $telefone);
    	$form->handleRequest($this->getRequest());
    	if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($telefone);
                $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Telefone adicionado com sucesso');
            } catch(\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
    	}
    	return $this->render('DGPBundle:Entidade:formTelefone.html.twig', array(
            'form' => $form->createView(), 
            'entidade' => $entidade,
            'telefones' => $entidade->getPessoaJuridica()->getTelefones()
        ));
    }
    
    public function removerTelefoneAction(Entidade $entidade, Telefone $telefone) {
    	try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($telefone);
            $em->flush();
            $this->get('session')->getFlashBag()->set('message', 'Telefone excluído com sucesso');
    	} catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
    	}
        return $this->redirect($this->generateUrl('dgp_entidade_telefone_cadastrar', array('entidade' => $entidade->getId())));
    }
    
    function listarAlocadosEntidadeAction(Entidade $entidade) {
        $alocacoes = $this->get('dgp_alocacao')->findByLocalTrabalho($entidade); 
        return $this->render('DGPBundle:Entidade:listaAlocacoesEntidade.html.twig', 
            array('alocacoes' => $alocacoes, 'entidade' => $entidade)
        );
    }
    
    function excluirAlocacaoAction (Entidade $entidade, Alocacao $alocacao) {
        try {
            $this->get('dgp_alocacao')->remove($alocacao);
            return $this->forward('DGPBundle:Entidade:listaAlocadosEntidade', array(
                'entidade'  => $entidade->getId()
            ));
    	} catch (\Exception $e) {
            return new Response($e->getMessage());
    	}
    }
}
