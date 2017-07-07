<?php
namespace SME\EstagioBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PedidoUsuarioForm extends AbstractType
{
        public function getDefaultOptions(array $options)
        {
            $options = parent::getDefaultOptions($options);
            $options['csrf_protection'] = false;

            return $options;
        }
    
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
            $instituicoes = array('UNIVALI','UNOPAR','UNIFIL','UNIDERP','UNIASSELVI','UDESC','SOCIESC','Universidade do Contestado Func','Nilton Kucker','SINERGIA','IFES','UNINTER','AVANTIS','SENEC - EAD','UNICESUMAR', 'IFC');
            
            $builder
                ->add('nome', 'text', array('attr' => array('class' => 'form-control', 'placeholder' => 'Nome'), 'required' => false))
                ->add('cpf', 'text', array('attr' => array('maxlength' => 11,'class' => 'form-control', 'placeholder' => 'CPF'), 'required' => false))
                ->add('dataNascimento', 'text', array('attr' => array('class' => 'form-control', 'placeholder' => 'Data de Nascimento'), 'required' => false))
                ->add('email', 'text', array('attr' => array('class' => 'form-control', 'placeholder' => 'Email'), 'required' => false))
                ->add('emailSupervisor', 'text', array('attr' => array('class' => 'form-control', 'placeholder' => 'Email do Supervisor'), 'required' => false))
                ->add('curso', 'text', array('attr' => array('class' => 'form-control', 'placeholder' => 'Curso'), 'required' => false))
                ->add('instituicao','choice', array('choices' => $instituicoes, 'attr' => array('class' => 'form-control'), 'label' => 'Instituição', 'required' => false))
		->add('Salvar', 'submit',  array('attr' => array('class' => 'btn btn-primary')));
	}

	public function getName()
	{
            return 'PedidoUsuarioForm';
	}
}
