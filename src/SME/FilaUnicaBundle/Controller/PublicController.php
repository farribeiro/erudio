<?php

namespace SME\FilaUnicaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use SME\FilaUnicaBundle\Entity\Inscricao;
use SME\FilaUnicaBundle\Entity\Zoneamento;
use SME\FilaUnicaBundle\Entity\AnoEscolar;

class PublicController extends Controller
{

    public function consultarInscricaoAction($protocolo)
    {
        $inscricao = $this->getDoctrine()->getRepository('FilaUnicaBundle:Inscricao')->findOneBy(array('protocolo' => $protocolo));
        if($inscricao instanceof Inscricao) {
            $fila = $this->gerarFilaReal($inscricao->getZoneamento(), $inscricao->getAnoEscolar());
            $arrayFila = array();
            foreach($fila as $insc) {
                $arrayFila[] = (object) array('protocolo' => $insc->getProtocolo(), 'dataInscricao' => $insc->getDataCadastro());
            }
            return new JsonResponse(
                    ['status' => true, 'inscricao' => $inscricao, 'fila' => $arrayFila], 
                    200, 
                    ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']
                );
        } else {
            return new JsonResponse(
                    ['status' => false, 'mensagem' => 'Protocolo não encontrado'], 
                    404,
                    ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']
                );
        }
    }
    
    public function consultarChamadasAction(Zoneamento $zoneamento) {
        $chamadas = $this->getDoctrine()->getManager()->createQueryBuilder() 
                ->select('vaga')
                ->from('FilaUnicaBundle:Vaga','vaga')
                ->join('vaga.unidadeEscolar','unidade')
                ->join('unidade.zoneamento','zoneamento')
                ->where('vaga.inscricaoChamada IS NOT NULL')
                ->andWhere('zoneamento.id = :zoneamento')
                ->setParameter('zoneamento', $zoneamento->getId())
                ->getQuery()->getResult();
        return new JsonResponse(array('zoneamento' => $zoneamento->getNome(),'chamadas' => $chamadas));
    }
    
    private function gerarFilaReal(Zoneamento $zoneamento, AnoEscolar $anoEscolar) {
        return $this->get('fila_unica')->gerarFilaReal($zoneamento, $anoEscolar);
    }
    
}

