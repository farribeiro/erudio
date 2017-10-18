<?php
namespace SME\QuestionarioBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PerguntaForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
            $builder
		->add('pergunta', 'text', array('attr' => array('class' => 'form-control'), 'required' => false))
                ->add('tem_resposta', 'checkbox', array('required' => false, 'label' => 'Possui resposta diferenciada?(Além de sim e não.)'))
                ->add('multi_resposta', 'checkbox', array('required' => false, 'label' => 'Possui mais de uma resposta selecionável?'))
                ->add('respostas', 'hidden', array('required' => false))
		->add('Salvar', 'submit',  array('attr' => array('class' => 'btn btn-primary')));
	}

	public function getName()
	{
            return 'PerguntaForm';
	}
}
