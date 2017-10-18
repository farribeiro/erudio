<?php

namespace SME\FilaUnicaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;
use SME\FilaUnicaBundle\Entity\TipoInscricao;
use SME\FilaUnicaBundle\Entity\Status;

class RelatorioController extends Controller {
    
    public function formRelatorioInscricoesAction() {
        return $this->render('FilaUnicaBundle:Relatorio:formRelatorioInscricoes.html.twig', array(
            'tiposInscricao' => $this->getDoctrine()->getRepository('FilaUnicaBundle:TipoInscricao')->findAll(),
            'status' => $this->getDoctrine()->getRepository('FilaUnicaBundle:Status')->findAll()
        ));
    }
    
    public function formRelatorioVagasAction() {
        return $this->render('FilaUnicaBundle:Relatorio:formRelatorioVagas.html.twig');
    }
    
    public function gerarRelatorioInscricoesAction() {
        $post = $this->getRequest()->request;
        $zoneamentos = $this->getDoctrine()->getRepository('FilaUnicaBundle:Zoneamento')->findAll();
        $anosEscolares = $this->getDoctrine()->getRepository('FilaUnicaBundle:AnoEscolar')->findAll();
        
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('i')
            ->from('FilaUnicaBundle:Inscricao', 'i')
            ->join('i.tipoInscricao', 't')
            ->join('i.status', 's')
            ->where($post->get('processoJudicial') ? 'i.processoJudicial = true' : 'i.processoJudicial = false');
        //Filtros
        if($post->get('filtroPeriodo') && $post->get('dataInicio') && $post->get('dataTermino')) {
            $dataInicio = \DateTime::createFromFormat('d/m/Y H:i', $post->get('dataInicio') . ' 00:00');
            $dataTermino = \DateTime::createFromFormat('d/m/Y H:i', $post->get('dataTermino') . ' 00:00');
            $qb = $post->get('filtroPeriodo') == 'dataChamada'
                ? $qb->andWhere('i.dataChamada BETWEEN :dataInicio AND :dataTermino')
                : $qb->andWhere('i.dataCadastro BETWEEN :dataInicio AND :dataTermino');
            $qb = $qb->setParameter('dataInicio', $dataInicio->format('Y-m-d H:i:s'))
                 ->setParameter('dataTermino', $dataTermino->format('Y-m-d H:i:s'));
        }
        if($post->get('filtroNaoMatriculados')) {
            $qb = $qb->andWhere('i.unidadeOrigem IS NULL');
        }
        if($post->getInt('tipoInscricao')) {
            $qb = $qb->andWhere('t.id = :tipoInscricao')->setParameter('tipoInscricao', $post->getInt('tipoInscricao'));
        }
        if($post->getInt('status')) {
            $qb = $qb->andWhere('s.id = :status')->setParameter('status', $post->getInt('status'));
        }
        $inscricoes = $qb->getQuery()->getResult();
        //Montagem da estrutura para exibição
        $numerosInscricoes = array();
        foreach($zoneamentos as $z) {
            $numerosInscricoes[$z->getId()]['total'] = 0;
            foreach($anosEscolares as $a) {
                $numerosInscricoes[$z->getId()][$a->getId()] = 0;
                $numerosInscricoes['total'][$a->getId()] = 0;
            }
        }
        //Preenchimento com as somatórias
        $totalGeral = 0;
        foreach($inscricoes as $inscricao) {
            $numerosInscricoes[$inscricao->getZoneamento()->getId()][$inscricao->getAnoEscolar()->getId()]++;
            $numerosInscricoes['total'][$inscricao->getAnoEscolar()->getId()]++;
            $numerosInscricoes[$inscricao->getZoneamento()->getId()]['total']++;
            $totalGeral++;
        }
        return $this->render('FilaUnicaBundle:Relatorio:relatorioInscricoes.html.twig', array(
            'zoneamentos' => $zoneamentos,
            'anosEscolares' => $anosEscolares,
            'numerosInscricoes' => $numerosInscricoes,
            'totalGeral' => $totalGeral
        ));
    }
    
