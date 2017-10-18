<?php

namespace SME\DGPContratacaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\DGPContratacaoBundle\Entity\Processo;
use SME\DGPContratacaoBundle\Entity\Cargo;
use SME\DGPContratacaoBundle\Entity\Inscricao;
use SME\DGPContratacaoBundle\Entity\TipoProcesso;
use SME\DGPContratacaoBundle\Report\TermoDesistenciaReport;

class InscricaoController extends Controller {
    
    public function pesquisarAction(Processo $processo) {
        $form = $this->createFormBuilder()
            ->add('cargo', 'entity', array(
                'label' => 'Cargo:', 
                'class' => 'DGPContratacaoBundle:Cargo',
                'property' => 'nome',
                'required' => true,
                'query_builder' => function($repository) use ($processo) { 
                    return $repository->createQueryBuilder('c')->join('c.processo', 'p')
                        ->where('p.id = :p')->setParameter('p', $processo->getId());
                }
            ))
            ->add('classificacao', 'text', array(
                'label' => 'A partir da posição:',
                'required' => false
            ))
            ->add('candidato', 'text', array(
                'label' => 'Nome do candidato:',
                'required' => false
            ))
            ->getForm();
        $form->handleRequest($this->getRequest());
        if($form->isValid()) {
            return $this->pesquisar($processo, $form->getData());
        }
        return $this->render('DGPContratacaoBundle:Inscricao:formPesquisa.html.twig', array(
            'processo' => $processo,
            'form' => $form->createView()
        ));
    }
    
    public function cadastrarAction(Processo $processo, Cargo $cargo) {
        $dados = $this->getRequest()->request;
        $inscricao = new Inscricao();
        $inscricao->setCargo($cargo);
        try {
            $pessoa = $this->get('cadastro_unico')->createByCpf($dados->get('cpf'));
            $pessoa->setNome($dados->get('nome'));
            $pessoa->setCpfCnpj($dados->get('cpf'));
            $this->get('cadastro_unico')->retain($pessoa);
            $inscricao->setCandidato($pessoa);
            $this->getDoctrine()->getManager()->persist($inscricao);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message', 'Inscrição cadastrada');
        } catch(\Exception $e) {
            $this->get('session')->getFlashBag()->set('error', $e->getMessage());
        }
        return $this->pesquisar($processo, array(
            'cargo' => $cargo->getId(), 
            'classificacao' => '', 
            'candidato' => ''
        ));
    }
    
    private function pesquisar(Processo $processo, array $data) {
        $qb = $this->getDoctrine()->getRepository('DGPContratacaoBundle:Inscricao')->createQueryBuilder('i')
            ->join('i.cargo', 'cargo')
            ->where('cargo.id = :cargo')
            ->setParameter('cargo', $data['cargo']);
        if(is_numeric($data['classificacao'])) {
            $qb = $qb->andWhere('i.classificacao >= :classificacao')
                ->setParameter('classificacao', $data['classificacao']);
        }
        if($data['candidato']) {
            $qb = $qb->join('i.candidato', 'c')
                ->andWhere('c.nome LIKE :candidato')
                ->setParameter('candidato', '%' . $data['candidato'] . '%');
        }
        $chamadaPublica = $processo->getTipoProcesso()->getId() === TipoProcesso::CHAMADA_PUBLICA;
        $inscricoes = $chamadaPublica
                ? $qb->orderBy('i.id', 'DESC')->getQuery()->getResult()
                : $qb->orderBy('i.classificacao', 'ASC')->getQuery()->getResult();
        return $this->render('DGPContratacaoBundle:Inscricao:listaInscricoes.html.twig', array(
            'inscricoes' => $inscricoes,
            'cargo' => $this->getDoctrine()->getRepository('DGPContratacaoBundle:Cargo')->find($data['cargo']),
            'chamadaPublica' => $chamadaPublica
        ));
    }
    
    public function formDesistenciaAction(Inscricao $inscricao) {
        $convocacoes = $this->getDoctrine()->getRepository('DGPContratacaoBundle:Convocacao')->findBy(
            array('processo' => $inscricao->getProcesso()), array('dataRealizacao' => 'DESC')
        );
        return $this->render('DGPContratacaoBundle:Inscricao:modalFormDesistencia.html.twig', 
            array('inscricao' => $inscricao, 'convocacoes' => $convocacoes)
        );
    }
    
    public function imprimirTermoDesistenciaAction(Inscricao $inscricao) {
        $post = $this->getRequest()->request;
        $doc = new TermoDesistenciaReport();
        $doc->setInscricao($inscricao);
        $convocacao = $this->getDoctrine()->getRepository('DGPContratacaoBundle:Convocacao')->find($post->getInt('convocacao'));
        $doc->setConvocacao($convocacao);
        $doc->setCargaHoraria($post->getInt('cargaHoraria'));
        if($inscricao->getProcesso()->getTipoProcesso()->getId() === TipoProcesso::CONCURSO_PUBLICO) {
            $doc->setDataNomeacao(\DateTime::createFromFormat('d/m/Y', $post->get('dataNomeacao')));
        }
        $doc->setAtendente($this->getUser()->getPessoa());
        return $this->get('pdf')->render($doc);
    }
    
}
