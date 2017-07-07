<?php

namespace SME\SuporteTecnicoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoriaBasic extends AbstractType {
    
    public function getName() {
        return 'editarCategoria';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('nome', 'text', array('label' => 'Nome:', 'attr' => array('placeholder' => 'Digite o nome da categoria', 'class' => 'form-control')))
            ->add('prioridade', 'entity', array('label' => 'Prioridade: ', 'attr' => array('class' => 'form-control'), 'class' => 'SuporteTecnicoBundle:Prioridade', 'query_builder' => function($repository) { return $repository->createQueryBuilder('p'); }, 'property' => 'nome'))
            ->add('btnSalvar', 'submit', array('label' => 'Salvar', 'attr' => array('class' => 'btn btn-primary')));
    }    
    
}
