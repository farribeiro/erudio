<?php
namespace SME\DGPBundle\Forms\Servidor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TelefoneForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('numero', 'text', array('attr' => array('class' => 'form-control'), 'label' => 'Número', 'required' => false))
            ->add('descricao','choice', array('attr' => array('class' => 'form-control'), 'label' => 'Descrição', 'choices' => array('RESIDENCIAL' => 'Residencial', 'CELULAR' => 'Celular'), 'required' => false))
            ->add('falarCom', 'text', array('attr' => array('class' => 'form-control'), 'required' => false))
            ->add('btnSalvar', 'submit',  array('label' => 'Incluir'));
    }

    public function getName()
    {
        return 'TelefoneForm';
    }
}
