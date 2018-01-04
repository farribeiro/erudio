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
use CoreBundle\ORM\AbstractFacade;

class FrequenciaFacade extends AbstractFacade {
    
    function getEntityClass() {
        return 'AulaBundle:Frequencia';
    }
    
    function queryAlias() {
        return 'f';
    }
    
    function parameterMap() {
        return [
            'status' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('f.status = :status')->setParameter('status', $value);
            },
            'aula' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('aula.id = :aula')->setParameter('aula', $value);
            },
            'aula_dataInicio' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('dia.data >= :dataInicio')->setParameter('dataInicio', $value->format('Y-m-d'));
            },
            'aula_dataFim' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('dia.data <= :dataFim')->setParameter('dataFim', $value->format('Y-m-d'));
            },
            'mes' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('dia.data LIKE :mes')->setParameter('mes', '%-' . $value . '-%');
            },
            'turma' => function(QueryBuilder $qb, $value) {
                $qb->join('aula.disciplinaOfertada', 'ofertada')->join('ofertada.turma', 'turma')
                   ->andWhere('turma.id = :turma')->setParameter('turma', $value)->setMaxResults(1)->orderBy('f.id','DESC');
            },
            'disciplinaCursada' => function(QueryBuilder $qb, $value) {
                $qb->andWhere(':disciplinaCursada MEMBER OF f.disciplinasCursadas')
                   ->setParameter('disciplinaCursada', $value);
            }
        ];
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->join('f.aula', 'aula')->join('aula.dia', 'dia');
    }
    
}

