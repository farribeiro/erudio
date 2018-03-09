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

namespace CursoBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;
use CoreBundle\Event\EntityEvent;
use CoreBundle\ORM\Exception\IllegalUpdateException;
use CoreBundle\ORM\Exception\IllegalOperationException;
use CursoBundle\Entity\Turma;
use CursoBundle\Entity\DisciplinaOfertada;
use MatriculaBundle\Service\EnturmacaoFacade;

class TurmaFacade extends AbstractFacade {
    
    private $disciplinaOfertadaFacade;
    private $enturmacaoFacade;
    
    function __construct(RegistryInterface $doctrine, EventDispatcherInterface $eventDispatcher,
            EnturmacaoFacade $enturmacaoFacade, DisciplinaOfertadaFacade $disciplinaFacade) {
        parent::__construct($doctrine, null, $eventDispatcher);
        $this->disciplinaOfertadaFacade = $disciplinaFacade;
        $this->enturmacaoFacade = $enturmacaoFacade;
    }
    
    function getEntityClass() {
        return 'CursoBundle:Turma';
    }
    
    function removerAgrupamento(Turma $turma) {
        $turma->setAgrupamento(null);
        $this->orm->getManager()->flush();
    }
    
    function queryAlias() {
        return 't';
    }
    
    function parameterMap() {
        return [
            'nome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('t.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
            'apelido' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('t.apelido LIKE :apelido')->setParameter('apelido', '%' . $value . '%');
            },
            'calendario' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('calendario = :calendario')->setParameter('calendario', $value);
            },
            'calendario_ano' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('calendario.dataInicio LIKE :ano')->setParameter('ano', $value . '%');
            },
            'status' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('t.status = :status')->setParameter('status', $value);
            },                    
            'encerrado' => function(QueryBuilder $qb, $value) {
                $operator = $value ? '=' : '<>';
                $qb->andWhere("t.status ${operator} :encerrado")->setParameter('encerrado', Turma::STATUS_ENCERRADO);
            },
            'curso' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('curso = :curso')->setParameter('curso', $value);
            },
            'etapa' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('etapa = :etapa')->setParameter('etapa', $value);
            },
            'etapa_ordem' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('etapa.ordem = :ordemEtapa')->setParameter('ordemEtapa', $value);
            },
            'agrupamentoDisciplinas' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('agrupamentoDisciplinas = :agrupamentoDisciplinas')->setParameter('agrupamentoDisciplinas', $value);
            },
            'quadroHorario' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('t.quadroHorario = :quadroHorario')->setParameter('quadroHorario', $value);
            },
            'agrupamento' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('agrupamento = :agrupamento')->setParameter('agrupamento', $value);
            },
            'unidadeEnsino' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('unidadeEnsino = :unidadeEnsino')->setParameter('unidadeEnsino', $value);
            }
        ];
    }
    
    protected function selectMap() {
        return ['t', 'etapa', 'unidadeEnsino', 'turno', 'curso', 'sistemaAvaliacao', 'agrupamento', 'agrupamentoDisciplinas'];
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->join('t.etapa', 'etapa')
           ->join('t.unidadeEnsino', 'unidadeEnsino')
           ->join('t.turno', 'turno')
           ->join('t.calendario', 'calendario')
           ->leftJoin('t.agrupamento', 'agrupamento')
           ->leftJoin('t.agrupamentoDisciplinas', 'agrupamentoDisciplinas')
           ->join('etapa.curso', 'curso')
           ->join('etapa.sistemaAvaliacao', 'sistemaAvaliacao')
           ->addOrderBy('etapa.curso','ASC')
           ->addOrderBy('etapa.ordem','ASC')
           ->addOrderBy('t.nome', 'ASC');
    }
    
    protected function beforeCreate($turma) {
        if ($turma->getEtapa()->getIntegral() && $turma->getPeriodo()) {
            throw new IllegalOperationException(
                'Turmas de etapa integral não devem ser vinculadas a um período do calendário'
            );
        }
    }
    
    protected function beforeRemove($turma) {
        if ($turma->getEnturmacoes()->count() > 0) {
            throw new IllegalUpdateException(
                IllegalUpdateException::ILLEGAL_STATE_TRANSITION, 
                'Operação não permitida, não é possível excluir uma turma com alunos enturmados'
            );
        }
    }
    
    protected function afterCreate($turma) {
        $this->criarDisciplinas($turma);
        EntityEvent::createAndDispatch($turma, EntityEvent::ACTION_CREATED, $this->eventDispatcher);
    }

    protected function afterUpdate($turma) {
        if ($turma->getTotalEnturmacoes() > $turma->getLimiteAlunos()) {
            throw new IllegalUpdateException(
                IllegalUpdateException::FINAL_STATE, 
                'Operação não permitida, não é possível diminuir a quantidade de vagas abaixo da quantidade de enturmações atual'
            );
        }
        if ($turma->getEncerrado()) {
            $this->finalizar($turma);
        }
        EntityEvent::createAndDispatch($turma, EntityEvent::ACTION_UPDATED, $this->eventDispatcher);
    }
    
    protected function afterRemove($turma) {
        $this->encerrarDisciplinas($turma);
        EntityEvent::createAndDispatch($turma, EntityEvent::ACTION_REMOVED, $this->eventDispatcher);
    }
    
    private function criarDisciplinas(Turma $turma) {
         if ($turma->getEtapa()->getIntegral()) {
            foreach ($turma->getEtapa()->getDisciplinas() as $disciplina) {
                $disciplinaOfertada = new DisciplinaOfertada($turma, $disciplina);
                $this->disciplinaOfertadaFacade->create($disciplinaOfertada);
            }
        } else if ($turma->getAgrupamentoDisciplinas()) {
            foreach ($turma->getAgrupamentoDisciplinas()->getDisciplinas() as $disciplina) {
                $disciplinaOfertada = new DisciplinaOfertada($turma, $disciplina);
                $this->disciplinaOfertadaFacade->create($disciplinaOfertada);
            }
        } else {
            throw new IllegalOperationException(
                    'Uma turma de etapa não integral deve ser vinculada a um agrupamento de disciplinas');
        }
    }
    
    private function encerrarDisciplinas(Turma $turma) {
        foreach ($turma->getDisciplinas() as $disciplina) {
            $this->disciplinaOfertadaFacade->remove($disciplina);
        }
    }
 
    function finalizar(Turma $turma) {
        $turma->encerrar();
        foreach ($turma->getEnturmacoes() as $enturmacao) {
            $this->enturmacaoFacade->finalizar($enturmacao);
        }
    }
    
}