    public function gerarRelatorioVagasAction() {
        $post = $this->getRequest()->request;
        $unidades = $this->getDoctrine()->getManager()->createQueryBuilder()->select('u')
                ->from('FilaUnicaBundle:UnidadeEscolar', 'u')->join('u.zoneamento', 'z')
                ->orderBy('z.nome', 'ASC')->getQuery()->getResult();
        $anosEscolares = $this->getDoctrine()->getRepository('FilaUnicaBundle:AnoEscolar')->findAll();
        
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('v')->from('FilaUnicaBundle:Vaga', 'v')
                ->where('v.ativo = :ativo')->setParameter('ativo', $post->getInt('ativo'));
        $qb = $post->getInt('vazia') 
                ? $qb->andWhere('v.inscricaoEmChamada IS NULL')
                : $qb->andWhere('v.inscricaoEmChamada IS NOT NULL');
        if($post->get('filtroPeriodo') && $post->get('dataInicio') && $post->get('dataTermino')) {
            $dataInicio = \DateTime::createFromFormat('d/m/Y', $post->get('dataInicio'));
            $dataTermino = \DateTime::createFromFormat('d/m/Y', $post->get('dataTermino'));
            $qb = $post->get('filtroPeriodo') == 'dataMatricula'
                ? $qb->andWhere('v.dataMatricula BETWEEN :dataInicio AND :dataTermino')
                : $qb->andWhere('v.dataCadastro BETWEEN :dataInicio AND :dataTermino');
            $qb = $qb->setParameter('dataInicio', $dataInicio->format('Y-m-d H:i:s'))
                 ->setParameter('dataTermino', $dataTermino->format('Y-m-d H:i:s'));
        }

        $vagas = $qb->getQuery()->getResult();
        //Montagem da estrutura para exibição
        $numerosVagas = array();
        foreach($unidades as $u) {
            $numerosVagas[$u->getId()]['total'] = 0;
            foreach($anosEscolares as $a) {
                $numerosVagas[$u->getId()][$a->getId()] = 0;
                $numerosVagas['total'][$a->getId()] = 0;
            }
        }
        //Preenchimento com as somatórias
        $totalGeral = 0;
        foreach($vagas as $vaga) {
            $numerosVagas[$vaga->getUnidadeEscolar()->getId()][$vaga->getAnoEscolar()->getId()]++;
            $numerosVagas['total'][$vaga->getAnoEscolar()->getId()]++;
            $numerosVagas[$vaga->getUnidadeEscolar()->getId()]['total']++;
            $totalGeral++;
        }

        return $this->render('FilaUnicaBundle:Relatorio:relatorioVagas.html.twig', array(
            'unidades' => $unidades,
            'anosEscolares' => $anosEscolares,
            'numerosVagas' => $numerosVagas,
            'totalGeral' => $totalGeral
        ));
    }
    
    /**
     * Método que gera um relatório de número
     * @return type
     */
    public function gerarPrevisaoFilaAction() {
        $zoneamentos = $this->getDoctrine()->getRepository('FilaUnicaBundle:Zoneamento')->findAll();
        $anosEscolares = new ArrayCollection(
            $this->getDoctrine()->getRepository('FilaUnicaBundle:AnoEscolar')->findAll()
        );
        foreach($anosEscolares->toArray() as $a) {
            $limiteInferior = $a->getDataLimiteInferior()->add(new \DateInterval('P1Y'));
            $limiteSuperior = $a->getDataLimiteSuperior()->add(new \DateInterval('P1Y'));
            $a->setDataLimiteInferior($limiteInferior);
            $a->setDataLimiteSuperior($limiteSuperior);
        }
        
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('c.dataNascimento, z.id AS zoneamento')
            ->from('FilaUnicaBundle:Inscricao', 'i')
            ->join('i.crianca', 'c')->join('i.tipoInscricao', 't')
            ->join('i.status', 's')->join('i.zoneamento', 'z')
            ->where('i.ativo = true')->andWhere('(s.id = :emEspera OR s.id = :emReserva)')
            ->andWhere('t.id <> :transferencia')->andWhere('z.id = :zoneamento')
            ->setParameter('emEspera', Status::EM_ESPERA)->setParameter('emReserva', Status::EM_RESERVA)
            ->setParameter('transferencia', TipoInscricao::TRANSFERENCIA);
        
        $numerosInscricoes = array();
        $totalGeral = 0;
        foreach($anosEscolares->toArray() as $a) {
            $numerosInscricoes['total'][$a->getId()] = 0;
        }
        foreach($zoneamentos as $z) {
            //Montagem da estrutura para exibicao, iniciando com valor 0 cada contagem
            foreach($anosEscolares->toArray() as $a) {
                $numerosInscricoes[$z->getId()][$a->getId()] = 0;
            }
            $numerosInscricoes[$z->getId()]['total'] = 0;
            $inscricoes = $qb->setParameter('zoneamento', $z->getId())->getQuery()->getResult();
            //Preenchimento com as somatórias
            foreach($inscricoes as $i) {
                $ano = $this->definirFuturoAnoEscolar($i['dataNascimento'], $anosEscolares);
                if($ano) {
                    $numerosInscricoes[$i['zoneamento']][$ano->getId()]++;
                    $numerosInscricoes['total'][$ano->getId()]++;
                    $numerosInscricoes[$i['zoneamento']]['total']++;
                    $totalGeral++;
                }
            }
        }

        return $this->render('FilaUnicaBundle:Relatorio:relatorioPrevisaoFila.html.twig', array(
            'zoneamentos' => $zoneamentos,
            'anosEscolares' => $anosEscolares,
            'numerosInscricoes' => $numerosInscricoes,
            'totalGeral' => $totalGeral
        ));
    }
    
    private function definirFuturoAnoEscolar(\DateTime $dataNascimento, ArrayCollection $anosEscolares) {
        $ano = $anosEscolares->filter(function($a) use ($dataNascimento) {
            return 
                $dataNascimento->getTimestamp() >= $a->getDataLimiteInferior()->getTimestamp()
                && $dataNascimento->getTimestamp() <= $a->getDataLimiteSuperior()->getTimestamp();
        });
        return $ano->isEmpty() ? null : $ano->first();
    }
    
}
