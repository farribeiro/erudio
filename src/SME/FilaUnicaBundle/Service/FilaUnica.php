<?php

namespace SME\FilaUnicaBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\NoResultException;
use SME\FilaUnicaBundle\Entity\Inscricao;
use SME\FilaUnicaBundle\Entity\Zoneamento;
use SME\FilaUnicaBundle\Entity\AnoEscolar;
use SME\FilaUnicaBundle\Entity\TipoInscricao;
use SME\FilaUnicaBundle\Entity\Status;
use Symfony\Component\HttpFoundation\Response;

/**
 * Serviço da fila única
 * 
 * Permite obter a fila de espera para determinado zoneamento e ano escolar, respeitando
 * a ordem prioritária dos tipos de inscrição.
 * 
 */
class FilaUnica
{
    
    const ANO_ESCOLAR_INICIAL = 1;
    
    private $orm;
    
    public function __construct (Registry $doctrine) {
        $this->orm = $doctrine;
    }
    
    public function gerarFilaReal(Zoneamento $zoneamento, AnoEscolar $anoEscolar) {
        $tiposInscricao = $this->orm->getRepository('FilaUnicaBundle:TipoInscricao')->findBy(array(), array('prioridade' => 'DESC'));
        $fila = array();
        for($processoJudicial = 1; $processoJudicial >= 0; $processoJudicial--) {
            foreach($tiposInscricao as $tipo) {
                $qb = $this->orm->getEntityManager()->createQueryBuilder();
                $restricoes = $qb->expr()->andX()
                        ->add($qb->expr()->eq('inscricao.ativo', true))
                        ->add($qb->expr()->eq('tipo.id', $tipo->getId()))
                        ->add($qb->expr()->eq('status.id', Status::EM_ESPERA))
                        ->add($qb->expr()->eq('ano.id', $anoEscolar->getId()))
                        ->add($qb->expr()->eq('inscricao.processoJudicial', $processoJudicial));
                if($tipo->getId() === TipoInscricao::TRANSFERENCIA) {
                    $restricoes->add($qb->expr()->orX()
                        ->add($qb->expr()->eq('zoneamento1.id', $zoneamento->getId()))
                        ->add($qb->expr()->eq('zoneamento2.id', $zoneamento->getId()))
                    );
                } else {
                    $restricoes->add($qb->expr()->eq('zoneamento1.id', $zoneamento->getId()));
                }
                //Critérios da inclusão
                $qb = $qb->select('inscricao')
                        ->from('FilaUnicaBundle:Inscricao', 'inscricao')
                        ->join('inscricao.tipoInscricao', 'tipo')
                        ->join('inscricao.status', 'status')
                        ->join('inscricao.anoEscolar', 'ano')
                        ->join('inscricao.unidadeDestino', 'unidade1')
                        ->join('unidade1.zoneamento', 'zoneamento1')
                        ->leftJoin('inscricao.unidadeDestinoAlternativa', 'unidade2')
                        ->leftJoin('unidade2.zoneamento', 'zoneamento2')
                        ->where($restricoes);
                //Critérios da ordenação
                if($tipo->getId() === TipoInscricao::REGULAR && !$processoJudicial) {
                    $qb = $qb->addOrderBy('inscricao.rendaPontuada', 'ASC')
                             ->addOrderBy('inscricao.dataCadastro', 'ASC');
                } elseif($tipo->getId() === TipoInscricao::TRANSFERENCIA && !$processoJudicial) {
                    $qb = $qb->addOrderBy('inscricao.dataCadastro', 'ASC');
                } elseif($processoJudicial) {
                    $qb = $qb->addOrderBy('inscricao.dataProcessoJudicial', 'ASC');
                }
                $fila = \array_merge($fila, $qb->getQuery()->getResult());
            }
        }
        return $fila;
    }
       
    /*
        * Fila meramente para consulta
        */
    public function gerarFilaPublica(Zoneamento $zoneamento, AnoEscolar $anoEscolar) {
        $qb = $this->orm->getManager()->createQueryBuilder();
        $restricoes = $qb->expr()->andX()
                ->add($qb->expr()->eq('inscricao.ativo', true)) 
                ->add($qb->expr()->eq('inscricao.processoJudicial', 0)) //oculta processos judiciais
                ->add($qb->expr()->eq('inscricao.movimentacaoInterna', 0)) //oculta movimentações decorrentes de erros de cadastro
                ->add($qb->expr()->eq('tipo.id', TipoInscricao::REGULAR)) //oculta transferências
                ->add($qb->expr()->eq('status.id', Status::EM_ESPERA)) //oculta inscrições em reserva
                ->add($qb->expr()->eq('zoneamento.id', $zoneamento->getId()))
                ->add($qb->expr()->eq('ano.id', $anoEscolar->getId()));
        return $qb->select('inscricao')
                ->from('FilaUnicaBundle:Inscricao', 'inscricao')
                ->join('inscricao.tipoInscricao', 'tipo')
                ->join('inscricao.status', 'status')
                ->join('inscricao.zoneamento', 'zoneamento')
                ->join('inscricao.anoEscolar', 'ano')
                ->where($restricoes)
                ->addOrderBy('inscricao.rendaPontuada', 'ASC')
                ->addOrderBy('inscricao.dataCadastro', 'ASC')
                ->getQuery()->getResult();
    }

