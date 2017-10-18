<?php

namespace SME\DGPPermutaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SME\DGPBundle\Entity\TipoVinculo;

class IntencaoType extends AbstractType {
    
    
    public function getName() {
        return 'cadastroIntencao';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->setAttribute('id', $this->getName())
            ->add('cargaHoraria', 'choice', array(
                'label' => 'Carga Horária:', 
                'choices' => array(10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 35 => 35, 40 => 40)
            ))
            ->add('vinculo', 'entity', array(
                'label' => 'Vínculo:', 
                'class' => 'DGPBundle:Vinculo',
                'property' => 'descricao',
                'query_builder' => function($rep) use ($options) { 
                    return $rep->createQueryBuilder('v')->join('v.servidor', 's')->join('v.tipoVinculo', 't')
                               ->where('v.ativo = true AND t.id = :efetivo AND s.id = :pessoa')
                               ->setParameter('efetivo', TipoVinculo::EFETIVO)
                               ->setParameter('pessoa', $options['pessoa']->getId()); 
                }
            ))
            ->add('lotacao', 'entity', array(
                'label' => 'Lotação:', 
                'class' => 'CommonsBundle:Entidade',
                'property' => 'nome',
                'query_builder' => function($rep) { 
                    return $rep->createQueryBuilder('e')->join('e.pessoaJuridica', 'p')->orderBy('p.nome', 'ASC'); 
                }
            ))
            ->add('bairrosDeInteresse', 'text', array('label' => 'Bairros de Interesse:'))
            ->add('btnSalvar', 'submit', array('label' => 'Salvar'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setRequired(array('pessoa'));
    }
    
}
