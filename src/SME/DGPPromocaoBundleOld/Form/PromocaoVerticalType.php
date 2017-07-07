<?php

namespace SME\DGPPromocaoBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use SME\DGPPromocaoBundle\Form\PromocaoType;

class PromocaoVerticalType extends PromocaoType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
        $builder
            ->add('nomeCurso', 'text', array('label' => 'Curso *'))
            ->add('cargaHorariaCurso', 'number', array('label' => 'Carga Horária *'))
            ->add('dataConclusaoCurso', 'date', array('label' => 'Carga Horária *', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy'))
            ->add('instituicaoCurso', 'text', array('label' => 'Instituição *'))
            ->add('grauCurso', 'entity', array(
                'label' => 'Tipo *', 
                'class' => 'CommonsBundle:GrauFormacao',
                'property' => 'nome'
            ));
    }

    public function getName() {
        return 'PromocaoVerticalForm';
    }
    
}
