<?php
namespace SME\PresencaBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use SME\PresencaBundle\Entity\Presenca;

class BuscaPresencaForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
            $presenca = new Presenca();
            $turmas = $presenca->getTurmas();
            $periodos = $presenca->getPeriodos();
            $meses = array('01'=>'Janeiro','02'=>'Fevereiro','03'=>'Março','04'=>'Abril','05'=>'Maio','06'=>'Junho','07'=>'Julho','08'=>'Agosto','09'=>'Setembro','10'=>'Outubro','11'=>'Novembro','12'=>'Dezembro');
            
            $builder
                ->add('mes','choice', array('attr' => array('class' => 'form-control'), 'choices' => $meses, 'required' => false, 'mapped' => false))
                ->add('turma','choice', array('attr' => array('class' => 'form-control'), 'choices' => $turmas, 'required' => false))
                ->add('turno','choice', array('attr' => array('class' => 'form-control'), 'choices' => $periodos, 'required' => false))
                ->add('entidade', 'entity', array('attr' => array('class' => 'form-control'), 'label' => 'Unidade Escolar:*', 'required' => true, 'class' => 'CommonsBundle:Entidade', 
                    'query_builder' => function($repository) { 
                        return $repository->createQueryBuilder('e')->join('e.pessoaJuridica', 'p')->orderBy('p.nome'); }, 'property' => 'nome'))
		->add('Buscar', 'submit',  array('attr' => array('class' => 'btn btn-primary')));
	}

	public function getName()
	{
            return 'BuscaPresencaForm';
	}
}
