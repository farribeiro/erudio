<?php

namespace SME\SuporteTecnicoBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SME\SuporteTecnicoBundle\Form\ChamadoPesquisaBasicType;

class ChamadoPesquisaCompleteType extends ChamadoPesquisaBasicType {
    
    public function getName() {
        return 'pesquisaChamadoAdmin';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
        $builder->add('local', 'entity', array(
                'label' => 'Local:', 
                'class' => 'CommonsBundle:Entidade',
                'property' => 'nome',
                'required' => false,
                'empty_value' => 'Todos',
                'query_builder' => function($rep) { 
                    return $rep->createQueryBuilder('e')->join('e.pessoaJuridica', 'p')->orderBy('p.nome', 'ASC'); 
                }
            ))
            ->add('equipe', 'entity', array(
                'label' => 'Equipe:', 
                'class' => 'SuporteTecnicoBundle:Equipe',
                'property' => 'nome',
                'empty_value' => 'Todas',
                'query_builder' => function($rep) { 
                    return $rep->createQueryBuilder('e')->where('e.ativo = true')->orderBy('e.nome', 'ASC'); 
                }
            ))
            ->add('bairro', 'text', array('label' => 'Bairro:', 'required' => false));  
    }
    
    protected function addCategorias($builder, $options) {
        $builder->add('categoria', 'entity', array(
            'label' => 'Categoria:', 
            'class' => 'SuporteTecnicoBundle:Categoria',
            'property' => 'nomeHierarquico',
            'required' => false,
            'empty_value' => 'Todas',
            'choices' => $options['categorias']
        ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setRequired(array('categorias'));
    }
    
}
