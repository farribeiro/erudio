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

namespace QuadroHorarioBundle\Service;

use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;

class HorarioFacade extends AbstractFacade {
    
    function getEntityClass() {
        return 'QuadroHorarioBundle:Horario';
    }
    
    function queryAlias() {
        return 'h';
    }
    
    function parameterMap() {
        return [
            'quadroHorario' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('h.quadroHorario = :quadroHorario')->setParameter('quadroHorario', $value);
            },
            'diaSemana' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('diaSemana = :diaSemana')->setParameter('diaSemana', $value);
            },
            'turma' => function(QueryBuilder $qb, $value) {
                $qb->join('h.disciplinasAlocadas', 'disciplinaAlocada')
                    ->addSelect('disciplinaAlocada')
                    ->andWhere('disciplinaAlocada.ativo = true')
                    ->join('disciplinaAlocada.disciplina', 'disciplina')
                    ->addSelect('disciplina')
                    ->andWhere('disciplina.turma = :turma')
                    ->setParameter('turma', $value);
            }
        ];
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->join('h.diaSemana', 'diaSemana');
    }
    
    protected function selectMap() {
        return ['h', 'diaSemana'];
    }
    
}