    public function reordenarFilaGeral() {
        $hoje = new \DateTime();
        if(((int)$hoje->format('d')) <= 3 && ((int)$hoje->format('m')) % 4 === 0) {
            $statusFila = $this->orm->getRepository('FilaUnicaBundle:Status')->find(Status::EM_ESPERA);
            $inscricoes = $this->orm->getManager()->createQuery(
                    'SELECT i FROM FilaUnicaBundle:Inscricao i JOIN i.status s JOIN i.tipoInscricao t'
                    . ' WHERE i.ativo = true AND (s.id = :reserva OR s.id = :espera) AND t.id = :tipo')
                    ->setParameter('reserva', Status::EM_RESERVA)
                    ->setParameter('espera', Status::EM_ESPERA)
                    ->setParameter('tipo', TipoInscricao::REGULAR)
                    ->getResult();
            foreach($inscricoes as $inscricao) {
                $inscricao->calcularPontuacao();
                $inscricao->setStatus($statusFila);
                $inscricao->setMovimentacaoInterna(0);
                $this->orm->getManager()->merge($inscricao);
            }
            $this->orm->getManager()->flush();
        } else {
            throw new \Exception('Operação bloqueada até a próxima data de reordenação');
        }
    }
    
    public function reordenarFilaParcial(Zoneamento $zoneamento, AnoEscolar $anoEscolar) {
        $statusFila = $this->orm->getRepository('FilaUnicaBundle:Status')->find(Status::EM_ESPERA);
        $qb = $this->orm->getManager()->createQueryBuilder();
        $restricoes = $qb->expr()->andX()
                ->add($qb->expr()->eq('inscricao.ativo', true)) 
                ->add($qb->expr()->eq('status.id', Status::EM_RESERVA))
                ->add($qb->expr()->eq('zoneamento.id', $zoneamento->getId()))
                ->add($qb->expr()->eq('ano.id', $anoEscolar->getId()));
        $inscricoes = $qb->select('inscricao')
                ->from('FilaUnicaBundle:Inscricao', 'inscricao')
                ->join('inscricao.status', 'status')
                ->join('inscricao.unidadeDestino', 'unidadeDestino')
                ->join('unidadeDestino.zoneamento', 'zoneamento')
                ->join('inscricao.anoEscolar', 'ano')
                ->where($restricoes)
                ->getQuery()->getResult();
        foreach($inscricoes as $inscricao) {
            $inscricao->calcularPontuacao();
            $inscricao->setStatus($statusFila);
            $this->orm->getManager()->merge($inscricao);
        }
        $this->orm->getManager()->flush();
    }

    public function aplicarViradaAnual() {
		try {
        $offset = 0;
        $size = 200;
        $now = new \DateTime();
        $statusRemovido = $this->orm->getRepository('FilaUnicaBundle:Status')->find(Status::REMOVIDO_NA_VIRADA);
        while($size == 200) {
           $inscricoes = $this->orm->getManager()->createQuery(
            'SELECT i FROM FilaUnicaBundle:Inscricao i JOIN i.status s JOIN i.tipoInscricao t'
            . ' WHERE i.ativo = true AND (s.id = :reserva OR s.id = :espera)' . ' ORDER BY i.dataModificacao ASC')
            ->setParameter('reserva', Status::EM_RESERVA)
            ->setParameter('espera', Status::EM_ESPERA)
			->setMaxResults(200)
            ->setFirstResult($offset)
            ->getResult();
           foreach($inscricoes as $inscricao) {
             try {
                 $this->definirAnoEscolar($inscricao);
             } catch(\Exception $ex) {
                 $inscricao->setAtivo(false);
                 $inscricao->setStatus($statusRemovido);
             }		
             $inscricao->setDataModificacao($now);
             $this->orm->getManager()->merge($inscricao);
            }
			$this->orm->getManager()->flush();
			$offset += 200;
            $size = count($inscricoes);
        }
		
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
			
        }
    }
    
    public function definirAnoEscolar(Inscricao $inscricao) {
        try {
            $anoEscolar = $this->orm->getManager()->createQuery(
                'SELECT a FROM FilaUnicaBundle:AnoEscolar a'
                . ' WHERE :dataNascimento BETWEEN a.dataLimiteInferior AND a.dataLimiteSuperior')
                ->setParameter('dataNascimento', $inscricao->getCrianca()->getDataNascimento()->format('Y-m-d'))
                ->getSingleResult();
            $inscricao->setAnoEscolar($anoEscolar);
        } catch(NoResultException $ex) {
            $now = new \DateTime();
            if($now->format('Y') === $inscricao->getCrianca()->getDataNascimento()->format('Y')) {
                $primeiroAno = $this->orm->getRepository('FilaUnicaBundle:AnoEscolar')->find(self::ANO_ESCOLAR_INICIAL);
                $inscricao->setAnoEscolar($primeiroAno);
            } else {
                throw new \Exception('A criança não possui idade compatível com nenhuma turma de ensino infantil. Data informada: ' 
                    . $inscricao->getCrianca()->getDataNascimento()->format('d/m/Y'));
            }
        }
    }
    
}

