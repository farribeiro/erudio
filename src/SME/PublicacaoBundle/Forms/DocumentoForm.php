<?php
namespace SME\PublicacaoBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DocumentoForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
            $builder
                ->add('id', 'hidden', array('required' => false))
		->add('arquivo', 'file', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Arquivo *'))
		->add('nomeExibicao', 'text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Nome de Exibição *'))
                ->add('publicacao', 'text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Data de Publicação *'))
                ->add('visibilidade', 'entity', array('attr' => array('class' => 'form-control'), 'label' => 'Visibilidade *', 'required' => false, 'class' => 'PublicacaoBundle:Visibilidade', 'query_builder' => function($repository) { return $repository->createQueryBuilder('p'); }, 'property' => 'nomeExibicao'))
		->add('Salvar', 'submit',  array('attr' => array('class' => 'btn btn-primary btn-block')));
	}

	public function getName()
	{
            return 'CategoriaForm';
	}
}
