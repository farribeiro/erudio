<?php

namespace SME\DGPPromocaoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PromocaoHorizontalServidorType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        for($i = 1; $i <= 8; $i++) {
            $builder
                ->add('formacaoExterna' . $i, 'checkbox', array('required' => false))
                ->add('formacaoExterna' . $i . '_nome', 'text', array('required' => false))
                ->add('formacaoExterna' . $i . '_cargaHoraria', 'number', array(
                    'required' => false, 
                    'invalid_message' => 'Os campos de <strong>CARGA HORÁRIA</strong> só aceitam números'
                ))
                ->add('formacaoExterna' . $i . '_dataConclusao', 'date', array('required' => false, 'widget' => 'single_text', 'format' => 'dd/MM/yyyy'))
                ->add('formacaoExterna' . $i . '_instituicao', 'text', array('required' => false));
        }
        $formacoes = $options['formacoesInternas'];
        $builder
            ->add('formacoesInternas', 'entity', array(
                'required' => false,
                'label' => ' ',
                'multiple' => true,
                'expanded' => true,
                'class' => 'DGPFormacaoBundle:Matricula',
                'property' => 'formacao.nomeCertificado',
                'choices' => $formacoes
            ))
            ->add('matricula', 'text', array('label' => 'Matrícula *', 'mapped' => false, 'required' => true))
            ->add('btnSalvar', 'submit',  array('label' => 'Enviar'));
    }
    
    public function getName() {
        return 'PromocaoHorizontalServidorForm';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setRequired(array('formacoesInternas'));
    }
    
}
