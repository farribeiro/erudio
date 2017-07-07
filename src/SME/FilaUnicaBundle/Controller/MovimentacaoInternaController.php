<?php

namespace SME\FilaUnicaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\FilaUnicaBundle\Report\MovimentacoesInternasReport;
use SME\FilaUnicaBundle\Entity\MovimentacaoInterna;
use SME\FilaUnicaBundle\Entity\TipoInscricao;
use SME\FilaUnicaBundle\Entity\Status;
use SME\FilaUnicaBundle\Entity\Vaga;

class MovimentacaoInternaController extends Controller {
    
    public function formPesquisaAction() {
        return $this->render('FilaUnicaBundle:MovimentacaoInterna:formPesquisa.html.twig');
    }
    
    public function pesquisarAction() {
        $post = $this->getRequest()->request;
        $dataInicio = \DateTime::createFromFormat('d/m/Y', $post->get('dataInicio'));
        $dataTermino = \DateTime::createFromFormat('d/m/Y', $post->get('dataTermino'));
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('m')->from('FilaUnicaBundle:MovimentacaoInterna', 'm')
                ->where('m.dataCadastro BETWEEN :dataInicio AND :dataTermino')
                ->setParameter('dataInicio', $dataInicio->format('Y-m-d H:i:s'))
                ->setParameter('dataTermino', $dataTermino->format('Y-m-d H:i:s'));
        $movimentacoes = $qb->getQuery()->getResult();
        return $this->render('FilaUnicaBundle:MovimentacaoInterna:listaMovimentacoes.html.twig', 
            array('movimentacoes' => $movimentacoes)
        );
    }
    
    public function visualizarAction(MovimentacaoInterna $movimentacao) {
        return $this->render('FilaUnicaBundle:MovimentacaoInterna:modalConsulta.html.twig', array('movimentacao' => $movimentacao));
    }
    
    public function formMovimentacaoFilaAction() {
        $unidades = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('u')->from('FilaUnicaBundle:UnidadeEscolar', 'u')
                ->join('u.entidade', 'e')->join('e.pessoaJuridica', 'p')
                ->orderBy('p.nome','ASC')->getQuery()->getResult();
        return $this->render('FilaUnicaBundle:MovimentacaoInterna:formMovimentacaoFila.html.twig', array(
            'unidades' => $unidades,
            'anosEscolares' => $this->getDoctrine()->getRepository('FilaUnicaBundle:AnoEscolar')->findAll()
        ));
    }
    
    public function moverFilaAction() {
        $post = $this->getRequest()->request;
        try {
            $inscricao = $this->getDoctrine()->getRepository('FilaUnicaBundle:Inscricao')->findOneBy(
                array('protocolo' => $post->get('protocolo'), 'ativo' => true)
            );
            if($inscricao == null) {
                throw new \Exception('Não há nenhuma inscrição ativa com o protocolo informado');
            }
            elseif($inscricao->getStatus()->getId() != Status::EM_ESPERA) {
                throw new \Exception('Movimentações internas só se aplicam à inscrições que estejam em espera na fila.'
                    . ' Caso a inscrição esteja em reserva, seu zoneamento pode ser alterado diretamente na pesquisa de inscrição');
            }
            $unidadeOriginal = $inscricao->getUnidadeDestino();
            $unidadeAlterada = $this->getDoctrine()->getRepository('FilaUnicaBundle:UnidadeEscolar')->find($post->get('unidade'));
            $anoEscolarOriginal = $inscricao->getAnoEscolar();
            $anoEscolarAlterado = $this->getDoctrine()->getRepository('FilaUnicaBundle:AnoEscolar')->find($post->get('anoEscolar'));
            $inscricao->setMovimentacaoInterna(true);
            $inscricao->setUnidadeDestino($unidadeAlterada);
            $inscricao->setZoneamento($unidadeAlterada->getZoneamento());
            $inscricao->setAnoEscolar($anoEscolarAlterado);
            $this->getDoctrine()->getManager()->merge($inscricao);
            $movimentacaoInterna = new MovimentacaoInterna();
            $movimentacaoInterna->setInscricao($inscricao);
            $movimentacaoInterna->setUnidadeEscolarOriginal($unidadeOriginal);
            $movimentacaoInterna->setUnidadeEscolarAlterada($unidadeAlterada);
            $movimentacaoInterna->setAnoEscolarOriginal($anoEscolarOriginal);
            $movimentacaoInterna->setAnoEscolarAlterado($anoEscolarAlterado);
            $movimentacaoInterna->setJustificativa($post->get('justificativa'));
            $movimentacaoInterna->setAtendente($this->getUser()->getPessoa());
            $movimentacaoInterna->setAtivo(true);
            $this->getDoctrine()->getManager()->persist($movimentacaoInterna);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message','Movimentação interna realizada');
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('fu_movimentacaoInterna_formCadastro'));
    }
    
