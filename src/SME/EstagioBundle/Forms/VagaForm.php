<?php
namespace SME\EstagioBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class VagaForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
            /*$repo = $options['data'][0];
            $roles = $repo->findBy(array(), array('nomeExibicao' => 'ASC'));
            $unidades = array();
            foreach($roles as $role) {
                if(\strstr($role->getRole(),'ROLE_UNIDADE_')) {
                    $unidades[] = $role->getNomeExibicao();
                }
            }*/
            $turnos = array(1 => 'Matutino', 2 => 'Matutino/Verpertino', 3 => 'Vespertino',  4 => 'Vespertino/Noturno',  5 => 'Noturno',  6 => 'Integral');
            //$instituicoes = array('UNIVALI','UNOPAR','UNIFIL','UNIDERP','UNIASSELVI','UDESC','SOCIESC','Universidade do Contestado Func','Nilton Kucker','SINERGIA','IFES', 'IFC');
            
            $builder
                ->add('titulo', 'text', array('attr' => array('class' => 'form-control', 'placeholder' => 'Título'), 'required' => false))
                //->add('curso', 'text', array('attr' => array('class' => 'form-control', 'placeholder' => 'Curso'), 'required' => false))
                ->add('descricao', 'textarea', array('attr' => array('class' => 'form-control', 'placeholder' => 'Descrição da vaga'), 'required' => false))
                ->add('disciplina', 'text', array('attr' => array('class' => 'form-control', 'placeholder' => 'Etapa/Modalidade'), 'required' => false))
                ->add('totalVagas', 'text', array('attr' => array('class' => 'form-control', 'placeholder' => 'Vagas'), 'required' => false))
                //->add('unidade','entity', array('attr' => array('class' => 'form-control'), 'label' => 'Unidade', 'class' => 'IntranetBundle:Role', 'query_builder' => function($repository) { return $repository->createQueryBuilder('q'); }, 'property' => 'nomeExibicao', 'required' => false))
                ->add('turno','choice', array('choices' => $turnos, 'attr' => array('class' => 'form-control'), 'label' => 'Turno', 'required' => false))
                //->add('instituicao','choice', array('choices' => $instituicoes, 'attr' => array('class' => 'form-control'), 'label' => 'Instituição', 'required' => false))
                //->add('turno','entity', array('attr' => array('class' => 'form-control'), 'label' => 'Turno', 'class' => 'EstagioBundle:Turno', 'query_builder' => function($repository) { return $repository->createQueryBuilder('q'); }, 'property' => 'nome', 'required' => false))
		->add('Salvar', 'submit',  array('attr' => array('class' => 'btn btn-primary')));
	}

	public function getName()
	{
            return 'VagaForm';
	}
}
