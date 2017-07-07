<?php

namespace SME\DGPPromocaoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use SME\DGPPromocaoBundle\Entity\Promocao;

class CIGeralType extends AbstractType  {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('ano', 'number', array('label' => 'Ano *'))
            ->add('numero', 'number', array('label' => 'Número *'))
            ->add('tipoPromocao', 'choice', array(
                'label' => 'Tipo de Promoção *', 
                'choices' => array(
                    Promocao::TIPO_PROMOCAO_HORIZONTAL => 'Horizontal',
                    Promocao::TIPO_PROMOCAO_VERTICAL => 'Vertical'
                )
            ))
            ->add('btnSalvar', 'submit',  array('label' => 'Salvar'));
    }

    public function getName() {
        return 'CIGeralForm';
    }
    
}
