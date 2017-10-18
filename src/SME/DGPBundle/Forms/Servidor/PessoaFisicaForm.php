<?php
namespace SME\DGPBundle\Forms\Servidor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PessoaFisicaForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden', array('required' => false))
            ->add('nome', 'text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Nome *'))
            ->add('dataNascimento', 'date', array('required' => false, 'label' => 'Data de Nascimento *', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy'))
            ->add('email', 'text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Email *'))
            ->add('cpfCnpj', 'text', array('attr' => array('class' => 'form-control'), 'label' => 'CPF *', 'required' => false))
            ->add('numeroRg', 'text', array('attr' => array('class' => 'form-control'), 'label' => 'RG ', 'required' => false))
            ->add('orgaoExpedidorRg', 'text', array('attr' => array('class' => 'form-control'), 'label' => 'Órgão Expedidor RG', 'required' => false))
            ->add('dataExpedicaoRg', 'date', array('label' => 'Data de Expedição RG', 'required' => false, 'widget' => 'single_text', 'format' => 'dd/MM/yyyy'))
            ->add('numeroTituloEleitor', 'text', array('attr' => array('class' => 'form-control'), 'label' => 'Título de Eleitor', 'required' => false))
            ->add('zonaTituloEleitor', 'text', array('attr' => array('class' => 'form-control'), 'label' => 'Zona Título de Eleitor', 'required' => false))
            ->add('secaoTituloEleitor', 'text', array('attr' => array('class' => 'form-control'), 'label' => 'Seção Título de Eleitor', 'required' => false))
            ->add('naturalidade', 'text', array('attr' => array('class' => 'form-control'), 'label' => 'Naturalidade', 'required' => false))
            ->add('nacionalidade', 'text', array('attr' => array('class' => 'form-control'), 'label' => 'Nacionalidade', 'required' => false))
            ->add('raca', 'entity', array('attr' => array('class' => 'form-control'), 'label' => 'Raça', 'required' => false, 'class' => 'CommonsBundle:Raca', 'query_builder' => function($repository) { return $repository->createQueryBuilder('p'); }, 'property' => 'nome'))
            ->add('nomeMae', 'text', array('attr' => array('class' => 'form-control'), 'label' => 'Nome da Mãe', 'required' => false))
            ->add('nomePai', 'text', array('attr' => array('class' => 'form-control'), 'label' => 'Nome do Pai', 'required' => false))
            ->add('estadoCivil', 'entity', array('attr' => array('class' => 'form-control'), 'label' => 'Estado Civil', 'required' => false, 'class' => 'CommonsBundle:EstadoCivil', 'query_builder' => function($repository) { return $repository->createQueryBuilder('p'); }, 'property' => 'nome'))
            ->add('pisPasep', 'text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'PIS / PASEP'))
            ->add('carteiraTrabalhoNumero', 'text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'CTPS - Número'))
            ->add('carteiraTrabalhoSerie', 'text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'CTPS - Série'))
            ->add('carteiraTrabalhoDataExpedicao', 'date', array('required' => false, 'label' => 'CTPS - Data Expedição', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy'))
            ->add('carteiraTrabalhoEstado', 'entity', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'CTPS - Estado', 'class' => 'CommonsBundle:Estado', 'query_builder' => function($repository) { return $repository->createQueryBuilder('p'); }, 'property' => 'nome'))
            ->add('btnSalvar', 'submit',  array('label' => 'Salvar','attr' => array('class' => 'btn btn-primary')));
    }

    public function getName()
    {
        return 'PessoaFisicaForm';
    }
}
