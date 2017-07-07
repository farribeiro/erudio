<?php

namespace SME\FilaUnicaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use SME\FilaUnicaBundle\Entity\TipoInscricao;
use SME\FilaUnicaBundle\Entity\Status;
use SME\FilaUnicaBundle\Entity\Inscricao;
use SME\FilaUnicaBundle\Entity\Vaga;

class VagaController extends Controller {
    
    public function formCadastroAction() {
        if($this->get('security.context')->isGranted('ROLE_INFANTIL_MEMBRO')) {
            $unidades = $this->getDoctrine()->getManager()->createQueryBuilder()
                    ->select('u')->from('FilaUnicaBundle:UnidadeEscolar', 'u')
                    ->join('u.zoneamento', 'z')->join('u.entidade', 'e')->join('e.pessoaJuridica', 'p')
                    ->where('u.ativo = true')->orderBy('p.nome','ASC')->getQuery()->getResult();
        } else {
            $session = $this->getRequest()->getSession();
            $unidades = $this->getDoctrine()->getRepository('FilaUnicaBundle:UnidadeEscolar')->findBy(array('entidade' => $session->get('unidade')));
        }
        return $this->render('FilaUnicaBundle:Vaga:formCadastro.html.twig', array(
            'unidades' => $unidades,
            'anosEscolares' => $this->getDoctrine()->getRepository('FilaUnicaBundle:AnoEscolar')->findAll(),
            'periodos' => $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('p')->from('CommonsBundle:PeriodoDia', 'p')
                ->where('p.matutino + p.vespertino + p.noturno = :parcial OR p.matutino + p.vespertino + p.noturno = :integral')
                ->setParameter('parcial', 1)
                ->setParameter('integral', 3)
                ->getQuery()->getResult()
        ));
    }
    
