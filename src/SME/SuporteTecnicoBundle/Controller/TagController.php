<?php

namespace SME\SuporteTecnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\SuporteTecnicoBundle\Entity\Categoria;
use SME\SuporteTecnicoBundle\Entity\Tag;

class TagController extends Controller {
    
    public function cadastrarAction(Categoria $categoria) {
        $tag = new Tag();
        $tag->setCategoria($categoria);
        $form = $this->createFormBuilder()
            ->add('nome', 'text', array('label' => 'Tag:'))
            ->add('prioridade', 'entity', array(
                'label' => 'Prioridade:', 
                'class' => 'SuporteTecnicoBundle:Prioridade',
                'property' => 'nome'
            ))
            ->add('btnSalvar', 'submit', array('label' => 'Incluir'))
            ->getForm();
        $form->handleRequest($this->getRequest());
        if($form->isValid()) {
            $tag->setNome($form->getData()['nome']);
            $tag->setPrioridade($form->getData()['prioridade']);
            $this->getDoctrine()->getManager()->persist($tag);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('SuporteTecnicoBundle:Tag:formCadastro.html.twig', array(
            'categoria' => $categoria,
            'tags' => $categoria->getTags(),
            'form' => $form->createView()
        ));
    }
    
    public function excluirAction(Categoria $categoria, Tag $tag) {
        try {
            $tag->setAtivo(false);
            $this->getDoctrine()->getManager()->merge($tag);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('message', 'Tag excluída com sucesso');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Não foi possível excluir a tag. Erro: ' . $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('suporte_categoria_tag_cadastrar', array(
            'categoria' => $categoria->getId()
        )));
    }
    
}
