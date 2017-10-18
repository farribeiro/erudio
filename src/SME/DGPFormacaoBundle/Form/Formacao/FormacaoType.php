<?php
namespace SME\DGPFormacaoBundle\Form\Formacao;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FormacaoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('nome', 'text', array('label' => 'Nome de Identificação *'))
            ->add('nomeCertificado', 'text', array('label' => 'Nome no Certificado *'))
            ->add('publicoAlvo', 'text', array('label' => 'Público-alvo *'))
            ->add('cargaHoraria', 'text', array('label' => 'Carga Horária *'))
            ->add('limiteVagas', 'text', array('required' => false, 'label' => 'Limite de Vagas'))
            ->add('dataInicioInscricao', 'datetime', array('required' => false, 'label' => 'Início das Inscrições *', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy HH:mm', 'empty_data' => ''))
            ->add('dataTerminoInscricao', 'datetime', array('required' => false, 'label' => 'Término das Inscrições *', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy HH:mm', 'empty_data' => ''))
            ->add('dataInicioFormacao', 'datetime', array('required' => false, 'label' => 'Início da Formação *', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'empty_data' => ''))
            ->add('dataTerminoFormacao', 'datetime', array('required' => false, 'label' => 'Término da Formação *', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'empty_data' => ''))
            ->add('descricao', 'textarea', array('required' => false, 'label' => 'Descrição'))
            ->add('aberto', 'choice', array(
                'choices' => array(0 => 'Bloqueado', 1 =>'Aberto'),
                'label' => 'Público Externo'
            ))
            ->add('btnSalvar', 'submit',  array('label' => 'Salvar'));
    }

    public function getName() {
        return 'FormacaoType';
    }
}
