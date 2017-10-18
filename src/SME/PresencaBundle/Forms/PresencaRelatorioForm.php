<?php
namespace SME\PresencaBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use SME\PresencaBundle\Entity\Presenca;

class PresencaRelatorioForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
            $presenca = new Presenca();
            $turmas = $presenca->getTurmas();
            $periodos = $presenca->getPeriodos();
            
            $builder
                ->add('turma','choice', array('attr' => array('class' => 'form-control'), 'choices' => $turmas, 'required' => false))
                ->add('turno','choice', array('attr' => array('class' => 'form-control'), 'choices' => $periodos, 'required' => false))
                ->add('dataInicial', 'text', array('attr' => array('class' => 'form-control datepickerSME'), 'required' => false, 'label' => 'Data Inicial:'))
                ->add('dataFinal', 'text', array('attr' => array('class' => 'form-control datepickerSME'), 'required' => false, 'label' => 'Data Final:'))
		->add('Buscar', 'submit',  array('attr' => array('class' => 'btn btn-primary')));
	}

	public function getName()
	{
            return 'PresencaRelatorioForm';
	}
}
