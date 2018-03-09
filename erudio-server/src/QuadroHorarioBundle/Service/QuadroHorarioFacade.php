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

class QuadroHorarioFacade extends AbstractFacade {
    
    function getEntityClass() {
        return 'QuadroHorarioBundle:QuadroHorario';
    }
    
    function queryAlias() {
        return 'q';
    }
    
    function parameterMap() {
        return [
            'nome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('q.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
            'unidadeEnsino' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('q.unidadeEnsino = :unidade')->setParameter('unidade', $value);
            },
            'turno' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('q.turno = :turno')->setParameter('turno', $value);
            },
            'modelo_curso' => function(QueryBuilder $qb, $value) {
                $qb->join('q.modelo', 'modelo')
                   ->andWhere('modelo.curso = :curso')->setParameter('curso', $value);
            }
        ];
    }
    
     protected function beforeApplyChanges($quadroHorario, $patch) {
        foreach($quadroHorario->getDiasSemana() as $dia) {
            $dia->setQuadroHorario($quadroHorario);
        }
        foreach($quadroHorario->getHorarios() as $horario) {
            $horario->setQuadroHorario($quadroHorario);
        }
    }
}

