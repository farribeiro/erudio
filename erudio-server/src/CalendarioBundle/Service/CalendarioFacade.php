<?php

namespace CalendarioBundle\Service;

use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use CoreBundle\ORM\AbstractFacade;
use CoreBundle\Event\EntityEvent;

class CalendarioFacade extends AbstractFacade {
    
    function __construct(RegistryInterface $doctrine, LoggerInterface $logger, 
            EventDispatcherInterface $eventDispatcher) {
        parent::__construct($doctrine, $logger, $eventDispatcher);
    }
    
    function getEntityClass() {
        return 'CalendarioBundle:Calendario';
    }
    
    function queryAlias() {
        return 'c';
    }
    
    function parameterMap() {
        return [
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
        ];
    }
    
    protected function beforeRemove($calendario) {
        EntityEvent::createAndDispatch(
            $calendario, 
            EntityEvent::ACTION_REMOVED, 
            $this->eventDispatcher
        );
    }
    
}
