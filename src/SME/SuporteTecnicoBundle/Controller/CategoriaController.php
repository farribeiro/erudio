<?php

namespace SME\SuporteTecnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use SME\SuporteTecnicoBundle\Entity\Categoria;
use SME\SuporteTecnicoBundle\Form\CategoriaType;
use SME\SuporteTecnicoBundle\Form\CategoriaBasic;
use SME\SuporteTecnicoBundle\Form\SubCategoriaType;

class CategoriaController extends Controller {
    
    public function pesquisarAction() {
        $categoriasRaiz = $this->getDoctrine()->getRepository('SuporteTecnicoBundle:Categoria')->findBy(
            array('categoriaPai' => null, 'ativo' => true), array('nome' => 'ASC')
        );
        $nivel = 2;
        return $this->render('SuporteTecnicoBundle:Categoria:categorias.html.twig', array('categorias' => $categoriasRaiz, 'nivel' => $nivel));
    }
    
    public function cadastrarAction() {
        $get = $this->getRequest()->query;
    	$categoria = new Categoria();
    	$possuiPai = false;
    	if ($get->get('categoria')) {
            $categoriaId = str_replace('categoria', '', $get->get('categoria'));
            $categoriaPai = $this->getDoctrine()->getRepository('SuporteTecnicoBundle:Categoria')->findOneById($categoriaId);
            $categoria->setEquipe($categoriaPai->getEquipe());
            $categoria->setCategoriaPai($categoriaPai);
            $possuiPai = true;
    	}
    	$form = $this->createForm($possuiPai ? new SubCategoriaType() : new CategoriaType(), $categoria);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $categoria->setAtivo(true);
            try {
                $this->getDoctrine()->getManager()->persist($categoria);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateUrl('suporte_categoria_pesquisar'));
            } catch (\Exception $ex) {
                return new Response('error');
            }
        }
        return $this->render('SuporteTecnicoBundle:Categoria:criarCategoria.html.twig', array('form' => $form->createView()));
    }
    
    public function atualizarAction(Categoria $categoria) {
    	try {
            $categoria->setNome($this->getRequest()->request->get('nome'));
            $this->getDoctrine()->getManager()->flush();
            return new Response('success');
    	} catch (\Exception $ex) {
            return new Response('error');
    	}
    }
    
    public function editarAction(Categoria $categoria) {
        $form = $this->createForm(new CategoriaBasic(), $categoria);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            try {
                $this->getDoctrine()->getManager()->flush();
                return new Response('success');
            } catch (\Exception $ex) {
                return new Response('error');
            }
        }
        return $this->render('SuporteTecnicoBundle:Categoria:editarCategoria.html.twig', array('form' => $form->createView(), 'categoria' => $categoria));
    }
    
    public function excluirAction(Categoria $categoria) {
        $this->excluirRecursivamente($categoria);
        return $this->redirect($this->generateUrl('suporte_categoria_pesquisar'));
    }
    
    private function excluirRecursivamente(Categoria $categoria) {
        $subCategorias = $categoria->getSubcategorias();
        foreach ($subCategorias as $c) {
            if(count($c->getSubcategorias())) {
                $this->excluirRecursivamente($c);
            }
            $c->setAtivo(false);
            $this->getDoctrine()->getManager()->merge($c);
        }
        $categoria->setAtivo(false);
        $this->getDoctrine()->getManager()->merge($categoria);
        $this->getDoctrine()->getManager()->flush();
    }
    
    public function gerarComboAction() {
        $post = $this->getRequest()->request;
        $repository = $this->getDoctrine()->getRepository('SuporteTecnicoBundle:Categoria');
        return $this->render('SuporteTecnicoBundle:Categoria:comboCategoria.html.twig', array(
            'categoria' => $post->getInt('categoria') ? $repository->find($post->getInt('categoria')) : null,
            'subcategoria' => $post->getInt('subcategoria') ? $repository->find($post->getInt('subcategoria')) : null,
            'categoriasRaiz' => $this->getDoctrine()->getRepository('SuporteTecnicoBundle:Categoria')->findBy(
                array('categoriaPai' => null, 'ativo' => true), array('nome' => 'ASC')
            )
        ));
    }
    
    public function getSubcategoriasAction(Categoria $categoria) {
        return new JsonResponse($categoria->getSubcategorias()->toArray());
    }
    
}
