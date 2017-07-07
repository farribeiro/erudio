<?php
namespace SME\PublicacaoBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GrupoForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
            $builder
		->add('nome', 'text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Nome *'))
		->add('nomeExibicao', 'text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Nome de Exibição *'))
                ->add('ativo', 'hidden', array('attr' => array('class' => 'form-control', 'data' => 1), 'required' => false, 'label' => 'Nome de Exibição *'))
		->add('Salvar', 'submit',  array('attr' => array('class' => 'btn btn-primary btn-adjust')));
	}

	public function getName()
	{
            return 'GrupoForm';
	}
}
