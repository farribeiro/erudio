<?php

namespace SME\DGPBundle\Forms\Servidor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AlocacaoForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{	
            $ch = array();
            for($i = 1; $i <= 40; $i++) {
                $ch[$i] = $i . ' horas';
            }
            $builder
		->add('localTrabalho','entity', array('attr' => array('class' => 'form-control'), 'label' => 'Local de Trabalho', 'class' => 'CommonsBundle:Entidade', 'query_builder' => function($repository) { 
                    return $repository->createQueryBuilder('p')->join('p.pessoaJuridica', 'n')->orderBy('n.nome','ASC');
                }, 'property' => 'pessoaJuridica.nome', 'required' => true))
                ->add('localLotacao','entity', array('attr' => array('class' => 'form-control'), 'label' => 'Lotação', 'class' => 'CommonsBundle:Entidade', 'query_builder' => function($repository) { 
                    return $repository->createQueryBuilder('p')->join('p.pessoaJuridica', 'n')->orderBy('n.nome','ASC'); 
                }, 'property' => 'pessoaJuridica.nome', 'empty_value' => 'Não possui', 'required' => false)) 
		->add('cargaHoraria','choice', array('attr' => array('class' => 'form-control'), 'label' => 'Carga Horária', 'choices' => $ch, 'required' => true))
		->add('funcaoAtual', 'text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Função Atual'))
                ->add('periodo','entity', array('attr' => array('class' => 'form-control'), 'label' => 'Período', 'class' => 'CommonsBundle:PeriodoDia', 'property' => 'nome', 'required' => false))
                ->add('original','choice', array('attr' => array('class' => 'form-control'), 'label' => 'Alocação de Admissão (Original)', 'choices' => array(0 => 'Não', 1 => 'Sim'), 'required' => true))
                ->add('motivoEncaminhamento', 'textarea', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Motivo de encaminhamento')) 
                ->add('observacao', 'text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Turma / Observações'))  
		->add('btnSalvar', 'submit',  array('label' => 'Salvar', 'attr' => array('class' => 'btn btn-primary')));
	}

	public function getName()
	{
            return 'AlocacaoForm';
	} 
}
