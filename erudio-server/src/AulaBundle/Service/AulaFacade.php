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

use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Psr\Log\LoggerInterface;
use CoreBundle\ORM\AbstractFacade;
use CoreBundle\ORM\Exception\IllegalOperationException;

class AulaFacade extends AbstractFacade {
    
    function __construct(RegistryInterface $doctrine, LoggerInterface $logger) {
        parent::__construct($doctrine, $logger);
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
    
    protected function beforeCreate($aula) {
        $hoje = new \DateTime();
        if ($hoje < $aula->getDia()->getData() && count($aula->getChamada())) {
            throw new IllegalOperationException('A chamada não pode ser realizada antes da data da aula');
        }
    }
    
}

