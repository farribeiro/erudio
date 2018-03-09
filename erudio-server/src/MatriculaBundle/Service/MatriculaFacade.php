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

use CoreBundle\ORM\AbstractFacade;
use CoreBundle\Event\EntityEvent;
use CoreBundle\ORM\Exception\IllegalOperationException;
use Doctrine\ORM\QueryBuilder;
use MatriculaBundle\Entity\Matricula;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MatriculaFacade extends AbstractFacade {
    
    function __construct(RegistryInterface $doctrine, LoggerInterface $logger, 
            EventDispatcherInterface $eventDispatcher) {
        parent::__construct($doctrine, $logger, $eventDispatcher);
    }
    
    function getEntityClass() {
        return 'MatriculaBundle:Matricula';
    }
    
    function queryAlias() {
        return 'm';
    }
    
    function parameterMap() {
        return [
            'aluno' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('aluno.id = :aluno')->setParameter('aluno', $value);
            },
            'aluno_nome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('aluno.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
            'aluno_dataNascimento' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('aluno.dataNascimento = :dataNascimento')->setParameter('dataNascimento', $value);
            },
            'aluno_deficiente' => function(QueryBuilder $qb, $value) {
                $operador = $value ? 'NOT' : '';
                $qb->andWhere("aluno.particularidades IS {$operador} EMPTY");
            },
            'curso' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('curso.id = :curso')->setParameter('curso', $value);
            },
            'etapa' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('m.etapa = :etapa')->setParameter('etapa', $value);
            },
            'unidadeEnsino' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('m.unidadeEnsino = :unidadeEnsino')->setParameter('unidadeEnsino', $value);
            },
            'codigo' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('m.codigo LIKE :codigo')->setParameter('codigo', '%' . $value . '%');
            },
            'status' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('m.status = :status')->setParameter('status', $value);
            },
            'enturmado' => function(QueryBuilder $qb, $value) {
                $operator = $value ? '>' : '=';
                $qb->andWhere('(SELECT COUNT(en.id) FROM MatriculaBundle:Enturmacao AS en WHERE en.matricula = m'
                    . " AND en.ativo = true AND en.encerrado = false AND en.concluido = false) {$operator} 0");
            }
        ];
    }
    
    function uniqueMap($matricula) {
        return [[
            'curso' => $matricula->getCurso()->getId(), 
            'aluno' => $matricula->getAluno()->getId(), 
            'status' => Matricula::STATUS_CURSANDO
        ]];
    }
    
    protected function selectMap() {
        return ['m', 'aluno', 'curso', 'unidadeEnsino', 'etapa', 'tipoUnidadeEnsino', 'instituicao'];
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->join('m.aluno', 'aluno')
           ->join('m.unidadeEnsino', 'unidadeEnsino')
           ->join('m.curso', 'curso')
           ->join('unidadeEnsino.tipo', 'tipoUnidadeEnsino')
           ->leftJoin('unidadeEnsino.instituicaoPai', 'instituicao')
           ->leftJoin('m.etapa', 'etapa')
           ->orderBy('aluno.nome', 'ASC');
    }
    
    protected function beforeCreate($matricula) {
        if ($this->jaExiste($matricula)) {
            throw new IllegalOperationException('Pessoa já possui matrícula em aberto neste curso');
        }
        $this->gerarCodigo($matricula);
    }
    
    protected function afterCreate($matricula) {
        $this->orm->getManager()->detach($matricula);
        EntityEvent::createAndDispatch($matricula, EntityEvent::ACTION_CREATED, $this->eventDispatcher);
    }
    
    protected function beforeApplyChanges($matricula, $patch) {
        if ($patch->getStatus() === Matricula::STATUS_CANCELADO) {
            $agora = new \DateTime();
            $tempoDecorrido = $agora->diff($matricula->getDataCadastro(), true);
            if ($tempoDecorrido->days > 7) {
                throw new IllegalOperationException(
                    'Matrículas só podem ser canceladas em até 7 dias a contar de seu cadastro'
                );
            }
        }
    }
    
    private function jaExiste(Matricula $matricula) {
        $qb = $this->orm->getManager()->createQueryBuilder();
        return $qb->select('COUNT(m.id)')
            ->from($this->getEntityClass(), 'm')
            ->join('m.aluno', 'aluno')->join('m.curso', 'curso')
            ->where('m.ativo = true')
            ->andWhere('aluno.id = :aluno')->setParameter('aluno', $matricula->getAluno()->getId())
            ->andWhere('curso.id = :curso')->setParameter('curso', $matricula->getCurso()->getId())
            ->andWhere('m.status IN (:status)')
            ->setParameter('status', [Matricula::STATUS_CURSANDO, Matricula::STATUS_TRANCADO])
            ->getQuery()->getSingleScalarResult() > 0;
    }
    
    private function gerarCodigo(Matricula $matricula) {
        $now = new \DateTime();
        $ano = $now->format('Y');
        $qb = $this->orm->getManager()->createQueryBuilder()
            ->select('MAX(m.codigo)')
            ->from($this->getEntityClass(), 'm')
            ->where('m.codigo LIKE :codigo')
            ->setParameter('codigo', $ano . $matricula->getCurso()->getId() . '%');
        $numero = $qb->getQuery()->getSingleScalarResult();
        if (!$numero) {
            $numero = $ano . $matricula->getCurso()->getId() . '00000';
        }
        $matricula->definirCodigo($numero + 1);
    }
    
}