    public function formMovimentacaoVagaAction(Vaga $vaga) {
        $unidades = $this->getDoctrine()->getManager()->createQueryBuilder()
                    ->select('u')->from('FilaUnicaBundle:UnidadeEscolar', 'u')
                    ->join('u.zoneamento', 'z')->join('u.entidade', 'e')->join('e.pessoaJuridica', 'p')
                    ->orderBy('p.nome','ASC')->getQuery()->getResult();
        return $this->render('FilaUnicaBundle:MovimentacaoInterna:modalFormMovimentacaoVaga.html.twig', array(
            'vaga' => $vaga,
            'unidades' => $unidades,
            'zoneamentos' => $this->getDoctrine()->getRepository('FilaUnicaBundle:Zoneamento')->findAll(),
            'anosEscolares' => $this->getDoctrine()->getRepository('FilaUnicaBundle:AnoEscolar')->findAll(),
            'periodos' => $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('p')->from('CommonsBundle:PeriodoDia', 'p')
                ->where('p.matutino + p.vespertino + p.noturno = :parcial OR p.matutino + p.vespertino + p.noturno = :integral')
                ->setParameter('parcial', 1)
                ->setParameter('integral', 3)
                ->getQuery()->getResult()
        ));
    }
    
    public function moverVagaAction(Vaga $vaga) {
        $post = $this->getRequest()->request;
        try {
            if(!$this->get('security.context')->isGranted('ROLE_INFANTIL_MEMBRO')) {
                throw new \Exception('Você não está autorizado a realizar esta operação');
            }
            $qb = $this->getDoctrine()->getManager()->createQueryBuilder()
                    ->select('v')
                    ->from('FilaUnicaBundle:Vaga', 'v')
                    ->join('v.unidadeEscolar', 'u')
                    ->join('v.anoEscolar', 'a')
                    ->join('v.periodoDia', 'p')
                    ->join('u.zoneamento', 'z')
                    ->where('v.inscricaoEmChamada is NULL')->andWhere('v.ativo = true')
                    ->andWhere('z.id = :zoneamento')->setParameter('zoneamento', $post->get('zoneamento'))
                    ->andWhere('a.id = :anoEscolar')->setParameter('anoEscolar', $post->get('ano'));
            if($post->get('unidade')) {
                $qb = $qb->andWhere('u.id = :unidade')->setParameter('unidade', $post->get('unidade'));
            }
            if($post->get('periodo')) {
                $qb = $qb->andWhere('p.id = :periodo')->setParameter('periodo', $post->get('periodo'));
            }
            $vagasDisponiveis = $qb->getQuery()->getResult();
            if(count($vagasDisponiveis)) {
                $vagaDestino = $vagasDisponiveis[0];
                $vaga->getInscricaoEmChamada()->setVagaOfertada($vagaDestino);
                $vaga->getInscricaoEmChamada()->setAnoEscolar($vagaDestino->getAnoEscolar());
                $vaga->getInscricaoEmChamada()->setZoneamento($vagaDestino->getUnidadeEscolar()->getZoneamento());
                $vagaDestino->setInscricaoEmChamada($vaga->getInscricaoEmChamada());
                $vaga->setInscricaoEmChamada(null);
                $this->getDoctrine()->getManager()->merge($vaga);
                $this->getDoctrine()->getManager()->merge($vagaDestino);
                $movimentacaoInterna = new MovimentacaoInterna();
                $movimentacaoInterna->setInscricao($vagaDestino->getInscricaoEmChamada());
                $movimentacaoInterna->setUnidadeEscolarOriginal($vaga->getUnidadeEscolar());
                $movimentacaoInterna->setUnidadeEscolarAlterada($vagaDestino->getUnidadeEscolar());
                $movimentacaoInterna->setAnoEscolarOriginal($vaga->getAnoEscolar());
                $movimentacaoInterna->setAnoEscolarAlterado($vagaDestino->getAnoEscolar());
                $movimentacaoInterna->setJustificativa($post->get('justificativa'));
                $movimentacaoInterna->setAtendente($this->getUser()->getPessoa());
                $movimentacaoInterna->setAtivo(true);
                $this->getDoctrine()->getManager()->persist($movimentacaoInterna);
                $this->getDoctrine()->getManager()->flush();
            } else {
                throw new \Exception('Nenhuma vaga compatível encontrada');
            }
            $this->get('session')->getFlashBag()->set('message', 'Movimentação efetuada');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        $this->getRequest()->request->set('unidade', $vaga->getUnidadeEscolar()->getId());
        return $this->forward('FilaUnicaBundle:Vaga:pesquisar');
    }
    
    public function imprimirPeriodoAction() {
        $post = $this->getRequest()->request;
        $dataInicio = \DateTime::createFromFormat('d/m/Y', $post->get('dataInicio'));
        $dataTermino = \DateTime::createFromFormat('d/m/Y', $post->get('dataTermino'));
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('m')->from('FilaUnicaBundle:MovimentacaoInterna', 'm')
                ->where('m.dataCadastro BETWEEN :dataInicio AND :dataTermino')
                ->setParameter('dataInicio', $dataInicio->format('Y-m-d H:i:s'))
                ->setParameter('dataTermino', $dataTermino->format('Y-m-d H:i:s'));
        $movimentacoes = $qb->getQuery()->getResult();
        $relatorio = new MovimentacoesInternasReport();
        $relatorio->setDataInicio($dataInicio);
        $relatorio->setDataTermino($dataTermino);
        $relatorio->setMovimentacoes($movimentacoes);
        return $this->get('pdf')->render($relatorio);
    }
    
}
