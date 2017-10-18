<?php
namespace SME\PublicacaoBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoriaForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
            $builder
		->add('nome', 'text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Nome *'))
		->add('nomeExibicao', 'text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Nome de Exibição *'))
                ->add('grupo', 'entity', array('attr' => array('class' => 'form-control', 'readonly' => 'readlonly'), 'label' => 'Grupo', 'required' => false, 'class' => 'PublicacaoBundle:Grupo', 'query_builder' => function($repository) { return $repository->createQueryBuilder('p')->where('p.ativo = 1'); }, 'property' => 'nomeExibicao'))
                ->add('categoria', 'entity', array('attr' => array('class' => 'form-control', 'readonly' => 'readlonly'), 'label' => 'Categoria', 'required' => false, 'class' => 'PublicacaoBundle:Categoria', 'query_builder' => function($repository) { return $repository->createQueryBuilder('p'); }, 'property' => 'nomeExibicao'))
                ->add('ativo', 'hidden', array('attr' => array('class' => 'form-control', 'data' => 1), 'required' => false))
		->add('Salvar', 'submit',  array('attr' => array('class' => 'btn btn-primary')));
	}

	public function getName()
	{
            return 'CategoriaForm';
	}
}
