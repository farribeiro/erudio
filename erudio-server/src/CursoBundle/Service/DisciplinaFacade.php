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

use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;

class DisciplinaFacade extends AbstractFacade {
    
   function getEntityClass() {
        return 'CursoBundle:Disciplina';
    }
    
    function queryAlias() {
        return 'd';
    }
    
    function parameterMap() {
        return [
            'nome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('d.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
            'curso' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('d.curso = :curso')->setParameter('curso', $value);
            },
            'etapa' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('d.etapa = :etapa')->setParameter('etapa', $value);
            },
            'opcional' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('d.opcional = :opcional')->setParameter('opcional', $value);
            },
            'ofertado' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('d.ofertado = :ofertado')->setParameter('ofertado', $value);
            }
        ];
    }
    
    /**
    * Por padrão, as disciplinas não ofertadas não são listadas, já que são utilizadas
    * apenas em casos específicos como na geração de históricos. O parâmetro booleano
    * "incluirNaoOfertadas" é usado para anular esta restrição e trazer todas as disciplinas.
    * 
    * @param QueryBuilder $qb
    * @param array $params
    */
    function prepareQuery(QueryBuilder $qb, array $params) {
        if (!array_key_exists('incluirNaoOfertadas', $params) || !$params['incluirNaoOfertadas']) {
            $qb->andWhere('d.ofertado = true');
        }
    }
    
}
