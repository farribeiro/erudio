<?php
namespace SME\DGPFormacaoBundle\Form\Formacao;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PesquisaFormacaoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('nome', 'text', array('label' => 'Nome', 'required' => false))
            ->add('periodoConclusaoInicio', 'text', array('label' => 'Data de conclusão entre:', 'required' => false))
            ->add('periodoConclusaoFim', 'text', array('label' => 'E:', 'required' => false))
            ->add('btnPesquisar', 'submit',  array('label' => 'Buscar'));
    }

    public function getName() {
        return 'PesquisaFormacaoType';
    }
    
}
