<?php

namespace SME\DGPContratacaoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ConvocacaoType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->setAttribute('id', $this->getName())
            ->add('nome', 'text', array('label' => 'Entrada:'))
            ->add('numeroEdital', 'number', array('label' => 'Saída:'))
            ->add('anoEdital', 'number', array('label' => 'Saída:'))
            ->add('dataRealizacao', 'date', array(
                'widget' => 'single_text',
                'label' => 'Data de Realização:',
                'format' => 'dd/MM/yyyy'
            ))
            ->add('btnSalvar', 'submit', array('label' => '+'));
    }
    
    public function getName() {
        return 'ConvocacaoForm';
    }

}
