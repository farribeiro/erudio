<?php

namespace SME\SuporteTecnicoBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SME\SuporteTecnicoBundle\Form\ChamadoBasicType;

class ChamadoCompleteType extends ChamadoBasicType {
    
    public function getName() {
        return 'gerenciaChamado';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
        $builder->setAttribute('id', $this->getName())
                ->add('solucao', 'textarea', array('label' => 'Solução:', 'required' => ''))
                ->add('status', 'entity', array(
                    'label' => 'Status:', 
                    'class' => 'SuporteTecnicoBundle:Status',
                    'property' => 'nome'
                ))
                ->add('prioridade', 'entity', array(
                    'label' => 'Prioridade:', 
                    'class' => 'SuporteTecnicoBundle:Prioridade',
                    'property' => 'nome'
                ));
    }
    
    protected function addCategorias($builder, $options) {
        $builder->add('categoria', 'entity', array(
            'label' => 'Categoria:', 
            'class' => 'SuporteTecnicoBundle:Categoria',
            'property' => 'nomeHierarquico',
            'choices' => $options['categorias']
        ));
    }
    
    protected function addLocais($builder, $options) {
        
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setRequired(array('categorias'));
    }
    
}
