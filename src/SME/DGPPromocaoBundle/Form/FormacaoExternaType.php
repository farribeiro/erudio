<?php

namespace SME\DGPPromocaoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FormacaoExternaType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options) {
            $builder
		->add('nome', 'text', array('label' => 'Nome do Curso *', 'required' => false))
		->add('cargaHoraria','text', array('label' => 'Carga Horária *', 'required' => false))
		->add('dataConclusao', 'date', array('label' => 'Data de Conclusão *', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'empty_data' => '01/01/1970', 'required' => false))
		->add('instituicao', 'text', array('label' => 'Instituição', 'required' => false))
		->add('btnSalvar', 'submit',  array('label' => 'Salvar'));
	}

	public function getName() {
            return 'FormacaoForm';
	}
}
