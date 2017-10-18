<?php

namespace SME\DGPBundle\Forms\Servidor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SME\DGPBundle\Entity\TipoVinculo;

class VinculoSimpleType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('matricula', 'text', array('label' => 'Matrícula', 'required' => false))
            ->add('cargo', 'entity', array( 
                'label' => 'Cargo *',
                'class' => 'DGPBundle:Cargo',
                'property' => 'nome',
                'empty_value' => 'Selecione um cargo',
                'query_builder' => function($rep) { 
                    return $rep->createQueryBuilder('c')->orderBy('c.nome', 'ASC'); 
                }
                
            ))
            ->add('tipoVinculo', 'entity', array(
                'label' => 'Tipo de Vínculo *',
                'class' => 'DGPBundle:TipoVinculo',
                'property' => 'nome',
                'query_builder' => function($rep) { 
                    return $rep->createQueryBuilder('t')
                            ->where('t.id != :act')->setParameter('act', TipoVinculo::ACT)->orderBy('t.nome', 'DESC'); 
                }
            ))
            ->add('cargaHoraria', 'choice', array(
                'label' => 'Carga Horária *',
                'choices' => array(10 => '10', 20 => '20', 30 => '30', 40 => '40')
            ))
            ->add('dataInicio', 'date', array(
                'widget' => 'single_text',
                'label' => 'Data de Nomeação *',
                'format' => 'dd/MM/yyyy'
            ))
            ->add('vinculoOriginal', 'entity', array(
                'required' => false,
                'label' => 'Vínculo Original',
                'class' => 'DGPBundle:Vinculo',
                'property' => 'descricao',
                'empty_value' => 'Não possui vínculo efetivo',
                'query_builder' => function($rep) use ($options) { 
                    return $rep->createQueryBuilder('v')->join('v.servidor', 'p')->join('v.tipoVinculo', 't')
                            ->where('p.id = :pessoa')->andWhere('t.id = :efetivo')->andWhere('v.ativo = true')
                            ->setParameter('pessoa', $options['pessoa']->getId())
                            ->setParameter('efetivo', TipoVinculo::EFETIVO);
                }
            ));
    }

    public function getName() {
        return 'Vinculo_CadastroSimples';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setRequired(array('pessoa'));
    }
    
}
