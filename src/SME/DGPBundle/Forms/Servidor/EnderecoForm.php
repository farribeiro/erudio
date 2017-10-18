<?php
namespace SME\DGPBundle\Forms\Servidor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use SME\CommonsBundle\Entity\Estado;

class EnderecoForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
            $builder
		->add('logradouro', 'text', array('attr' => array('class' => 'form-control'), 'required' => false))
		->add('numero', 'text', array('attr' => array('class' => 'form-control'), 'required' => false))
		->add('complemento', 'text', array('attr' => array('class' => 'form-control'), 'required' => false))
		->add('bairro', 'text', array('attr' => array('class' => 'form-control'), 'required' => false))
		->add('cep', 'text', array('attr' => array('class' => 'form-control'), 'label' => 'CEP', 'required' => false))
		->add('cidade', 'entity', array('attr' => array('class' => 'form-control'), 'class' => 'CommonsBundle:Cidade', 'query_builder' => function($repository) { return $repository->createQueryBuilder('c')->where('c.estado = :e')->setParameter('e', Estado::SC); }, 'property' => 'nome', 'required' => false))
		->add('latitude', 'text', array('attr' => array('class' => 'form-control'), 'required' => false))
                ->add('longitude', 'text', array('attr' => array('class' => 'form-control'), 'required' => false))
                ->add('btnSalvar', 'submit',  array('label' => 'Salvar', 'attr' => array('class' => 'btn btn-primary')));
	}

	public function getName() {
            return 'EnderecoForm';
	}
}
