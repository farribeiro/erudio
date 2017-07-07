<?php
namespace SME\PresencaBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use SME\PresencaBundle\Entity\Presenca;

class PresencaForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
            $presenca = new Presenca();
            $turmas = $presenca->getTurmas();
            $periodos = $presenca->getPeriodos();
            
            //->add('entidade', 'entity', array('attr' => array('class' => 'form-control'), 'label' => 'Unidade Escolar:*', 'required' => true, 'class' => 'CommonsBundle:Entidade', 
            //'query_builder' => function($repository) { 
            //return $repository->createQueryBuilder('e')->join('e.pessoaJuridica', 'p')->orderBy('p.nome'); }, 'property' => 'nome'))
            
            $builder
		->add('dataCadastro', 'text', array('attr' => array('class' => 'form-control datepickerSME'), 'required' => false, 'label' => 'Data:*'))
                ->add('turma','choice', array('attr' => array('class' => 'form-control'), 'choices' => $turmas, 'required' => false))
                ->add('turno','choice', array('attr' => array('class' => 'form-control'), 'choices' => $periodos, 'required' => false))
                ->add('qtdeAlunos', 'integer', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Quantidade de Alunos:*'))
		->add('Salvar', 'submit',  array('attr' => array('class' => 'btn btn-primary')));
	}

	public function getName()
	{
            return 'PresencaForm';
	}
}
