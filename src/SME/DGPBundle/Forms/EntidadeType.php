<?php

namespace SME\DGPBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EntidadeType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome', 'text', array('label' => 'Nome *'))
            ->add('dataNascimento', 'date', array('label' => 'Data de Fundação', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'required' => false))
            ->add('email', 'text', array('label' => 'E-mail', 'required' => false))
            ->add('cpfCnpj', 'text', array('label' => 'CNPJ', 'required' => false))
            ->add('codigoInep', 'text', array('label' => 'Código da Escola (INEP)', 'required' => false))
            ->add('classe', 'entity', array(
                'label' => 'Classe *',
                'class' => 'CommonsBundle:ClasseEntidade',
                'property' => 'nome'
            ))
            ->add('entidadePai', 'entity', array(
                'label' => 'Entidade Pai',
                'required' => false,
                'class' => 'CommonsBundle:Entidade',
                'property' => 'nome',
                'empty_value' => 'Não possui'
            ))
            ->add('btnSalvar', 'submit',  array('label' => 'Salvar'));
    }

    public function getName() {
        return 'EntidadeForm';
    }
    
}
