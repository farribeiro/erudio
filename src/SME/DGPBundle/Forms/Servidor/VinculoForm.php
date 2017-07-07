<?php
namespace SME\DGPBundle\Forms\Servidor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SME\DGPBundle\Entity\TipoVinculo;
use SME\DGPContratacaoBundle\Entity\Inscricao;
use SME\DGPContratacaoBundle\Entity\Convocacao;

class VinculoForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
            $builder
		->add('matricula','text', array('attr' => array('class' => 'form-control'), 'label' => 'Matrícula', 'required' => false))
                ->add('numeroControle', 'number', array('attr' => array('class' => 'form-control'), 'label' => 'Número de Controle', 'required' => false))    
		->add('dataInicio','date', array('attr' => array('class' => 'form-control datePickerSME'), 'label' => 'Data de Nomeação / Início do Contrato *', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy'))
		->add('dataTermino','date', array('attr' => array('class' => 'form-control datePickerSME'), 'label' => 'Data de Posse / Término do Contrato *', 'empty_value' => null, 'widget' => 'single_text', 'format' => 'dd/MM/yyyy'))
		->add('portaria','text', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Portaria'))
                ->add('edicaoJornalNomeacao','number', array('attr' => array('class' => 'form-control'), 'required' => false, 'label' => 'Edição do Jornal'))
		->add('cargo','entity', array('attr' => array('class' => 'form-control'), 'label' => 'Cargo *', 'class' => 'DGPBundle:Cargo', 'query_builder' => function($repository) { return $repository->createQueryBuilder('p')->orderBy('p.nome'); }, 'property' => 'nome'))
		->add('tipoVinculo', 'entity', array('attr' => array('class' => 'form-control'), 'label' => 'Tipo de Vínculo *', 'class' => 'DGPBundle:TipoVinculo', 'query_builder' => function($repository) { return $repository->createQueryBuilder('r'); }, 'property' => 'nome'))
		->add('cargaHoraria','choice', array('attr' => array('class' => 'form-control'), 'label' => 'Carga Horária *', 'choices' => array('10' => '10 horas', '20' => '20 horas', '30' => '30 horas', '40' => '40 horas')))
		->add('bancoContaBancaria','entity', array('attr' => array('class' => 'form-control'), 'label' => 'Banco', 'class' => 'DGPBundle:Banco', 'query_builder' => function($repository) { return $repository->createQueryBuilder('q'); }, 'property' => 'nome', 'required' => false))
		->add('agenciaContaBancaria','text', array('attr' => array('class' => 'form-control'), 'label' => 'Agência', 'required' => false))
		->add('numeroContaBancaria','text', array('attr' => array('class' => 'form-control'), 'label' => 'Número da Conta', 'required' => false))
		->add('quadroEspecial', 'choice', array('attr' => array('class' => 'form-control'), 'label' => 'Quadro Especial', 'choices' => array(0 => 'Não', 1 => 'Sim')))
		->add('gratificacao','text', array('attr' => array('class' => 'form-control'), 'label' => 'Gratificação', 'required' => false))
		->add('lotacaoSecretaria','text', array('attr' => array('class' => 'form-control'), 'label' => 'Secretaria de Lotação', 'required' => false))
		->add('codigoDepartamento','text', array('attr' => array('class' => 'form-control'), 'label' => 'Código de Departamento', 'required' => false))
		->add('codigoSetor','text', array('attr' => array('class' => 'form-control'), 'label' => 'Código de Setor', 'required' => false))
		->add('observacao','choice', array(
                    'attr' => array('class' => 'form-control'), 
                    'label' => 'Opção de Lei (Comissionados)', 
                    'required' => false,
                    'choices' => array('Lei 150/2003' => 'Lei 150/2003', 'Lei 4027/2003' => 'Lei 4027/2003'),
                    'empty_value' => ''
                ))
		->add('inscricaoVinculacao', 'entity', array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Processo Admissional',
                    'class' => 'DGPContratacaoBundle:Inscricao',
                    'required' => false,
                    'property' => 'descricao',
                    'empty_value' => 'Não se aplica (Comissionado / Efetivado antes de 2012)',
                    'query_builder' => function($rep) use ($options) {
                        return $rep->createQueryBuilder('i')
                            ->join('i.cargo', 'c')->join('c.processo', 'p')->join('i.candidato', 'pessoa')
                            ->where('pessoa.id = :pessoa')->andWhere('p.ativo = true')
                            ->setParameter('pessoa', $options['pessoa']->getId()); 
                    }
		))
                ->add('vinculoOriginal', 'entity', array(
                    'required' => false,
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Vínculo Original',
                    'class' => 'DGPBundle:Vinculo',
                    'required' => false,
                    'property' => 'descricao',
                    'empty_value' => 'Não possui vínculo efetivo',
                    'query_builder' => function($rep) use ($options) {
                        return $rep->createQueryBuilder('v')->join('v.servidor', 'p')->join('v.tipoVinculo', 't')
                            ->where('p.id = :pessoa')->andWhere('t.id = :efetivo')->andWhere('v.ativo = true')
                            ->setParameter('pessoa', $options['pessoa']->getId())
                            ->setParameter('efetivo', TipoVinculo::EFETIVO);
                    }
		))
		->add('btnSalvar', 'submit',  array('label' => 'Salvar'));
            
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

	public function getName()
	{
            return 'VinculoForm';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
            $resolver->setRequired(array('pessoa'));
	}
}
