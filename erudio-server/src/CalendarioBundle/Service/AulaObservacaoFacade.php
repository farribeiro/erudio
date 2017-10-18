<?php

namespace CalendarioBundle\Service;

use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;

class AulaObservacaoFacade extends AbstractFacade {
    
    function getEntityClass() {
        return 'CalendarioBundle:AulaObservacao';
    }
    
    function queryAlias() {
        return 'o';
    }
    
    function parameterMap() {
        return [
            'observacao' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('o.observacao LIKE :observacao')
                   ->setParameter('observacao', '%' . $value . '%');
            },
            'aula' => function(QueryBuilder $qb, $value) {
                $qb->join('o.aula', 'aula')
                   ->andWhere('aula.id = :aula')->setParameter('aula', $value);
            }
       ];
    }
    
}
