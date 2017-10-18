<?php

namespace SME\DGPBundle\Forms\Servidor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PessoaFisicaMinified extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('nome', 'text', array('attr' => array('class' => 'form-control'), 'required' => false))
            ->add('email', 'text', array('attr' => array('class' => 'form-control'), 'required' => false))
            ->add('cpfCnpj', 'text', array('attr' => array('class' => 'form-control'), 'label' => 'CPF', 'required' => false));
    }

    public function getName() {
        return 'PessoaFisicaMinified';
    }

}
