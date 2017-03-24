<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CursoBundle\Service;

use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;

class CursoOfertadoFacade extends AbstractFacade {
    
    function getEntityClass() {
        return 'CursoBundle:CursoOfertado';
    }
    
    function queryAlias() {
        return 'c';
    }
    
    function parameterMap() {
        return [
            'curso' => function(QueryBuilder $qb, $value) {
                $qb->join('c.curso', 'curso')                   
                   ->andWhere('curso.id = :curso')->setParameter('curso', $value);
            },
            'unidadeEnsino' => function(QueryBuilder $qb, $value) {
                $qb->join('c.unidadeEnsino', 'unidadeEnsino')
                   ->andWhere('unidadeEnsino.id = :unidadeEnsino')
                   ->setParameter('unidadeEnsino', $value);
            }
        ];
    }
    
}
