<?php

namespace SME\DGPPromocaoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PromocaoVerticalServidorType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('nomeCurso', 'text', array('label' => 'Curso *'))
            ->add('cargaHorariaCurso', 'number', array('label' => 'Carga Horária *'))
            ->add('dataConclusaoCurso', 'date', array('label' => 'Carga Horária *', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy'))
            ->add('instituicaoCurso', 'text', array('label' => 'Instituição *'))
            ->add('grauCurso', 'entity', array(
                'label' => 'Tipo *', 
                'class' => 'CommonsBundle:GrauFormacao',
                'property' => 'nome'
            ))
            ->add('matricula', 'text', array('label' => 'Matrícula *', 'mapped' => false, 'required' => true))
            ->add('btnSalvar', 'submit',  array('label' => 'Enviar'));
    }

    public function getName() {
        return 'PromocaoVerticalServidorForm';
    }
    
}