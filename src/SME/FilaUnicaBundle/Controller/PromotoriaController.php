<?php

namespace SME\FilaUnicaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\FilaUnicaBundle\Entity\Inscricao;
use SME\FilaUnicaBundle\Report\InscricaoReport;

class PromotoriaController extends Controller {
    
    public function formFilaAction() {
        return $this->render('FilaUnicaBundle:Promotoria:formFila.html.twig', array(
            'zoneamentos' => $this->getDoctrine()->getRepository('FilaUnicaBundle:Zoneamento')->findAll(),
            'anosEscolares' => $this->getDoctrine()->getRepository('FilaUnicaBundle:AnoEscolar')->findAll()
        ));
    }
    
    public function exibirFilaAction() {
        $post = $this->getRequest()->request;
        $inscricao = $this->getDoctrine()->getRepository('FilaUnicaBundle:Inscricao')->findOneBy(array('protocolo' => $post->get('protocolo')));
        if($inscricao instanceof Inscricao) {
            $aviso = 'Protocolo encontrado, status: ' . $inscricao->getStatus()->getNome();
            $zoneamento = $inscricao->getZoneamento();
            $anoEscolar = $inscricao->getAnoEscolar();
        } else {
            $aviso = 'Protocolo de busca não informado ou inválido';
            $zoneamento = $this->getDoctrine()->getRepository('FilaUnicaBundle:Zoneamento')->find($post->getInt('zoneamento'));
            $anoEscolar = $this->getDoctrine()->getRepository('FilaUnicaBundle:AnoEscolar')->find($post->getInt('ano'));
        }
        $fila = $this->get('fila_unica')->gerarFilaReal($zoneamento, $anoEscolar);
        return $this->render('FilaUnicaBundle:Promotoria:fila.html.twig', array(
            'inscricoes' => $fila,
            'protocolo' => $post->get('protocolo'),
            'aviso' => $aviso
        ));
    }
    
    public function formPesquisaAction() {
        $unidades =  $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('u')->from('FilaUnicaBundle:UnidadeEscolar', 'u')
                ->join('u.entidade', 'e')->join('e.pessoaJuridica', 'p')
                ->orderBy('p.nome','ASC')->getQuery()->getResult();
        return $this->render('FilaUnicaBundle:Promotoria:formPesquisa.html.twig', array(
            'zoneamentos' => $this->getDoctrine()->getRepository('FilaUnicaBundle:Zoneamento')->findAll(),
            'anosEscolares' => $this->getDoctrine()->getRepository('FilaUnicaBundle:AnoEscolar')->findAll(),
            'tiposInscricao' => $this->getDoctrine()->getRepository('FilaUnicaBundle:TipoInscricao')->findAll(),
            'status' => $this->getDoctrine()->getRepository('FilaUnicaBundle:Status')->findAll()
        ));
    }
    
    public function pesquisarAction() {
        $post = $this->getRequest()->request;
        $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
        $restricoes = $qb->expr()->andX();
        $qb = $qb->select('inscricao')
                    ->from('FilaUnicaBundle:Inscricao','inscricao')
                    ->join('inscricao.tipoInscricao','tipoInscricao')
                    ->join('inscricao.status','status')
                    ->join('inscricao.unidadeDestino', 'unidadeDestino')
                    ->join('unidadeDestino.zoneamento','zoneamento')
                    ->join('inscricao.anoEscolar','anoEscolar')
                    ->join('inscricao.crianca','crianca')
                    ->where($restricoes);
        if($post->get('protocolo')) {
            $restricoes = $restricoes->add($qb->expr()->eq('inscricao.protocolo', $post->get('protocolo')));
        }
        if($post->get('nome')) {
            $restricoes = $restricoes->add($qb->expr()->like('crianca.nome', ':nome'));
            $qb->setParameter('nome', '%' . $post->get('nome') . '%');
        }
        if($post->getInt('zoneamento')) {
            $restricoes = $restricoes->add($qb->expr()->eq('zoneamento.id', $post->getInt('zoneamento')));
        }
        if($post->getInt('anoEscolar')) {
            $restricoes = $restricoes->add($qb->expr()->eq('anoEscolar.id', $post->getInt('anoEscolar')));
        }
        if($post->getInt('tipoInscricao')) {
            $restricoes = $restricoes->add($qb->expr()->eq('tipoInscricao.id', $post->getInt('tipoInscricao')));
        }
        if($post->getInt('status')) {
            $restricoes = $restricoes->add($qb->expr()->eq('status.id', $post->getInt('status')));
        }
        if(is_numeric($post->get("processoJudicial"))) {
            $restricoes = $restricoes->add($qb->expr()->eq('inscricao.processoJudicial', $post->getInt('processoJudicial')));
        }
        return $this->render('FilaUnicaBundle:Promotoria:inscricoes.html.twig', array(
            'inscricoes' => $qb->getQuery()->getResult()
        ));
    }
    
    public function imprimirAction(Inscricao $inscricao) {
        $impressao = new InscricaoReport();
        $impressao->setInscricao($inscricao);
        return $this->get('pdf')->render($impressao);
    }
    
    public function glossarioAction() {
        return $this->render('FilaUnicaBundle:Promotoria:glossario.html.twig');
    }
    
}
