<?php

namespace SME\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SME\DGPFormacaoBundle\Entity\Formacao;
use SME\DGPFormacaoBundle\Entity\Matricula;
use SME\PublicacaoBundle\Entity\Categoria;
use SME\PublicacaoBundle\Entity\Documento;

class PublicController extends Controller {

    function servicosPublicosAction () {
    	return $this->render('PublicBundle:Public:services.html.twig');
    }
    
    function consultaFilaUnicaAction (Request $request) {
    	$protocolo = null;
    	if ($request->get('protocolo')) {
    		return $this->redirect($this->generateUrl('fu_publico_consultarInscricao',array('protocolo'=>$request->get('protocolo'))));
    	}
    	
    	return $this->render('PublicBundle:Public:consultaFilaUnica.html.twig', array('protocolo' => $protocolo));
    }
    
    function formacaoExternaAction () {
        return $this->render('PublicBundle:Public:formacaoExterna.html.twig');
    }
    
    function formacaoExternaMatriculaAction (Formacao $formacao) {
        $form = $this->createFormBuilder()
            ->add('nome', 'text', array('label' => 'Nome:', 'required' => false))
            ->add('cpf', 'text', array('label' => 'CPF:', 'required' => false))
            ->add('email', 'text', array('label' => 'Email:', 'required' => false))
            ->add('profissao', 'text', array('label' => 'Profissão:', 'required' => false))
            ->add('Inscrever', 'submit',  array('attr' => array('class' => 'btn btn-primary')))->getForm();

        return $this->render('PublicBundle:Public:modalFormacaoExternaMatricula.html.twig', array('form' => $form->createView(), 'formacao' => $formacao));
    }
    
    function buscaCertificadosAction() {
        $form = $this->createFormBuilder()->add('CPFMatricula', 'text', array('label' => 'Digite seu CPF ou Número de Matrícula:', 'required' => false))->getForm();
        $form->handleRequest($this->getRequest());
        $matriculas = null;
        $errorFormacoes = '';
        
    	if ($form->isValid()) {
            $pessoa = $this->get('cadastro_unico')->createByCpf($form->getData()['CPFMatricula']);

            if ($pessoa->getId() == null) {
                $matricula = $this->getDoctrine()->getRepository('DGPFormacaoBundle:Matricula')->findOneById($form->getData()['CPFMatricula']);
                if (!$matricula) {
                    $errorFormacoes = 'Você não possui nenhuma formação concluída';
                }
                $pessoa = $matricula->getPessoa();
            }
            
            $matriculas = $this->getDoctrine()->getRepository('DGPFormacaoBundle:Matricula')->findBy(
                array('pessoa' => $pessoa, 'aprovado' => true, 'ativo' => true)
            );
            if (count($matriculas) == 0) {
                $errorFormacoes = 'Nenhuma formação encontrada';
            }
    	}
        
        return $this->render('PublicBundle:FormacaoExterna:buscaCertificados.html.twig', array(
            'form' => $form->createView(), 
            'matriculas' => $matriculas, 
            'errorFormacoes' => $errorFormacoes, 
            'ajax' => $this->getRequest()->isXmlHttpRequest()
        ));
    }
    
    function imprimirCertificadoAction(Matricula $matricula){
        return $this->forward('DGPFormacaoBundle:Matricula:imprimirCertificado', array('matricula' => $matricula->getId()));
    }
    
    function requerimentosExternosAction() {
        return $this->render('PublicBundle:RequerimentoExterno:requerimentosExternos.html.twig');
    }
        
    function publicacoesSubPastaAction(Categoria $categoriaPai) {
        $documentos = $this->getDoctrine()->getRepository('PublicacaoBundle:Documento')->findBy(array('ativo' => true, 'categoria' => $categoriaPai));
        $categorias = $this->getDoctrine()->getRepository('PublicacaoBundle:Categoria')->findBy(array('ativo' => true, 'categoria' => $categoriaPai));
        return $this->render('PublicBundle:Publicacao:publicacaoSubPasta.html.twig', array('documentos' => $documentos, 'categoria' => $categoriaPai, 'categorias' => $categorias));
    }
}