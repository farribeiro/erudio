<?php

namespace SME\SuporteTecnicoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AtividadeType extends AbstractType {
    
    
    public function getName() {
        return 'cadastroAtividade';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->setAttribute('id', $this->getName())
            ->add('inicio', 'datetime', array(
                'widget' => 'single_text',
                'label' => 'Entrada:',
                'format' => 'dd/MM/yyyy HH:mm'
            ))
            ->add('termino', 'datetime', array(
                'widget' => 'single_text',
                'label' => 'Saída:',
                'format' => 'dd/MM/yyyy HH:mm'
            ))
            ->add('descricao', 'textarea', array('label' => 'Descrição:'))
            ->add('tecnicos', 'entity', array(
                'label' => 'Atendentes:',
                'class' => 'CommonsBundle:PessoaFisica',
                'property'     => 'nome',
                'multiple'     => true,
                'choices' => $options['chamado']->getCategoria()->getEquipe()->getIntegrantes() 
            ))
            ->add('btnSalvar', 'submit', array('label' => 'Salvar'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setRequired(array('chamado'));
    }
    
}
