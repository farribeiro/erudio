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
use CoreBundle\ORM\AbstractFacade;
use CoreBundle\ORM\Exception\IllegalOperationException;
use CalendarioBundle\Entity\Aula;
use CursoBundle\Entity\Turma;

class AulaFacade extends AbstractFacade {
    
    private $horarioDisciplinaFacade;
    
    function setHorarioDisciplinaFacade(HorarioDisciplinaFacade $horarioDisciplinaFacade) {
        $this->horarioDisciplinaFacade = $horarioDisciplinaFacade;
    }
    
    function getEntityClass() {
        return 'CalendarioBundle:Aula';
    }
    
    function queryAlias() {
        return 'a';
    }
    
    function parameterMap() {
        return array (
            'dia' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('dia.id = :dia')->setParameter('dia', $value);
            },
            'mes' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('dia.data LIKE :mes')
                   ->setParameter('mes', '%-' . $value . '-%');
            },
            'disciplina' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('disciplina.id = :disciplina')->setParameter('disciplina', $value);
            },
            'turma' => function(QueryBuilder $qb, $value) {
                $qb->join('disciplina.turma', 'turma')
                   ->andWhere('turma.id = :turma')->setParameter('turma', $value);
            }
        );
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->join('a.disciplinaOfertada', 'disciplina')->join('a.dia', 'dia');    
    }
    
    function gerarAulas($turmaId, $dataInicio = null) {
        $turma = $this->loadEntity($turmaId, 'CursoBundle:Turma');
        //if($turma->getStatus() != Turma::STATUS_CRIADO) { throw new IllegalOperationException('As aulas desta turma já foram definidas'); }
        $horarios = new ArrayCollection(
            $this->horarioDisciplinaFacade->findAll(['turma' => $turmaId])
        );
        $dias = $turma->getCalendario()->getDias()->matching(Criteria::create()->where(Criteria::expr()->eq('efetivo', true)));
        foreach($dias as $dia) {
            $horarios
                ->filter(function($h) use ($dia) {
                    return $dia->getData()->format('w') + 1 == $h->getHorario()->getDiaSemana()->getDiaSemana();
                })
                ->map(function($h) use ($dia) {
                    $disciplina = $h->getDisciplina(); 
                    $horario = $h->getHorario();
                    $aulaBusca = $this->orm->getRepository('CalendarioBundle:Aula')->findBy(
                        ['dia' => $dia, 'disciplinaOfertada' => $disciplina, 'horario' => $horario]
                    );
                    if (count($aulaBusca) == 0) {
                        $aula = new Aula($h->getDisciplina(), $dia, $h->getHorario());
                        $this->orm->getManager()->persist($aula);
                    }
                });
            $this->orm->getManager()->flush();
            $this->orm->getManager()->clear('CalendarioBundle:Aula');
        }
        $turma->setStatus(Turma::STATUS_EM_ANDAMENTO);
        $this->orm->getManager()->flush();
    }
    
    function gerarNovasAulas($turmaId, $dataInicio = null) {
        $turma = $this->loadEntity($turmaId, 'CursoBundle:Turma');
        $dias = $turma->getCalendario()->getDias()->matching( Criteria::create()->where(Criteria::expr()->eq('efetivo', true)) );
        $horarios = new ArrayCollection($this->horarioDisciplinaFacade->findAll(array('turma' => $turmaId)));
        $datas = array();
        foreach($dias as $dia) {
            $diaCalendario = $dia->getData()->format('Y-m-d');
            if ($diaCalendario >= $dataInicio) {
                $datas[] = $diaCalendario;
                $horarios
                    ->filter(function($h) use ($dia) {
                        return $dia->getData()->format('w') + 1 == $h->getHorario()->getDiaSemana()->getDiaSemana();
                    })
                    ->map(function($h) use ($dia) {
                        $disciplina = $h->getDisciplina(); $horario = $h->getHorario();
                        $aulaBusca = $this->orm->getRepository('CalendarioBundle:Aula')->findBy(array('dia'=>$dia,'disciplinaOfertada' => $disciplina, 'horario' => $horario));
                        if (count($aulaBusca) == 0) {
                            $aula = new Aula($h->getDisciplina(), $dia, $h->getHorario());
                            $this->orm->getManager()->persist($aula);
                        }
                    });
                $this->orm->getManager()->flush();
                $this->orm->getManager()->clear('CalendarioBundle:Aula');
            }
        }
        $turma->setStatus(Turma::STATUS_EM_ANDAMENTO);
        $this->orm->getManager()->flush();
    }
}

