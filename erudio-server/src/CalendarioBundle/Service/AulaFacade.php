<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *    @author Municipio de Itajaí - Secretaria de Educação - DITEC         *
 *    @updated 30/06/2016                                                  *
 *    Pacote: Erudio                                                       *
 *                                                                         *
 *    Copyright (C) 2016 Prefeitura de Itajaí - Secretaria de Educação     *
 *                       DITEC - Diretoria de Tecnologias educacionais     *
 *                        ditec@itajai.sc.gov.br                           *
 *                                                                         *
 *    Este  programa  é  software livre, você pode redistribuí-lo e/ou     *
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme     *
 *    publicada pela Free  Software  Foundation,  tanto  a versão 2 da     *
 *    Licença   como  (a  seu  critério)  qualquer  versão  mais  nova.    *
 *                                                                         *
 *    Este programa  é distribuído na expectativa de ser útil, mas SEM     *
 *    QUALQUER GARANTIA. Sem mesmo a garantia implícita de COMERCIALI-     *
 *    ZAÇÃO  ou  de ADEQUAÇÃO A QUALQUER PROPÓSITO EM PARTICULAR. Con-     *
 *    sulte  a  Licença  Pública  Geral  GNU para obter mais detalhes.     *
 *                                                                         *
 *    Você  deve  ter  recebido uma cópia da Licença Pública Geral GNU     *
 *    junto  com  este  programa. Se não, escreva para a Free Software     *
 *    Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA     *
 *    02111-1307, USA.                                                     *
 *                                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace CalendarioBundle\Service;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Psr\Log\LoggerInterface;
use CoreBundle\ORM\AbstractFacade;
use CoreBundle\ORM\Exception\IllegalOperationException;
use CalendarioBundle\Entity\Aula;
use CalendarioBundle\Entity\HorarioDisciplina;
use CursoBundle\Entity\Turma;

class AulaFacade extends AbstractFacade {
    
    function __construct(RegistryInterface $doctrine, LoggerInterface $logger) {
        parent::__construct($doctrine, $logger);
    }
    
    function getEntityClass() {
        return 'CalendarioBundle:Aula';
    }
    
    function queryAlias() {
        return 'a';
    }
    
    function parameterMap() {
        return [
            'dia' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('dia.id = :dia')->setParameter('dia', $value);
            },
            'mes' => function(QueryBuilder $qb, $value) {
                $mes = $value < 10 ? '0' . $value : $value;
                $qb->andWhere('dia.data LIKE :mes')
                   ->setParameter('mes', '%-' . $mes . '-%');
            },
            'disciplina' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('disciplina.id = :disciplina')->setParameter('disciplina', $value);
            },
            'disciplinas' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('disciplina.id IN (:disciplinas)')->setParameter('disciplinas', $value);
            },
            'horario' => function(QueryBuilder $qb, $value) {
                $qb->join('a.horario', 'horario')
                   ->andWhere('horario.id = :horario')->setParameter('horario', $value);
            },
            'turma' => function(QueryBuilder $qb, $value) {
                $qb->join('disciplina.turma', 'turma')
                   ->andWhere('turma.id = :turma')->setParameter('turma', $value);
            },
            'dataInicio' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('dia.data > :dataInicio')->setParameter('dataInicio', $value->format('Y-m-d'));
            }
       ];
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->join('a.disciplinaOfertada', 'disciplina')->join('a.dia', 'dia');    
    }
    
    function gerarAulas(Turma $turma, array $horariosTurma) {
        if ($turma->getStatus() != Turma::STATUS_CRIADO) { 
            throw new IllegalOperationException('As aulas desta turma já foram definidas');
        }
        $aulasCriadas = 0;
        $horarios = new ArrayCollection($horariosTurma);
        $dias = $turma->getCalendario()->getDias()->matching(
            Criteria::create()->where(Criteria::expr()->andX(              
                Criteria::expr()->eq('ativo', true), Criteria::expr()->eq('efetivo', true)
            ))
        );
        foreach($dias as $dia) {
            $horariosAula = $horarios->filter(function($h) use ($dia) {
                return $dia->getData()->format('w') + 1 == $h->getHorario()->getDiaSemana()->getDiaSemana();
            });
            foreach ($horariosAula as $h) {
                $aula = new Aula($h->getDisciplina(), $dia, $h->getHorario());
                $this->orm->getManager()->persist($aula);
                $aulasCriadas++;
            }
            $this->orm->getManager()->flush();
            $this->orm->getManager()->clear('CalendarioBundle:Aula');
        }
        if ($aulasCriadas > 0) {
            $turma->setStatus(Turma::STATUS_EM_ANDAMENTO);
            $this->orm->getManager()->flush();
        }
    }
    
    /**
     * 
     * @param type $turmaId
     * @param type $dataInicio
     */
    function trocarAulas(HorarioDisciplina $horario1, HorarioDisciplina $horario2, \DateTime $data = null) {
        $dataInicio = $data ? $data : new \DateTime();
        $aulas1 = $this->findAll([
            'horario' => $horario1->getHorario()->getId(), 
            'disciplina' => $horario1->getDisciplina()->getId(),
            'dataInicio' => $dataInicio
        ]);
        $aulas2 = $this->findAll([
            'horario' => $horario2->getHorario()->getId(), 
            'disciplina' => $horario2->getDisciplina()->getId(),
            'dataInicio' => $dataInicio
        ]);
        $numeroAulas1 = count($aulas1);
        $numeroAulas2 = count($aulas2);
        $this->logger->info('Existem ' . $numeroAulas1 . ' - ' . $numeroAulas2);
        $maiorAulas = $numeroAulas1 >= $numeroAulas2 ? $aulas1 : $aulas2;
        $menorAulas = $numeroAulas1 < $numeroAulas2 ? $aulas1 : $aulas2;
        $quantidadeTrocas = count($menorAulas);
        foreach ($maiorAulas as $k => $v) {
            if ($k < $quantidadeTrocas) {
                $v->trocarDataHorario($menorAulas[$k]);
            } else {
                //criar
            }
            $this->orm->getManager()->flush();
        }
        $this->logger->info($quantidadeTrocas . ' trocas de horário de aulas efetuados.');
    }
}

