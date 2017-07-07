<?php

namespace SME\DGPFormacaoBundle\Form\Encontro;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EncontroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('conteudo', 'text', array('label' => 'Conteúdo / Tema'))
            ->add('cargaHoraria', 'text', array('label' => 'Carga Horária'))
            ->add('dataRealizacao', 'date', array('label' => 'Data de Realização', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy'))
            ->add('btnSalvar', 'submit',  array('label' => 'Salvar'));
    }

    public function getName() {
        return 'EncontroType';
    }
}
