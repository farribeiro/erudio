<?php

namespace SME\SuporteTecnicoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnotacaoType extends AbstractType {
    
    
    public function getName() {
        return 'cadastroAnotacao';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->setAttribute('id', $this->getName())
            ->add('descricao', 'textarea', array('label' => 'Descrição:'))
            ->add('btnSalvar', 'submit', array('label' => 'Salvar'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setRequired(array('chamado'));
    }
    
}
