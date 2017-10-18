<?php

namespace SME\SuporteTecnicoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoriaType extends AbstractType {
    
    public function getName() {
        return 'cadastroCategoria';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('nome', 'text', array('label' => 'Nome:', 'attr' => array('placeholder' => 'Digite o nome da categoria', 'class' => 'form-control')))
            ->add('categoriaPai', 'entity', array('required' => false, 'label' => 'Categoria Pai: ', 'attr' => array('class' => 'form-control hidden'), 'class' => 'SuporteTecnicoBundle:Categoria', 'query_builder' => function($repository) { return $repository->createQueryBuilder('p'); }, 'property' => 'nome', 'empty_data' => null))
            ->add('equipe', 'entity', array('label' => 'Equipe: ', 'attr' => array('class' => 'form-control'), 'class' => 'SuporteTecnicoBundle:Equipe', 'query_builder' => function($repository) { return $repository->createQueryBuilder('p'); }, 'property' => 'nome'))
            ->add('prioridade', 'entity', array('label' => 'Prioridade: ', 'attr' => array('class' => 'form-control'), 'class' => 'SuporteTecnicoBundle:Prioridade', 'query_builder' => function($repository) { return $repository->createQueryBuilder('p'); }, 'property' => 'nome'))
            ->add('btnSalvar', 'submit', array('label' => 'Salvar', 'attr' => array('class' => 'btn btn-primary')));
    }    
    
}
