<?php

namespace CalendarioBundle\Service;

use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;

class CalendarioFacade extends AbstractFacade {
    
    function getEntityClass() {
        return 'CalendarioBundle:Calendario';
    }
    
    function queryAlias() {
        return 'c';
    }
    
    function parameterMap() {
        return array (
            'nome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('c.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
            'ano' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('c.dataInicio LIKE :ano')->setParameter('ano', $value . '%');
            },
            'instituicao' => function(QueryBuilder $qb, $value) {
                $qb->join('c.instituicao', 'instituicao')
                   ->andWhere('instituicao.id = :instituicao')->setParameter('instituicao', $value);
            }
        );
    }
    
}
