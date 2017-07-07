<?php
namespace SME\DGPBundle\Forms\Servidor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FormacaoForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
            $builder
		->add('nome', 'text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Nome do Curso *'))
		->add('cargaHoraria','text', array('attr' => array('class' => 'form-control'), 'label' => 'Carga Horária', 'required' => false))
		->add('dataConclusao', 'date', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Data de Conclusão', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'empty_data' => '01/01/1500'))
		->add('instituicao', 'text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Instituição *'))
		->add('grauFormacao', 'entity', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Grau da Formação *', 'class' => 'CommonsBundle:GrauFormacao', 'query_builder' => function($repository) { return $repository->createQueryBuilder('p'); }, 'property' => 'nome'))
		->add('btnSalvar', 'submit',  array('label' => 'Salvar'));
	}

	public function getName() {
            return 'FormacaoForm';
	}
}
