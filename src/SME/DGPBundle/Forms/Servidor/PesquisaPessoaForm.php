<?php
namespace SME\DGPBundle\Forms\Servidor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use SME\DGPBundle\Forms\Servidor\PessoaFisicaMinified;
use SME\DGPBundle\Forms\Servidor\VinculosMinified;

class PesquisaPessoaForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pessoaFisicaMinified', new PessoaFisicaMinified())
            ->add('vinculos', new VinculosMinified())
            ->add('Buscar', 'submit',  array('attr' => array('class' => 'btn btn-primary')));
    }

    public function getName()
    {
        return 'PesquisaPessoaForm';
    }
}
