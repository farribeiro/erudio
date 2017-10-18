<?php
namespace SME\DGPBundle\Forms\Servidor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use SME\DGPBundle\Forms\Servidor\TelefoneForm;
use SME\DGPBundle\Forms\Servidor\PessoaFisica;
use SME\DGPBundle\Forms\Servidor\EnderecoForm;

class EditarPessoa extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options) {
            $builder
		->add('pessoaFisica', new PessoaFisica())
		->add('telefones', new TelefoneForm())
		->add('endereco', new EnderecoForm())
		->add('Editar', 'submit',  array('attr' => array('class' => 'btn btn-primary')));
	}

	public function getName() {
            return 'EditarPessoa';
	}
}
