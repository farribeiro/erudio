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

namespace MatriculaBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;
use CoreBundle\Event\EntityEvent;
use CoreBundle\ORM\Exception\IllegalOperationException;
use CursoBundle\Entity\Etapa;
use MatriculaBundle\Entity\Matricula;
use MatriculaBundle\Entity\DisciplinaCursada;
use MatriculaBundle\Entity\EtapaCursada;

class EtapaCursadaFacade extends AbstractFacade {
   
    private $disciplinaCursadaFacade;
    
    function __construct(RegistryInterface $doctrine, LoggerInterface $logger, 
            EventDispatcherInterface $eventDispatcher, DisciplinaCursadaFacade $disciplinaCursadaFacade) {
        parent::__construct($doctrine, $logger, $eventDispatcher);
        $this->disciplinaCursadaFacade = $disciplinaCursadaFacade;
    }
    
    function getEntityClass() {
        return 'MatriculaBundle:EtapaCursada';
    }

     function queryAlias() {
        return 'e';
    }
    
    function parameterMap() {
        return [
            'matricula' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('e.matricula = :matricula')->setParameter('matricula', $value);
            },
            'ano' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('e.ano = :ano')->setParameter('ano', $value);
            },
            'etapa' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('e.etapa = :etapa')->setParameter('etapa', $value);
            },
            'status' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('e.status = :status')->setParameter('status', $value);
            },
            'enturmacao' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('e.enturmacao = :enturmacao')->setParameter('enturmacao', $value);
            }
        ];
    }
    
    function uniqueMap($etapa) {
        $constraints = [[
            'matricula' => $etapa->getMatricula(), 
            'ano' => $etapa->getAno(), 
            'etapa' => $etapa->getEtapa(),
            'status' => $etapa->getStatus()
        ]];
        if ($etapa->getEnturmacao()) {
            $constraints[] = ['enturmacao' => $etapa->getEnturmacao()];
        }
        return $constraints;
    }
    
    protected function beforeCreate($etapaCursada) {
        $quantidadeEtapas = $etapaCursada->getMatricula()->getCurso()->getEtapas()->count();
        if (!$etapaCursada->getAuto() && $etapaCursada->getEtapa()->getOrdem() >= $quantidadeEtapas) {
            throw new IllegalOperationException('Última etapa do curso não pode ser inserida manualmente no histórico');
        }
    }
    
    protected function afterCreate($etapaCursada) {
        EntityEvent::createAndDispatch($etapaCursada, EntityEvent::ACTION_CREATED, $this->eventDispatcher);
    }
    
    protected function beforeApplyChanges($etapaCursada, $patch) {
        if ($etapaCursada->getAuto()) {
            throw new IllegalOperationException('Etapas cursadas geradas pelo sistema não podem ser modificadas');
        }
    }
    
    protected function beforeRemove($etapaCursada) {
        if ($etapaCursada->getAuto()) {
            throw new IllegalOperationException('Etapas cursadas geradas pelo sistema não podem ser excluídas');
        }
    }
    
    function isCompleta(Matricula $matricula, Etapa $etapa) {
        $disciplinasCursadas = $this->disciplinaCursadaFacade->findFinalizadas($matricula, $etapa);
        $disciplinas = $etapa->getDisciplinas()->toArray();
        if (count($disciplinasCursadas) < count($disciplinas)) {
            return false;
        }
        $aprovacoes = array_filter($disciplinasCursadas, function($d) {
            return $d->getStatus() != DisciplinaCursada::STATUS_REPROVADO;
        });
        $numeroAprovacoes = array_reduce($disciplinas, function($count, $d) use ($aprovacoes) {
            return $count + ($d->possuiEquivalenteCursada($aprovacoes) ? 1 : 0);
        }, 0);
        return $numeroAprovacoes === count($disciplinas)
                ? EtapaCursada::STATUS_APROVADO : EtapaCursada::STATUS_REPROVADO;
    }
    
}
