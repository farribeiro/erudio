<?php

namespace SME\SuporteTecnicoBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use SME\SuporteTecnicoBundle\Form\EquipeBasicType;
use SME\CommonsBundle\Entity\PessoaFisica;

class EquipeCompleteType extends EquipeBasicType {
    
    public function getName() {
        return 'gerenciaEquipe';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
    }
    
}
