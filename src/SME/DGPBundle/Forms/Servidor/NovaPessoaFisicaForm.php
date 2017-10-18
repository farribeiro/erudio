<?php
namespace SME\DGPBundle\Forms\Servidor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class NovaPessoaFisicaForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
            $builder
		->add('id', 'hidden', array('required' => false))
		->add('nome', 'text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Nome *'))
		->add('dataNascimento', 'date', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Data de Nascimento *', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'empty_data'=>'01/01/2014'))
		->add('email', 'text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Email *'))
		->add('cpfCnpj', 'text', array('attr' => array('class' => 'form-control'), 'label' => 'CPF *', 'required' => false))
		->add('numeroRg', 'text', array('attr' => array('class' => 'form-control'), 'label' => 'RG ', 'required' => false))
		->add('estadoCivil', 'entity', array('attr' => array('class' => 'form-control'), 'label' => 'Estado Civil', 'required' => false, 'class' => 'CommonsBundle:EstadoCivil', 'query_builder' => function($repository) { return $repository->createQueryBuilder('p'); }, 'property' => 'nome'))
		->add('Salvar', 'submit',  array('attr' => array('class' => 'btn btn-primary')));
	}

	public function getName() {
            return 'NovaPessoaFisicaForm';
	}
}
