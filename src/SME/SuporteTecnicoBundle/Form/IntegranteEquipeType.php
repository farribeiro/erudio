<?php

namespace SME\SuporteTecnicoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class IntegranteEquipeType extends AbstractType {
    
    public function getName() {
        return 'cadastroIntegrante';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('nome', 'text', array('label' => 'Nome: '))
            ->add('btnSalvar', 'submit', array('label' => "Salvar"));
    }
    
}
