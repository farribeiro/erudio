<?php
namespace SME\DGPBundle\Forms\Servidor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class VinculosMinified extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
            $builder
                ->add('cargo','text', array('attr' => array('class' => 'form-control'), 'required' => false))
                ->add('tipoVinculo', 'choice', array('attr' => array('class' => 'form-control'), 'label' => 'Tipo de Vínculo', 'choices' => array('1' => 'Efetivo', '2' => 'ACT', '3' => 'Comissionado'), 'required' => false));
	}

	public function getName() {
            return 'VinculoMinifiedForm';
	}
}
