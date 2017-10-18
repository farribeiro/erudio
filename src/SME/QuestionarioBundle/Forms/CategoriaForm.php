<?php
namespace SME\QuestionarioBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoriaForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
            $builder
		->add('nome', 'text', array('attr' => array('class' => 'form-control'), 'required' => false))
                ->add('descricao', 'textarea', array('attr' => array('class' => 'form-control'), 'required' => false))
		->add('Salvar', 'submit',  array('attr' => array('class' => 'btn btn-primary')));
	}

	public function getName()
	{
            return 'CategoriaForm';
	}
}
