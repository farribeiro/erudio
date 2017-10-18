<?php

namespace SME\SuporteTecnicoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChamadoPesquisaBasicType extends AbstractType {
    
    const ORDENACAO_PRIORIDADE = 'prioridade';
    const ORDENACAO_DATA_CADASTRO_ASC = 'dataCadastro:ASC';
    const ORDENACAO_DATA_CADASTRO_DESC = 'dataCadastro:DESC';
    
    public function getName() {
        return 'pesquisaChamado';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->setAttribute('id', $this->getName())
            ->add('pagina', 'hidden', array('data' => 0))
            ->add('status', 'entity', array(
                'label' => 'Status:', 
                'class' => 'SuporteTecnicoBundle:Status',
                'property' => 'nome',
                'required' => false,
                'empty_value' => 'Todos'
            ))
            ->add('numero', 'text', array('label' => 'Número:', 'required' => false))
            ->add('periodoCadastroInicio', 'text', array('required' => false))
            ->add('periodoCadastroFim', 'text', array('required' => false))
            ->add('ordenacao', 'choice', array(
                'label' => 'Ordenação:',
                'choices' => array(
                    self::ORDENACAO_PRIORIDADE => 'Prioridade', 
                    self::ORDENACAO_DATA_CADASTRO_ASC => 'Mais antigos',
                    self::ORDENACAO_DATA_CADASTRO_DESC => 'Mais recentes'
                )
            )) 
            ->add('aberto', 'choice', array(
                'label' => 'Em aberto:',
                'choices' => array(1 => 'Sim', 0 => 'Não', '' => 'Todos')
            ));
        $this->addCategorias($builder, $options);
    }
    
    protected function addCategorias($builder, $options) {
        $builder->add('categoria', 'entity', array(
            'label' => 'Categoria:', 
            'class' => 'SuporteTecnicoBundle:Categoria',
            'property' => 'nome',
            'required' => false,
            'empty_value' => 'Todas',
            'query_builder' => function($rep) { 
                return $rep->createQueryBuilder('c')->where('c.ativo = true AND c.categoriaPai IS NULL')->orderBy('c.nome', 'ASC'); 
            }
        ));
    }
    
}
