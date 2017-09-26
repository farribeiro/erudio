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

namespace AulaBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;
use CoreBundle\ORM\AbstractFacade;
use CoreBundle\ORM\Exception\IllegalOperationException;
use CoreBundle\Event\EntityEvent;
use AulaBundle\Entity\Aula;

class AulaFacade extends AbstractFacade {
    
    function __construct(RegistryInterface $doctrine, LoggerInterface $logger, 
            EventDispatcherInterface $eventDispatcher) {
        parent::__construct($doctrine, $logger, $eventDispatcher);
    }
    
    function getEntityClass() {
        return 'AulaBundle:Aula';
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
                $qb->andWhere(':disciplina MEMBER OF a.disciplinasOfertadas')
                   ->setParameter('disciplina', $value);
            },
            'horario' => function(QueryBuilder $qb, $value) {
                $qb->andWhere(is_null($value) ? 'a.horario IS NULL' : 'a.horario = :horario')
                   ->setParameter('horario', $value);
            },
            'turma' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('turma.id = :turma')->setParameter('turma', $value);
            },
            'dataInicio' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('dia.data > :dataInicio')->setParameter('dataInicio', $value->format('Y-m-d'));
            }
       ];
    }
    
    function uniqueMap($aula) {
        return [[
            'matricula' => $aula->getDia(), 
            'turma' => $aula->getTurma(), 
            'horario' => $aula->getHorario()
        ]];
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->join('a.turma', 'turma')->join('a.dia', 'dia');    
    }
    
    protected function beforeCreate($aula) {
        if ($aula->getTurma()->getEtapa()->getFrequenciaUnificada()) {
            $aula->setDisciplinasOfertadas($this->getDisciplinasPorProfessor($aula));
        }
        $this->validarAula($aula);
    }
    
    protected function afterCreate($aula) {
        EntityEvent::createAndDispatch($aula, EntityEvent::ACTION_CREATED, $this->eventDispatcher);
    }


    protected function getDisciplinasPorProfessor(Aula $aula) {
        return $aula->getTurma()->getDisciplinas()->filter(function($d) use ($aula) {
            return $d->getProfessores()->exists(function($i, $p) use ($aula) {
                return $p->getFuncionario() === $aula->getProfessor();
            });
        });
    }
    
    protected function validarAula(Aula $aula) {
        if (!$aula->getTurma()->getEtapa()->getFrequenciaUnificada() && $aula->getHorario()->getQuadroHorario() !== $aula->getTurma()->getQuadroHorario()) {
            throw new IllegalOperationException('Aulas cuja frequência é controlada por disciplina devem especificar o horário');
        }
        if (!$aula->getDisciplinasOfertadas()->count()) {
            throw new IllegalOperationException('Não é possível criar uma aula sem disciplinas associadas');
        }
        if ($aula->getDia()->getCalendario() !== $aula->getTurma()->getCalendario()) {
            throw new IllegalOperationException('Calendário do dia da aula deve ser o mesmo adotado pela turma');
        }
    }
    
}

