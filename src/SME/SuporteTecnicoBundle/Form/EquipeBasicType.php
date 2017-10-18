<?php

namespace SME\SuporteTecnicoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EquipeBasicType extends AbstractType {
    
    public function getName() {
        return 'cadastroEquipe';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('nome', 'text', array('label' => 'Nome:'))
            ->add('departamento', 'text', array('label' => 'Departamento:'))
            ->add('btnSalvar', 'submit', array('label' => 'Salvar'));
    }
    
}