    public function cadastrarAction() {
        $post = $this->getRequest()->request;
        $session = $this->getRequest()->getSession();
        $unidade = $this->getDoctrine()->getRepository('FilaUnicaBundle:UnidadeEscolar')->find($post->getInt('unidade'));
        try {
            
            //LINHA BLOQUEIO DE ABERTURA DE VAGAS
            //if(!$this->get('security.context')->isGranted('ROLE_INFANTIL_MEMBRO')) { throw new \Exception('Período de abertura de vagas encerrado'); }
            
            if(!$this->get('security.context')->isGranted('ROLE_INFANTIL_MEMBRO') && $unidade->getEntidade()->getId() != $session->get('unidade')->getId()) {
                throw new \Exception('Você não está autorizado a cadastrar vagas para esta unidade');
            }
            if($post->has('quantidade') && $post->getInt('quantidade') > 0) {
                $anoEscolar = $this->getDoctrine()->getRepository('FilaUnicaBundle:AnoEscolar')->find($post->getInt('ano'));
                $periodoDia = $this->getDoctrine()->getRepository('CommonsBundle:PeriodoDia')->find($post->getInt('periodo'));
                for($i = 0; $i < $post->getInt('quantidade'); $i++) {
                    $vaga = new Vaga();
                    $vaga->setPessoaCadastro($this->getUser()->getPessoa());
                    $vaga->setAtivo(true);
                    $vaga->setMatriculaConfirmada(false);
                    $vaga->setUnidadeEscolar($unidade);
                    $vaga->setAnoEscolar($anoEscolar);
                    $vaga->setPeriodoDia($periodoDia);
                    $now = new \DateTime();
                    $vaga->setDataCadastro($now);
                    $vaga->setDataModificacao($now);
                    $vaga->setDataMatricula(null);
                    $this->getDoctrine()->getManager()->persist($vaga);
                }
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->set('message','Vagas criadas com sucesso');
            } else { throw new \Exception('Não foi informada uma quantidade válida de vagas'); }
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage() );
        }
        return $this->redirect($this->generateUrl('fu_vaga_cadastrar'));
    }
    
    public function formPesquisaAction() {
        $params = array('anosEscolares' => $this->getDoctrine()->getRepository('FilaUnicaBundle:AnoEscolar')->findAll());
        if($this->get('security.context')->isGranted('ROLE_INFANTIL_MEMBRO')) {
            $params['unidades'] = $this->getDoctrine()->getManager()->createQueryBuilder()
                    ->select('u')->from('FilaUnicaBundle:UnidadeEscolar', 'u')
                    ->join('u.zoneamento', 'z')->join('u.entidade', 'e')->join('e.pessoaJuridica', 'p')
                    ->orderBy('p.nome','ASC')->getQuery()->getResult();
            $params['zoneamentos'] = $this->getDoctrine()->getRepository('FilaUnicaBundle:Zoneamento')->findAll();
        } else {
            $session = $this->getRequest()->getSession();
            $params['unidades'] = $this->getDoctrine()->getRepository('FilaUnicaBundle:UnidadeEscolar')->findBy(array('entidade' => $session->get('unidade')));
        }
        return $this->render('FilaUnicaBundle:Vaga:formPesquisa.html.twig', $params);
    }
    
    public function pesquisarAction(Request $request) {
        $post = $request->request;
        $session = $request->getSession();
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('v')->from('FilaUnicaBundle:Vaga', 'v')
                ->join('v.unidadeEscolar', 'u')->join('u.zoneamento', 'z')->join('v.anoEscolar', 'a')
                ->where('v.ativo = true')->andWhere('v.matriculaConfirmada = false');
        try {
            if($post->get('zoneamento') && $this->get('security.context')->isGranted('ROLE_INFANTIL_MEMBRO')) {
                $qb = $qb->andWhere('z.id = :zoneamento')->setParameter('zoneamento', $post->getInt('zoneamento'));
            }
            if($post->get('unidade')) {
                $unidade = $this->getDoctrine()->getRepository('FilaUnicaBundle:UnidadeEscolar')->find($post->getInt('unidade'));
                if(!$this->get('security.context')->isGranted('ROLE_INFANTIL_MEMBRO') && $unidade->getEntidade()->getId() != $session->get('unidade')->getId()) {
                    throw new \Exception('Você não está autorizado a visualizar vagas desta unidade');
                } else {
                    $qb = $qb->andWhere('u.id = :unidade')->setParameter('unidade', $unidade->getId());
                }
            } elseif(!$this->get('security.context')->isGranted('ROLE_INFANTIL_MEMBRO')) {
                throw new \Exception('Você não está autorizado a visualizar vagas de outras unidades');
            }
            if($post->get('ano')) {
                $qb = $qb->andWhere('a.id = :anoEscolar')->setParameter('anoEscolar', $post->getInt('ano'));
            }
            $vagas = $qb->orderBy('a.id')->orderBy('u.id')->getQuery()->getResult();
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage() );
            $vagas = array();
        }
        return $this->render('FilaUnicaBundle:Vaga:listaVagas.html.twig', array('vagas' => $vagas));
    }

    /**
        * Bloqueado temporariamente para acesso das unidades
        * @PreAuthorize("hasRole('ROLE_INFANTIL_MEMBRO')")
        */
    public function preencherAction(Vaga $vaga) {
        $session = $this->getRequest()->getSession();
        try {
            if(!$this->get('security.context')->isGranted('ROLE_INFANTIL_MEMBRO') && $vaga->getUnidadeEscolar()->getEntidade()->getId() != $session->get('unidade')->getId()) {
                throw new \Exception('Você não está autorizado a preencher vagas desta unidade');
            }
            $this->preencherVaga($vaga);
            $this->get('session')->getFlashBag()->set('message', 'A inscrição ' . $vaga->getInscricaoEmChamada()->getProtocolo() . ' foi chamada para vaga');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->forward('FilaUnicaBundle:Vaga:pesquisar');
    }
      
    private function preencherVaga(Vaga $vaga, $reordenarFila = true) {
        $fila = $this->get('fila_unica')->gerarFilaReal($vaga->getUnidadeEscolar()->getZoneamento(), $vaga->getAnoEscolar());
        if ($vaga->getInscricaoEmChamada() == null) {
            foreach ($fila as $inscricao) {
                if ($inscricao->getTipoInscricao()->getId() != TipoInscricao::TRANSFERENCIA || $this->compativelComTransferencia($vaga, $inscricao)) {
                    $now = new \DateTime();
                    $vaga->setInscricaoEmChamada($inscricao);
                    $vaga->setDataModificacao($now);
                    $vaga->setPessoaModificacao($this->getUser()->getPessoa());
                    $inscricao->setVagaOfertada($vaga);
                    $inscricao->setStatus($this->getDoctrine()->getRepository('FilaUnicaBundle:Status')->find(Status::EM_CHAMADA));
                    $inscricao->setDataModificacao($now);
                    $inscricao->setDataChamada(new \DateTime());
                    $this->getDoctrine()->getManager()->merge($inscricao);
                    $this->getDoctrine()->getManager()->merge($vaga);
                    $this->getDoctrine()->getManager()->flush();
                    return; //não retirar! evita uma reordenação não desejada da fila
                }
            }

            //Realiza uma reordenação da fila, chamando inscrições que estão em reserva, e tenta preencher a vaga novamente
            if ($reordenarFila && $vaga->getInscricaoEmChamada() == null) {
                $this->get('fila_unica')->reordenarFilaParcial($vaga->getUnidadeEscolar()->getZoneamento(), $vaga->getAnoEscolar());
                $this->preencherVaga($vaga, false);
            } else {
                throw new \Exception('Não existem candidatos para esta vaga no momento');
            }
        } else {
            throw new \Exception('Vaga já preenchida');
        }
    }
    
    private function compativelComTransferencia(Vaga $vaga, Inscricao $inscricao) {
        return $inscricao->getPeriodoDia()->estaContido($vaga->getPeriodoDia()) &&
               ($vaga->getUnidadeEscolar()->getId() === $inscricao->getUnidadeDestino()->getId() 
               || $vaga->getUnidadeEscolar()->getId() === $inscricao->getUnidadeDestinoAlternativa()->getId());
    }
    
    public function formGerenciaAction(Vaga $vaga) {
        $renderParams = array(
            'vaga' => $vaga,
            'matricula' => Status::MATRICULADO,
            'cancelamento' => Status::DESISTENTE_VAGA,
            'eliminacao' => Status::ELIMINADO
        );
        return $this->render('FilaUnicaBundle:Vaga:modalFormGerencia.html.twig', $renderParams);
    }
    
    public function atualizarAction(Vaga $vaga, Status $status) {
        $session = $this->getRequest()->getSession();
        try {
            if(!$this->get('security.context')->isGranted('ROLE_INFANTIL_MEMBRO') && $vaga->getUnidadeEscolar()->getEntidade()->getId() != $session->get('unidade')->getId()) {
                throw new \Exception('Você não está autorizado a gerenciar vagas desta unidade');
            }
            if($status->getTerminal()) {
                $vaga->getInscricaoEmChamada()->setStatus($status);
                $vaga->getInscricaoEmChamada()->setAtivo(false);
                $vaga->getInscricaoEmChamada()->setDataModificacao(new \DateTime());
                $vaga->getInscricaoEmChamada()->setPessoaUltimaModificacao($this->getUser()->getPessoa());
                $this->getDoctrine()->getManager()->merge($vaga->getInscricaoEmChamada());
                if($status->getId() === Status::MATRICULADO) {
                    //encerra vaga
                    $vaga->setDataMatricula(new \DateTime());
                    $vaga->setMatriculaConfirmada(true);
                    $vaga->setAtivo(false);
                    $this->get('session')->getFlashBag()->set('message', 'Vaga preenchida com sucesso');
                } else {
                    //libera vaga
                    $vaga->setInscricaoEmChamada(null);
                    $this->get('session')->getFlashBag()->set('message', 'Inscrição eliminada, vaga liberada para nova chamada');
                }
                $this->getDoctrine()->getManager()->merge($vaga);
                $this->getDoctrine()->getManager()->flush();
            } else {
                throw new \Exception('Transição de status não permitida');
            }
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        $this->getRequest()->request->set('unidade', $vaga->getUnidadeEscolar()->getId());
        return $this->forward('FilaUnicaBundle:Vaga:pesquisar');
    }
    
    public function cancelarAction(Vaga $vaga) {
         try {
            if(!$this->get('security.context')->isGranted('ROLE_INFANTIL_MEMBRO')) {
                throw new \Exception('Você não está autorizado a realizar esta operação');
            }
            $motivo = $vaga->getHistoricoChamadas()->isEmpty() ? 'erro de cadastro' : 'remanejamento de turmas';
            $vaga->setObservacao('Vaga cancelada por ' . $motivo . ', pelo usuário ' . $this->getUser()->getUsername());
            $vaga->setDataModificacao(new \DateTime());
            $vaga->setAtivo(false);
            $this->getDoctrine()->getManager()->merge($vaga);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message', 'Vaga cancelada com sucesso');
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        $this->getRequest()->request->set('unidade', $vaga->getUnidadeEscolar()->getId());
        return $this->forward('FilaUnicaBundle:Vaga:pesquisar');
    }
    
    public function reverterChamadaAction(Vaga $vaga) {
        try {
            if(!$this->get('security.context')->isGranted('ROLE_INFANTIL_MEMBRO')) {
                throw new \Exception('Você não está autorizado a realizar esta operação');
            }
            $inscricao = $vaga->getInscricaoEmChamada();
            $now = new \DateTime();
            //if($now->diff($inscricao->getDataChamada())->d > 0) {
            //    throw new \Exception('Esta operação só pode ser feita até 24 horas após a chamada');
            //}
            $vaga->setInscricaoEmChamada(null);
            $inscricao->setVagaOfertada(null);
            $inscricao->setDataChamada(null);
            $statusEspera = $this->getDoctrine()->getRepository('FilaUnicaBundle:Status')->find(Status::EM_ESPERA);
            $inscricao->setStatus($statusEspera);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message','A inscrição de protocolo ' . $inscricao->getProtocolo() . ' foi retornada à sua fila');
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        $this->getRequest()->request->set('unidade', $vaga->getUnidadeEscolar()->getId());
        return $this->forward('FilaUnicaBundle:Vaga:pesquisar');
    }
    
}
