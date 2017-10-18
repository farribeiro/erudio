<?php

namespace SME\DGPPromocaoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PromocaoType extends AbstractType  {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('ano', 'number', array('label' => 'Ano *'))
            ->add('nivelAnterior', 'text', array('label' => 'Nível Anterior'))
            ->add('nivelAtual', 'text', array('label' => 'Nível Atual'))
            ->add('ano', 'number', array('label' => 'Ano *'))
            ->add('dataInicio', 'date', array(
                'label' => 'Data de Início', 
                'widget' => 'single_text', 
                'format' => 'dd/MM/yyyy', 
                'required' => false
            ))
            ->add('resposta', 'choice', array(
                'label' => 'Observações', 
                'required' => false,
                'choices' => array(
                    'Encaminhado para Secretaria de Administração' => 'Encaminhado para Secretaria de Administração',
                    'Descumprimento do interstício' => 'Descumprimento do interstício',
                    'Servidor fora da área de atuação' => 'Servidor fora da área de atuação',
                    'Em estágio probatório' => 'Em estágio probatório',
                    'Carga horária de cursos de formação insuficiente' => 'Carga horária de cursos de formação insuficiente',
                    'Não obtenção da aprovação mínima necessária na avaliação de desempenho' => 'Não obtenção da aprovação mínima necessária na avaliação de desempenho',
                    'Documento incompleto ou contendo erros' => 'Documento incompleto ou contendo erros',
                    'Pedido cancelado à pedido do servidor' => 'Pedido cancelado à pedido do servidor'
                )
            ))
            ->add('observacao', 'textarea', array('label' => 'Anotações', 'required' => false))
            ->add('status', 'entity', array(
                'label' => 'Status *', 
                'class' => 'DGPPromocaoBundle:Status',
                'property' => 'nome'
            ))
            ->add('btnSalvar', 'submit',  array('label' => 'Salvar'));
    }

    public function getName() {
        return 'PromocaoForm';
    }
    
}
