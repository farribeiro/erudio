<?php

namespace SME\SuporteTecnicoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SME\SuporteTecnicoBundle\Entity\Categoria;

class ChamadoBasicType extends AbstractType {
    
    public function getName() {
        return 'cadastroChamado';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->setAttribute('id', $this->getName())
            ->add('descricao', 'textarea', array('label' => 'Descrição:'))
            ->add('btnSalvar', 'submit', array('label' => 'Salvar'));
            
        $this->addCategorias($builder, $options);
        $this->addLocais($builder, $options);
            
        $formModifier = function (FormInterface $form, Categoria $categoria = null) {
            $form->add('tags', 'entity', array(
                'label' => 'Itens relacionados:',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'class' => 'SuporteTecnicoBundle:Tag',
                'property' => 'nome',
                'choices' => $categoria ? $categoria->getTags() : array()
            ));
        };
            
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $formModifier($event->getForm(), $data->getCategoria());
            }
        );

        $builder->get('categoria')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $categoria = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $categoria);
            }
        );
    }
    
    protected function addCategorias($builder, $options) {
        $builder->add('categoria', 'entity', array(
            'label' => 'Categoria:', 
            'class' => 'SuporteTecnicoBundle:Categoria',
            'property' => 'nomeHierarquico',
            'query_builder' => function($rep) { 
                return $rep->createQueryBuilder('c')->where('c.ativo = true')->orderBy('c.nome', 'ASC'); 
            }
        ));
    }
    
    protected function addLocais($builder, $options) {
        $builder->add('local', 'entity', array(
            'label' => 'Local:', 
            'class' => 'CommonsBundle:Entidade',
            'property' => 'nome',
            'choices' => $options['locais']
        ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setRequired(array('locais'));
    }

}
