<?php

namespace SME\SuporteTecnicoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TagType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->setAttribute('id', $this->getName())
            ->add('nome', 'text', array('label' => 'Nome:'))
            ->add('categoria', 'entity', array(
                'label' => 'Categoria:', 
                'class' => 'SuporteTecnicoBundle:Categoria',
                'property' => 'nomeHierarquico',
                'query_builder' => function($rep) { 
                    return $rep->createQueryBuilder('c')->where('c.ativo = true')->orderBy('c.nome', 'ASC'); 
                }
            ))
            ->add('btnSalvar', 'submit', array('label' => 'Salvar'));
            
        $formModifier = function (FormInterface $form, Inscricao $inscricaoVinculacao = null) {
                $form->add('convocacaoVinculacao', 'entity', array(
                    'required' => false,
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Edital de Convocação',
                    'class' => 'DGPContratacaoBundle:Convocacao',
                    'property' => 'nome',
                    'empty_value' => 'Não se aplica (Comissionado / Efetivado antes de 2012)',
                    'query_builder' => function($rep) use ($inscricaoVinculacao) {
                        return null === $inscricaoVinculacao
                            ? $rep->createQueryBuilder('c')->where('c.id < 0')
                            : $rep->createQueryBuilder('c')->join('c.processo', 'p')->where('p.id = :processo')
                                  ->setParameter('processo', $inscricaoVinculacao->getProcesso()->getId())->orderBy('c.dataRealizacao', 'DESC');
                    }
                ));
                
            };
            
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    $data = $event->getData();
                    $formModifier($event->getForm(), $data->getInscricaoVinculacao());
                }
            );
                
            $builder->get('inscricaoVinculacao')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $inscricao = $event->getForm()->getData();
                    $formModifier($event->getForm()->getParent(), $inscricao);
                }
            );
    }
    
    public function getName() {
        return 'cadastroTag';
    }
}
